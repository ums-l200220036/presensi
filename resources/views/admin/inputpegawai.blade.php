<x-headeradmin>
  <div class="flex items-center justify-center">
      <div class=" rounded-lg shadow p-2 w-full max-w-xl bg-white">
          {{-- Menampilkan pesan error validasi --}}
          @if ($errors->any())
              <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          {{-- Menampilkan pesan sukses --}}
          @if (session('success'))
              <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                  {{ session('success') }}
              </div>
          @endif

          {{-- Definisikan array untuk Jabatan dan Bidang --}}
          @php
              $jabatans = ['Anggota', 'Sekretaris', 'Kepala', 'Staf Khusus'];
              $bidangs = [
                  'Bidang Teknologi Informasi',
                  'Bidang Keuangan',
                  'Bidang Statistik',
                  'Bidang Umum',
                  'Bidang Media Komunikasi',
                  'Bidang Humas'
              ];
          @endphp

          <form action="{{ route('pegawai.store') }}" method="POST" class="space-y-4">
              @csrf
              <div>
                  <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                  <input type="text" id="name" name="name" value="{{ old('name') }}" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                  @error('name')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                  @enderror
              </div>

              <div>
                  <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                  <input type="email" id="email" name="email" value="{{ old('email') }}" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                  @error('email')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                  @enderror
              </div>

              <div>
                  <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                  <input type="password" id="password" name="password" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                  @error('password')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                  @enderror
              </div>

              <div>
                  <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                  <input type="password" id="password_confirmation" name="password_confirmation" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
              </div>

              <div>
                  <label for="jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                  <select id="jabatan" name="jabatan" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('jabatan') border-red-500 @enderror">
                      <option value="">-- Pilih Jabatan --</option>
                      @foreach($jabatans as $jabatan)
                          <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                      @endforeach
                  </select>
                  @error('jabatan')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                  @enderror
              </div>

              <div>
                  <label for="bidang" class="block text-sm font-medium text-gray-700">Bidang</label>
                  <select id="bidang" name="bidang" required
                      class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('bidang') border-red-500 @enderror">
                      <option value="">-- Pilih Bidang --</option>
                      @foreach($bidangs as $bidang)
                          <option value="{{ $bidang }}" {{ old('bidang') == $bidang ? 'selected' : '' }}>{{ $bidang }}</option>
                      @endforeach
                  </select>
                  @error('bidang')
                      <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                  @enderror
              </div>

              <div class="text-right">
                  <button type="submit"
                      class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-semibold transition">
                      Simpan
                  </button>
              </div>
          </form>
      </div>
  </div>
</x-headeradmin>