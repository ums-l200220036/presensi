<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Ini mengontrol guard autentikasi default dan opsi reset kata sandi.
    | Jika Anda selalu secara eksplisit menentukan guard (misal, Auth::guard('admin')),
    | maka nilai default ini kurang signifikan tetapi tetap perlu valid.
    |
    */

    'defaults' => [
        // Guard default yang akan digunakan jika tidak ada guard yang ditentukan secara eksplisit.
        // Kita bisa arahkan ke 'pegawai' karena itu mungkin guard yang paling sering diakses umum,
        // atau biarkan 'web' dan pastikan guard 'web' di bawah diarahkan ke provider yang valid.
        'guard' => 'web', // Mempertahankan 'web' sebagai default, tapi pastikan 'web' guard valid di bawah.
        'passwords' => 'pegawais', // Mengubah default password broker ke 'pegawais'.
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Mendefinisikan setiap guard autentikasi untuk aplikasi Anda.
    |
    */

    'guards' => [
        // Guard 'web' default: Kita arahkan ke provider 'pegawais' karena tabel 'users' tidak ada.
        // Ini adalah pilihan umum jika Anda masih ingin mempertahankan guard 'web' tanpa tabel 'users'.
        'web' => [
            'driver' => 'session',
            'provider' => 'pegawais', // Guard 'web' sekarang menggunakan provider 'pegawais'
        ],
        // Guard khusus untuk Admin
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // Menggunakan provider 'admins'
        ],
        // Guard khusus untuk Pegawai
        'pegawai' => [
            'driver' => 'session',
            'provider' => 'pegawais', // Menggunakan provider 'pegawais'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Ini mendefinisikan bagaimana pengguna diambil dari database.
    |
    */

    'providers' => [
        // Provider 'users' DIHAPUS SEPENUHNYA karena tabel 'users' tidak digunakan.
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'pegawais' => [
            'driver' => 'eloquent',
            'model' => App\Models\Pegawai::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Mengkonfigurasi pengaturan reset kata sandi.
    |
    */

    'passwords' => [
        // Password broker 'users' DIHAPUS SEPENUHNYA.
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'pegawais' => [
            'provider' => 'pegawais',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];