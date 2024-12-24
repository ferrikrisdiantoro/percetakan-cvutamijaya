@extends('layouts.app')

@section('content')
@guest
    <div class="container mx-auto py-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($products as $product)
                <div div class="bg-teal-100 text-gray-900 p-6 rounded-lg shadow-lg">
                    <!-- Gambar Produk -->
                    <img alt="{{ $product->nama_produk }}" class="w-full h-48 object-cover mb-4" src="{{ $product->gambar ?? 'https://via.placeholder.com/300' }}" />
                    <h2 class="text-xl font-bold mb-2">{{ $product->nama_produk }}</h2>
                    <p class="mb-2">Harga Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    <p class="mb-2">Bahan: {{ $product->bahan }}</p>
                    <p class="mb-2">Ukuran: {{ $product->ukuran }}</p>
                    <p class="mb-4">Stok: {{ $product->stok }}</p>
                    <a href="{{ route('login') }}" class="bg-teal-500 text-white py-2 px-4 rounded">Pesan</a>
                </div>
            @empty
                <p class="text-center text-gray-500">Belum ada produk yang tersedia.</p>
            @endforelse
        </div>
    </div>
@else
    @if (Auth::user()->role === 'admin')
    <div class="flex flex-col w-full py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl text-white font-bold">Product List</h1>
            <a href="{{ route('product.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-blue-700">
            + Add Product
            </a>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-teal-600 dark:bg-teal-600 dark:text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Bahan</th>
                        <th scope="col" class="px-6 py-3">Ukuran</th>
                        <th scope="col" class="px-6 py-3">Harga</th>
                        <th scope="col" class="px-6 py-3">Stok</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr class="odd:bg-teal-200 text-black even:bg-teal-100 border-b dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-black dark:text-black">
                        {{ $product->nama_produk }}
                        </td>
                        <td class="px-6 py-4"> <img class="h-16 w-16 object-cover rounded" src="{{ $product->gambar }}" alt=""></td>
                        <td class="px-6 py-4">{{ $product->bahan }}</td>
                        <td class="px-6 py-4">{{ $product->ukuran }}</td>
                        <td class="px-6 py-4">Rp.{{ number_format($product->harga, 0) }}</td>
                        <td class="px-6 py-4">{{ $product->stok }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <form action="{{ route('product.edit', $product->id_produk) }}" method="GET" class="inline">
                                <button 
                                    type="submit"
                                    class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                    Edit
                                </button>
                            </form>
                            <form action="{{ route('product.destroy', $product->id_produk) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif (Auth::user()->role === 'pelanggan')
        <div class="container mx-auto py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($products as $product)
                <!-- KARTU PRODUK-->
                <div class="bg-teal-100 text-gray-900 p-6 rounded-lg shadow-lg">
                    <img alt="{{ $product->nama_produk }}" 
                    class="w-full h-48 object-cover mb-4" 
                    src="{{ $product->gambar ? asset($product->gambar) : 'https://via.placeholder.com/300' }}" />
                    <h2 class="text-xl font-bold mb-1">{{ $product->nama_produk }}</h2>
                    <p class="mb-1">Harga Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    <p class="mb-1">Bahan: {{ $product->bahan }}</p>
                    <p class="mb-1">Ukuran: {{ $product->ukuran }}</p>
                    <p class="mb-4">Stok: {{ $product->stok }}</p>
                    <a href="javascript:void(0)" 
                    class="bg-teal-500 text-white py-2 px-4 rounded" 
                    onclick="showOrderModal('{{ $product->id_produk }}', 
                                            '{{ $product->nama_produk }}', 
                                            '{{ $product->harga }}', 
                                            '{{ $product->gambar ?? 'https://via.placeholder.com/300' }}', 
                                            '{{ $product->stok }}')">
                        Pesan
                    </a>
                </div>
                

                <!-- Modal ke 1 -->
                <div id="orderModal{{ $product->id_produk}}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white w-[90%] max-w-md p-5 rounded-lg shadow-lg">
                        <img id="modalProductImage{{ $product->id_produk }}" class="w-full h-48 object-cover rounded mb-4" src="" alt="Produk" />
                        <h3 class="text-xl font-semibold mb-4" id="modalProductName{{ $product->id_produk }}">Nama Produk</h3>
                        <p class="text-gray-700 mb-2" id="modalProductPrice{{ $product->id_produk }}">Harga per Unit: Rp0</p>
                        <!-- Pilihan Jumlah -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-700">Jumlah</span>
                            <div class="flex items-center space-x-2">
                                <button class="px-2 py-1 bg-gray-300 rounded" id="decreaseButton{{ $product->id_produk }}">-</button>
                                <span id="quantity{{ $product->id_produk }}" class="text-gray-700">1</span>
                                <button class="px-2 py-1 bg-gray-300 rounded" id="increaseButton{{ $product->id_produk }}">+</button>
                            </div>
                            <input type="hidden" id="productId{{ $product->id_produk }}" value="{{ $product->id_produk}}">
                        </div>

                        <p class="text-gray-700 mb-2">
                            Total Harga: <span id="modalTotalPrice{{ $product->id_produk }}">Rp0</span>
                        </p>
                        <p class="text-gray-700 mb-2">
                            Stok Tersisa: <span id="modalProductStock{{ $product->id_produk }}">0</span>
                        </p>

                        <div class="flex justify-between space-x-2">
                            <button type="button" 
                                id="buyNowButton{{ $product->id_produk }}" 
                                class="bg-green-500 text-white px-4 py-2 rounded flex-1 text-center"
                                onclick="createTransaction('{{ $product->id_produk}}')">
                                Beli Langsung
                            </button>
                            <button type="button" 
                                    id="addToCartButton{{ $product->id_produk }}" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded flex-1 text-center"
                                    onclick="addToCart({{ json_encode($product) }})">
                                Tambah ke Keranjang
                            </button>

                        </div>
                        <button type="button" class="block mt-4 mx-auto bg-gray-300 text-gray-800 px-4 py-2 rounded" onclick="closeModal('{{ $product->id_produk }}')">Batal</button>
                    </div>
                </div>

                <!-- Modal ke 2 -->
                <div id="secondModal{{ $product->id_produk }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-cyan-100 p-4 rounded-lg shadow-lg w-[80%] mt-4 mb-4">
                        <form id="transactionForm{{ $product->id_produk }}" enctype="multipart/form-data">
                            @csrf
                            <div class="flex justify-between items-center">
                                <h1 class="text-lg font-semibold">Pesanan: {{ $product->nama_produk }}</h1>
                            </div>
                            <div class="mt-4">
                                <input type="hidden" id="orderId{{ $product->id_produk }}" name="order_id" value="{{ $product->id_pesanan}}">
                                <input type="hidden" name="product_id" value="{{ $product->id_produk }}">
                                <!-- Data User -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2">Nama Lengkap</label>
                                        <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block mb-2">Alamat</label>
                                        <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block mb-2">Nomor Telepon/WA</label>
                                        <input type="text" name="telepon" value="{{ Auth::user()->telepon }}" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label for="payment_method" class="block font-medium mb-2">Mode Pembayaran</label>
                                        <select name="payment_method" class="border border-gray-300 rounded-lg p-2 w-full">
                                            <option value="">-- Pilih Jenis Pembayaran --</option>
                                            <option value="dp">DP</option>
                                            <option value="lunas">LUNAS</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block mb-2">Jumlah Produk</label>
                                        <input type="text" name="kuantitas" value="1" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block mb-2">Upload Custom Gambar</label>
                                        <input type="file" name="custom_image" accept="image/*" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block mb-2">Total Pembayaran</label>
                                        <input type="number" name="total_pembayaran" value="{{ $product->harga }}" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                </div>
                                <hr class="border-black my-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="bank_name" class="block font-medium mb-2">Pilih Bank</label>
                                        <select name="bank_name" class="border border-gray-300 rounded-lg p-2 w-full">
                                            <option value="">-- Pilih Bank --</option>
                                            <option value="BCA">BCA</option>
                                            <option value="Mandiri">Mandiri</option>
                                            <option value="BRI">BRI</option>
                                            <option value="BNI">BNI</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="proof_of_payment" class="block font-medium mb-2">Unggah Bukti Pembayaran</label>
                                        <input type="file" name="proof_of_payment" class="border border-gray-300 rounded-lg p-2 w-full">
                                    </div>
                                </div>
                                <div class="flex justify-center mt-4 space-x-4">
                                    <button type="button" class="bg-cyan-400 text-white px-4 py-2 rounded" onclick="submitTransaction('{{ $product->id_produk }}')">Bayar</button>
                                    <!-- Tombol Cancel -->
                                    <button type="button" class="bg-red-400 text-white px-4 py-2 rounded" onclick="closeModal('{{ $product->id_produk }}', true)">Cancel</button>
                                </div>
                                @if(session('message'))
                                    <script>
                                        // Menampilkan popup jika ada pesan
                                        alert("{{ session('message') }}");
                                    </script>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                @empty
                <p class="text-center text-gray-500">Belum ada produk yang tersedia.</p>
                @endforelse
            </div>
        </div>

        <script>
            function showOrderModal(productId, productName, productPrice, productImage, productStock, buyUrl) {
                const modal = document.getElementById('orderModal' + productId);
                const quantityElement = document.getElementById('quantity' + productId);
                const totalPriceElement = document.getElementById('modalTotalPrice' + productId);
                const increaseButton = document.getElementById('increaseButton' + productId);
                const buyNowButton = document.getElementById('buyNowButton' + productId);
                const modalProductImage = document.getElementById('modalProductImage' + productId);
                if (modalProductImage) {
                    modalProductImage.src = productImage;
                } else {
                    console.error('Modal image element not found for product ID:', productId);
                }
                let currentTransactionId = null;

                // Set data produk ke modal
                document.getElementById('modalProductImage' + productId).src = productImage;
                document.getElementById('modalProductName' + productId).innerText = productName;
                document.getElementById('modalProductStock' + productId).innerText = productStock;
                document.getElementById('modalProductPrice' + productId).innerText = 'Harga per Unit: Rp' + productPrice;
                quantityElement.innerText = 1;
                totalPriceElement.innerText = 'Rp' + productPrice;

                // Menyimpan ID produk ke elemen hidden input agar bisa diakses saat pemesanan
                const hiddenProductIdElement = document.getElementById('productId' + productId);
                hiddenProductIdElement.value = productId; // Mengatur nilai produk

                // Set URL beli
                buyNowButton.setAttribute('href', buyUrl + '?quantity=1');

                // Tampilkan modal
                modal.classList.remove('hidden');

                // Fungsi tombol + dan -
                increaseButton.onclick = () => {
                    let quantity = parseInt(quantityElement.innerText);
                    if (quantity < productStock) {
                        quantity++;
                        quantityElement.innerText = quantity;

                        // Hitung total harga
                        const totalPrice = quantity * parseFloat(productPrice);
                        totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                        // Update URL Beli Langsung
                        buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
                    }
                };

                document.getElementById('decreaseButton' + productId).onclick = () => {
                    let quantity = parseInt(quantityElement.innerText);
                    if (quantity > 1) {
                        quantity--;
                        quantityElement.innerText = quantity;

                        // Hitung total harga
                        const totalPrice = quantity * parseFloat(productPrice);
                        totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                        // Update URL Beli Langsung
                        buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
                    }
                };
            }

            function closeModal(productId, isCancel) {
                console.log('closeModal triggered for productId:', productId);

                const firstModal = document.getElementById('orderModal' + productId);
                const secondModal = document.getElementById('secondModal' + productId);

                if (!firstModal && !secondModal) {
                    console.error('Modals not found for productId:', productId);
                    return;
                }

                // Ambil ID pesanan dari elemen hidden di modal kedua
                const orderIdElement = document.getElementById('orderId' + productId);
                const orderId = orderIdElement ? orderIdElement.value : null;

                if (isCancel && orderId) {
                    console.log('Deleting order with ID:', orderId);
                    deleteOrder(orderId)
                        .then(() => {
                            console.log('Order deleted successfully:', orderId);
                        })
                        .catch(error => {
                            console.error('Failed to delete order:', error);
                        });
                } else {
                    console.log('Not deleting order:', orderId);
                }

                // Close modals
                firstModal.style.display = 'none';
                secondModal.style.display = 'none';
            }
            
            // Tombol Cancel
            document.getElementById('cancelButton' + productId).addEventListener('click', function() {
                closeModal(productId, true);  // isCancel = true
            });

            // Tombol Bayar
            document.getElementById('payButton' + productId).addEventListener('click', function() {
                closeModal(productId, false);  // isCancel = false
            });




            async function deleteOrder(orderId) {
                try {
                    const response = await fetch(`/order/destroy/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Gagal menghapus pesanan');
                    }

                    return response.json();
                } catch (error) {
                    console.error('Terjadi kesalahan saat menghapus pesanan:', error);
                    throw error;
                }
            }


            function createTransaction(productId) {
                const quantity = parseInt(document.getElementById('quantity' + productId).innerText);
                const totalPrice = parseFloat(document.getElementById('modalTotalPrice' + productId).innerText.replace(/[^\d]/g, ''));
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                // Ambil ID produk dari elemen input tersembunyi
                console.log('Product ID Element Value:', document.getElementById('productId' + productId).value);
                const productIdValue = document.getElementById('productId' + productId).value;

                console.log({
                    product_id: productIdValue || 'NULL', // Debug jika ID kosong
                    kuantitas: quantity,
                    total_pembayaran: totalPrice
                });

                // Kirim data ke server menggunakan fetch
                fetch('/order/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productIdValue,
                        kuantitas: quantity,
                        total_pembayaran: totalPrice
                    })
                })
                .then(response => {
                    console.log('Raw response:', response); // Debug respons mentah
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text || 'Terjadi kesalahan');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data from server:', data);
                    if (data.order_id) {
                        document.getElementById('orderId' + productId).value = data.order_id;
                        console.log('Order ID saved in form:', data.order_id);
                    } else {
                        console.error('Element with ID orderId' + productId + ' not found.');
                    }

                    const firstModal = document.getElementById('orderModal' + productId);
                    if (firstModal) {
                        firstModal.classList.add('hidden');  // Menyembunyikan modal pertama
                    }

                    // Buka modal kedua setelah order_id diatur
                    const secondModal = document.getElementById('secondModal' + productId);
                    if (secondModal) {
                        secondModal.classList.remove('hidden');
                    }
                })
            }

            function addToCart(product) {
                const { id_produk, nama_produk, harga, gambar } = product;
                console.log("Data Produk:", product);

                const quantity = parseInt(document.getElementById('quantity' + id_produk).textContent);

                if (!id_produk || !nama_produk || !harga || !gambar || !quantity) {
                    console.error("Produk tidak lengkap atau kuantitas tidak valid, tidak bisa ditambahkan ke keranjang");
                    return;
                }

                const cart = JSON.parse(localStorage.getItem('cart')) || [];

                const existingProductIndex = cart.findIndex(item => item.id_produk === id_produk);

                // Memastikan produk memiliki order_id yang valid sebelum melanjutkan
                if (existingProductIndex !== -1 && !cart[existingProductIndex].id_pesanan) {
                    console.error(`Order ID untuk produk ${id_produk} tidak valid!`);
                    return;  // Menghentikan proses jika id_pesanan tidak ada
                }

                if (existingProductIndex !== -1) {
                    cart[existingProductIndex].kuantitas += quantity;
                } else {
                    cart.push({
                        id_produk,
                        nama_produk,
                        harga,
                        gambar,
                        kuantitas: quantity,
                        id_pesanan: null, // Pastikan id_pesanan di set sebagai null saat produk baru ditambahkan
                    });
                }

                // Setelah menambah atau memperbarui produk, simpan keranjang yang diperbarui di localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Kirim data ke server untuk mendapatkan order_id
                fetch('/order/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: id_produk,
                        kuantitas: quantity,
                        total_pembayaran: harga * quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.order_id) {
                        // Cari produk yang sesuai dengan id_produk dan update dengan order_id
                        const productToUpdate = cart[existingProductIndex !== -1 ? existingProductIndex : cart.length - 1];
                        productToUpdate.id_pesanan = data.order_id;

                        // Simpan keranjang yang sudah diperbarui ke localStorage
                        localStorage.setItem('cart', JSON.stringify(cart)); // Pastikan keranjang diperbarui
                        console.log("Keranjang diperbarui dengan ID pesanan", cart);
                    }
                })
                .catch(error => console.error("Error saat menambah ID pesanan ke keranjang:", error));
            }

            function submitTransaction(productId) {
                const form = document.getElementById('transactionForm' + productId);
                const formData = new FormData(form);

                // Ambil nilai order_id yang sudah diisi di modal kedua
                const orderId = document.getElementById('orderId' + productId).value;
                formData.append('order_id', orderId);

                fetch('/transaction/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok && response.headers.get('content-type').includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text().then(html => {
                            throw new Error('Server returned error: ' + html);
                        });
                    }
                })
                .then(data => {
                    alert(data.message || 'Transaksi berhasil!');
                    closeModal(productId);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Coba lagi nanti.'));
                });
            }



        </script>

    </div>
    @endif    
@endguest
@endsection