@extends('layouts.app')

@section('content')
<div class="mt-4 flex justify-center items-center">
    <!-- Card Pendaftaran dengan max-width -->
    <div class="bg-cyan-200 p-8 rounded-lg shadow-lg w-full max-w-lg text-gray-900"> <!-- Menambahkan max-w-lg -->
        <h1 class="text-center text-2xl font-bold mb-6">PENDAFTARAN</h1>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="nama-lengkap">Nama Lengkap</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded" type="text" id="nama-lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                @error('nama_lengkap')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="nama">Nama</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded" type="text" id="nama" name="nama" value="{{ old('nama') }}">
                @error('nama')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Alamat e-mail</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded" type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 flex space-x-4">
                <div class="w-1/2">
                    <label class="block text-gray-700 mb-2" for="password">Kata sandi</label>
                    <input class="w-full px-3 py-2 border border-gray-400 rounded" type="password" id="password" name="password">
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 mb-2" for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input class="w-full px-3 py-2 border border-gray-400 rounded" type="password" id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2" for="telepon">No telepon</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded" type="tel" id="telepon" name="telepon" value="{{ old('telepon') }}">
                @error('telepon')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2" for="alamat">Alamat</label>
                <textarea class="w-full px-3 py-2 border border-gray-400 rounded" id="alamat" name="alamat" rows="4">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-center">
                <button class="bg-cyan-300 hover:bg-cyan-400 text-gray-800 font-bold py-2 px-4 rounded" type="submit">Daftar</button>
            </div>
        </form>
    </div>
</div>
@endsection
