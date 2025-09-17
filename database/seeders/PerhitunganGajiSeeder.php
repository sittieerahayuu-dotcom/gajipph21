<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PerhitunganGaji;
use App\Models\Karyawan;
use App\Models\Kehadiran;
use App\Models\PotonganGaji;
use App\Models\PerhitunganPph;
use Carbon\Carbon;

class PerhitunganGajiSeeder extends Seeder
{
    public function run(): void
    {
        $tanggal = Carbon::now()->startOfMonth();
        $bulan = $tanggal->format('m');
        $tahun = $tanggal->format('Y');

        // Ambil nilai potongan dari tabel potongan_gajis
        $potonganAlpha = PotonganGaji::where('jenis_potongan', 'alpha')->value('nilai_potongan') ?? 0;
        $potonganCuti = PotonganGaji::where('jenis_potongan', 'cuti di luar jatah cuti')->value('nilai_potongan') ?? 0;

        $karyawans = Karyawan::with('jabatan')->get();

        foreach ($karyawans as $karyawan) {
            $jabatan = $karyawan->jabatan;

            // Ambil data kehadiran bulan ini
            $kehadiran = Kehadiran::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->first();

            $jumlah_alpha = $kehadiran->jumlah_alpha ?? 0;
            $jumlah_cuti = $kehadiran->jumlah_cuti ?? 0;
            $jatah_cuti = 1;
            $cuti_luar_jatah = max(0, $jumlah_cuti - $jatah_cuti);

            // Ambil perhitungan pph21 bulan ini
            $pph = PerhitunganPph::where('karyawan_id', $karyawan->id)->first();
            $potongan_pph21 = $pph->pph21_sebulan ?? 0;

            $gaji_pokok = $jabatan->gaji_pokok ?? 0;
            $tunjangan_makan = $jabatan->tunjangan_makan ?? 0;
            $tunjangan_transportasi = $jabatan->tunjangan_transportasi ?? 0;
            $tunjangan_bpjs = $jabatan->tunjangan_bpjs_kesehatan ?? 0; // PERBAIKAN NAMA FIELD
            $tunjangan_jkm = $jabatan->tunjangan_jkm ?? 0;
            $tunjangan_jkk = $jabatan->tunjangan_jkk ?? 0;

            // Potongan BPJS (misalnya 1% dari gaji pokok)
            $potongan_bpjs = $gaji_pokok * 0.01;

            // Potongan kehadiran
            $potongan_kehadiran = ($jumlah_alpha * $potonganAlpha) + ($cuti_luar_jatah * $potonganCuti);

            // Total dan bersih
            $total_pendapatan = $gaji_pokok + $tunjangan_makan + $tunjangan_transportasi + $tunjangan_bpjs + $tunjangan_jkm + $tunjangan_jkk;
            $total_potongan = $potongan_bpjs + $potongan_pph21 + $potongan_kehadiran;
            $gaji_bersih = $total_pendapatan - $total_potongan;

            PerhitunganGaji::create([
                'tanggal' => $tanggal,
                'karyawan_id' => $karyawan->id,
                'nik' => $karyawan->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'jabatan' => $jabatan->nama_jabatan,
                'gaji_pokok' => $gaji_pokok,
                'tunjangan_makan' => $tunjangan_makan,
                'tunjangan_transportasi' => $tunjangan_transportasi,
                'tunjangan_bpjs' => $tunjangan_bpjs,
                'tunjangan_jkm' => $tunjangan_jkm,
                'tunjangan_jkk' => $tunjangan_jkk,
                'potongan_bpjs' => $potongan_bpjs,
                'potongan_pph21' => $potongan_pph21,
                'potongan_kehadiran' => $potongan_kehadiran,
                'total_pendapatan' => $total_pendapatan,
                'total_potongan' => $total_potongan,
                'gaji_bersih' => $gaji_bersih,
            ]);
        }
    }
}
