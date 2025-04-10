@extends('layouts.app')

@section('content')
<div class="mt-4 flex justify-center items-center">
    <div class="bg-teal-200 p-8 rounded-lg shadow-lg w-full max-w-lg text-gray-900">
        <h1 class="text-center text-2xl font-bold mb-6">Reset Kata Sandi</h1>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Alamat Email</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500"
                       type="email" id="email" name="email" required>
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password">Kata Sandi Baru</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500"
                       type="password" id="password" name="password" required>
                @error('password')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500"
                       type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="text-center">
                <button class="w-full bg-green-500 text-white px-4 py-2 rounded mb-4 hover:bg-white hover:text-green-500" type="submit">
                    Reset Kata Sandi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
