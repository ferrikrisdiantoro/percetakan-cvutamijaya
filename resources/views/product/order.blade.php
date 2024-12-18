@extends('layouts.app')

@section('title', 'Form Pemesanan')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-center mb-6">Form Pemesanan</h1>

    <form action="{{ route('order.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="product_name" class="block font-bold mb-2">Produk</label>
            <input type="text" id="product_name" value="{{ $product->name }}" class="bg-gray-100 border rounded-lg p-3 w-full" readonly>
        </div>

        <div class="mb-4">
            <label for="quantity" class="block font-bold mb-2">Jumlah</label>
            <input type="number" name="quantity" id="quantity" class="bg-gray-100 border rounded-lg p-3 w-full" required>
        </div>

        <button type="submit" class="bg-teal-500 text-white py-2 px-4 rounded">
            Konfirmasi Pesanan
        </button>
    </form>
</div>
@endsection
