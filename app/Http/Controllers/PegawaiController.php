<?php

namespace App\Http\Controllers;

use App\Models\Pegawai; // Menggunakan model Pegawai
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Untuk login

class PegawaiController extends Controller
{
    // Method index ini mungkin tidak digunakan lagi jika /pegawai/home ditangani AbsensiController@index
    // Jika Anda ingin ini menjadi halaman landing umum pegawai (bukan absensi), sesuaikan.
    public function index()
    {
        return view('pegawai.home'); // Ini mungkin perlu diganti jika AbsensiController yang menangani home
    }

    public function list()
    {
        $pegawais = Pegawai::all();
        return view('admin.daftarpegawai', compact('pegawais'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawais,email', // Validasi unik di tabel 'pegawais'
            'password' => 'required|string|min:8|confirmed',
            'jabatan' => 'required|string',
            'bidang' => 'required|string',
        ]);

        $pegawai = Pegawai::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'jabatan' => $validated['jabatan'],
            'bidang' => $validated['bidang'],
        ]);

        // Login otomatis setelah registrasi (menggunakan guard 'pegawai')
        return redirect()->route('pegawai.index') // Asumsi ini route daftar pegawai di admin
            ->with('success', 'Data pegawai berhasil Tambahkan.');

        return redirect()->route('pegawai.home')->with('success', 'Pendaftaran berhasil, Anda telah login.');
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();

        // Menggunakan nama route yang benar, jika setelah hapus ingin kembali ke daftar admin
        return redirect()->route('pegawai.index') // Asumsi ini route daftar pegawai di admin
            ->with('success', 'Data pegawai berhasil dihapus');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('admin.editpegawai', compact('pegawai'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawais,email,' . $pegawai->id, // Validasi unik dengan pengecualian ID saat update
            'password' => 'nullable|string|min:8|confirmed',
            'jabatan' => 'required|string',
            'bidang' => 'required|string'
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'jabatan' => $validated['jabatan'],
            'bidang' => $validated['bidang']
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $pegawai->update($updateData);

        return redirect()->route('pegawai.index') // Asumsi ini route daftar pegawai di admin
            ->with('success', 'Data pegawai berhasil diperbarui');
    }
}