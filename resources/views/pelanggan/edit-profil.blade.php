<!-- resources/views/edit-profil.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-full mb-4">
    <h1 class="text-2xl font-bold mb-6 text-white">Edit Profil</h1>
    <form action="{{ route('update.profile') }}" method="POST" enctype="multipart/form-data" class="rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm text-white font-bold">Nama Lengkap:</label>
            <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" 
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="username" class="block text-sm text-white font-bold">Username:</label>
            <input type="text" id="username" name="username" value="{{ Auth::user()->username }}" 
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <div class="mb-4">
            <label for="telepon" class="block text-sm text-white font-bold">Telepon:</label>
            <input type="text" id="telepon" name="telepon" value="{{ Auth::user()->telepon }}" 
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>

        <div class="mb-4">
            <label for="alamat" class="block text-sm text-white font-bold">Alamat:</label>
            <textarea id="alamat" name="alamat" class="p-3 mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ Auth::user()->alamat }}</textarea>
        </div>

        <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
