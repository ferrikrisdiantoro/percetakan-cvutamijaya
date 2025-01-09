<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    // Tampilkan halaman profil
    public function index()
    {
        return view('pelanggan.profil-user');
    }

    // Tampilkan halaman edit profil
    public function edit()
    {
        return view('pelanggan.edit-profil');
    }

    // Simpan perubahan profil
    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id() . ',id_user',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->nama_lengkap = $request->nama_lengkap;
        $user->username = $request->username;
        $user->telepon = $request->telepon;
        $user->alamat = $request->alamat;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
