<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi; // Import model Absensi
use Carbon\Carbon; // Import Carbon untuk waktu

class AutoCheckOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:auto-checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically checks out employees who have not checked out by the end of the day.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();
        // Waktu batas untuk auto check-out, misalnya setelah jam 16:00
        $autoCheckoutTime = Carbon::createFromTimeString('16:00:00', 'Asia/Jakarta')->toTimeString();

        $this->info('Mulai proses auto check-out untuk tanggal ' . $today . '...');

        // Cari semua absensi hari ini yang sudah check-in tapi belum check-out
        $absensiToAutoCheckout = Absensi::where('tanggal', $today)
                                        ->whereNotNull('jam_masuk')
                                        ->whereNull('jam_pulang')
                                        ->get();

        if ($absensiToAutoCheckout->isEmpty()) {
            $this->info('Tidak ada absensi yang perlu di-auto check-out hari ini.');
            return Command::SUCCESS;
        }

        foreach ($absensiToAutoCheckout as $absensi) {
            // Perbarui jam_pulang dengan waktu auto check-out
            $absensi->update([
                'jam_pulang' => $autoCheckoutTime
            ]);
            $this->info('Pegawai ID ' . $absensi->pegawai_id . ' di-auto check-out pada jam ' . $autoCheckoutTime . ' untuk tanggal ' . $today . '.');
        }

        $this->info('Proses auto check-out selesai.');
        return Command::SUCCESS;
    }
}