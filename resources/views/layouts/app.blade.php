<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DR AKTA PERCETAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-800 flex flex-col w-[95%] mx-auto"> <!-- Body dengan w-[95%] dan mx-auto -->
<div class="min-h-screen flex flex-col">
    <!-- Header Section -->
    <header class="text-white flex justify-between items-center p-4 bg-gray-800 relative">
        <h1 class="text-3xl font-bold">DR AKTA PERCETAKAN</h1>
        @guest
        <span>Halo, Selamat Datang</span>
        @else
            <div class="relative">
                @if(Auth::user()->role === 'admin')
                    <button class="flex items-center bg-gray-700 px-4 py-2 rounded hover:bg-white hover:rounded-lg hover:text-gray-900 focus:outline-none rounded-lg">
                        <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                        class="block">Hai Admin, Logout</a>
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <button id="dropdownButton" class="flex items-center bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 focus:outline-none rounded-lg">
                        Hai, {{ Auth::user()->name }}
                    </button>

                    <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded shadow-lg z-50">
                        <a href="{{ route('profile') }}" class="block px-4 py-2 hover:bg-gray-200">Profil</a>
                        <a href="{{ route('logout') }}" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                        class="block px-4 py-2 hover:bg-gray-200">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                        </form>
                    </div>
                @endif
            </div>
        @endguest

    </header>

    <!-- Navbar Section -->
    @guest
        <div class="w-full mx-auto">
            <div class="bg-teal-600 p-4 rounded-lg shadow-lg">
                <nav class="flex justify-between items-center w-full">
                    <div class="flex space-x-8">
                        <a href="{{ auth()->check() ? route('product.index') : route('home') }}" class="text-black text-lg hover:text-white">Home</a>
                        <a href="{{route('product.index')}}" class="text-black text-lg hover:text-white">Katalog Produk</a>
                        <a href="{{route('login')}}" class="text-black text-lg hover:text-white">Cek Pesanan</a>
                        <a href="{{route('profil-perusahaan')}}" class="text-black text-lg hover:text-white">Profil Perusahaan</a>
                    </div>
                    <a href="{{route('login')}}" class="bg-teal-400 text-black px-4 py-2 border border-black rounded hover:bg-white hover:text-teal-600">Masuk/Daftar</a>
                </nav>
            </div>
        </div>
    @else
        @if (Auth::check() && Auth::user()->role === 'admin')
            <div class="w-full mx-auto mt-2"> <!-- Navbar dengan w-[95%] dan mx-auto -->
                <div class="bg-teal-600 p-4 rounded-lg shadow-lg">
                    <nav class="flex justify-between items-center w-full">
                        <div class="flex space-x-8">
                            <a href="{{route('HomeAdmin')}}" class="hover:text-white text-black text-lg">Home</a>
                            <a href="{{route('product.index')}}" class="hover:text-white text-black text-lg">Data Produk</a>
                            <a href="{{route('data-pemesanan')}}" class="hover:text-white text-black text-lg">Data Pemesanan</a>
                            <a href="{{route('laporan')}}" class="hover:text-white text-black text-lg">Laporan</a>
                            <a href="{{route('profil-perusahaan-edit')}}" class="hover:text-white text-black text-lg">Manajemen Profil Perusahaan</a>
                            <a href="{{route('manajemen-user')}}" class="hover:text-white text-black text-lg">Manajemen User</a>
                            <a href="{{route('beranda-edit')}}" class="hover:text-white text-black text-lg">Manajemen Beranda</a>
                        </div>
                    </nav>
                </div>
            </div>
        @elseif (Auth::check() && Auth::user()->role === 'pelanggan')
            <!--Navbar -->
            <div class="w-full mx-auto mt-2">
                <div class="bg-teal-600 p-4 rounded-lg shadow-lg flex justify-between items-center">
                    <div class="flex space-x-4">
                        <a href="{{route('home')}}" class="text-black text-lg hover:text-white">Home</a>
                        <a href="{{route('product.index')}}" class="text-black text-lg hover:text-white">Katalog Produk</a>
                        <a href="{{route('data-pesanan')}}" class="text-black text-lg hover:text-white">Cek Pesanan</a>
                        <a href="{{route('profil-perusahaan')}}" class="text-black text-lg hover:text-white">Profil Perusahaan</a>
                    </div>
                    <div class="flex items-center space-x-4 ml-auto"> <!-- ml-auto untuk memastikan berada di sebelah kanan -->
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Cari produk" 
                                class="pl-8 pr-4 py-1 rounded bg-gray-200 text-black">
                            <i class="fas fa-search absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-600"></i>
                        </div>
                        <i class="fas fa-shopping-cart text-black cursor-pointer" id="cartIcon"></i> <!-- Pastikan ini tetap ada di dalam flex container -->
                    </div>
                </div>
            </div>

            <!-- Modal Konfirmasi Hapus -->
            <div id="confirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden flex items-center justify-center">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-lg font-bold mb-4">Apakah Anda ingin menghapus produk yang dipilih?</h2>
                    <div class="flex justify-end space-x-4">
                        <button id="confirmDeleteButton" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-400">Ya</button>
                        <button id="cancelDeleteButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-400">Tidak</button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Keranjang -->
            <div id="cartSidebar" class="fixed inset-y-0 right-0 bg-gray-800 bg-opacity-50 z-40 hidden">
                <div class="w-full max-w-md bg-teal-200 p-4 border border-black h-full overflow-y-auto">
                    <!-- Header -->
                    <div class="flex items-center mb-4">
                        <i class="fas fa-arrow-left text-2xl cursor-pointer" id="closeCart"></i>
                        <h1 class="text-2xl font-bold ml-4">Keranjang Saya</h1>
                    </div>
                    <hr class="border-black mb-4" />

                    <!-- Daftar Produk -->
                    <div id="cartItems" class="space-y-4"></div>
                    <hr class="mt-4 border-black mb-4" />

                    <!-- Total dan Tombol Aksi -->
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-lg font-bold">Total Bayar: Rp<span id="totalAmount">0</span></p>
                    </div>

                    <div class="flex justify-between">
                        <button id="checkoutButton" class="bg-teal-500 text-white px-4 py-2 rounded-lg hover:bg-teal-300 hover:text-teal-500">Bayar</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-300 hover:text-red-500" id="deleteSelected">Hapus</button>
                    </div>
                </div>
            </div>

            <!-- Container Modal -->
            <div id="paymentModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-cyan-100 p-4 rounded-lg shadow-lg w-[80%] max-h-[90%] overflow-y-auto">
                    <!-- Header Modal -->
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-bold">Detail Pembayaran</h1>
                    </div>

                    <!-- Daftar Card Produk -->
                    <div id="modalProductCards" class="space-y-4">
                        <!-- Card Produk akan ditambahkan di sini oleh JavaScript -->
                    </div>

                    <!-- Total Harga -->
                    <div id="totalHargaContainer" class="mb-2 bg-teal-600 text-white px-4 py-2 rounded text-center mt-4">
                        <span class="text-lg font-semibold">Total Harga:</span>
                        <span id="totalHarga" class="text-lg font-bold">0</span>
                    </div>
                    <!-- Tombol Aksi -->
                    <div class="flex justify-center mt-4 space-x-4">
                        <button type="button" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-300 hover:text-teal-500" id="payButton">Bayar</button>
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-300 hover:text-red-500" id="cancelButton">Cancel</button>
                    </div>
                </div>
            </div>
            
    <script>
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            const query = this.value.trim();
            
            // Get the products container
            const productsContainer = document.querySelector('.products-container');
            
            // Only proceed if we're on the products page
            if (productsContainer) {
                searchTimeout = setTimeout(() => {
                    if (query.length > 0) {
                        // Show loading state
                        productsContainer.innerHTML = '<div class="text-center p-4">Mencari produk...</div>';
                        
                        // In your search input event listener:
                        fetch(`/product/search?query=${encodeURIComponent(query)}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new TypeError("Oops, we haven't got JSON!");
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.html) {
                                document.querySelector('.products-container').innerHTML = data.html;
                            } else {
                                document.querySelector('.products-container').innerHTML = '<p class="text-center p-4 text-red-500">Tidak ada produk yang ditemukan</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.querySelector('.products-container').innerHTML = '<p class="text-center p-4 text-red-500">Terjadi kesalahan saat mencari produk</p>';
                        });
                    } else {
                        // If search is empty, reload the page to get all products
                        window.location.reload();
                    }
                }, 300); // Delay for 300ms to prevent too many requests
            } else {
                // If we're not on the products page, redirect to products page with search query
                window.location.href = `/product?query=${encodeURIComponent(query)}`;
            }
        });

        // Function to reinitialize all event listeners and functions for product cards
        function initializeProductCardFunctions() {
            // Add your existing product card initialization code here
            // For example:
            const addToCartButtons = document.querySelectorAll('.add-to-cart-button');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Your existing add to cart functionality
                });
            });
            
            // Add other initialization code for your product card functions
        }

        document.addEventListener("DOMContentLoaded", function() {
            console.log("Script berjalan!");

            const dropdownButton = document.getElementById("dropdownButton");
            const dropdownMenu = document.getElementById("dropdownMenu");

            dropdownButton.addEventListener("click", function (event) {
                event.stopPropagation(); // Menghentikan propagasi klik agar tidak memicu klik pada dokumen
                dropdownMenu.classList.toggle("hidden");
            });

            // Menyembunyikan dropdown saat klik di luar
            document.addEventListener("click", function () {
                if (!dropdownMenu.classList.contains("hidden")) {
                    dropdownMenu.classList.add("hidden");
                }
            });

            const updateCartItems = () => {
                fetch('/cart/user', {  
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(cart => {
                    console.log('Data cart:', cart);
                    const cartItemsContainer = document.getElementById('cartItems');
                    const totalAmountElement = document.getElementById('totalAmount');
                    cartItemsContainer.innerHTML = '';

                    if (!cart || cart.length === 0) {
                        cartItemsContainer.innerHTML = '<p>Keranjang kosong</p>';
                        totalAmountElement.innerText = '0';
                        return;
                    }

                    let totalAmount = 0;

                    cart.forEach(item => {
                        if (item && item.product) {
                            const totalHargaProduk = item.product.harga * item.kuantitas;
                            totalAmount += totalHargaProduk;

                            const cartItem = `
                                <div class="flex items-center mb-4">
                                    <input type="checkbox" class="cart-checkbox" data-cart-id="${item.id}" />
                                    <img 
                                        alt="${item.product.nama_produk}" 
                                        class="w-24 h-24 mr-4 rounded-lg object-cover" 
                                        src="${item.product.gambar}" 
                                        onerror="this.src='/images/placeholder.png'; this.onerror=null;"
                                    />
                                    <div>
                                        <p class="text-lg font-bold">${item.product.nama_produk}</p>
                                        <p class="text-lg">Rp${Number(totalHargaProduk).toLocaleString('id-ID')}</p>
                                        <div class="flex items-center">
                                            <button class="decrease-quantity text-2xl" data-cart-id="${item.id}">-</button>
                                            <input type="text" value="${item.kuantitas}" class="quantity-input w-12 text-center mx-2" data-cart-id="${item.id}" />
                                            <button class="increase-quantity text-2xl" data-cart-id="${item.id}">+</button>
                                        </div>
                                    </div>
                                </div>`;
                            cartItemsContainer.innerHTML += cartItem;
                        }
                    });

                    totalAmountElement.innerText = totalAmount.toLocaleString('id-ID');
                    setupQuantityListeners();
                })
                .catch(error => {
                    console.error('Error memuat keranjang:', error);
                    document.getElementById('cartItems').innerHTML = '<p>Error memuat keranjang. Silakan coba lagi.</p>';
                });
            };

            const updateCartQuantity = (cartId, quantity) => {
                fetch(`/cart/update/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        kuantitas: quantity
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateCartItems(); // Refresh tampilan keranjang
                    } else {
                        alert('Gagal mengupdate kuantitas: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengupdate kuantitas');
                });
            };

            // Update the quantity listeners setup
            const setupQuantityListeners = () => {
                // Event listener untuk tombol tambah
                document.querySelectorAll('.increase-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                        const newQuantity = parseInt(input.value) + 1;
                        updateCartQuantity(cartId, newQuantity);
                    });
                });

                // Event listener untuk tombol kurang
                document.querySelectorAll('.decrease-quantity').forEach(button => {
                    button.addEventListener('click', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const input = document.querySelector(`.quantity-input[data-cart-id="${cartId}"]`);
                        const newQuantity = Math.max(1, parseInt(input.value) - 1); // Minimal 1
                        updateCartQuantity(cartId, newQuantity);
                    });
                });

                // Event listener untuk input langsung
                document.querySelectorAll('.quantity-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const cartId = this.getAttribute('data-cart-id');
                        const newQuantity = Math.max(1, parseInt(this.value) || 1);
                        this.value = newQuantity; // Update input value
                        updateCartQuantity(cartId, newQuantity);
                    });
                });
            };
            
            // Modify the removeSelectedItems function to show confirmation modal first
            const removeSelectedItems = () => {
                const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                
                if (selectedCheckboxes.length === 0) {
                    alert('Pilih item yang ingin dihapus!');
                    return;
                }

                // Show confirmation modal
                const confirmationModal = document.getElementById('confirmationModal');
                confirmationModal.classList.remove('hidden');
            };

            // Add event listeners for confirmation modal buttons
            document.getElementById('confirmDeleteButton').addEventListener('click', function() {
                const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                const cartIdsToDelete = Array.from(selectedCheckboxes).map(checkbox => 
                    checkbox.getAttribute('data-cart-id')
                );

                Promise.all(
                    cartIdsToDelete.map(cartId =>
                        fetch(`/cart/${cartId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                        })
                    )
                )
                .then(responses => {
                    const errors = responses.filter(response => !response.ok);
                    if (errors.length > 0) {
                        alert('Beberapa item gagal dihapus.');
                    } else {
                        alert('Item berhasil dihapus.');
                    }
                    // Hide confirmation modal
                    document.getElementById('confirmationModal').classList.add('hidden');
                    // Update cart items display
                    updateCartItems();
                })
                .catch(error => {
                    console.error('Error menghapus item:', error);
                    alert('Terjadi kesalahan saat menghapus item');
                    document.getElementById('confirmationModal').classList.add('hidden');
                });
            });

            // Add event listener for cancel button
            document.getElementById('cancelDeleteButton').addEventListener('click', function() {
                document.getElementById('confirmationModal').classList.add('hidden');
            });

            // Update the delete button event listener
            document.getElementById('deleteSelected').addEventListener('click', removeSelectedItems);

            document.getElementById('checkoutButton').addEventListener('click', function() {
                // Get selected items from cart
                const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                
                if (selectedCheckboxes.length === 0) {
                    alert('Pilih produk yang ingin dibayar!');
                    return;
                }

                const cartSidebar = document.getElementById('cartSidebar');
                const paymentModal = document.getElementById('paymentModal');
                const modalProductCards = document.getElementById('modalProductCards');
                
                // Hide cart sidebar and show payment modal
                cartSidebar.classList.add('hidden');
                paymentModal.classList.remove('hidden');
                
                modalProductCards.innerHTML = ''; // Clear previous content
                
                // Get cart items from server
                fetch('/cart/user', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    }
                })
                .then(response => response.json())
                .then(cartItems => {
                    const selectedCartIds = Array.from(selectedCheckboxes).map(checkbox => 
                        checkbox.getAttribute('data-cart-id')
                    );
                    
                    let totalHarga = 0;
                    
                    // Filter only selected items and create their orders
                    cartItems.forEach(item => {
                        if (selectedCartIds.includes(item.id.toString())) {
                            const hargaProduk = item.product.harga * item.kuantitas;
                            totalHarga += hargaProduk;

                            // Create order for each selected item
                            fetch('/order/store', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    product_id: item.product.id_produk,
                                    kuantitas: item.kuantitas,
                                    total_pembayaran: hargaProduk
                                })
                            })
                            .then(response => response.json())
                            .then(data => console.log(data))
                            .catch(error => console.error('Error creating order:', error));

                            const productCard = `
                                <div class="bg-teal-600 text-white p-4 rounded-lg">
                                    <h2 class="text-lg font-bold">Pesanan: ${item.product.nama_produk}</h2>
                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                        <input type="hidden" name="cart_id" value="${item.id}">
                                        <input type="hidden" name="product_id" value="${item.product.id}">
                                        <div>
                                            <img src="${item.product.gambar}" alt="Gambar Produk" class="w-32 h-32 object-cover rounded-lg">
                                        </div><br>
                                        <div>
                                            <label class="block mb-2">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ Auth::user()->name }}" class="w-full p-2 rounded bg-teal-900" disabled>
                                        </div>
                                        <div>
                                            <label class="block mb-2">Alamat</label>
                                            <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" class="w-full p-2 bg-teal-900 rounded" disabled>
                                        </div>
                                        <div>
                                            <label class="block mb-2">Nomor Telepon/WA</label>
                                            <input type="text" name="telepon" value="{{ Auth::user()->telepon }}" class="w-full p-2 bg-teal-900 rounded" disabled>
                                        </div>
                                        <div>
                                            <label class="block mb-2">Upload Custom Gambar</label>
                                            <input type="file" name="custom_image" accept="image/*" class="w-full p-2 bg-teal-900 rounded">
                                        </div>
                                        <div>
                                            <label class="block mb-2">Jumlah Produk</label>
                                            <input type="text" name="quantity" value="${item.kuantitas}" class="w-full p-2 rounded bg-teal-900" disabled>
                                        </div>
                                    </div>
                                </div>
                            `;
                            modalProductCards.innerHTML += productCard;
                        }
                    });

                    document.getElementById('totalHarga').innerText = totalHarga.toLocaleString('id-ID');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat data produk');
                });
            });

            document.getElementById('cancelButton').addEventListener('click', function() {
                const paymentModal = document.getElementById('paymentModal');
                paymentModal.classList.add('hidden');
            });

            document.getElementById('payButton').addEventListener('click', async function() {
                try {
                    // 1. Get all checked items from cart
                    const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                    
                    if (selectedCheckboxes.length === 0) {
                        alert("Pilih item yang ingin dibayar!");
                        return;
                    }

                    // 2. Get custom image if exists
                    const customImageInput = document.querySelector("input[name='custom_image']");

                    try {
                        // 3. Get cart items data
                        const response = await fetch('/cart/user', {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            }
                        });
                        
                        const cartItems = await response.json();
                        const selectedCartIds = Array.from(selectedCheckboxes).map(cb => cb.getAttribute('data-cart-id'));
                        const selectedItems = cartItems.filter(item => selectedCartIds.includes(item.id.toString()));

                        // 4. Create orders first
                        const orderPromises = selectedItems.map(item => 
                            fetch('/order/store', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                },
                                body: JSON.stringify({
                                    product_id: item.id_produk,
                                    kuantitas: item.kuantitas,
                                    total_pembayaran: item.product.harga * item.kuantitas
                                })
                            }).then(res => res.json())
                        );

                        const orders = await Promise.all(orderPromises);

                        // 5. Prepare form data for transaction
                        const formData = new FormData();
                        
                        // Add custom image if exists
                        if (customImageInput && customImageInput.files[0]) {
                            formData.append("custom_image", customImageInput.files[0]);
                        }

                        // 6. Add cart items with their order IDs
                        const cartData = selectedItems.map((item, index) => {
                            if (orders[index] && orders[index].order_id) {
                                return {
                                    user_id: item.id_user,
                                    product_id: item.id_produk,
                                    order_id: orders[index].order_id,
                                    kuantitas: item.kuantitas,
                                    total_pembayaran: item.product.harga
                                };
                            }
                        }).filter(Boolean);

                        // Modified formData preparation in your JavaScript
                        formData.append('cart', JSON.stringify(cartData));

                        // Change to this:
                        // const cartData = selectedItems.map((item, index) => {
                        //     if (orders[index] && orders[index].order_id) {
                        //         const pricePerItem = item.product.harga;
                        //         return {
                        //             user_id: item.id_user,
                        //             product_id: item.id_produk,
                        //             order_id: orders[index].order_id,
                        //             kuantitas: parseInt(item.kuantitas),
                        //             total_pembayaran: pricePerItem  // Price per item, not multiplied by quantity
                        //         };
                        //     }
                        // }).filter(Boolean);

                        cartData.forEach((item, index) => {
                            formData.append(`cart[${index}][user_id]`, item.user_id);
                            formData.append(`cart[${index}][product_id]`, item.product_id);
                            formData.append(`cart[${index}][order_id]`, item.order_id);
                            formData.append(`cart[${index}][kuantitas]`, item.kuantitas.toString());
                            formData.append(`cart[${index}][total_pembayaran]`, item.total_pembayaran.toString());
                        });

                        // 7. Send transaction request
                        const transactionResponse = await fetch("/transaction/cart", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: formData
                        });

                        const result = await transactionResponse.json();

                        if (result.success && result.snap_token) {
                            // 8. Handle Midtrans payment
                            window.snap.pay(result.snap_token, {
                                onSuccess: async function(result) {
                                    try {
                                        // Delete paid items from cart
                                        const deletePromises = selectedCartIds.map(cartId =>
                                            fetch(`/cart/${cartId}`, {
                                                method: 'DELETE',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                }
                                            })
                                        );

                                        await Promise.all(deletePromises);

                                        // Update payment status
                                        await fetch(`/payment/update-status/${result.transaction_id}`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                            },
                                            body: JSON.stringify({
                                                status: 'success',
                                                payment_data: result
                                            })
                                        });

                                        // Redirect to products page
                                        window.location.href = '/product';
                                    } catch (error) {
                                        console.error("Error in success handling:", error);
                                        alert("Pembayaran berhasil, tetapi terjadi kesalahan dalam memperbarui status.");
                                    }
                                },
                                onPending: function(result) {
                                    window.location.href = `/product`;
                                },
                                onError: function(result) {
                                    console.error("Payment error:", result);
                                    alert("Pembayaran gagal!");
                                },
                                onClose: function() {
                                    alert("Anda menutup popup tanpa menyelesaikan pembayaran");
                                }
                            });
                        } else {
                            throw new Error(result.message || "Gagal memulai pembayaran.");
                        }
                    } catch (error) {
                        console.error("Error:", error);
                        alert(error.message || "Terjadi kesalahan saat memproses pembayaran.");
                    }
                } catch (error) {
                    console.error("Error in payment process:", error);
                    alert("Terjadi kesalahan saat memproses pembayaran.");
                }
            });


            document.getElementById('cartIcon').addEventListener('click', () => {
                const cartSidebar = document.getElementById('cartSidebar');
                cartSidebar.classList.remove('hidden');
                updateCartItems();
            });

            document.getElementById('closeCart').addEventListener('click', () => {
                const cartSidebar = document.getElementById('cartSidebar');
                cartSidebar.classList.add('hidden');
            });
        });
    </script>






        @endif
    @endguest
 

    <!-- Main Content Section -->
    <main class="w-full mx-auto">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="w-full mx-auto flex flex-col justify-between bg-gray-700 p-4 text-left mt-auto">
        <p class="text-gray-300">&copy; 2024 DR AKTA PERCETAKAN. All rights reserved.</p>
    </footer>

    <!-- ICON CHAT-->
    <!-- Chat Button -->
<a href="http://localhost:8000/chatify" 
   class="fixed bottom-6 right-6 flex items-center bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 pl-3 pr-4 rounded-full shadow-lg transition duration-300">
    
    <!-- Chat Icon -->
    <div class="bg-white rounded-full w-6 h-6 flex items-center justify-center mr-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-blue-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m-9 5a1 1 0 0 1-1-1v-4a9 9 0 1 1 18 0v4a1 1 0 0 1-1 1H5z" />
        </svg>
    </div>

    <!-- Text -->
    <span>Live Chat 24/7</span>
</a>
</div>

<script 
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

@yield('scripts')
</body>
</html>
