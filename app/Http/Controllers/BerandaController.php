<?php

namespace App\Http\Controllers;

use App\Models\Beranda;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function edit()
    {
        $beranda = Beranda::first(); // Ambil data pertama (asumsikan hanya ada satu entri)
        return view('admin.edit-beranda', compact('beranda'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_carousel1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gambar_carousel2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sec2_text1' => 'required|string',
            'sec2_text2' => 'required|string',
            'sec2_text3' => 'required|string',
            'sec3_judul' => 'required|string',
            'sec3_text1' => 'required|string',
            'sec3_text2' => 'required|string',
            'sec3_text3' => 'required|string',
            'sec3_map' => 'required|string',
        ]);

        $beranda = Beranda::first();
        if ($request->hasFile('gambar_utama')) {
            $file = $request->file('gambar_utama');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_utama = 'storage/' . $path;
        }
    
        if ($request->hasFile('gambar_carousel1')) {
            $file = $request->file('gambar_carousel1');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_carousel1 = 'storage/' . $path;
        }
    
        if ($request->hasFile('gambar_carousel2')) {
            $file = $request->file('gambar_carousel2');
            $path = $file->store('uploads', 'public');
            $beranda->gambar_carousel2 = 'storage/' . $path;
        }
    
        $beranda->update($request->except(['gambar_utama', 'gambar_carousel1', 'gambar_carousel2']));
        $beranda->save();
        return redirect()->route('beranda-edit')->with('success', 'Beranda berhasil diperbarui!');
    }
}
