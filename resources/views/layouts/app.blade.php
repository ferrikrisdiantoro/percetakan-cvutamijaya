<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DR AKTA PERCETAKAN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ Auth::user()->id_user ?? '' }}">
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
                        Hai, {{ Auth::user()->nama_lengkap }}
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
                            <input type="text" placeholder="Cari produk" class="pl-8 pr-4 py-1 rounded bg-gray-200 text-black">
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

                    <div class="mb-2 bg-teal-600 px-4 py-2 rounded mt-4">
                        <div>
                            <label for="payment_method" class="block font-large text-white font-bold mb-2">Mode Pembayaran</label>
                            <select name="payment_method" class="bg-teal-900 text-white rounded-lg p-2 w-full">
                                <option value="">-- Pilih Jenis Pembayaran --</option>
                                <option value="dp">DP</option>
                                <option value="lunas">LUNAS</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="bank_name" class="block font-large text-white font-bold mb-2">Pilih Bank</label>
                                <select name="bank_name" class="bg-teal-900 text-white rounded-lg p-2 w-full">
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="BCA">BCA</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="BRI">BRI</option>
                                    <option value="BNI">BNI</option>
                                </select>
                            </div>
                            <div>
                                <label for="proof_of_payment" class="block font-large text-white font-bold mb-2">Unggah Bukti Pembayaran</label>
                                <input type="file" name="proof_of_payment" class="rounded-lg p-2 bg-teal-900 text-white w-full">
                            </div>
                        </div>
                    </div>
                    <!-- Tombol Aksi -->
                    <div class="flex justify-center mt-4 space-x-4">
                        <button type="button" class="bg-teal-500 text-white px-4 py-2 rounded hover:bg-teal-300 hover:text-teal-500" id="payButton">Bayar</button>
                        <button type="button" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-300 hover:text-red-500" id="cancelButton">Cancel</button>
                    </div>
                </div>
            </div>
            
    <script>
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
                                    <input type="checkbox" class="cart-checkbox" data-cart-id="${item.id_cart}" />
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
                                            <button class="decrease-quantity text-2xl" data-cart-id="${item.id_cart}">-</button>
                                            <input type="number" value="${item.kuantitas}" class="quantity-input w-12 text-center mx-2" data-cart-id="${item.id_cart}" />
                                            <button class="increase-quantity text-2xl" data-cart-id="${item.id_cart}">+</button>
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
            
            const removeSelectedItems = () => {
                const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                const cartIdsToDelete = Array.from(selectedCheckboxes).map(checkbox => checkbox.getAttribute('data-cart-id'));

                if (cartIdsToDelete.length === 0) {
                    alert('Pilih item yang ingin dihapus!');
                    return;
                }

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
                    updateCartItems();
                })
                .catch(error => console.error('Error menghapus item:', error));
            };

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
                        if (selectedCartIds.includes(item.id_cart.toString())) {
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
                                        <input type="hidden" name="cart_id" value="${item.id_cart}">
                                        <input type="hidden" name="product_id" value="${item.product.id_produk}">
                                        <div>
                                            <img src="${item.product.gambar}" alt="Gambar Produk" class="w-32 h-32 object-cover rounded-lg">
                                        </div><br>
                                        <div>
                                            <label class="block mb-2">Nama Lengkap</label>
                                            <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" class="w-full p-2 rounded bg-teal-900" disabled>
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
                                            <input type="number" name="quantity" value="${item.kuantitas}" class="w-full p-2 rounded bg-teal-900" disabled>
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
                // 1. Get all checked items from cart
                const selectedCheckboxes = document.querySelectorAll('.cart-checkbox:checked');
                
                if (selectedCheckboxes.length === 0) {
                    alert("Pilih item yang ingin dibayar!");
                    return;
                }

                // 2. Get and validate form data
                const paymentMethod = document.querySelector("select[name='payment_method']").value;
                const bankName = document.querySelector("select[name='bank_name']").value;
                const proofOfPaymentInput = document.querySelector("input[name='proof_of_payment']");
                const customImageInput = document.querySelector("input[name='custom_image']");

                if (!paymentMethod || !bankName || !proofOfPaymentInput.files[0]) {
                    alert("Lengkapi semua informasi pembayaran!");
                    return;
                }

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
                    const selectedItems = cartItems.filter(item => selectedCartIds.includes(item.id_cart.toString()));

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

                    // 5. Prepare form data with files
                    const formData = new FormData();
                    formData.append("payment_method", paymentMethod);
                    formData.append("bank_name", bankName);
                    
                    // Add proof of payment file
                    if (proofOfPaymentInput.files[0]) {
                        formData.append("proof_of_payment", proofOfPaymentInput.files[0]);
                    }
                    
                    // Add custom image if exists
                    if (customImageInput && customImageInput.files[0]) {
                        formData.append("custom_image", customImageInput.files[0]);
                    }

                    // 6. Add cart items with their order IDs
                    selectedItems.forEach((item, index) => {
                        if (orders[index] && orders[index].order_id) {
                            formData.append(`cart[${index}][user_id]`, item.id_user);
                            formData.append(`cart[${index}][product_id]`, item.id_produk);
                            formData.append(`cart[${index}][order_id]`, orders[index].order_id);
                            formData.append(`cart[${index}][kuantitas]`, item.kuantitas);
                            formData.append(`cart[${index}][total_harga]`, item.product.harga * item.kuantitas);
                            formData.append(`cart[${index}][total_pembayaran]`, item.product.harga * item.kuantitas);
                        }
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

                    if (result.success) {
                        // 8. Delete paid items from cart
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

                        alert("Pembayaran berhasil!");
                        updateCartItems(); // Refresh cart display
                        document.getElementById('paymentModal').classList.add("hidden");
                        
                        // Clear form
                        proofOfPaymentInput.value = '';
                        if (customImageInput) customImageInput.value = '';
                    } else {
                        throw new Error(result.message || "Gagal melakukan pembayaran.");
                    }
                } catch (error) {
                    console.error("Error:", error);
                    alert(error.message || "Terjadi kesalahan saat memproses pembayaran.");
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
</div>
</body>
</html>
