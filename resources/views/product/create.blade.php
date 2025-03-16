@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-[80%] mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-white">Tambah Produk Baru</h1>
    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div>
            <label for="nama_produk" class="block text-sm text-white font-bold">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="deskripsi" class="block text-sm text-white font-bold">Deskripsi</label>
            <input type="text" name="deskripsi" id="deskripsi" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="harga" class="block text-sm text-white font-bold">Harga</label>
            <input type="number" step="0.01" name="harga" id="harga" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="bahan" class="block text-sm text-white font-bold">Bahan</label>
            <input type="text" name="bahan" id="bahan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="ukuran" class="block text-sm text-white font-bold">Ukuran</label>
            <input type="text" name="ukuran" id="ukuran" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="stok" class="block text-sm text-white font-bold">Stok</label>
            <input type="number" name="stok" id="stok" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>
        <div>
            <label for="gambar" class="block text-sm text-white font-bold">Gambar</label>
            <input type="file" name="gambar" id="gambar" class="mt-1 block w-full border-gray-900 text-white rounded-md shadow-sm">
        </div>
        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg">Simpan</button>
    </form>
</div>
@endsection
