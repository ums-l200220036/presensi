<?php
// database/migrations/xxxx_xx_xx_create_pegawais_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB; // Pastikan ini ada
use Illuminate\Support\Facades\Hash; // Pastikan ini ada
use Illuminate\Support\Facades\Schema;

class CreatePegawaisTable extends Migration
{
    public function up()
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('jabatan');
            $table->string('bidang');
            $table->rememberToken(); // Penting untuk remember me functionality
            $table->timestamps();
        });

        // Data awal pegawai (bisa ditambah sesuai kebutuhan)
        $pegawais = [
            [
                'name' => 'Yakub Firman Mustofa',
                'email' => 'pegawai@example.com',
                'password' => Hash::make('pegawai1'),
                'jabatan' => 'Staf',
                'bidang' => 'Keuangan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'password' => Hash::make('pegawai2'),
                'jabatan' => 'Manajer',
                'bidang' => 'SDM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andi Pratama',
                'email' => 'andi@example.com',
                'password' => Hash::make('pegawai3'),
                'jabatan' => 'Staff IT',
                'bidang' => 'Teknologi Informasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('pegawais')->insert($pegawais);
    }

    public function down()
    {
        Schema::dropIfExists('pegawais');
    }
}