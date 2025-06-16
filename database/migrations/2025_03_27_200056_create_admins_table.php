<?php
// database/migrations/xxxx_xx_xx_create_admins_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Pastikan ini ada
use Illuminate\Support\Facades\Hash; // Pastikan ini ada
use Carbon\Carbon; // Pastikan ini ada untuk now()

return new class extends Migration
{
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken(); // Penting untuk remember me functionality
            $table->timestamps();
        });

        // Data awal admin (bisa ditambah sesuai kebutuhan)
        $admins = [
            [
                'name' => 'Admin Utama',
                'email' => 'admin@example.com', // Email admin untuk login
                'password' => Hash::make('password'), // Password admin
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // Anda bisa tambahkan admin lain di sini
            /*
            [
                'name' => 'Admin Divisi',
                'email' => 'divisi@example.com',
                'password' => Hash::make('divisi123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            */
        ];

        // Memasukkan data admin ke tabel 'admins'
        DB::table('admins')->insert($admins);
    }

    public function down()
    {
        Schema::dropIfExists('admins');
    }
};