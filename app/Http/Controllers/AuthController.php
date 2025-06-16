<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting untuk Auth facade

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Asumsi view login Anda di resources/views/auth/login.blade.php
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login sebagai Admin
        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Redirect ke dashboard admin
        }

        // Coba login sebagai Pegawai
        if (Auth::guard('pegawai')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/pegawai/home'); // Redirect ke home pegawai
        }

        // Jika tidak ada guard yang berhasil
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Logout dari guard 'admin' jika sedang login
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }
        // Logout dari guard 'pegawai' jika sedang login
        if (Auth::guard('pegawai')->check()) {
            Auth::guard('pegawai')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // Kembali ke halaman login
    }
}