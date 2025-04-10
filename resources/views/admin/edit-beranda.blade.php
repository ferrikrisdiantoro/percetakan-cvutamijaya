@extends('layouts.app')

@section('content')
<div class="bg-teal-600 p-8 mt-4 rounded-lg shadow-lg w-[80%] mx-auto mb-4">
    <h1 class="text-2xl font-bold mb-6 text-white">Edit Beranda</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('beranda-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mt-2 form-group">
            <label for="gambar_utama" class="block text-sm text-white font-bold">Gambar Utama</label>
            <input type="file" name="gambar_utama" id="gambar_utama" class="mt-1 block w-full border-gray-900 text-white rounded-md shadow-sm form-control">
            @if($beranda->gambar_utama)
                <img src="{{ asset($beranda->gambar_utama) }}" alt="Gambar Utama" class="img-thumbnail mt-2" width="200">
            @endif
        </div>

        <div class="mt-2 form-group">
            <label for="gambar_carousel1" class="block text-sm text-white font-bold">Gambar Carousel 1</label>
            <input type="file" name="gambar_carousel1" id="gambar_carousel1" class="mt-1 block w-full border-gray-900 text-white rounded-md shadow-sm form-control form-control">
            @if($beranda->gambar_carousel1)
                <img src="{{ asset($beranda->gambar_carousel1) }}" alt="Carousel 1" class="img-thumbnail mt-2" width="200">
            @endif
        </div>

        <div class="mt-2 form-group">
            <label for="link1_g1" class="block text-sm text-white font-bold">Link Produk 1</label>
            <input type="text" name="link1_g1" id="link1_g1" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ $beranda->link1_g1 }}">
        </div>

        <div class="mt-2 form-group">
            <label for="gambar_carousel2" class="block text-sm text-white font-bold">Gambar Carousel 2</label>
            <input type="file" name="gambar_carousel2" id="gambar_carousel2" class="mt-1 block w-full border-gray-900 text-white rounded-md shadow-sm form-control form-control">
            @if($beranda->gambar_carousel2)
                <img src="{{ asset($beranda->gambar_carousel2) }}" alt="Carousel 2" class="img-thumbnail mt-2" width="200">
            @endif
        </div>

        <div class="mt-2 form-group">
            <label for="link1_g2" class="block text-sm text-white font-bold">Link Produk 2</label>
            <input type="text" name="link1_g2" id="link1_g2" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ $beranda->link1_g2 }}">
        </div>

        <div class="mt-2 form-group">
            <label for="sec2_text1" class="block text-sm text-white font-bold">Section 2 - Text 1</label>
            <textarea name="sec2_text1" id="sec2_text1" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm  form-control">{{ $beranda->sec2_text1 }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="sec2_text2" class="block text-sm text-white font-bold">Section 2 - Text 2</label>
            <textarea name="sec2_text2" id="sec2_text2" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ $beranda->sec2_text2 }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="sec2_text3" class="block text-sm text-white font-bold">Section 2 - Text 3</label>
            <textarea name="sec2_text3" id="sec2_text3" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ $beranda->sec2_text3 }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="sec3_judul" class="block text-sm text-white font-bold">Section 3 - Judul</label>
            <input type="text" name="sec3_judul" id="sec3_judul" class="mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control" value="{{ $beranda->sec3_judul }}">
        </div>

        <div class="mt-2 form-group">
            <label for="sec3_text1" class="block text-sm text-white font-bold">Section 3 - Text 1</label>
            <textarea name="sec3_text1" id="sec3_text1" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ $beranda->sec3_text1 }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="sec3_text2" class="block text-sm text-white font-bold">Section 3 - Text 2</label>
            <textarea name="sec3_text2" id="sec3_text2" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ $beranda->sec3_text2 }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="sec3_text3" class="block text-sm text-white font-bold">Section 3 - Text 3</label>
            <textarea name="sec3_text3" id="sec3_text3" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ $beranda->sec3_text3 }}</textarea>
        </div>

        <div class="mt-2 form-group">
            <label for="sec3_map" class="block text-sm text-white font-bold">Section 3 - Map</label>
            <textarea name="sec3_map" id="sec3_map" class="p-3 mt-1 block w-full border-gray-300 text-gray-900 rounded-md shadow-sm form-control">{{ $beranda->sec3_map }}</textarea>
        </div>

        <button type="submit" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg">Simpan Perubahan</button>
    </form>
</div>
@endsection
