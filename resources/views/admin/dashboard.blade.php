<x-headeradmin>



    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Hadir Hari Ini</p>
                    <p class="text-2xl font-bold">87</p>
                </div>
                <div class="bg-green-100 text-green-600 p-3 rounded-full">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Terlambat</p>
                    <p class="text-2xl font-bold">5</p>
                </div>
                <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between">
                <div>
                    <p class="text-gray-500">Total Pegawai</p>
                    <p class="text-2xl font-bold">{{ $totalPegawai }}</p>
                </div>
                <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Presensi Hari Ini -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-lg">Presensi Hari Ini</h3>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Pulang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">Andi Wijaya</td>
                            <td class="px-6 py-4 whitespace-nowrap">08:00</td>
                            <td class="px-6 py-4 whitespace-nowrap">17:00</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Hadir</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-600 hover:text-blue-800">Detail</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">Budi Santoso</td>
                            <td class="px-6 py-4 whitespace-nowrap">08:15</td>
                            <td class="px-6 py-4 whitespace-nowrap">-</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Terlambat</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-600 hover:text-blue-800">Detail</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">Citra Dewi</td>
                            <td class="px-6 py-4 whitespace-nowrap">-</td>
                            <td class="px-6 py-4 whitespace-nowrap">-</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Izin</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="text-blue-600 hover:text-blue-800">Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
</x-headeradmin>