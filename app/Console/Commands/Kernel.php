<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1. Penjadwalan command untuk menandai pegawai yang "Tidak Hadir"
        //    Ini akan berjalan setiap hari pada pukul 13:35 WIB.
        //    Akan mencari pegawai yang belum check-in sama sekali hingga batas waktu 13:30.
        $schedule->command('absensi:mark-missing')->dailyAt('13:35');

        // 2. Penjadwalan command untuk "Auto Check-Out"
        //    Ini akan berjalan setiap hari pada pukul 16:05 WIB.
        //    Akan mencari pegawai yang sudah check-in tetapi belum check-out,
        //    dan akan secara otomatis mengisi jam_pulang mereka ke 16:00.
        $schedule->command('absensi:auto-checkout')->dailyAt('16:05');

        // Contoh: Jika Anda memiliki command lain di masa depan, Anda bisa menambahkannya di sini.
        // $schedule->command('nama:command-lain')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // Memuat semua command yang ada di folder 'app/Console/Commands/'
        $this->load(__DIR__.'/Commands');

        // Ini diperlukan untuk mendaftarkan command Artisan yang Anda definisikan di routes/console.php
        require base_path('routes/console.php');
    }
}