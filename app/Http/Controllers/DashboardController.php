<?php

namespace App\Http\Controllers;

use App\Models\Absensi; // Pastikan model Absensi terimport
use App\Models\Pegawai; // Pastikan model Pegawai terimport
use Illuminate\Http\Request;
use Carbon\Carbon; // Pastikan Carbon terimport

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama untuk admin.
     * Mengambil data statistik absensi hari ini dan total pegawai.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil tanggal hari ini dalam format YYYY-MM-DD
        $today = now()->toDateString();

        // Statistik Hadir Hari Ini:
        // Menghitung absensi dengan status 'tepat_waktu' atau 'terlambat' untuk hari ini.
        $hadirHariIni = Absensi::where('tanggal', $today)
                              ->whereIn('status_masuk', ['tepat_waktu', 'terlambat'])
                              ->count();

        // Statistik Terlambat Hari Ini:
        // Menghitung absensi dengan status 'terlambat' untuk hari ini.
        $terlambatHariIni = Absensi::where('tanggal', $today)
                                   ->where('status_masuk', 'terlambat')
                                   ->count();

        // Statistik Tidak Hadir Hari Ini:
        // Menghitung absensi dengan status 'tidak_hadir' untuk hari ini.
        $tidakHadirHariIni = Absensi::where('tanggal', $today)
                                    ->where('status_masuk', 'tidak_hadir')
                                    ->count();

        // Menghitung total jumlah pegawai dari tabel 'pegawais'.
        $totalPegawai = Pegawai::count();

        // Mengambil daftar presensi untuk hari ini saja.
        // Menggunakan with('pegawai') untuk eager loading relasi 'pegawai'.
        // Ini mencegah masalah N+1 query saat mengakses nama pegawai di view.
        // Diurutkan berdasarkan jam masuk.
        $presensiHariIni = Absensi::with('pegawai')
                                  ->where('tanggal', $today)
                                  ->orderBy('jam_masuk', 'asc')
                                  ->get();

        // Meneruskan semua variabel yang telah dihitung ke view 'admin.dashboard'.
        return view('admin.dashboard', compact(
            'totalPegawai',
            'hadirHariIni',
            'terlambatHariIni',
            'tidakHadirHariIni',
            'presensiHariIni'
        ));
    }

    /**
     * Menampilkan rekap presensi bulanan untuk semua pegawai.
     * Halaman ini ditujukan untuk admin, sehingga dapat melihat data presensi seluruh pegawai.
     * Memiliki fitur filter berdasarkan bulan dan juga berdasarkan pegawai tertentu.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function rekapAllAbsensi(Request $request)
    {
        // Mengambil bulan dan tahun dari parameter request, default ke bulan dan tahun saat ini.
        // Format input 'bulan' diharapkan dalam YYYY-MM (misalnya '2025-06').
        $selectedMonthYear = $request->input('bulan', now()->format('Y-m'));

        // Memisahkan tahun dan bulan dari string 'YYYY-MM'.
        // Fungsi list() digunakan untuk secara langsung menetapkan elemen array ke variabel.
        list($year, $month) = explode('-', $selectedMonthYear);

        // Mengambil ID pegawai yang dipilih dari filter, default ke null (yang berarti "semua pegawai").
        $selectedPegawaiId = $request->input('pegawai_id');

        // Menentukan tanggal awal dan akhir untuk rentang bulan yang dipilih.
        // startOfDay() dan endOfDay() memastikan bahwa seluruh hari tercakup.
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        // Membangun query dasar untuk mengambil data absensi.
        // Eager loading relasi 'pegawai' sangat penting di sini untuk performa.
        $query = Absensi::with('pegawai')
                        ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
                        ->orderBy('tanggal', 'asc'); // Mengurutkan berdasarkan tanggal dari terlama ke terbaru.

        // Menambahkan kondisi filter berdasarkan pegawai jika $selectedPegawaiId tidak kosong.
        if ($selectedPegawaiId) {
            $query->where('pegawai_id', $selectedPegawaiId);
        }

        // Mengeksekusi query dan mendapatkan koleksi daftar presensi.
        $daftarPresensi = $query->get();

        // Menyiapkan data untuk dropdown filter bulan/tahun di tampilan.
        $availableMonths = [];
        // Loop untuk mengisi 12 bulan terakhir dari bulan saat ini.
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $availableMonths[] = [
                'value' => $date->format('Y-m'), // Nilai yang akan dikirim saat submit form (misalnya "2025-06").
                'label' => $date->translatedFormat('F Y'), // Teks yang ditampilkan di dropdown (misalnya "Juni 2025").
            ];
        }
        // Mengurutkan array bulan dari yang terlama ke terbaru untuk tampilan dropdown yang umum.
        $availableMonths = array_reverse($availableMonths);

        // Mengambil semua data pegawai dari database untuk dropdown filter pegawai di tampilan.
        // Diurutkan berdasarkan nama pegawai untuk tampilan yang rapi.
        $allPegawai = Pegawai::orderBy('name', 'asc')->get();

        // Meneruskan semua data yang diperlukan ke view 'admin.rekapallabsensi'.
        // 'compact()' adalah cara yang rapi untuk meneruskan variabel ke view.
        return view('admin/rekapadmin', compact('daftarPresensi', 'availableMonths', 'selectedMonthYear', 'allPegawai', 'selectedPegawaiId'));
    }
}