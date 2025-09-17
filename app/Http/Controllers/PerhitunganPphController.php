<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerhitunganPph;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\PotonganGaji;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PerhitunganPphController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        // Tangkap jumlah data per halaman, default 10, bisa diubah lewat query param 'entries'
        $perPage = $request->input('entries', 5);
        if (!in_array($perPage, [5, 10, 25, 50, 100])) {
            $perPage = 5; // fallback jika input tidak valid
        }
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();


        $perhitungans = PerhitunganPph::when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
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

        $filterMessage = "Menampilkan data Perhitungan PPh 21 bulan <strong>{$bulanText}</strong> tahun <strong>{$tahunText}</strong>";

        return view('perhitungan-pph.index', compact(
            'perhitungans',
            'search',
            'bulan',
            'tahun',
            'filterMessage'
        ));
    }


    public function hitungPph21(Karyawan $karyawan, $bulan = null, $tahun = null)
{
    $bulan = $bulan ?? Carbon::now()->month;
    $tahun = $tahun ?? Carbon::now()->year;

    // Ambil jabatan
    $jabatan = Jabatan::find($karyawan->jabatan_id) ?? (object)[
        'gaji_pokok' => 0,
        'tunjangan_makan' => 0,
        'tunjangan_transportasi' => 0,
        'tunjangan_bpjs_kesehatan' => 0,
        'tunjangan_jkm' => 0,
        'tunjangan_jkk' => 0,
        'nama_jabatan' => 'Tidak Diketahui',
    ];

    // Ambil nilai potongan
    $potonganAlpha = PotonganGaji::where('jenis_potongan', 'alpha')->value('nilai_potongan') ?? 0;
    $potonganCuti  = PotonganGaji::where('jenis_potongan', 'cuti di luar jatah cuti')->value('nilai_potongan') ?? 0;

    // Ambil data kehadiran bulan berjalan
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

    // Hitung potongan
    $potongan_kehadiran = ($jumlah_alpha * $potonganAlpha) + ($cuti_luar_jatah_bulan_ini * $potonganCuti);

    // Komponen gaji
    $gaji_pokok = $jabatan->gaji_pokok;
    $t_makan    = $jabatan->tunjangan_makan;
    $t_transportasi = $jabatan->tunjangan_transportasi;
    $t_bpjs     = $jabatan->tunjangan_bpjs_kesehatan;
    $t_jkm      = $jabatan->tunjangan_jkm;
    $t_jkk      = $jabatan->tunjangan_jkk;

    // Penghasilan bruto per bulan
    $penghasilan_bruto = $gaji_pokok + $t_makan + $t_transportasi + $t_bpjs + $t_jkm + $t_jkk - $potongan_kehadiran;

    // Biaya jabatan max 500rb/bln
    $biaya_jabatan = min($penghasilan_bruto * 0.05, 500000);

    // Iuran BPJS → ambil dari tunjangan BPJS
    $iuran_bpjs = $gaji_pokok * 0.01;

    // Netto per bulan & setahun
    $netto_per_bulan = $penghasilan_bruto - $biaya_jabatan - $iuran_bpjs;
    $netto_per_tahun = $netto_per_bulan * 12;

    // PTKP sesuai kode PTKP
    $ptkp = 54000000; // TK/0
    if ($karyawan->kode_ptkp) {
        if (str_starts_with($karyawan->kode_ptkp, 'K')) {
            $ptkp += 4500000; // tambahan status kawin
        }
        if (preg_match('/\/(\d+)/', $karyawan->kode_ptkp, $matches)) {
            $anak = min((int)$matches[1], 3);
            $ptkp += $anak * 4500000;
        }
    }

    // PKP
    $pkp = max(0, floor($netto_per_tahun - $ptkp));

    // Hitung PPh21 setahun
    $pph21_setahun = 0;
    if ($pkp > 0) {
        if ($pkp <= 60000000) {
            $pph21_setahun = $pkp * 0.05;
        } elseif ($pkp <= 250000000) {
            $pph21_setahun = (60000000 * 0.05) + (($pkp - 60000000) * 0.15);
        } elseif ($pkp <= 500000000) {
            $pph21_setahun = (60000000 * 0.05) + (190000000 * 0.15) + (($pkp - 250000000) * 0.25);
        } elseif ($pkp <= 5000000000) {
            $pph21_setahun = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (($pkp - 500000000) * 0.3);
        } else {
            $pph21_setahun = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (4500000000 * 0.3) + (($pkp - 5000000000) * 0.35);
        }
    }

    $pph21_sebulan = $pph21_setahun / 12;

    // Simpan hasil perhitungan
    PerhitunganPph::updateOrCreate(
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
            'penghasilan_bruto' => $penghasilan_bruto,
            'biaya_jabatan' => $biaya_jabatan,
            'iuran_bpjs' => $iuran_bpjs,
            'potongan_kehadiran' => $potongan_kehadiran,
            'netto_per_bulan' => $netto_per_bulan,
            'netto_per_tahun' => $netto_per_tahun,
            'ptkp' => $ptkp,
            'pkp' => $pkp,
            'pph21_setahun' => $pph21_setahun,
            'pph21_sebulan' => $pph21_sebulan,
        ]
    );
}
public function hitungPphBulanan($bulan, $tahun)
{
    $karyawans = Karyawan::all();

    foreach ($karyawans as $karyawan) {
        $this->hitungPph21($karyawan, $bulan, $tahun);
    }

    return "Perhitungan PPh21 bulan $bulan/$tahun berhasil diproses.";
}




    public function destroy($id)
    {
        $perhitungan = PerhitunganPph::findOrFail($id);
        $perhitungan->delete();

        return redirect()->route('perhitungan-pph.index')->with('success', 'Perhitungan gaji berhasil dihapus');
    }

    public function hapusBulanan(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (!$bulan || !$tahun) {
            return back()->with('error', 'Bulan dan tahun wajib dipilih.');
        }

        $deleted = PerhitunganPph::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->delete();

        return back()->with('success', "$deleted data berhasil dihapus untuk bulan $bulan tahun $tahun.");
    }


    public function laporan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $karyawan_id = $request->karyawan_id;

        $perhitungans = PerhitunganPph::when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan))
            ->when($tahun, fn($q) => $q->whereYear('tanggal', $tahun))
            ->when($karyawan_id, fn($q) => $q->where('karyawan_id', $karyawan_id))
            ->get();

        $karyawans = Karyawan::all();

        return view('perhitungan-pph.laporan-pph', compact('perhitungans', 'bulan', 'tahun', 'karyawan_id', 'karyawans'));
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
        $karyawan_id = $request->karyawan_id;

        $query = PerhitunganPph::query()
            ->whereYear('tanggal', $tahun)
            ->when($bulan, fn($q) => $q->whereMonth('tanggal', $bulan));

        if ($karyawan_id) {
            $query->where('karyawan_id', $karyawan_id);
        }

        $perhitungans = $query->get();
        $karyawans = Karyawan::all();

        if ($request->has('preview')) {
            return view('perhitungan-pph.cetak-pph', compact('perhitungans', 'bulan', 'tahun', 'karyawan_id', 'karyawans'));
        }

        $fileNameBulan = $bulan ?? 'semua-bulan';
        $fileNameTahun = $tahun ?? 'semua-tahun';

        $pdf = PDF::loadView('perhitungan-pph.cetak-pph', compact('perhitungans', 'bulan', 'tahun', 'karyawan_id', 'karyawans'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("Laporan-PPh21-{$fileNameBulan}-{$fileNameTahun}.pdf");
    }
}
