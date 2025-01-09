<!-- resources/views/profil-user.blade.php -->
@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-full mb-4">
    <h1 class="text-2xl font-bold mb-6 text-white">Profil Saya</h1>
        <div class="mt-2">
            <label for="nama_lengkap " class="block text-sm text-white font-bold">Nama Lengkap</label>
            <input type="text" name="nama_lengkap " id="nama_lengkap " class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ basename (Auth::user()->nama_lengkap) }}" readonly>
        </div>
        <div class="mt-2">
            <label for="username " class="block text-sm text-white font-bold">Username</label>
            <input type="text" name="username " id="username " class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ basename (Auth::user()->username) }}" readonly>
        </div>
        <div class="mt-2">
            <label for="email " class="block text-sm text-white font-bold">Email</label>
            <input type="text" name="email " id="email " class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ basename (Auth::user()->email) }}" readonly>
        </div>
        <div class="mt-2">
            <label for="telepon " class="block text-sm text-white font-bold">Telepon</label>
            <input type="text" name="telepon " id="telepon " class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ basename (Auth::user()->telepon) }}" readonly>
        </div>
        <div class="mt-2 mb-4">
            <label for="alamat " class="block text-sm text-white font-bold">Alamat</label>
            <input type="text" name="alamat " id="alamat " class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ basename (Auth::user()->alamat) }}" readonly>
        </div>

        <a href="{{ route('edit.profile') }}" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-white hover:text-green-500">
            Edit Profil
        </a>

</div>
@endsection
