@extends('layouts.app')

@section('content')
<div class="w-full mt-4 bg-gray-800 flex justify-center items-center">
  <!-- Grid Layout: 1 kolom pada ukuran kecil, 2 kolom pada ukuran lebih besar -->
  <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-screen-lg bg-cyan-200 p-6 rounded-lg shadow-lg space-x-8">
    
    <!-- Left Section -->
    <div class="w-full p-6">
      <!-- Form login -->
      <form method="POST" action="{{ route('login') }}">
        @csrf
        <h2 class="text-xl text-gray-800 font-semibold mb-4">Username</h2>
        <input name="nama" class="w-full p-2 border border-gray-400 rounded mb-6" placeholder="Masukkan username anda" type="text" required/>
        
        <h2 class="text-xl text-gray-800 font-semibold mb-4">Kata sandi</h2>
        <input name="password" class="w-full p-2 border border-gray-400 rounded mb-4" placeholder="Masukkan kata sandi anda" type="password" required/>
        
        <div class="flex justify-between items-center mb-6">
          <a class="text-sm text-gray-600" href="#">Lupa data sandi?</a>
          <a class="text-sm text-gray-600" href="{{ route('register') }}">Daftar</a>
        </div>
        
        <button type="submit" class="w-full bg-cyan-400 text-white px-4 py-2 rounded mb-4">Masuk</button>
      </form>
    </div>
    
    <!-- Right Section -->
    <div class="w-full p-6">
      <img alt="Image of a house with a car parked in front" class="w-full h-64 object-cover rounded mb-4" height="300" src="https://storage.googleapis.com/a1aa/image/j0qchJH071rWCJ1iUixvOhy2tHNyCn0snXdt8pIJikXr5C8E.jpg" width="400"/>
    </div>
  </div>
</div>
@endsection
