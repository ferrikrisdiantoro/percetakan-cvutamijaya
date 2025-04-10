@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="mt-4 w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mt-4 w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="w-full mt-4 bg-gray-800 flex justify-center items-center">
  <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-screen-lg bg-teal-200 p-6 rounded-lg shadow-lg space-x-8">

    <!-- Left Section -->
    <div class="w-full p-6">
      <h1 class="text-center text-2xl font-bold mb-6">Masuk Akun</h1>
      
      <!-- Form login -->
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <h2 class="text-xl text-gray-800 font-semibold mb-4">Username</h2>
        <input name="username" class="w-full p-2 border border-gray-400 rounded mb-6 focus:ring-green-500 focus:border-green-500" placeholder="Masukkan username anda" type="text" required/>
        
        <h2 class="text-xl text-gray-800 font-semibold mb-4">Kata sandi</h2>
        <input name="password" class="w-full p-2 border border-gray-400 rounded mb-4 focus:ring-green-500 focus:border-green-500" placeholder="Masukkan kata sandi anda" type="password" required/>
        
        <div class="flex justify-between items-center mb-6">
          <a class="text-sm text-gray-600" href="{{ route('password.request') }}">Lupa kata sandi?</a>
          <a class="text-sm text-gray-600 hover:bg-green-500 hover:text-white rounded-lg p-2" href="{{ route('register') }}">Daftar</a>
        </div>
        
        <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded mb-4 hover:bg-white hover:text-green-500">Masuk</button>
      </form>
    </div>
    
    <!-- Right Section -->
    <div class="w-full p-6">
      <img alt="Image of a house with a car parked in front" class="w-full h-64 object-cover rounded mb-4" height="300" src="/images/coverperusahaan.jpg" width="400">
    </div>
  </div>
</div>
@endsection
