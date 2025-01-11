<?php

namespace App\Http\Controllers;

use App\Models\ProfilPerusahaan;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function index()
    {
        $profil = ProfilPerusahaan::first(); 
        return view('pelanggan.profile', compact('profil'));
    }

    public function edit()
    {
        $profil = ProfilPerusahaan::first();
        return view('admin.edit-profil-perusahaan', compact('profil'));
    }

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

        $profil = ProfilPerusahaan::first();

        // Handle logo
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('logos', 'public');
            $profil->logo = 'storage/' . $path;
        }
    
        // Format misi dan kontak sebelum update
        $data = $request->except(['logo']);
        if (isset($data['isi_misi'])) {
            $data['isi_misi'] = $this->formatMisi($data['isi_misi']);
        }
        if (isset($data['isi_kontak'])) {
            $data['isi_kontak'] = $this->formatKontak($data['isi_kontak']);
        }
    
        $profil->update($data);
    
        return redirect()->route('profil-perusahaan-edit')
            ->with('success', 'Profil Perusahaan berhasil diperbarui!');
    }

    public function formatMisi($text) 
    {
        $lines = explode("\n", trim($text));
        $formatted = [];
        foreach ($lines as $index => $line) {
            $line = trim($line);
            if (!empty($line)) {
                $formatted[] = ($index + 1) . ". " . $line;
            }
        }
        return implode("\n", $formatted);
    }

    public function formatKontak($text)
    {
        $lines = explode("\n", trim($text));
        $telp = trim($lines[0] ?? '');
        $email = trim($lines[1] ?? '');
        
        return "Hubungi Kami:\nTelp: {$telp}\nEmail: {$email}";
    }

    
}