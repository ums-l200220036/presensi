<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status_masuk',
    ];

    /**
     * Get the pegawai that owns the absensi.
     */
    public function pegawai() // Mengganti 'user()' menjadi 'pegawai()'
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id'); // Merujuk ke model Pegawai
    }
}