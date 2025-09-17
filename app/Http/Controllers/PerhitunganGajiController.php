<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerhitunganGaji;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Kehadiran;
use App\Models\PotonganGaji;
use App\Models\PerhitunganPph;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PerhitunganGajiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $perPage = $request->input('entries', 5);

        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5;
        }

        // Query data perhitungan
        $perhitungans = PerhitunganGaji::when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nama_karyawan', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%')
                        ->orWhere('jabatan', 'like', '%' . $search . '%')
                        ->orWhere('tanggal', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'asc')
            ->paginate($perPage);


        // Buat pesan filter yang informatif untuk ditampilkan di view
        $bulanText = $request->filled('bulan')
            ? \Carbon\Carbon::create()->month($request->bulan)->translatedFormat('F')
            : 'Semua Bulan';

        $tahunText = $request->filled('tahun')
            ? $request->tahun
            : 'Semua Tahun';

        $filterMessage = "Menampilkan data Perhitungan Gaji bulan <strong>{$bulanText}</strong> tahun <strong>{$tahunText}</strong>";

        // Kirim semua variabel ke view
        return view('perhitungan-gaji.index', compact(
            'perhitungans',
            'search',
            'bulan',
            'tahun',
            'filterMessage'
        ));
    }


    public function hitungGaji(Karyawan $karyawan, $bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? Carbon::now()->month;
        $tahun = $tahun ?? Carbon::now()->year;

        // Ambil data jabatan
        $jabatan = Jabatan::find($karyawan->jabatan_id) ?? (object)[
            'gaji_pokok' => 0,
            'tunjangan_makan' => 0,
            'tunjangan_transportasi' => 0,
            'tunjangan_bpjs_kesehatan' => 0,
            'tunjangan_jkm' => 0,
            'tunjangan_jkk' => 0,
            'nama_jabatan' => 'Tidak Diketahui',
        ];

        // Ambil semua kehadiran bulan ini
        $kehadiran = Kehadiran::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $jumlah_alpha = $kehadiran->sum('jumlah_alpha');
        $jumlah_cuti_bulan_ini = $kehadiran->sum('jumlah_cuti');

        // Hitung cuti luar jatah
        $jatah_cuti_tahunan = 12;
        $total_cuti_terpakai = Kehadiran::where('karyawan_id', $karyawan->id)
            ->whereYear('tanggal', $tahun)
            ->sum('jumlah_cuti');

        $cuti_luar_jatah_bulan_ini = max(
            0,
            $jumlah_cuti_bulan_ini - max(0, $jatah_cuti_tahunan - ($total_cuti_terpakai - $jumlah_cuti_bulan_ini))
        );

        // Potongan kehadiran
        $potonganAlpha = PotonganGaji::where('jenis_potongan', 'alpha')->value('nilai_potongan') ?? 0;
        $potonganCuti  = PotonganGaji::where('jenis_potongan', 'cuti di luar jatah cuti')->value('nilai_potongan') ?? 0;
        $potongan_kehadiran = ($jumlah_alpha * $potonganAlpha) + ($cuti_luar_jatah_bulan_ini * $potonganCuti);

        // Komponen gaji
        $gaji_pokok = $jabatan->gaji_pokok;
        $t_makan    = $jabatan->tunjangan_makan;
        $t_transportasi = $jabatan->tunjangan_transportasi;
        $t_bpjs     = $jabatan->tunjangan_bpjs_kesehatan;
        $t_jkm      = $jabatan->tunjangan_jkm;
        $t_jkk      = $jabatan->tunjangan_jkk;


        // Potongan BPJS → 1% dari gaji pokok (sama dengan hitungPph21)
        $potongan_bpjs = $gaji_pokok * 0.01;


        // Ambil PPh21 otomatis dari tabel perhitungan_pph
        $pph21 = PerhitunganPph::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->value('pph21_sebulan') ?? 0;

        // Total pendapatan & potongan
        $total_pendapatan = $gaji_pokok + $t_makan + $t_transportasi + $t_bpjs + $t_jkm + $t_jkk;
        $total_potongan   = $potongan_bpjs + $pph21 + $potongan_kehadiran;
        $gaji_bersih      = $total_pendapatan - $total_potongan;

        // Simpan ke tabel perhitungan_gaji
        PerhitunganGaji::updateOrCreate(
            ['karyawan_id' => $karyawan->id, 'tanggal' => Carbon::create($tahun, $bulan, 1)],
            [
                'nik' => $karyawan->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jabatan' => $jabatan->nama_jabatan,
                'gaji_pokok' => $gaji_pokok,
                'tunjangan_makan' => $t_makan,
                'tunjangan_transportasi' => $t_transportasi,
                'tunjangan_bpjs' => $t_bpjs,
                'tunjangan_jkm' => $t_jkm,
                'tunjangan_jkk' => $t_jkk,
                'potongan_bpjs' => $potongan_bpjs,
                'potongan_pph21' => $pph21,
                'potongan_kehadiran' => $potongan_kehadiran,
                'total_pendapatan' => $total_pendapatan,
                'total_potongan' => $total_potongan,
                'gaji_bersih' => $gaji_bersih,
            ]
        );
    }
    public function hitungGajiBulanan($bulan, $tahun)
    {
        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $this->hitungGaji($karyawan, $bulan, $tahun);
        }

        return "Perhitungan gaji bulan $bulan/$tahun berhasil diproses.";
    }







    public function show($id)
    {
        $perhitungan = PerhitunganGaji::findOrFail($id);
        return view('perhitungangaji.show', compact('perhitungan'));
    }

    public function destroy($id)
    {
        $perhitungan = PerhitunganGaji::findOrFail($id);
        $perhitungan->delete();

        return redirect()->route('perhitungan-gaji.index')->with('success', 'Perhitungan gaji berhasil dihapus');
    }

    public function hapusBulanan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (!$bulan || !$tahun) {
            return back()->with('error', 'Bulan dan tahun wajib dipilih.');
        }

        $deleted = PerhitunganGaji::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->delete();

        return back()->with('success', "$deleted data berhasil dihapus untuk bulan $bulan tahun $tahun.");
    }


    public function laporan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $karyawanId = $request->karyawan_id;

        $perhitungans = PerhitunganGaji::when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($karyawanId, fn($q) => $q->where('karyawan_id', $karyawanId))
            ->get();

        $karyawans = Karyawan::orderBy('nama_karyawan')->get();

        return view('perhitungan-gaji.laporan-gaji', compact('perhitungans', 'bulan', 'tahun', 'karyawans'));
    }

    public function cetakLaporan(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020',
            'bulan' => 'nullable|integer|min:1|max:12',
        ], [
            'tahun.required' => 'Tahun harus dipilih.',
            'bulan.integer' => 'Format bulan tidak valid.',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $karyawanId = $request->karyawan_id;

        $query = PerhitunganGaji::query()
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan));

        if ($karyawanId) {
            $query->where('karyawan_id', $karyawanId);
        }

        $perhitungans = $query->get();
        $karyawans = Karyawan::all();

        if ($request->has('preview')) {
            return view('perhitungan-gaji.cetak-gaji', compact('perhitungans', 'bulan', 'tahun', 'karyawanId', 'karyawans'));
        }

        $fileNameBulan = $bulan ?? 'semua-bulan';
        $fileNameTahun = $tahun ?? 'semua-tahun';

        $pdf = PDF::loadView('perhitungan-gaji.cetak-gaji', compact('perhitungans', 'bulan', 'tahun', 'karyawanId', 'karyawans'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan-Gaji-{$fileNameBulan}-{$fileNameTahun}.pdf");
    }

    // Form filter slip gaji
    public function slipGajiForm()
    {
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();
        return view('perhitungan-gaji.slip-form', compact('karyawans'));
    }

    // Cetak slip gaji berdasarkan filter
    public function cetakSlipGaji(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'nama_karyawan' => 'required|string',
        ]);

        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nama = $request->nama_karyawan;
        $action = $request->input('action'); // ambil action dari tombol

        $karyawan = Karyawan::where('nama_karyawan', $nama)->first();
        if (!$karyawan) {
            return redirect()->back()->with('error', 'Karyawan tidak ditemukan.');
        }

        $gaji = PerhitunganGaji::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->first();

        if (!$gaji) {
            return redirect()->back()->with('error', 'Data gaji tidak ditemukan untuk karyawan dan periode tersebut.');
        }

        // Jika action-nya "lihat", tampilkan di view biasa
        if ($action === 'lihat') {
            return view('perhitungan-gaji.slip-cetak', compact('gaji'));
        }

        // Jika bukan lihat (default), cetak PDF
        $pdf = Pdf::loadView('perhitungan-gaji.slip-cetak', compact('gaji'));
        return $pdf->stream('Slip-Gaji-' . $karyawan->nama_karyawan . '.pdf');
    }
}
