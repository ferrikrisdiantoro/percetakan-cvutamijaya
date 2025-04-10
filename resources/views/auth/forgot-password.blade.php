@extends('layouts.app')

@section('content')
<div class="mt-4 flex justify-center items-center">
    <div class="bg-teal-200 p-8 rounded-lg shadow-lg w-full max-w-lg text-gray-900">
        <h1 class="text-center text-2xl font-bold mb-6">Lupa Kata Sandi</h1>
        
        @if (session('status'))
            <div class="mb-4 text-green-700 bg-green-100 border border-green-400 p-4 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 mb-2" for="email">Masukkan Alamat Email Anda</label>
                <input class="w-full px-3 py-2 border border-gray-400 rounded focus:ring-green-500 focus:border-green-500"
                       type="email" id="email" name="email" required>
                @error('email')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-center">
                <button class="w-full bg-green-500 text-white px-4 py-2 rounded mb-4 hover:bg-white hover:text-green-500" type="submit">
                    Kirim Link Reset Kata Sandi
                </button>
            </div>
        </form>

        <div class="text-center">
            <a class="text-sm text-gray-700 hover:text-green-600" href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </div>
</div>
@endsection
