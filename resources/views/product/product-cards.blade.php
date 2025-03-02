<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    @forelse ($products as $product)
    <div class="bg-teal-100 text-gray-900 p-6 rounded-lg shadow-lg">
        <img 
            class="w-full h-48 object-cover rounded cursor-pointer hover:opacity-80 transition-opacity" 
            src="{{ $product->gambar }}" 
            alt="{{ $product->nama_produk }}"
            onclick="openImageModal('{{ $product->gambar }}')"
        >
        <h2 class="text-xl font-bold mb-1">{{ $product->nama_produk }}</h2>
        <p class="mb-1">{{ $product->deskripsi }}</p>
        <p class="mb-1">Harga Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
        <p class="mb-1">Bahan: {{ $product->bahan }}</p>
        <p class="mb-1">Ukuran: {{ $product->ukuran }}</p>
        <p class="mb-4">Stok: {{ $product->stok }}</p>
        <a href="javascript:void(0)" 
            class="bg-teal-500 text-white py-2 px-4 rounded hover:bg-white hover:text-teal-600" 
            onclick="showOrderModal('{{ $product->id }}', 
                                '{{ $product->nama_produk }}', 
                                '{{ $product->harga }}', 
                                '{{ $product->gambar ?? 'https://via.placeholder.com/300' }}', 
                                '{{ $product->stok }}')">
            Pesan
        </a>
    </div>
    @empty
    <p class="text-center text-gray-500">Tidak ada produk yang ditemukan.</p>
    @endforelse
</div>