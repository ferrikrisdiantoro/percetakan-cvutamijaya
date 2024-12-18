@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-full text-dark">
    <h1 class="text-2xl font-bold mb-6">Add New Product</h1>
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="nama_produk" class="block text-sm font-medium">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="harga" class="block text-sm font-medium">Harga</label>
            <input type="number" step="0.01" name="harga" id="harga" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="bahan" class="block text-sm font-medium">Bahan</label>
            <input type="text" name="bahan" id="bahan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="ukuran" class="block text-sm font-medium">Ukuran</label>
            <input type="text" name="ukuran" id="ukuran" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="stok" class="block text-sm font-medium">Stok</label>
            <input type="number" name="stok" id="stok" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="gambar" class="block text-sm font-medium">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg">Save</button>
    </form>
</div>
@endsection
