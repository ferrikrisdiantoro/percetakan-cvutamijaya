@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-full mb-4">
    <h1 class="text-2xl font-bold mb-6 text-white">Edit User</h1>

    <form action="{{ route('user-update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="block text-sm text-white font-bold">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $user->name }}" required>
        </div>

        <div class="form-group">
            <label for="username" class="block text-sm text-white font-bold">Username</label>
            <input type="text" name="username" id="username" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $user->username }}" required>
        </div>

        <div class="form-group">
            <label for="email" class="block text-sm text-white font-bold">Email</label>
            <input type="email" name="email" id="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $user->email }}" required>
        </div>

        <div class="form-group">
            <label for="telepon" class="block text-sm text-white font-bold">Telepon</label>
            <input type="text" name="telepon" id="telepon" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" value="{{ $user->telepon }}" required>
        </div>

        <div class="form-group">
            <label for="alamat" class="block text-sm text-white font-bold">Alamat</label>
            <textarea name="alamat" id="alamat" class="p-3 mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ $user->alamat }}</textarea>
        </div>

        <div class="form-group">
            <label for="role" class="block text-sm text-white font-bold">Role</label>
            <select name="role" id="role" class="p-3 mt-1 mb-4 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            </select>
        </div>

        <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-600">Simpan Perubahan</button>
        <a href="{{ route('manajemen-user') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Batal</a>
    </form>
</div>
@endsection
