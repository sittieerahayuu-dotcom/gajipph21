<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data jabatan
        $jabatans = [
            [
                'nama_jabatan' => 'Manager',
                'gaji_pokok' => 10000000,
                'tunjangan_makan' => 500000,
                'tunjangan_transportasi' => 400000,
                'status_jabatan' => 'Aktif',
            ],
            [
                'nama_jabatan' => 'Staff',
                'gaji_pokok' => 5000000,
                'tunjangan_makan' => 500000,
                'tunjangan_transportasi' => 400000,
                'status_jabatan' => 'Aktif',
            ]
        ];

        // Reset indeks array
        $jabatans = array_values($jabatans);

        // Insert data jabatan
        foreach ($jabatans as $jabatan) {
            Jabatan::create([
                'nama_jabatan' => $jabatan['nama_jabatan'],
                'gaji_pokok' => $jabatan['gaji_pokok'],
                'tunjangan_makan' => $jabatan['tunjangan_makan'],
                'tunjangan_transportasi' => $jabatan['tunjangan_transportasi'],
                'tunjangan_bpjs_kesehatan' => $jabatan['gaji_pokok'] * 0.04,   // 4% dari gaji pokok
                'tunjangan_jkm' => $jabatan['gaji_pokok'] * 0.003,             // 0,30% dari gaji pokok
                'tunjangan_jkk' => $jabatan['gaji_pokok'] * 0.0054,            // 0,54% dari gaji pokok
                'biaya_jabatan' => $jabatan['gaji_pokok'] * 0.05,              // 5% dari gaji pokok

            ]);
        }
    }
}
