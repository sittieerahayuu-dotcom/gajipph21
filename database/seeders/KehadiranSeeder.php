<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kehadiran;
use App\Models\Karyawan;
use Carbon\Carbon;

class KehadiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua karyawan
        $karyawans = Karyawan::all();

        // Buat data kehadiran untuk setiap karyawan
        foreach ($karyawans as $karyawan) {
            Kehadiran::create([
                'tanggal' => Carbon::now()->format('Y-m-d'), // Tanggal sekarang
                'karyawan_id' => $karyawan->id, // ID Karyawan
                'nik' => $karyawan->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'nama_jabatan' => $karyawan->jabatan->nama_jabatan,
                'jumlah_masuk' => rand(0, 1), // Masuk (0 atau 1)
                'jumlah_alpha' => rand(0, 1), // Alpha (0 atau 1)
                'jumlah_sakit' => rand(0, 1), // Sakit (0 atau 1)
                'jumlah_izin' => rand(0, 1), // Izin (0 atau 1)
                'jumlah_cuti' => rand(0, 1), // Cuti (0 atau 1)
            ]);
        }
    }
}
