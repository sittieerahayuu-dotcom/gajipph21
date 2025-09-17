<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->role == 1) {
                return redirect()->route('dashboard'); // admin/staff penggajian
            } elseif ($user->role == 2) {
                return redirect()->route('dashboard'); // direktur
            } elseif ($user->role == 3) {
                return redirect()->route('kehadiran.index'); // halaman absensi mandiri karyawan
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }


    // Logout
    public function logout(Request $request)
    {
        // Log out pengguna
        Auth::logout();

        // Hapus data sesi pengguna
        $request->session()->invalidate();

        // Regenerasi token CSRF untuk mencegah serangan CSRF
        $request->session()->regenerateToken();

        // Alihkan pengguna ke halaman login
        return redirect()->route('login');
    }
}
