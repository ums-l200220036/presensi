<x-header>
    <div class="container mx-auto px-4 py-8 max-w-md">

        {{-- Bagian Profil Pengguna (menggunakan Auth::guard('pegawai')->user()) --}}
        <div class="bg-gradient-to-r from-blue-50 to-white rounded-2xl p-5 mb-6 border border-gray-200 shadow-md hover:shadow-lg transition">
            <div class="flex items-center space-x-4">
                <div class="relative w-16 h-16">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                </div>

                <div>
                    {{-- Menggunakan Auth::guard('pegawai')->user() untuk data pegawai --}}
                    <h2 class="text-xl font-bold text-gray-800 leading-snug">{{ Auth::guard('pegawai')->user()->name }}</h2>
                    <div class="flex flex-wrap items-center space-x-2 mt-1">
                        <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-0.5 rounded-full">{{ Auth::guard('pegawai')->user()->jabatan }}</span>
                        <span class="bg-indigo-100 text-indigo-700 text-xs font-medium px-2 py-0.5 rounded-full">{{ Auth::guard('pegawai')->user()->bidang }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tombol Check In/Check Out (Menggunakan Alpine.js) --}}
        <div x-data="{ checkedIn: {{ session('absen_today') ? 'true' : 'false' }} }" class="mb-6">
            <template x-if="!checkedIn">
                {{-- Tombol Check In akan terlihat jika belum check-in hari ini --}}
                <div @click.prevent="
                    fetch('{{ route('absensi.cekIn') }}', { // Panggil route check-in
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Penting untuk keamanan Laravel
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            checkedIn = true; // Update state Alpine.js
                            alert(data.message); // Tampilkan pesan sukses dari controller
                            window.location.reload(); // Reload halaman untuk update tampilan data
                        } else {
                            alert('Check-in gagal: ' + data.message); // Tampilkan pesan error dari controller
                        }
                    })
                    .catch(error => {
                        console.error('Error saat check-in:', error);
                        alert('Terjadi kesalahan saat melakukan check-in.');
                    });
                    "
                    class="flex flex-col items-center justify-center bg-green-100 border border-green-300 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 cursor-pointer">
                    <p class="text-base font-semibold text-green-800">Check In</p>
                </div>
            </template>

            <template x-if="checkedIn">
                {{-- Tombol Check Out akan terlihat jika sudah check-in dan belum check-out --}}
                <div @click.prevent="
                    fetch('{{ route('absensi.cekOut') }}', { // Panggil route check-out
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Penting untuk keamanan Laravel
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            checkedIn = false; // Update state Alpine.js
                            alert(data.message); // Tampilkan pesan sukses dari controller
                            window.location.reload(); // Reload halaman untuk update tampilan data
                        } else {
                            alert('Check-out gagal: ' + data.message); // Tampilkan pesan error dari controller
                        }
                    })
                    .catch(error => {
                        console.error('Error saat check-out:', error);
                        alert('Terjadi kesalahan saat melakukan check-out.');
                    });
                    "
                    class="flex flex-col items-center justify-center bg-red-100 border border-red-300 rounded-xl p-6 shadow-md hover:shadow-xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 cursor-pointer">
                    <p class="text-base font-semibold text-red-800">Check Out</p>
                </div>
            </template>
        </div>

        {{-- Statistik Absensi Bulan Ini --}}
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-700 mb-3 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Bulan Ini</span>
            </h3>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-r from-green-50 to-white border border-green-200 rounded-xl p-5 shadow-md hover:shadow-lg transition">
                    <p class="text-3xl font-extrabold text-green-700 text-center">{{ $kehadiranBulanIni ?? 0 }}</p>
                    <p class="text-sm text-gray-600 text-center mt-1">Kehadiran</p>
                </div>

                <div class="bg-gradient-to-r from-red-50 to-white border border-red-200 rounded-xl p-5 shadow-md hover:shadow-lg transition">
                    <p class="text-3xl font-extrabold text-red-700 text-center">{{ $tidakHadirBulanIni ?? 0 }}</p>
                    <p class="text-sm text-gray-600 text-center mt-1">Tidak Hadir</p>
                </div>
            </div>
        </div>

        {{-- Link ke Rekap Presensi --}}
        <div class="mb-6">
            <a href="{{ route('rekap') }}" class="flex items-center justify-between p-5 rounded-2xl border border-blue-200 bg-gradient-to-r from-blue-50 to-white hover:from-blue-100 hover:to-white transition shadow-md hover:shadow-lg">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-base font-semibold text-blue-800">Rekap Presensi</p>
                        <p class="text-xs text-gray-500">Lihat data presensi lengkap Anda</p>
                    </div>
                </div>
                <div class="bg-blue-100 rounded-full p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </a>
        </div>

        {{-- Detail Absensi Hari Ini --}}
        <div>
            <h3 class="text-base font-semibold text-gray-700 mb-3 flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-8 8h8M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>Hari Ini</span>
            </h3>

            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5 shadow-sm">
                <div class="grid grid-cols-3 gap-4 text-center mt-6">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                        <p class="text-sm font-medium text-indigo-700">{{ now()->format('d-m-Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jam Masuk</p>
                        <p class="text-sm font-medium text-green-700">{{ $absensi?->jam_masuk ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jam Keluar</p>
                        <p class="text-sm font-medium text-red-600">{{ $absensi?->jam_pulang ?? '-' }}</p>
                    </div>
                </div>
                {{-- Menampilkan status absensi hari ini --}}
                @if($absensi)
                    <div class="mt-4 text-center">
                        <p class="text-sm font-bold text-gray-700">Status Hari Ini:</p>
                        @if($absensi->status_masuk == 'tepat_waktu')
                            <span class="text-green-600 font-semibold">Tepat Waktu</span>
                        @elseif($absensi->status_masuk == 'terlambat')
                            <span class="text-orange-500 font-semibold">Terlambat</span>
                        @elseif($absensi->status_masuk == 'tidak_hadir')
                            <span class="text-red-600 font-semibold">Tidak Hadir</span>
                        @else
                            <span class="text-gray-500">Status tidak diketahui</span>
                        @endif
                    </div>
                @else
                    <div class="mt-4 text-center">
                        <p class="text-sm font-bold text-gray-700">Status Hari Ini:</p>
                        <span class="text-gray-500">Belum ada data absensi untuk hari ini.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-header>