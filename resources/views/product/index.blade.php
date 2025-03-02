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
                    <p class="mb-1">{{ $product->deskripsi }}</p>
                    <p class="mb-2">Harga Rp{{ number_format($product->harga, 0, ',', '.') }}</p>
                    <p class="mb-2">Bahan: {{ $product->bahan }}</p>
                    <p class="mb-2">Ukuran: {{ $product->ukuran }}</p>
                    <p class="mb-4">Stok: {{ $product->stok }}</p>
                    <div class="flex justify-center">
                        <a href="{{ route('login') }}" class="bg-teal-500 text-white items-center py-2 px-4 rounded max-w-[50%] hover:bg-white hover:text-teal-600">Pesan</a>
                    </div>
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
            <h1 class="text-2xl text-white font-bold">Daftar List Produk</h1>
            <a href="{{ route('product.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-white hover:text-teal-600">
            + Tambah Produk
            </a>
        </div>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg ">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-teal-600 dark:bg-teal-600 dark:text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Deskripsi</th>
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
                        <td class="px-6 py-4">{{ $product->deskripsi }}</td>
                        <td class="px-6 py-4">{{ $product->bahan }}</td>
                        <td class="px-6 py-4">{{ $product->ukuran }}</td>
                        <td class="px-6 py-4">Rp.{{ number_format($product->harga, 0) }}</td>
                        <td class="px-6 py-4">{{ $product->stok }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <form action="{{ route('product.edit', $product->id) }}" method="GET" class="inline">
                                <button 
                                    type="submit"
                                    class="focus:outline-none text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                    Edit
                                </button>
                            </form>
                            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- First, add this modal HTML after your table div -->
    <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-lg font-bold mb-4">Apakah Anda yakin ingin menghapus produk ini?</h2>
            <div class="flex justify-center space-x-4">
                <form id="deleteProductForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400">Ya</button>
                </form>
                <button id="cancelDeleteProduct" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
            </div>
        </div>
    </div>

    <!-- Modify your delete button to use the confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteConfirmationModal');
        const deleteForm = document.getElementById('deleteProductForm');
        const cancelButton = document.getElementById('cancelDeleteProduct');

        // Find all delete buttons that are inside forms
        const deleteForms = document.querySelectorAll('form[action*="/product/"][method="POST"]');
        
        deleteForms.forEach(form => {
            const deleteButton = form.querySelector('button[type="submit"]');
            if (deleteButton) {
                // Prevent the default form submission
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Store the form's action URL in the modal's form
                    deleteForm.action = form.action;
                    
                    // Show the modal
                    modal.classList.remove('hidden');
                });
            }
        });

        // Handle cancel button click
        cancelButton.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Handle the actual delete form submission
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Submit the form using fetch to handle the DELETE request
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: new FormData(this)
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload(); // Reload the page after successful deletion
                } else {
                    throw new Error('Gagal menghapus produk');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus produk');
            })
            .finally(() => {
                modal.classList.add('hidden');
            });
        });
    });
    </script>
    @elseif (Auth::user()->role === 'pelanggan')
        <div class="products-container mx-auto py-10">
        @include('product.product-cards')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse ($products as $product)
                <!-- KARTU PRODUK-->
                <!-- <div class="bg-teal-100 text-gray-900 p-6 rounded-lg shadow-lg">
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
                </div> -->

                <!-- Image Modal -->
                <div id="imageModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex items-center justify-center">
                    <div class="max-w-4xl w-full mx-4 bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="relative">
                            <img id="modalImage" src="" alt="Large preview" class="w-full h-auto max-h-[80vh] object-contain">
                            <button onclick="closeImageModal()" class="absolute top-2 right-2 bg-gray-800 text-white rounded-full p-2 hover:bg-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Modal pertama (Order Modal) -->
                <div id="orderModal{{ $product->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white w-[90%] max-w-md p-5 rounded-lg shadow-lg">
                        <img id="modalProductImage{{ $product->id }}" class="w-full h-48 object-cover rounded mb-4" src="" alt="Produk" />
                        <h3 class="text-xl font-semibold mb-4" id="modalProductName{{ $product->id }}">Nama Produk</h3>
                        <p class="text-gray-700 mb-2" id="modalProductPrice{{ $product->id }}">Harga per Unit: Rp0</p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-700">Jumlah</span>
                            <div class="flex items-center space-x-2">
                                <button class="px-2 py-1 bg-gray-300 rounded" id="decreaseButton{{ $product->id }}">-</button>
                                <span id="quantity{{ $product->id }}" class="text-gray-700">1</span>
                                <button class="px-2 py-1 bg-gray-300 rounded" id="increaseButton{{ $product->id }}">+</button>
                            </div>
                            <input type="hidden" id="productId{{ $product->id }}" value="{{ $product->id}}">
                        </div>
                        <p class="text-gray-700 mb-2">Total Harga: <span id="modalTotalPrice{{ $product->id }}">Rp0</span></p>
                        <p class="text-gray-700 mb-2">Stok Tersisa: <span id="modalProductStock{{ $product->id }}">0</span></p>
                        <div class="flex justify-between space-x-2">
                            <button type="button" id="buyNowButton{{ $product->id }}" class="bg-green-500 text-white px-4 py-2 rounded flex-1 text-center hover:bg-green-200 hover:text-green-500" onclick="createTransaction('{{ $product->id}}')">Beli Langsung</button>
                            <button type="button" id="addToCartButton{{ $product->id }}" class="bg-blue-500 text-white px-4 py-2 rounded flex-1 text-center hover:bg-blue-200 hover:text-blue-500" onclick="addToCart({{ json_encode($product) }})">Tambah ke Keranjang</button>
                        </div>
                        <button type="button" class="block mt-4 mx-auto bg-gray-800 text-gray-300 px-4 py-2 rounded hover:bg-gray-300 hover:text-gray-800" onclick="closeModal('{{ $product->id }}')">Batal</button>
                    </div>
                </div>

                <!-- Modal untuk Konfirmasi Barang Ditambahkan ke Keranjang -->
                <div id="cartConfirmationModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-60">
                    <div class="bg-white w-[90%] max-w-md p-5 rounded-lg shadow-lg text-center">
                        <p class="text-xl font-semibold mb-4">Barang Ditambahkan ke Keranjang!</p>
                        <button onclick="closeCartConfirmationModal()" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-200 hover:text-teal-500">
                            Tutup
                        </button>
                    </div>
                </div>


                <!-- Modal ke 2 -->
                <div id="secondModal{{ $product->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-cyan-100 p-4 rounded-lg shadow-lg w-[80%] mt-4 mb-4">
                        <form id="transactionForm{{ $product->id }}" enctype="multipart/form-data">
                            @csrf
                            <div class="flex justify-between items-center">
                                <h1 class="text-lg font-semibold">Pesanan: {{ $product->nama_produk }}</h1>
                            </div>
                            <div class="mt-4">
                                <input type="hidden" id="orderId{{ $product->id }}" name="order_id" value="{{ optional($product->order)->id }}">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <!-- Data User -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                                    </div>
                                    <div>
                                        <label class="block mb-2">Alamat</label>
                                        <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                                    </div>
                                    <div>
                                        <label class="block mb-2">Nomor Telepon/WA</label>
                                        <input type="text" name="telepon" value="{{ Auth::user()->telepon }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                                    </div>
                                    <div>
                                        <label class="block mb-2">Jumlah Produk</label>
                                        <input type="text" name="kuantitas" value="{{ optional($product->order)->kuantitas }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                                    </div>
                                    <div>
                                        <label class="block mb-2">Upload Custom Gambar</label>
                                        <input type="file" name="custom_image" accept="image/*" class="w-full p-2 border border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <label class="block mb-2">Total Pembayaran</label>
                                        <input type="text" name="total_pembayaran" value="{{ optional($product->order)->total_pembayaran }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                                    </div>
                                </div>
                                <div class="flex justify-center mt-4 space-x-4">
                                    <button type="button" class="bg-cyan-400 text-white px-4 py-2 rounded" onclick="submitTransaction('{{ $product->id }}')">Bayar</button>
                                    <!-- Tombol Cancel -->
                                    <button type="button" class="bg-red-400 text-white px-4 py-2 rounded" onclick="closeModal('{{ $product->id }}', true)">Cancel</button>
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

                <!-- Modal Konfirmasi Cancel -->
                <div id="cancelConfirmationModal{{ $product->id }}" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex items-center justify-center">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                        <h2 class="text-lg font-bold mb-4">Apakah Anda yakin ingin membatalkan pesanan ini?</h2>
                        <div class="flex justify-end space-x-4">
                            <button onclick="confirmCancel('{{ $product->id }}')" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400">Ya</button>
                            <button onclick="closeCancelConfirmation('{{ $product->id }}')" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
                        </div>
                    </div>
                </div>

                @empty
                <p class="text-center text-gray-500">Belum ada produk yang tersedia.</p>
                @endforelse
            </div>
        </div>

        <script>
            function openImageModal(imageUrl) {
                const modal = document.getElementById('imageModal');
                const modalImage = document.getElementById('modalImage');
                
                modalImage.src = imageUrl;
                modal.classList.remove('hidden');
                
                // Prevent scrolling on the background
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                
                // Re-enable scrolling
                document.body.style.overflow = 'auto';
            }

            // Close modal when clicking outside the image
            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });

            // Add keyboard support to close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !document.getElementById('imageModal').classList.contains('hidden')) {
                    closeImageModal();
                }
            });

            function showOrderModal(productId, productName, productPrice, productImage, productStock, buyUrl) {
                const modal = document.getElementById('orderModal' + productId);
                const quantityElement = document.getElementById('quantity' + productId);
                const totalPriceElement = document.getElementById('modalTotalPrice' + productId);
                const buyNowButton = document.getElementById('buyNowButton' + productId);

                // Pastikan productPrice adalah angka
                const formattedProductPrice = parseFloat(productPrice).toLocaleString();

                // Set data produk ke modal
                document.getElementById('modalProductImage' + productId).src = productImage;
                document.getElementById('modalProductName' + productId).innerText = productName;
                document.getElementById('modalProductStock' + productId).innerText = productStock;
                document.getElementById('modalProductPrice' + productId).innerText = 'Harga per Unit: Rp' + formattedProductPrice;

                // Atur kuantitas awal ke 1
                quantityElement.innerText = 1;

                // Hitung total harga awal
                const initialTotalPrice = parseFloat(productPrice);
                totalPriceElement.innerText = 'Rp' + initialTotalPrice.toLocaleString();

                // Atur URL beli langsung untuk kuantitas awal
                buyNowButton.setAttribute('href', buyUrl + '?quantity=1');

                // Tampilkan modal
                modal.classList.remove('hidden');

                // Event listener untuk tombol + dan -
                const increaseButton = document.getElementById('increaseButton' + productId);
                const decreaseButton = document.getElementById('decreaseButton' + productId);

                increaseButton.onclick = () => {
                    let quantity = parseInt(quantityElement.innerText);
                    if (quantity < productStock) {
                        quantity++;
                        quantityElement.innerText = quantity;

                        const totalPrice = quantity * parseFloat(productPrice);
                        totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                        buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
                    }
                };

                decreaseButton.onclick = () => {
                    let quantity = parseInt(quantityElement.innerText);
                    if (quantity > 1) {
                        quantity--;
                        quantityElement.innerText = quantity;

                        const totalPrice = quantity * parseFloat(productPrice);
                        totalPriceElement.innerText = 'Rp' + totalPrice.toLocaleString();

                        buyNowButton.setAttribute('href', buyUrl + '?quantity=' + quantity);
                    }
                };
            }

            // Enhanced closeModal function to handle order deletion
            // Modify the existing closeModal function
            function closeModal(productId, isCancel = false) {
                if (isCancel) {
                    // Show confirmation modal instead of directly canceling
                    const confirmationModal = document.getElementById('cancelConfirmationModal' + productId);
                    confirmationModal.classList.remove('hidden');
                    return;
                }

                const firstModal = document.getElementById('orderModal' + productId);
                const secondModal = document.getElementById('secondModal' + productId);
                
                if (firstModal) firstModal.classList.add('hidden');
                if (secondModal) secondModal.classList.add('hidden');
            }

            // Add new function to handle cancel confirmation
            function confirmCancel(productId) {
                const orderIdElement = document.getElementById('orderId' + productId);
                
                if (orderIdElement && orderIdElement.value) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    fetch(`/order/destroy/${orderIdElement.value}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(async response => {
                        const data = await response.json();
                        
                        if (!response.ok) {
                            throw new Error(data.message || `HTTP error! status: ${response.status}`);
                        }
                        
                        // Reset orderId and hide all modals
                        orderIdElement.value = '';
                        const firstModal = document.getElementById('orderModal' + productId);
                        const secondModal = document.getElementById('secondModal' + productId);
                        const confirmationModal = document.getElementById('cancelConfirmationModal' + productId);
                        
                        if (firstModal) firstModal.classList.add('hidden');
                        if (secondModal) secondModal.classList.add('hidden');
                        if (confirmationModal) confirmationModal.classList.add('hidden');
                        
                        // Show success message
                        alert(data.message || 'Pesanan berhasil dibatalkan');
                    })
                    .catch(error => {
                        console.error('Error deleting order:', error);
                        alert(error.message || 'Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.');
                    });
                }
            }

            // Add function to close cancel confirmation modal
            function closeCancelConfirmation(productId) {
                const confirmationModal = document.getElementById('cancelConfirmationModal' + productId);
                if (confirmationModal) {
                    confirmationModal.classList.add('hidden');
                }
            }

            // Helper function for deleting orders
            async function deleteOrder(orderId) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                try {
                    const response = await fetch(`/order/destroy/${orderId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || 'Gagal menghapus pesanan');
                    }

                    return data;
                } catch (error) {
                    console.error('Terjadi kesalahan saat menghapus pesanan:', error);
                    throw error;
                }
            }

            function createTransaction(productId) {
                const quantity = parseInt(document.getElementById('quantity' + productId).innerText);
                const totalPrice = parseFloat(document.getElementById('modalTotalPrice' + productId).innerText.replace(/[^\d]/g, ''));
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                const productIdValue = document.getElementById('productId' + productId).value;

                fetch('/order/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productIdValue,
                        kuantitas: quantity,
                        total_pembayaran: totalPrice
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text || 'Terjadi kesalahan'); });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.order_id) {
                        document.getElementById('orderId' + productId).value = data.order_id;
                        
                        // Update total pembayaran di modal kedua
                        const totalPembayaranInput = document.querySelector('#secondModal' + productId + ' input[name="total_pembayaran"]');
                        if(totalPembayaranInput) {
                            totalPembayaranInput.value = totalPrice; // totalPrice dihitung dari kuantitas * harga
                        }
                    }
                    // Sembunyikan modal pertama dan tampilkan modal kedua
                    const firstModal = document.getElementById('orderModal' + productId);
                    if (firstModal) {
                        firstModal.classList.add('hidden');
                    }
                    const secondModal = document.getElementById('secondModal' + productId);
                    if (secondModal) {
                        secondModal.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Unknown error'));
                });
            }


            // Function to show the cart confirmation modal
            function showCartConfirmationModal() {
                const modal = document.getElementById('cartConfirmationModal');
                modal.classList.remove('hidden'); // Menampilkan modal
            }

            // Function to close the cart confirmation modal
            function closeCartConfirmationModal() {
                const modal = document.getElementById('cartConfirmationModal');
                modal.classList.add('hidden'); // Menyembunyikan modal
            }

            // Function to add product to cart and trigger modal flow
            function addToCart(product) {
                const { id, nama_produk, harga } = product;
                const quantity = parseInt(document.getElementById('quantity' + id)?.textContent || 0);

                if (!id || !quantity) {
                    console.error("Produk atau kuantitas tidak valid.");
                    return;
                }

                fetch('/cart/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        id_produk: id,
                        kuantitas: quantity,
                        subtotal: harga * quantity,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the order modal
                        const orderModal = document.getElementById('orderModal' + id);
                        orderModal.classList.add('hidden');
                        
                        // Show the confirmation modal
                        const confirmationModal = document.getElementById('cartConfirmationModal');
                        confirmationModal.classList.remove('hidden');
                        
                        console.log("Berhasil menambahkan ke keranjang:", data.cart);
                    } else {
                        console.error("Gagal menambahkan ke keranjang:", data.message);
                        alert("Gagal menambahkan ke keranjang: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Terjadi kesalahan saat menambahkan ke keranjang");
                });
            }

            function submitTransaction(productId) {
                const form = document.getElementById('transactionForm' + productId);
                const formData = new FormData(form);

                // Validasi
                if (!validateForm(formData)) {
                    return;
                }

                // Tambahkan order_id ke formData
                const orderId = document.getElementById('orderId' + productId).value;
                formData.append('order_id', orderId);

                // Kirim request
                fetch('/transaction/store', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                handlePaymentSuccess(result, data.transaction_id);
                            },
                            onPending: function(result) {
                                handlePaymentPending(result, data.transaction_id);
                            },
                            onError: function(result) {
                                handlePaymentError(result);
                            },
                            onClose: function() {
                                handlePaymentClose();
                            }
                        });
                    } else {
                        throw new Error(data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + (error.message || 'Unknown error'));
                });
            }

            function validateForm(formData) {
                const requiredFields = {
                    'total_pembayaran': 'Total Pembayaran'
                };

                let isValid = true;
                let errorMessage = 'Mohon lengkapi data berikut:\n';

                for (const [field, label] of Object.entries(requiredFields)) {
                    if (!formData.get(field)) {
                        errorMessage += `- ${label}\n`;
                        isValid = false;
                    }
                }

                if (!isValid) {
                    alert(errorMessage);
                }

                return isValid;
            }

            function handlePaymentSuccess(result, transactionId) {
                console.log('success', result);
                window.location.href = `/product`;
            }

            function handlePaymentPending(result, transactionId) {
                console.log('pending', result);
                window.location.href = `/payment/pending?order_id=${transactionId}`;
            }

            function handlePaymentError(result) {
                console.log('error', result);
                alert('Pembayaran gagal!');
            }

            function handlePaymentClose() {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran');
            }



        </script>

    </div>
    @endif    
@endguest
@endsection
@section("scripts")
    <script 
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
    </script>
@endsection