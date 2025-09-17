<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\User;
use App\Models\PerhitunganGaji;
use App\Models\PerhitunganPph;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function index()
{
    // Jumlah data master
    $jumlahKaryawan = Karyawan::count();
    $jumlahJabatan = Jabatan::count();
    $jumlahUser = User::count();

    $now = Carbon::now();
    $tahunIni = $now->year;
    $bulanIni = $now->month;

    // Total Gaji Bersih Bulan Ini
    // Total Gaji Bersih Tahun Ini
$totalGajiBersih = PerhitunganGaji::whereYear('tanggal', $tahunIni)
    ->sum('gaji_bersih');


    // Riwayat Gaji per bulan
    $riwayatGaji = PerhitunganGaji::select(
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('MIN(tanggal) as tanggal_proses'),
            DB::raw('COUNT(DISTINCT karyawan_id) as jumlah_karyawan'),
            DB::raw('SUM(gaji_bersih) as total_gaji_bersih')
        )
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'asc')
        ->orderBy('bulan', 'asc')
        ->get();

    // Riwayat PPh per bulan
    $riwayatPph21 = PerhitunganPph::select(
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('SUM(pph21_sebulan) as total_pph21')
        )
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'asc')
        ->orderBy('bulan', 'asc')
        ->get();

    // Gabungkan PPh21 ke data Gaji
    foreach ($riwayatGaji as $item) {
        $pphItem = $riwayatPph21->firstWhere(fn($pph) => 
            $pph->tahun == $item->tahun && $pph->bulan == $item->bulan
        );

        $item->total_pph21 = $pphItem ? (float) $pphItem->total_pph21 : 0;
        $item->total_gaji_bersih = (float) $item->total_gaji_bersih;
    }

    // Buat data grafik lengkap 12 bulan tahun ini
    $labels = [];
    $totalGaji = [];
    $totalPph = [];

    for ($i = 1; $i <= 12; $i++) {
        $labels[] = Carbon::create($tahunIni, $i)->translatedFormat('F');

        $gajiItem = $riwayatGaji->firstWhere('bulan', $i);
        $totalGaji[] = $gajiItem ? $gajiItem->total_gaji_bersih : 0;

        $totalPph[] = $gajiItem ? $gajiItem->total_pph21 : 0;
    }

    // Data Pie Chart Kehadiran khusus bulan & tahun ini (jangan total semua)
    $kehadiranPerBulan = Kehadiran::select(
        DB::raw('MONTH(tanggal) as bulan'),
        DB::raw('SUM(jumlah_masuk) as total_masuk'),
        DB::raw('SUM(jumlah_izin) as total_izin'),
        DB::raw('SUM(jumlah_cuti) as total_cuti'),
        DB::raw('SUM(jumlah_sakit) as total_sakit'),
        DB::raw('SUM(jumlah_alpha) as total_alpha'),
    )
    ->whereYear('tanggal', $tahunIni)
    ->groupBy('bulan')
    ->orderBy('bulan')
    ->get();

$bulanLabels = [];
$masukData = [];
$izinData = [];
$cutiData = [];
$sakitData = [];
$alphaData = [];

for ($i = 1; $i <= 12; $i++) {
    $bulanLabels[] = Carbon::create($tahunIni, $i)->translatedFormat('F');

    $dataBulan = $kehadiranPerBulan->firstWhere('bulan', $i);

    $masukData[] = $dataBulan ? (int) $dataBulan->total_masuk : 0;
    $izinData[] = $dataBulan ? (int) $dataBulan->total_izin : 0;
    $cutiData[] = $dataBulan ? (int) $dataBulan->total_cuti : 0;
    $sakitData[] = $dataBulan ? (int) $dataBulan->total_sakit : 0;
    $alphaData[] = $dataBulan ? (int) $dataBulan->total_alpha : 0;
}

return view('dashboard', [
    'jumlahKaryawan' => $jumlahKaryawan,
    'jumlahJabatan' => $jumlahJabatan,
    'jumlahUser' => $jumlahUser,
    'totalGajiBersih' => $totalGajiBersih,
    'riwayat' => $riwayatGaji,
    'labels' => $labels,
    'totalGaji' => $totalGaji,
    'totalPph' => $totalPph,
    'bulanIni' => $bulanIni,
    'tahunIni' => $tahunIni,

    // Data line chart kehadiran
    'bulanLabels' => $bulanLabels,
    'masukData' => $masukData,
    'izinData' => $izinData,
    'cutiData' => $cutiData,
    'sakitData' => $sakitData,
    'alphaData' => $alphaData,
]);
}


}
