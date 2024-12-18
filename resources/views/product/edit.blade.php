@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-full text-dark">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>
    <form action="{{ route('product.update', $product->id_produk) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')
    <div>
        <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
        <input type="number" step="0.01" name="harga" id="harga" value="{{ old('harga', $product->harga) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label for="bahan" class="block text-sm font-medium text-gray-700">Bahan</label>
        <input type="text" name="bahan" id="bahan" value="{{ old('bahan', $product->bahan) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label for="ukuran" class="block text-sm font-medium text-gray-700">Ukuran</label>
        <input type="text" name="ukuran" id="ukuran" value="{{ old('ukuran', $product->ukuran) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
        <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
    </div>
    <div>
        <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar</label>
        <input type="file" name="gambar" id="gambar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @if($product->gambar)
            <p class="text-sm text-gray-500 mt-2">Current image: <a href="{{ asset('storage/' . $product->gambar) }}" target="_blank" class="text-blue-600 underline">View Image</a></p>
        @endif
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Update</button>
</form>
</div>
@endsection
