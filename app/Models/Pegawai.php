<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Penting: Pegawai juga bisa diautentikasi
use Illuminate\Notifications\Notifiable;

class Pegawai extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'pegawai'; // Menentukan guard yang akan digunakan untuk model ini

    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan',
        'bidang',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // Jika Anda menambahkan verified email ke tabel pegawai
        'password' => 'hashed',
    ];

    /**
     * Get the absensis for the pegawai.
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'pegawai_id');
    }
}