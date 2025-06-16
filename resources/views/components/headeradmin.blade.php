<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title', 'Default Title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="font-poppins ">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-800 text-white p-4 flex flex-col relative">
            <div class="flex items-center space-x-2 mb-8">
                <img src="/images/logo-presensi.png" alt="Presensi Logo" class="h-10 w-auto">
            </div>
            
            <nav>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2 py-2 px-4 bg-blue-700 rounded-lg mb-2">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.rekap.absensi') }}" class="flex items-center space-x-2 py-2 px-4 hover:bg-blue-700 rounded-lg mb-2">
                    <i class="fas fa-calendar-check"></i>
                    <span>Presensi</span>
                </a>
                <a href="{{ route('pegawai.index') }}" class="flex items-center space-x-2 py-2 px-4 hover:bg-blue-700 rounded-lg mb-2">
                    <i class="fas fa-users"></i>
                    <span>Pegawai</span>
                </a>
                <a href="{{ route('input-pegawai') }}" class="flex items-center space-x-2 py-2 px-4 hover:bg-blue-700 rounded-lg mb-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Tambah Pegawai</span>
                </a>
            </nav>
            <form action="{{ route('logout') }}" method="POST" class="absolute bottom-4 left-4 right-4">
                @csrf
                <button type="submit" class="flex gap-2 items-center w-full px-4 py-2 bg-blue-700 hover:bg-blue-600 rounded-lg text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6ZM5.78 8.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 0 0 0 1.06l3 3a.75.75 0 0 0 1.06-1.06l-1.72-1.72H15a.75.75 0 0 0 0-1.5H4.06l1.72-1.72a.75.75 0 0 0 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>            
        </div>
        <div class="flex-1 p-8 overflow-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Presensi</h2>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-user text-3xl text-blue-600"></i>
                        <span class="font-medium">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </div>
    
            {{ $slot }}

        </div>

        <!-- Main Content -->
        
    </div>        
</body>
</html>