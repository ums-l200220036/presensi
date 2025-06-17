
<x-headeradmin>
<div class="container mx-auto px-4 py-8">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="check_in_start" class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai Check-in (Tepat Waktu):</label>
                    <input type="time" id="check_in_start" name="check_in_start" value="{{ old('check_in_start', \Carbon\Carbon::parse($settings['check_in_start'] ?? '00:00:00')->format('H:i')) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_start') border-red-500 @enderror">
                    @error('check_in_start')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_in_end" class="block text-gray-700 text-sm font-bold mb-2">Jam Akhir Check-in (Setelah ini Terlambat):</label>
                    <input type="time" id="check_in_end" name="check_in_end" value="{{ old('check_in_end', \Carbon\Carbon::parse($settings['check_in_end'] ?? '00:00:00')->format('H:i')) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_in_end') border-red-500 @enderror">
                    @error('check_in_end')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_out_start" class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai Check-out:</label>
                    <input type="time" id="check_out_start" name="check_out_start" value="{{ old('check_out_start', \Carbon\Carbon::parse($settings['check_out_start'] ?? '00:00:00')->format('H:i')) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_out_start') border-red-500 @enderror">
                    @error('check_out_start')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="check_out_end" class="block text-gray-700 text-sm font-bold mb-2">Jam Akhir Check-out:</label>
                    <input type="time" id="check_out_end" name="check_out_end" value="{{ old('check_out_end', \Carbon\Carbon::parse($settings['check_out_end'] ?? '00:00:00')->format('H:i')) }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('check_out_end') border-red-500 @enderror">
                    @error('check_out_end')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
</x-headerad>