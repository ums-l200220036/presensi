<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <--- TAMBAHKAN BARIS INI

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'check_in_start', 'check_in_end', 'check_out_start', 'check_out_end'
            $table->string('value'); // Store time as string (e.g., '08:00:00')
            $table->timestamps();
        });

        // Insert default values after table creation
        DB::table('settings')->insert([
            ['key' => 'check_in_start', 'value' => '08:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'check_in_end', 'value' => '08:15:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'check_out_start', 'value' => '17:00:00', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'check_out_end', 'value' => '17:30:00', 'created_at' => now(), 'updated_at' => now()],
            // Add other settings if needed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};