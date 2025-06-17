<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\Admin\SettingController; // <--- Import SettingController di sini

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth Routes (Login & Logout)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (dilindungi oleh middleware 'auth:admin')
// Pastikan guard 'admin' sudah dikonfigurasi di config/auth.php
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Routes for Pegawai Management
    Route::get('input-pegawai', function () {
        return view('admin.inputpegawai'); // Pastikan view ini ada
    })->name('input-pegawai');
    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');
    Route::get('/pegawai', [PegawaiController::class, 'list'])->name('pegawai.index');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');

    // ROUTE BARU UNTUK REKAP ABSENSI SEMUA PEGAWAI DI ADMIN
    Route::get('/admin/rekapadmin', [DashboardController::class, 'rekapAllAbsensi'])->name('admin.rekap.absensi');

    // ROUTE UNTUK PENGATURAN JAM ABSENSI (SettingController)
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::put('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

// Pegawai Routes (dilindungi oleh middleware 'auth:pegawai')
// Pastikan guard 'pegawai' sudah dikonfigurasi di config/auth.php
Route::group(['middleware' => ['auth:pegawai']], function () {
    Route::get('pegawai/home', [AbsensiController::class, 'index'])->name('pegawai.home');

    // Route for Rekap Absensi (Pegawai sendiri)
    // Note: Anda memiliki 'rekap' dan '/absensi' yang mengarah ke AbsensiController@index
    // Jika 'rekap' hanya menampilkan rekap bulanan, sebaiknya namanya lebih spesifik atau tetap 'rekap'.
    // Saya asumsikan 'rekap' adalah untuk rekap bulanan di sisi pegawai.
    Route::get('rekap', [AbsensiController::class, 'rekap'])->name('rekap');

    // Routes for Check-in/Check-out
    Route::post('/absensi/cek-in', [AbsensiController::class, 'cekIn'])->name('absensi.cekIn');
    Route::post('/absensi/cek-out', [AbsensiController::class, 'cekOut'])->name('absensi.cekOut');

    // Route '/absensi' yang mengarah ke AbsensiController::index (mungkin redundant jika sudah ada 'pegawai/home')
    // Jika 'pegawai/home' dan '/absensi' memiliki fungsi yang sama, Anda bisa menghapus salah satunya atau mengarahkannya ke view yang sama.
    // Saya biarkan dulu sesuai dengan kode Anda, tetapi perlu diperhatikan.
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
});

// Route untuk halaman akses tidak diizinkan
Route::get('/unauthorized-access', function () {
    return view('errors.unauthorized_access'); // Pastikan view ini ada
})->name('unauthorized.access');