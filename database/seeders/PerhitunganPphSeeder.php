<?php

namespace Database\Seeders;

use App\Models\PerhitunganPph;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Kehadiran;
use App\Models\PotonganGaji;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PerhitunganPphSeeder extends Seeder
{
    public function run()
    {
        $tanggal = Carbon::now()->startOfMonth();
        $bulan = $tanggal->format('m');
        $tahun = $tanggal->format('Y');

        // Ambil nilai potongan dari tabel potongan_gajis
        $potonganAlpha = PotonganGaji::where('jenis_potongan', 'alpha')->value('nilai_potongan') ?? 0;
        $potonganCuti = PotonganGaji::where('jenis_potongan', 'cuti di luar jatah cuti')->value('nilai_potongan') ?? 0;

        $karyawans = Karyawan::all();

        foreach ($karyawans as $karyawan) {
            $jabatan = Jabatan::find($karyawan->jabatan_id);

            if (!$jabatan) {
                $jabatan = new \stdClass();
                $jabatan->gaji_pokok = 0;
                $jabatan->tunjangan_makan = 0;
                $jabatan->tunjangan_transportasi = 0;
                $jabatan->tunjangan_bpjs_kesehatan = 0;
                $jabatan->tunjangan_jkm = 0;
                $jabatan->tunjangan_jkk = 0;
                $jabatan->nama_jabatan = 'Tidak Diketahui';
            }

            // Ambil data kehadiran bulan ini
            $kehadiran = Kehadiran::where('karyawan_id', $karyawan->id)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->first();

            $jumlah_alpha = $kehadiran->jumlah_alpha ?? 0;
            $jumlah_cuti = $kehadiran->jumlah_cuti ?? 0;
            $jatah_cuti = 1;
            $cuti_luar_jatah = max(0, $jumlah_cuti - $jatah_cuti);

            // Hitung potongan kehadiran
            $potongan_kehadiran = ($jumlah_alpha * $potonganAlpha) + ($cuti_luar_jatah * $potonganCuti);

            $gaji_pokok = $jabatan->gaji_pokok ?? 0;
            $t_makan = $jabatan->tunjangan_makan ?? 0;
            $t_transportasi = $jabatan->tunjangan_transportasi ?? 0;
            $t_bpjs = $jabatan->tunjangan_bpjs_kesehatan ?? 0;
            $t_jkm = $jabatan->tunjangan_jkm ?? 0;
            $t_jkk = $jabatan->tunjangan_jkk ?? 0;

            $penghasilan_bruto = $gaji_pokok + $t_makan + $t_transportasi + $t_bpjs + $t_jkm + $t_jkk - $potongan_kehadiran;
            $biaya_jabatan = $gaji_pokok * 0.05;
            $iuran_bpjs = $gaji_pokok * 0.01;
            $netto_per_bulan = $penghasilan_bruto - $biaya_jabatan - $iuran_bpjs; // Potongan kehadiran dikurang dari netto
            $netto_per_tahun = $netto_per_bulan * 12;

            // Hitung PTKP berdasarkan kode PTKP
            $ptkp = 54000000; // Default TK/0

            if ($karyawan->kode_ptkp) {
                if (str_starts_with($karyawan->kode_ptkp, 'K')) {
                    $ptkp += 4500000; // Status menikah
                }

                if (preg_match('/\/(\d+)/', $karyawan->kode_ptkp, $matches)) {
                    $jumlah_anak = min((int)$matches[1], 3);
                    $ptkp += $jumlah_anak * 4500000;
                }
            }

            $pkp = $netto_per_tahun - $ptkp;

            if ($pkp <= 0) {
                $pph21_setahun = 0;
                $pph21_sebulan = 0;
            } else {
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

                $pph21_sebulan = $pph21_setahun / 12;
            }

            PerhitunganPph::create([
                'tanggal' => $tanggal,
                'karyawan_id' => $karyawan->id,
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
                'potongan_kehadiran' => $potongan_kehadiran, // tambahkan field potongan kehadiran
                'netto_per_bulan' => $netto_per_bulan,
                'netto_per_tahun' => $netto_per_tahun,
                'ptkp' => $ptkp,
                'pkp' => $pkp,
                'pph21_setahun' => $pph21_setahun,
                'pph21_sebulan' => $pph21_sebulan,
            ]);
        }
    }
}
