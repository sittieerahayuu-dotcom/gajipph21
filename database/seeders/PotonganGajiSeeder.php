<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PotonganGaji;

class PotonganGajiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan beberapa data potongan gaji awal
        PotonganGaji::create([
            'jenis_potongan' => 'Alpha',
            'nilai_potongan' => 100000,
        ]);

        PotonganGaji::create([
            'jenis_potongan' => 'Cuti di luar jatah cuti',
            'nilai_potongan' => 100000,
        ]);
    }
}
