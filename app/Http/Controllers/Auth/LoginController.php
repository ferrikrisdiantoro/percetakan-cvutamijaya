<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login'); // Pastikan Anda memiliki view login di resources/views/auth/login.blade.php
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('nama', 'password');

        if (Auth::attempt($credentials)) {
            // Cek apakah pengguna adalah admin atau bukan
            if (auth()->user()->role === 'admin') {
                // Jika admin, arahkan ke halaman admin
                return redirect()->route('HomeAdmin');  // Pastikan route admin.index sudah ada
            }

            // Jika bukan admin, arahkan ke halaman pelanggan
            return redirect()->route('product.index');  // Pastikan route customer.index sudah ada
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    // Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('product.index'); // Arahkan ke halaman produk setelah logout
    }
}
