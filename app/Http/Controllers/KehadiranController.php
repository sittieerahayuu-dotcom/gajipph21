<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

// Controller tambahan untuk perhitungan gaji & PPh21
use App\Http\Controllers\PerhitunganPphController;
use App\Http\Controllers\PerhitunganGajiController;

class KehadiranController extends Controller
{
    /**
     * Form input absen
     */
    public function create()
    {
        $karyawans = Karyawan::all();
        return view('kehadiran.create', compact('karyawans'));
    }

    /**
     * Daftar absen harian dengan filter + pagination
     */
    public function index(Request $request)
    {
        $perPage = $request->input('entries', 5);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) $perPage = 5;

        $user = Auth::user();

        $query = Kehadiran::with('karyawan.jabatan')->orderBy('tanggal', 'desc');

        // 🔑 Jika login sebagai karyawan (role = 3), tampilkan absensi dirinya saja
        if ($user->role == 3) {
            $karyawan = Karyawan::where('nik', $user->email_username_only())->first();
            if ($karyawan) {
                $query->where('karyawan_id', $karyawan->id);
            } else {
                $query->whereRaw('1=0'); // kosong
            }
        }

        // 🔍 Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('karyawan', function ($sub) use ($search) {
                    $sub->where('nama_karyawan', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%");
                })->orWhereHas('karyawan.jabatan', function ($sub) use ($search) {
                    $sub->where('nama_jabatan', 'like', "%{$search}%");
                });
            });
        }

        // 📅 Filter bulan & tahun
        if ($request->filled('bulan')) $query->whereMonth('tanggal', $request->bulan);
        if ($request->filled('tahun')) $query->whereYear('tanggal', $request->tahun);

        $kehadirans = $query->paginate($perPage)->withQueryString();
        $karyawans  = Karyawan::all();

        $bulanText  = $request->filled('bulan') ? Carbon::create()->month($request->bulan)->translatedFormat('F') : 'Semua Bulan';
        $tahunText  = $request->filled('tahun') ? $request->tahun : 'Semua Tahun';
        $filterMessage = "Menampilkan data kehadiran bulan <strong>{$bulanText}</strong> tahun <strong>{$tahunText}</strong>";

        return view('kehadiran.index', compact('kehadirans', 'karyawans', 'filterMessage'));
    }

    /**
     * Rekap bulanan dengan pagination
     */
    public function rekap(Request $request)
    {
        $perPage = $request->input('entries', 5);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) $perPage = 5;

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $karyawans = Karyawan::with('jabatan')->get();

        $rekapCollection = $karyawans->map(function ($karyawan) use ($bulan, $tahun) {
            $data = Kehadiran::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            return (object)[
                'id'            => $karyawan->id,
                'bulan'         => $bulan,
                'nik'           => $karyawan->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'nama_jabatan'  => $karyawan->jabatan->nama_jabatan ?? '-',
                'jumlah_masuk'  => $data->sum('jumlah_masuk'),
                'jumlah_alpha'  => $data->sum('jumlah_alpha'),
                'jumlah_sakit'  => $data->sum('jumlah_sakit'),
                'jumlah_izin'   => $data->sum('jumlah_izin'),
                'jumlah_cuti'   => $data->sum('jumlah_cuti'),
            ];
        });

        $totalKehadiran = $rekapCollection->sum(fn($k) =>
            $k->jumlah_masuk + $k->jumlah_alpha + $k->jumlah_sakit + $k->jumlah_izin + $k->jumlah_cuti
        );

        if ($totalKehadiran == 0) {
            $rekapCollection = collect();
        }

        $page = $request->input('page', 1);
        $rekap = new \Illuminate\Pagination\LengthAwarePaginator(
            $rekapCollection->forPage($page, $perPage),
            $rekapCollection->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $bulanText = Carbon::create()->month($bulan)->translatedFormat('F');
        $filterMessage = "Rekap kehadiran bulan <strong>{$bulanText}</strong> tahun <strong>{$tahun}</strong>";

        return view('kehadiran.rekap', compact('rekap', 'filterMessage', 'bulan', 'tahun'));
    }

    /**
     * Store absen harian + otomatis hitung gaji & PPh21 jika absensi bulan lengkap
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'tanggal' => 'required|date',
            'status'  => 'required|in:hadir,alpha,sakit,izin,cuti',
        ]);

        $tanggal = Carbon::parse($request->tanggal);

        // Cek hari Minggu
        if ($tanggal->isSunday()) {
            return back()->with('error', 'Tanggal ' . $tanggal->format('d-m-Y') . ' adalah hari Minggu.');
        }

        // Cek tanggal merah
        $holidays = ['2025-08-17', '2025-12-25'];
        if (in_array($tanggal->format('Y-m-d'), $holidays)) {
            return back()->with('error', 'Tanggal ' . $tanggal->format('d-m-Y') . ' adalah hari libur.');
        }

        $successCount = 0;
        $errorMessages = [];

        if ($user->role == 3) {
            // Jika karyawan login sendiri
            $karyawan = Karyawan::where('nama_karyawan', $user->name)->first();
            if (!$karyawan) {
                return back()->with('error', 'Data karyawan tidak ditemukan.');
            }

            $exists = Kehadiran::where('karyawan_id', $karyawan->id)
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if ($exists) {
                return back()->with('error', "Absen untuk {$karyawan->nama_karyawan} tanggal {$tanggal->format('d-m-Y')} sudah ada.");
            }

            Kehadiran::create([
                'tanggal'        => $tanggal,
                'karyawan_id'    => $karyawan->id,
                'nik'            => $karyawan->nik,
                'nama_karyawan'  => $karyawan->nama_karyawan,
                'nama_jabatan'   => $karyawan->jabatan->nama_jabatan ?? '-',
                'jumlah_masuk'   => $request->status == 'hadir' ? 1 : 0,
                'jumlah_alpha'   => $request->status == 'alpha' ? 1 : 0,
                'jumlah_sakit'   => $request->status == 'sakit' ? 1 : 0,
                'jumlah_izin'    => $request->status == 'izin' ? 1 : 0,
                'jumlah_cuti'    => $request->status == 'cuti' ? 1 : 0,
            ]);

            $successCount = 1;

            $this->prosesPerhitunganJikaLengkap($karyawan->id, $tanggal->month, $tanggal->year);

        } else {
            // Jika admin input banyak karyawan
            $request->validate([
                'karyawan_id'   => 'required|array',
                'karyawan_id.*' => 'exists:karyawans,id',
            ]);

            foreach ($request->karyawan_id as $id) {
                $karyawan = Karyawan::find($id);
                $exists = Kehadiran::where('karyawan_id', $id)
                    ->whereDate('tanggal', $tanggal)->exists();

                if ($exists) {
                    $errorMessages[] = "Absen untuk {$karyawan->nama_karyawan} tanggal {$tanggal->format('d-m-Y')} sudah ada.";
                    continue;
                }

                Kehadiran::create([
                    'tanggal'        => $tanggal,
                    'karyawan_id'    => $id,
                    'nik'            => $karyawan->nik,
                    'nama_karyawan'  => $karyawan->nama_karyawan,
                    'nama_jabatan'   => $karyawan->jabatan->nama_jabatan ?? '-',
                    'jumlah_masuk'   => $request->status == 'hadir' ? 1 : 0,
                    'jumlah_alpha'   => $request->status == 'alpha' ? 1 : 0,
                    'jumlah_sakit'   => $request->status == 'sakit' ? 1 : 0,
                    'jumlah_izin'    => $request->status == 'izin' ? 1 : 0,
                    'jumlah_cuti'    => $request->status == 'cuti' ? 1 : 0,
                ]);

                $successCount++;

                $this->prosesPerhitunganJikaLengkap($id, $tanggal->month, $tanggal->year);
            }
        }

        $message = "$successCount absen berhasil disimpan.";
        if (!empty($errorMessages)) {
            $message .= " Namun ada beberapa error: " . implode(' | ', $errorMessages);
        }

        return back()->with('success', $message);
    }

    /**
     * Helper: cek absensi bulan lengkap, lalu otomatis hitung gaji + PPh21
     */
   private function prosesPerhitunganJikaLengkap($karyawanId, $bulan, $tahun)
{
    $totalAbsensi = Kehadiran::where('karyawan_id', $karyawanId)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->count();

    $jumlahHariKerja = $this->jumlahHariKerja($bulan, $tahun);

    if ($totalAbsensi >= $jumlahHariKerja) {
        // 🔑 urutannya dibalik → hitung PPh dulu
        app(PerhitunganPphController::class)->hitungPphBulanan($bulan, $tahun);
        app(PerhitunganGajiController::class)->hitungGajiBulanan($bulan, $tahun);
    }
}

    /**
     * Hapus semua data absen per bulan
     */
        public function destroy($id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $kehadiran->delete();
        return redirect()->route('kehadiran.index')->with('success', 'Kehadiran berhasil dihapus');
    }
    // public function hapusBulanan(Request $request)
    // {
    //     $bulan = $request->bulan;
    //     $tahun = $request->tahun;

    //     if (!$bulan || !$tahun) {
    //         return back()->with('error', 'Bulan dan tahun harus dipilih.');
    //     }

    //     Kehadiran::whereMonth('tanggal', $bulan)
    //         ->whereYear('tanggal', $tahun)
    //         ->delete();

    //     return back()->with('success', "Data kehadiran bulan {$bulan}/{$tahun} berhasil dihapus.");
    // }

    /**
     * Helper: Hitung jumlah hari kerja (exclude Minggu & libur nasional)
     */
    private function jumlahHariKerja($bulan, $tahun)
    {
        $start = Carbon::create($tahun, $bulan, 1);
        $end = $start->copy()->endOfMonth();
        $holidays = ['2025-08-17', '2025-12-25'];
        $total = 0;

        for ($date = $start; $date->lte($end); $date->addDay()) {
            if (!$date->isSunday() && !in_array($date->format('Y-m-d'), $holidays)) {
                $total++;
            }
        }
        return $total;
    }

    /**
     * Laporan kehadiran (tampilan blade)
     */
    public function laporan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $karyawanId = $request->karyawan_id;

        $karyawans = Karyawan::all();

        $rekap = Karyawan::with('jabatan')
            ->when($karyawanId, fn($q) => $q->where('id', $karyawanId))
            ->get()
            ->map(function ($karyawan) use ($bulan, $tahun) {
                $data = Kehadiran::where('karyawan_id', $karyawan->id)
                    ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
                    ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
                    ->get();

                return (object)[
                    'id'            => $karyawan->id,
                    'nik'           => $karyawan->nik,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'nama_jabatan'  => $karyawan->jabatan->nama_jabatan ?? '-',
                    'jumlah_masuk'  => $data->sum('jumlah_masuk'),
                    'jumlah_alpha'  => $data->sum('jumlah_alpha'),
                    'jumlah_sakit'  => $data->sum('jumlah_sakit'),
                    'jumlah_izin'   => $data->sum('jumlah_izin'),
                    'jumlah_cuti'   => $data->sum('jumlah_cuti'),
                ];
            });

        return view('kehadiran.laporan-kehadiran', compact('rekap', 'bulan', 'tahun', 'karyawanId', 'karyawans'));
    }

    /**
     * Cetak laporan ke PDF
     */
    public function cetakLaporan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020',
            'bulan' => 'nullable|integer|min:1|max:12',
        ], [
            'tahun.required' => 'Tahun harus dipilih.',
            'bulan.integer'  => 'Format bulan tidak valid.',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $karyawanId = $request->karyawan_id;

        $rekap = Karyawan::with('jabatan')
            ->when($karyawanId, fn($q) => $q->where('id', $karyawanId))
            ->get()
            ->map(function ($karyawan) use ($bulan, $tahun) {
                $data = Kehadiran::where('karyawan_id', $karyawan->id)
                    ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
                    ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
                    ->get();

                return (object)[
                    'id'            => $karyawan->id,
                    'nik'           => $karyawan->nik,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'nama_jabatan'  => $karyawan->jabatan->nama_jabatan ?? '-',
                    'jumlah_masuk'  => $data->sum('jumlah_masuk'),
                    'jumlah_alpha'  => $data->sum('jumlah_alpha'),
                    'jumlah_sakit'  => $data->sum('jumlah_sakit'),
                    'jumlah_izin'   => $data->sum('jumlah_izin'),
                    'jumlah_cuti'   => $data->sum('jumlah_cuti'),
                ];
            });

        if ($request->has('preview')) {
            return view('kehadiran.cetak-rekap', compact('rekap', 'bulan', 'tahun', 'karyawanId'));
        }

        $fileNameBulan = $bulan ?? 'semua-bulan';
        $fileNameTahun = $tahun ?? 'semua-tahun';

        $pdf = Pdf::loadView('kehadiran.cetak-rekap', compact('rekap', 'bulan', 'tahun', 'karyawanId'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Rekap-Kehadiran-{$fileNameBulan}-{$fileNameTahun}.pdf");
    }
    public function edit($id)
{
    $kehadiran = Kehadiran::findOrFail($id);
    return view('kehadiran.edit', compact('kehadiran'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'tanggal' => 'required|date',
        'status'  => 'required|in:hadir,alpha,sakit,izin,cuti',
    ]);

    $kehadiran = Kehadiran::findOrFail($id);

    // update field sesuai status
    $kehadiran->update([
        'tanggal'       => $request->tanggal,
        'status'        => $request->status,
        'jumlah_masuk'  => $request->status == 'hadir' ? 1 : 0,
        'jumlah_alpha'  => $request->status == 'alpha' ? 1 : 0,
        'jumlah_sakit'  => $request->status == 'sakit' ? 1 : 0,
        'jumlah_izin'   => $request->status == 'izin' ? 1 : 0,
        'jumlah_cuti'   => $request->status == 'cuti' ? 1 : 0,
    ]);

    // ambil bulan & tahun dari tanggal absen
    $bulan = date('m', strtotime($kehadiran->tanggal));
    $tahun = date('Y', strtotime($kehadiran->tanggal));

    // panggil ulang perhitungan gaji & pph
    $gajiController = new \App\Http\Controllers\PerhitunganGajiController();
    $pphController  = new \App\Http\Controllers\PerhitunganPphController();

    $gajiController->hitungGaji($kehadiran->karyawan, $bulan, $tahun);
    $pphController->hitungPph21($kehadiran->karyawan, $bulan, $tahun);

    return redirect()
        ->route('kehadiran.index')
        ->with('success', 'Data kehadiran berhasil diperbarui & gaji/pph otomatis dihitung ulang.');
}



}
