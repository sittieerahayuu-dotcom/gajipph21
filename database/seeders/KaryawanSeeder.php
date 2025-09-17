<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data karyawan yang ingin dimasukkan
        $karyawans = [
            [
                'nik' => '3277036503040006',
                'nama_karyawan' => 'John Doe',
                'jabatan_id' => 1,
                'jenis_kelamin' => 'Laki-Laki',
                'status' => 'Menikah',
                'jumlah_tanggungan_anak' => 2,
                'kode_ptkp' => 'K2',
                'status_karyawan' => 'Aktif', // ✅ status keaktifan
            ],
            [
                'nik' => '3277036505040008',
                'nama_karyawan' => 'Jane Doe',
                'jabatan_id' => 2,
                'jenis_kelamin' => 'Perempuan',
                'status' => 'Belum Menikah',
                'jumlah_tanggungan_anak' => 0,
                'kode_ptkp' => 'TK0',
                'status_karyawan' => 'Aktif', // ✅ status keaktifan
            ]
        ];


        // Menambahkan data menggunakan foreach
        foreach ($karyawans as $karyawan) {
            Karyawan::create([
                'no_induk' => Karyawan::generateNoInduk(),
                'nik' => $karyawan['nik'],
                'nama_karyawan' => $karyawan['nama_karyawan'],
                'jabatan_id' => $karyawan['jabatan_id'],
                'jenis_kelamin' => $karyawan['jenis_kelamin'],
                'status' => $karyawan['status'],
                'jumlah_tanggungan_anak' => $karyawan['jumlah_tanggungan_anak'],
                'kode_ptkp' => $karyawan['kode_ptkp'],
                'status_karyawan' => $karyawan['status_karyawan'],
            ]);
        }
    }
}
