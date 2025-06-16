<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web', // Default guard (akan kita sesuaikan di route/controller)
        'passwords' => 'users', // Default password broker (akan kita ubah di bawah)
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here. You may now add more guards if you like.
    |
    | Laravel has several default guard drivers: session and token. Of course,
    | you may develop your own custom drivers.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users', // Akan kita hapus jika tidak pakai tabel users
        ],
        // Guard untuk Admin
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins', // Menggunakan provider 'admins'
        ],
        // Guard untuk Pegawai
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
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have several different user tables or models, you may configure
    | multiple sources and pass the provider driving each guard.
    |
    */

    'providers' => [
        'users' => [ // Jika Anda ingin benar-benar menghapus tabel users, hapus blok ini
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        // Provider untuk Admin
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        // Provider untuk Pegawai
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
    | You may specify all of the password reset settings for your application.
    | The "users" provider corresponds to the password reset table that should
    | be utilized for retrieving s, or you may setup multiple password
    | reset configurations here.
    |
    */

    'passwords' => [
        'users' => [ // Jika Anda menghapus tabel users, ini juga bisa dihapus
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        // Password broker untuk Admin
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens', // Bisa pakai tabel yang sama atau buat baru
            'expire' => 60,
            'throttle' => 60,
        ],
        // Password broker untuk Pegawai
        'pegawais' => [
            'provider' => 'pegawais',
            'table' => 'password_reset_tokens', // Bisa pakai tabel yang sama atau buat baru
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds that have elapsed since a great
    | number of seconds that have elapsed since a user was last confirmed to
    | have their password recently.
    |
    */

    'password_timeout' => 10800,

];