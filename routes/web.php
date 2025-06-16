<?php
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController; // Pastikan ini terimport
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Routes (Login & Logout)
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (dilindungi oleh middleware 'auth:admin')
Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('input-pegawai', function () {
        return view('admin.inputpegawai');
    })->name('input-pegawai');

    Route::post('/pegawai', [PegawaiController::class, 'store'])->name('pegawai.store');

    Route::get('/pegawai', [PegawaiController::class, 'list'])->name('pegawai.index');
    Route::delete('/pegawai/{pegawai}', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');

    Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');

    // ROUTE BARU UNTUK REKAP ABSENSI SEMUA PEGAWAI DI ADMIN
    Route::get('/admin/rekapadmin', [DashboardController::class, 'rekapAllAbsensi'])->name('admin.rekap.absensi');
});

// Pegawai Routes (dilindungi oleh middleware 'auth:pegawai')
Route::group(['middleware' => ['auth:pegawai']], function () {
    Route::get('pegawai/home', [AbsensiController::class, 'index'])->name('pegawai.home');

    Route::get('rekap', [AbsensiController::class, 'rekap'])->name('rekap');

    Route::post('/absensi/cek-in', [AbsensiController::class, 'cekIn'])->name('absensi.cekIn');
    Route::post('/absensi/cek-out', [AbsensiController::class, 'cekOut'])->name('absensi.cekOut');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
});

// Route untuk halaman akses tidak diizinkan
Route::get('/unauthorized-access', function () {
    return view('errors.unauthorized_access');
})->name('unauthorized.access');