<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\PotonganGajiController;
use App\Http\Controllers\PerhitunganPphController;
use App\Http\Controllers\PerhitunganGajiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanAbsensiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan ditugaskan ke grup
| middleware "web". Buat sesuatu yang hebat!
|
*/

// Halaman Pertama
Route::redirect('/', 'login');

// Rute untuk autentikasi pengguna
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang dilindungi oleh autentikasi
Route::middleware(['auth'])->group(function () {
 Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Manajemen Pengguna
    Route::resource('users', UserController::class);

    // Manajemen Jabatan
    Route::resource('jabatans', JabatanController::class);

    // Manajemen Karyawan
    Route::resource('karyawan', KaryawanController::class);

   // Route khusus sebelum resource
Route::get('/kehadiran/rekap', [KehadiranController::class, 'rekap'])->name('kehadiran.rekap');
Route::get('/laporan-kehadiran', [KehadiranController::class, 'laporan'])->name('kehadiran.laporan');
Route::get('/cetak-kehadiran', [KehadiranController::class, 'cetakLaporan'])->name('kehadiran.cetak');
Route::post('/kehadiran/hapus-bulanan', [KehadiranController::class, 'hapusBulanan'])->name('kehadiran.hapus-bulanan');

// Resource route untuk CRUD harian
Route::resource('kehadiran', KehadiranController::class);


    Route::get('/karyawan/absensi', [KaryawanAbsensiController::class, 'index'])->name('karyawan.absensi');
    Route::post('/karyawan/absensi', [KaryawanAbsensiController::class, 'store'])->name('karyawan.absensi.store');


    // Manajemen Potongan Gaji
    Route::resource('potongangaji', PotonganGajiController::class);

    // Manajemen Perhitungan pph
    Route::get('/perhitungan-pph', [PerhitunganPphController::class, 'index'])->name('perhitungan-pph.index');
     // Hapus pph
    Route::delete('/perhitungan-pph/{id}', [PerhitunganPphController::class, 'destroy'])->name('perhitungan-pph.destroy');
    Route::post('/perhitungan-pph/hapus-bulanan', [PerhitunganPphController::class, 'hapusBulanan'])->name('perhitungan-pph.hapus-bulanan');
    // Laporan & cetak PPh 21
    Route::get('/laporan-pph', [PerhitunganPphController::class, 'laporan'])->name('perhitungan-pph.laporan');
    Route::get('/laporan-pph/cetak', [PerhitunganPphController::class, 'cetakLaporan'])->name('perhitungan-pph.cetak');





    // Manajemen Perhitungan Gaji
    Route::get('/perhitungan-gaji', [PerhitunganGajiController::class, 'index'])->name('perhitungan-gaji.index');

    // hapus sebulan
    Route::post('/perhitungan-gaji/hapus-bulanan', [PerhitunganGajiController::class, 'hapusBulanan'])->name('perhitungan-gaji.hapus-bulanan');


    // Detail gaji
    Route::get('/perhitungan-gaji/{id}', [PerhitunganGajiController::class, 'show'])->name('perhitungan-gaji.show');

    // Hapus gaji
    Route::delete('/perhitungan-gaji/{id}', [PerhitunganGajiController::class, 'destroy'])->name('perhitungan-gaji.destroy');

    // Laporan & cetak
    Route::get('/laporan-gaji', [PerhitunganGajiController::class, 'laporan'])->name('perhitungan-gaji.laporan');
    Route::get('/laporan-gaji/cetak', [PerhitunganGajiController::class, 'cetakLaporan'])->name('perhitungan-gaji.cetak');

    Route::get('/slip-gaji', [PerhitunganGajiController::class, 'slipGajiForm'])->name('slip-gaji.form');
Route::get('/slip-gaji/cetak', [PerhitunganGajiController::class, 'cetakSlipGaji'])->name('slip-gaji.cetak');





});
