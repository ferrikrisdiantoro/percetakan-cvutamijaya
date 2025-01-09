<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    // Menampilkan halaman profil perusahaan
    public function index()
    {
        $profil = ProfilPerusahaan::first(); 
        return view('pelanggan.profile', compact('profil'));
    }

    // Edit Profil Perusahaan
    public function edit()
    {
        $profil = ProfilPerusahaan::first(); // Ambil data pertama (karena hanya ada satu entri)
        return view('admin.edit-profil-perusahaan', compact('profil'));
    }

    // Update Profil Perusahaan
    public function update(Request $request)
{
    $request->validate([
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'judul_p1' => 'nullable|string',
        'isi_p1' => 'nullable|string',
        'visi' => 'nullable|string',
        'isi_visi' => 'nullable|string',
        'misi' => 'nullable|string',
        'isi_misi' => 'nullable|string',
        'kontak' => 'nullable|string',
        'isi_kontak' => 'nullable|string',
    ]);

    $profil = ProfilPerusahaan::first(); // Ambil entri pertama karena hanya ada satu entri

    // Update logo jika ada file yang diupload
    if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $path = $file->store('logos', 'public');
        $profil->logo = 'storage/' . $path;
    }

    // Update data lainnya
    $profil->update($request->except(['logo'])); // Exclude logo from the update

    return redirect()->route('profil-perusahaan-edit')->with('success', 'Profil Perusahaan berhasil diperbarui!');
}

}
