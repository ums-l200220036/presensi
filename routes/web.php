<?php
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route; // Pastikan ini ada

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

    // Untuk menampilkan form edit
    Route::get('/pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('pegawai.edit');

    // Untuk menyimpan perubahan (update)
    Route::put('/pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('pegawai.update');
});

// Pegawai Routes (dilindungi oleh middleware 'auth:pegawai')
Route::group(['middleware' => ['auth:pegawai']], function () {
    // Rute ini akan memanggil AbsensiController@index untuk menampilkan halaman home pegawai
    Route::get('pegawai/home', [AbsensiController::class, 'index'])->name('pegawai.home');

    // Rute untuk halaman rekap absensi
    // Anda bisa mengganti ini dengan AbsensiController@rekap jika Anda membuat method rekap di AbsensiController
    Route::get('rekap', function() {
        return view('pegawai.rekap');
    })->name('rekap');

    // Rute untuk proses check-in absensi
    Route::post('/absensi/cek-in', [AbsensiController::class, 'cekIn'])->name('absensi.cekIn');
    // Rute untuk proses check-out absensi
    Route::post('/absensi/cek-out', [AbsensiController::class, 'cekOut'])->name('absensi.cekOut');

    // Rute ini juga memanggil AbsensiController@index, jika Anda memiliki link lain ke '/absensi'
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
});