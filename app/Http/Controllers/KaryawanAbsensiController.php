<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KaryawanAbsensiController extends Controller
{
    // Halaman absensi mandiri
    public function index()
    {
        return view('karyawan.absensi');
    }

    // Proses simpan absensi
    public function store(Request $request)
    {
        $user = Auth::user();

        // cari karyawan berdasarkan nama user yang login
        $karyawan = DB::table('karyawans')
            ->where('nama_karyawan', $user->name)
            ->first();

        if (!$karyawan) {
            return back()->with('error', 'Data karyawan tidak ditemukan.');
        }

        // Input absensi hari ini
        DB::table('kehadirans')->updateOrInsert(
            [
                'karyawan_id' => $karyawan->id,
                'tanggal'     => date('Y-m-d'),
            ],
            [
                'nik'           => $karyawan->nik,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'nama_jabatan'  => DB::table('jabatans')->where('id', $karyawan->jabatan_id)->value('nama_jabatan'),
                'jumlah_masuk'  => 1,
                'updated_at'    => now(),
                'created_at'    => now(),
            ]
        );

        return back()->with('success', 'Absensi berhasil dicatat.');
    }
}
