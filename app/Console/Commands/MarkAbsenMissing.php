<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use App\Models\Pegawai; // Menggunakan model Pegawai
use Carbon\Carbon;

class MarkAbsenMissing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:mark-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marks employees who did not check in by 13:30 as absent.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();

        // Dapatkan semua pegawai dari tabel 'pegawais'
        $pegawais = Pegawai::all();

        $this->info('Mengecek absensi untuk tanggal ' . $today . '...');

        foreach ($pegawais as $pegawai) {
            $absensi = Absensi::where('pegawai_id', $pegawai->id)
                              ->where('tanggal', $today)
                              ->first();

            if (!$absensi || is_null($absensi->jam_masuk)) {
                if (!$absensi) {
                    Absensi::create([
                        'pegawai_id' => $pegawai->id,
                        'tanggal' => $today,
                        'status_masuk' => 'tidak_hadir'
                    ]);
                    $this->info('Karyawan ' . $pegawai->name . ' ditandai sebagai TIDAK HADIR.');
                } elseif ($absensi->status_masuk !== 'tidak_hadir') {
                    $absensi->update(['status_masuk' => 'tidak_hadir']);
                    $this->info('Karyawan ' . $pegawai->name . ' ditandai sebagai TIDAK HADIR.');
                }
            }
        }

        $this->info('Pengecekan absensi selesai.');
        return Command::SUCCESS;
    }
}