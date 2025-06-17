<x-headeradmin>
    <div class="max-w-4xl mx-auto mt-6 px-4 sm:px-6 space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-semibold text-gray-800">Rekap Presensi Semua Pegawai</h2>
            <form action="{{ route('admin.rekap.absensi') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
                {{-- Filter Bulan --}}
                <select name="bulan" id="month-filter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @foreach($availableMonths as $m)
                        <option value="{{ $m['value'] }}" {{ $m['value'] == $selectedMonthYear ? 'selected' : '' }}>
                            {{ $m['label'] }}
                        </option>
                    @endforeach
                </select>

                {{-- Filter Pegawai --}}
                <select name="pegawai_id" id="pegawai-filter" class="border border-gray-300 rounded-lg px-4 py-2 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    <option value="">-- Semua Pegawai --</option>
                    @foreach($allPegawai as $pegawai)
                        <option value="{{ $pegawai->id }}" {{ $pegawai->id == $selectedPegawaiId ? 'selected' : '' }}>
                            {{ $pegawai->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl shadow-md">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-blue-600 text-white text-center">
                    <tr>
                        <th class="px-6 py-3 font-semibold tracking-wide">Hari, Tanggal</th>
                        <th class="px-6 py-3 font-semibold tracking-wide">Nama Pegawai</th> {{-- Kolom baru --}}
                        <th class="px-6 py-3 font-semibold tracking-wide">Keterangan</th>
                        <th class="px-6 py-3 font-semibold tracking-wide">Jam Masuk</th>
                        <th class="px-6 py-3 font-semibold tracking-wide">Jam Pulang</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($daftarPresensi as $presensi)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 text-center text-gray-700">
                                {{ \Carbon\Carbon::parse($presensi->tanggal)->translatedFormat('l, j F Y') }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700 font-medium">
                                {{ $presensi->pegawai->name ?? 'N/A' }} {{-- Akses nama pegawai dari relasi --}}
                            </td>
                            <td class="px-6 py-4 text-center font-medium">
                                @if($presensi->status_masuk == 'tepat_waktu')
                                    <span class="text-green-600">Masuk (Tepat Waktu)</span>
                                @elseif($presensi->status_masuk == 'terlambat')
                                    <span class="text-orange-500">Masuk (Terlambat)</span>
                                @elseif($presensi->status_masuk == 'tidak_hadir')
                                    <span class="text-red-600">Tidak Hadir</span>
                                @else
                                    <span class="text-gray-500">Belum Ada Keterangan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">
                                {{ $presensi->jam_masuk ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-700">
                                {{ $presensi->jam_pulang ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data presensi untuk bulan ini atau filter yang dipilih.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-headeradmin>