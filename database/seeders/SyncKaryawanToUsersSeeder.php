<?php   // <- jangan <? saja, harus <?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SyncKaryawanToUsersSeeder extends Seeder
{
    public function run()
    {
        $karyawans = DB::table('karyawans')->get();

        foreach ($karyawans as $karyawan) {
            $exists = DB::table('users')->where('email', $karyawan->nik . '@dummy.local')->first();

            if (!$exists) {
                DB::table('users')->insert([
                    'name'       => $karyawan->nama_karyawan,
                    'email'      => $karyawan->nik . '@dummy.local',
                    'password'   => Hash::make($karyawan->nik),
                    'role'       => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
