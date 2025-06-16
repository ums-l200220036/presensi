<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Pegawai; // Menggunakan model Pegawai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk Auth facade
use Carbon\Carbon; // Digunakan untuk manipulasi waktu
use Illuminate\Support\Facades\Log; // Untuk logging, sangat direkomendasikan untuk debug IP

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman dashboard pegawai dengan status absensi hari ini dan statistik bulanan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mengambil ID pengguna yang sedang login dari guard 'pegawai'
        $pegawaiId = Auth::guard('pegawai')->id();
        $today = now()->toDateString(); // Mengambil tanggal hari ini

        // Mengambil data absensi untuk pengguna yang login pada hari ini
        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $today)
            ->first();

        // Menghitung kehadiran dan ketidakhadiran untuk bulan ini
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $kehadiranBulanIni = Absensi::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereIn('status_masuk', ['tepat_waktu', 'terlambat'])
            ->count();

        $tidakHadirBulanIni = Absensi::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->where('status_masuk', 'tidak_hadir')
            ->count();

        // Mengatur status sesi 'absen_today' untuk mengontrol tampilan tombol di frontend (Alpine.js)
        if ($absensi && $absensi->jam_masuk && !$absensi->jam_pulang) {
            session(['absen_today' => true]);
        } else {
            session()->forget('absen_today');
        }

        // Meneruskan variabel ke view. Perhatikan Auth::user() sekarang adalah Pegawai.
        return view('pegawai.home', compact('absensi', 'kehadiranBulanIni', 'tidakHadirBulanIni'));
    }

    /**
     * Memproses permintaan check-in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cekIn(Request $request)
    {
        // Memastikan pengguna sudah login dan dari guard 'pegawai'
        if (!Auth::guard('pegawai')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // --- MULAI LOGIKA VALIDASI IP LOKAL JARINGAN TENDA N300 ---
        // Pastikan Anda sudah mengatur config/attendance.php dengan 'allowed_local_ips'
        $allowedLocalIps = config('attendance.allowed_local_ips');
        $userIp = $request->ip(); // Ini akan mendapatkan IP lokal perangkat klien

        // DEBUGGING: Untuk melihat IP yang terdeteksi dan IP yang diizinkan saat check-in
        // Hapus atau komen baris ini setelah debugging selesai
        // dd("IP Terdeteksi (Check In):", $userIp, "IP Diizinkan:", $allowedLocalIps);

        // Periksa apakah IP pengguna ada dalam daftar IP yang diizinkan
        if (!in_array($userIp, $allowedLocalIps)) {
            // Log peringatan jika IP tidak diizinkan untuk debugging
            Log::warning('Unauthorized IP attempt for check-in: ' . $userIp . ' by Pegawai ID: ' . Auth::guard('pegawai')->id());
            return response()->json(['success' => false, 'message' => 'Anda tidak terhubung ke jaringan Wi-Fi yang diizinkan (IP Anda: ' . $userIp . ').'], 403);
        }
        // --- AKHIR LOGIKA VALIDASI IP ---

        $pegawaiId = Auth::guard('pegawai')->id(); // Mengambil ID dari guard 'pegawai'
        $tanggal = now()->toDateString();
        // Mendapatkan waktu saat ini dengan timezone 'Asia/Jakarta'
        $currentTime = now('Asia/Jakarta');

        // Waktu Check-in (sesuaikan sesuai kebutuhan Anda, contoh ini untuk testing)
        $checkInStartTime = Carbon::createFromTimeString('00:00:00', 'Asia/Jakarta'); // Jam mulai tepat waktu
        $checkInEndTime = Carbon::createFromTimeString('00:15:00', 'Asia/Jakarta');   // Jam akhir tepat waktu (setelah ini terlambat)

        if (!$pegawaiId) {
            return response()->json(['success' => false, 'message' => 'ID pegawai tidak ditemukan.'], 400);
        }

        // Cek apakah sudah ada record absensi dengan jam_masuk untuk hari ini
        $existingAbsensi = Absensi::where('pegawai_id', $pegawaiId)
                                  ->where('tanggal', $tanggal)
                                  ->first();

        if ($existingAbsensi && $existingAbsensi->jam_masuk) {
            return response()->json(['success' => false, 'message' => 'Anda sudah melakukan Check In hari ini.'], 400);
        }

        // Validasi waktu Check In: tidak boleh check-in sebelum jam $checkInStartTime
        if ($currentTime->lt($checkInStartTime)) {
            return response()->json(['success' => false, 'message' => 'Check In belum bisa dilakukan. Silakan coba lagi pada jam ' . $checkInStartTime->format('H:i') . '.'], 400);
        }

        // Tentukan status kehadiran berdasarkan waktu check-in
        $status = 'tepat_waktu';
        if ($currentTime->gt($checkInEndTime)) {
            $status = 'terlambat';
        }

        // Buat atau perbarui record absensi.
        $absensi = Absensi::firstOrCreate(
            ['pegawai_id' => $pegawaiId, 'tanggal' => $tanggal],
            [
                'jam_masuk' => $currentTime->toTimeString(),
                'status_masuk' => $status // Simpan status masuk
            ]
        );

        // Periksa apakah record baru dibuat atau jam_masuk diubah (jika sebelumnya null)
        if ($absensi->wasRecentlyCreated || $absensi->wasChanged('jam_masuk')) {
            session(['absen_today' => true]); // Set sesi bahwa sudah check-in
            return response()->json(['success' => true, 'message' => 'Check In berhasil. Status: ' . ($status === 'tepat_waktu' ? 'Tepat Waktu' : 'Terlambat')], 200);
        } else {
            // Fallback, seharusnya tidak tercapai jika logika 'existingAbsensi' sudah benar
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan. Absensi sudah ada dan jam masuk sudah terisi.'], 400);
        }
    }

    /**
     * Memproses permintaan check-out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cekOut(Request $request)
    {
        // Memastikan pengguna sudah login dan dari guard 'pegawai'
        if (!Auth::guard('pegawai')->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // --- MULAI LOGIKA VALIDASI IP LOKAL JARINGAN TENDA N300 ---
        // Pastikan Anda sudah mengatur config/attendance.php dengan 'allowed_local_ips'
        $allowedLocalIps = config('attendance.allowed_local_ips');
        $userIp = $request->ip(); // Ini akan mendapatkan IP lokal perangkat klien

        // DEBUGGING: Untuk melihat IP yang terdeteksi dan IP yang diizinkan saat check-out
        // Hapus atau komen baris ini setelah debugging selesai
        // dd("IP Terdeteksi (Check Out):", $userIp, "IP Diizinkan:", $allowedLocalIps);

        if (!in_array($userIp, $allowedLocalIps)) {
            Log::warning('Unauthorized IP attempt for check-out: ' . $userIp . ' by Pegawai ID: ' . Auth::guard('pegawai')->id());
            return response()->json(['success' => false, 'message' => 'Anda tidak terhubung ke jaringan Wi-Fi yang diizinkan (IP Anda: ' . $userIp . ').'], 403);
        }
        // --- AKHIR LOGIKA VALIDASI IP ---

        $pegawaiId = Auth::guard('pegawai')->id(); // Mengambil ID dari guard 'pegawai'
        $tanggal = now()->toDateString();
        // Mendapatkan waktu saat ini dengan timezone 'Asia/Jakarta'
        $currentTime = now('Asia/Jakarta');

        // Waktu Check-out (sesuaikan sesuai kebutuhan Anda, contoh ini untuk testing)
        $checkOutStartTime = Carbon::createFromTimeString('00:00:00', 'Asia/Jakarta'); // Jam mulai check-out
        $checkOutEndTime = Carbon::createFromTimeString('00:20:00', 'Asia/Jakarta');   // Jam akhir check-out

        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->where('tanggal', $tanggal)
            ->first();

        // Cek apakah ada record absensi untuk hari ini, sudah check-in, dan belum check-out
        if ($absensi && $absensi->jam_masuk && !$absensi->jam_pulang) {
            // Validasi waktu Check Out: tidak boleh check-out sebelum jam $checkOutStartTime
            if ($currentTime->lt($checkOutStartTime)) {
                return response()->json(['success' => false, 'message' => 'Check Out belum bisa dilakukan. Silakan coba lagi pada jam ' . $checkOutStartTime->format('H:i') . '.'], 400);
            }

            // Validasi waktu Check Out: tidak boleh check-out setelah jam $checkOutEndTime
            if ($currentTime->gt($checkOutEndTime)) {
                return response()->json(['success' => false, 'message' => 'Anda sudah melewati batas waktu Check Out (' . $checkOutEndTime->format('H:i') . '). Silakan hubungi admin.'], 400);
            }

            // Update jam_pulang di record absensi yang sudah ada
            $absensi->update(['jam_pulang' => $currentTime->toTimeString()]);
            session()->forget('absen_today'); // Hapus sesi setelah check-out berhasil

            return response()->json(['success' => true, 'message' => 'Check Out berhasil'], 200);

        } elseif (!$absensi || !$absensi->jam_masuk) {
            // Jika tidak ada record absensi atau belum ada jam_masuk
            return response()->json(['success' => false, 'message' => 'Anda belum melakukan Check In hari ini.'], 400);
        } else {
            // Jika absensi ada, jam_masuk ada, dan jam_pulang juga sudah ada (sudah check-out)
            return response()->json(['success' => false, 'message' => 'Anda sudah Check Out hari ini.'], 400);
        }
    }

    /**
     * Menampilkan rekap presensi bulanan untuk pegawai yang sedang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function rekap(Request $request)
    {
        $pegawaiId = Auth::guard('pegawai')->id();

        // Ambil bulan dan tahun dari request, default ke bulan dan tahun sekarang
        // Request input 'bulan' akan datang dalam format YYYY-MM
        $selectedMonthYear = $request->input('bulan', now()->format('Y-m'));

        // Pisahkan tahun dan bulan dari format YYYY-MM
        list($year, $month) = explode('-', $selectedMonthYear);

        // Buat objek Carbon untuk awal dan akhir bulan yang dipilih
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth()->endOfDay();

        // Ambil data absensi untuk pegawai yang login, di bulan yang dipilih
        $daftarPresensi = Absensi::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('tanggal', 'asc')
            ->get();

        // Siapkan data untuk dropdown filter bulan/tahun
        $availableMonths = [];
        // Misalnya, tampilkan 12 bulan terakhir dari bulan saat ini
        for ($i = 0; $i < 12; $i++) { // Tampilkan 12 bulan ke belakang
            $date = Carbon::now()->subMonths($i);
            $availableMonths[] = [
                'value' => $date->format('Y-m'), // Format YYYY-MM untuk nilai dropdown
                'label' => $date->translatedFormat('F Y'), // Format Bahasa Indonesia (Juni 2025)
            ];
        }
        // Urutkan dari bulan terlama ke terbaru (opsional, tergantung preferensi)
        // Jika Anda ingin bulan terbaru di atas, jangan gunakan array_reverse
        $availableMonths = array_reverse($availableMonths);


        // Teruskan data ke view, termasuk selectedMonthYear untuk menandai option yang dipilih
        return view('pegawai.rekap', compact('daftarPresensi', 'availableMonths', 'selectedMonthYear'));
    }
}