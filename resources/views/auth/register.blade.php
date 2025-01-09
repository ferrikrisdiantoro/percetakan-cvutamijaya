@extends('layouts.app')

@section('content')
<div class="mt-4 flex justify-center items-center">
    <!-- Card Pendaftaran dengan max-width -->
    <div class="bg-teal-200 p-8 rounded-lg shadow-lg w-full max-w-lg text-gray-900"> <!-- Menambahkan max-w-lg -->
        <h1 class="text-center text-2xl font-bold mb-6">Daftar Akun</h1>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="nama-lengkap">Nama Lengkap</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" type="text" id="nama-lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                @error('nama_lengkap')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="username">Username</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" type="text" id="username" name="username" value="{{ old('username') }}">
                @error('username')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Alamat e-mail</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" type="email" id="email" name="email" value="{{ old('email') }}">
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4 flex space-x-4">
                <div class="w-1/2">
                    <label class="block text-gray-700 mb-2" for="password">Kata sandi</label>
                    <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" type="password" id="password" name="password">
                    @error('password')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 mb-2" for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" type="password" id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2" for="telepon">No telepon</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" type="tel" id="telepon" name="telepon" value="{{ old('telepon') }}">
                @error('telepon')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2" for="alamat">Alamat</label>
                <textarea class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500" id="alamat" name="alamat" rows="4">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-center">
                <button class="w-full bg-green-500 text-white px-4 py-2 rounded mb-4 hover:bg-white hover:text-green-500" type="submit">Daftar</button>
            </div>
        </form>
    </div>
</div>
@endsection
