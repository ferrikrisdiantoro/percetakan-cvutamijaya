@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-full">
    <h1 class="text-2xl font-bold mb-6 text-white">Edit Profil Perusahaan</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profil-perusahaan-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="logo" class="block text-sm text-white font-bold">Logo Perusahaan</label>
            <input type="file" name="logo" id="logo" class="mt-1 block w-full border-gray-900 text-white rounded-md shadow-sm form-control">
            @if($profil->logo)
                <img src="{{ asset($profil->logo) }}" alt="Logo Perusahaan" class="img-thumbnail mt-2" width="200">
            @endif
        </div>

        <div class="mt-2 form-group">
            <label for="judul_p1" class="block text-sm text-white font-bold">Judul Section 1</label>
            <input type="text" name="judul_p1" id="judul_p1" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ old('judul_p1', $profil->judul_p1) }}">
        </div>

        <div class="mt-2 form-group">
            <label for="isi_p1" class="block text-sm text-white font-bold">Isi Section 1</label>
            <textarea name="isi_p1" id="isi_p1" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ old('isi_p1', $profil->isi_p1) }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="visi" class="block text-sm text-white font-bold">Visi</label>
            <input type="text" name="visi" id="visi" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ old('visi', $profil->visi) }}">
        </div>

        <div class="mt-2 form-group">
            <label for="isi_visi" class="block text-sm text-white font-bold">Isi Visi</label>
            <textarea name="isi_visi" id="isi_visi" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ old('isi_visi', $profil->isi_visi) }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="misi" class="block text-sm text-white font-bold">Misi</label>
            <input type="text" name="misi" id="misi" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ old('misi', $profil->misi) }}">
        </div>

        <div class="mt-2 form-group">
            <label for="isi_misi" class="block text-sm text-white font-bold">Isi Misi</label>
            <textarea name="isi_misi" id="isi_misi" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" placeholder="Masukkan setiap poin misi di baris baru">{{ old('isi_misi', $profil->isi_misi) }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="kontak" class="block text-sm text-white font-bold">Kontak</label>
            <input type="text" name="kontak" id="kontak" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ old('kontak', $profil->kontak) }}">
        </div>

        <div class="mt-2 form-group">
            <label for="isi_kontak" class="block text-sm text-white font-bold">Isi Kontak</label>
            <textarea name="isi_kontak" id="isi_kontak" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" placeholder="Nomor Telepon&#10;Email">{{ old('isi_kontak', $profil->isi_kontak) }}</textarea>
        </div>

        <button type="submit" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg">Simpan Perubahan</button>
    </form>
</div>
@endsection
