<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        User::create(
            [
                "name"     => "Siti Nurindah Sari",
                "email"     => "Sitinurindahsari@gmail.com",
                "password"  => Hash::make("staffgaji123"),
                "role"   => 1

            ]
        );
        User::create([
            "name"     => "Yasmine Assegaf",
            "email"     => "Yasmineassegaf@gmail.com",
            "password"  => Hash::make("direktur123"),
            "role"   => 2
        ]);
        User::create([
            "name"     => "Siti Rahayu",
            "email"     => "Sitirhy@gmail.com",
            "password"  => Hash::make("Karyawan001"),
            "role"   => 3
        ]);
    }
}
