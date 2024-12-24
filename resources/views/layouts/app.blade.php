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

    <!-- Header Section -->
    <header class="text-white flex justify-between items-center p-4 bg-gray-800">
        <h1 class="text-3xl font-bold">DR AKTA PERCETAKAN</h1>
        @guest
            <span>Hai Kamu</span>
        @else
            @if (Auth::check() && Auth::user()->role === 'admin')
                <span>Hai Admin, <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:text-teal-300">Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form></span>
            @elseif (Auth::check() && Auth::user()->role === 'pelanggan')
                <span>Hai Pelanggan, <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:text-teal-300">Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form></span>
            @endif
        @endguest
    </header>

    <!-- Navbar Section -->
    @guest
        <div class="w-full mx-auto">
            <div class="bg-teal-600 p-4 rounded-lg shadow-lg">
                <nav class="flex justify-between items-center w-full">
                    <div class="flex space-x-8">
                        <a href="{{ auth()->check() ? route('product.index') : route('home') }}" class="text-black hover:text-white">Home</a>
                        <a href="{{route('login')}}" class="text-black text-lg">Cek Pesanan</a>
                        <a href="{{route('profil-perusahaan')}}" class="text-black text-lg">Profil Perusahaan</a>
                    </div>
                    <a href="{{route('login')}}" class="bg-teal-400 text-black px-4 py-2 border border-black rounded">Masuk/Daftar</a>
                </nav>
            </div>
        </div>
    @else
        @if (Auth::check() && Auth::user()->role === 'admin')
            <div class="w-full mx-auto mt-2"> <!-- Navbar dengan w-[95%] dan mx-auto -->
                <div class="bg-teal-600 p-4 rounded-lg shadow-lg">
                    <nav class="flex justify-between items-center w-full">
                        <div class="flex space-x-8">
                            <a href="{{route('HomeAdmin')}}" class="text-black text-lg">Home</a>
                            <a href="{{route('product.index')}}" class="text-black text-lg">Data Produk</a>
                            <a href="{{route('data-pemesanan')}}" class="text-black text-lg">Data Pemesanan</a>
                            <a href="{{route('laporan')}}" class="text-black text-lg">Laporan</a>
                            <a href="{{route('profil-perusahaan')}}" class="text-black text-lg">Profil Perusahaan</a>
                        </div>
                    </nav>
                </div>
            </div>
        @elseif (Auth::check() && Auth::user()->role === 'pelanggan')
            <!-- Bagian Keranjang & Navbar -->
            <div class="w-full mx-auto mt-2">
                <div class="bg-teal-600 p-4 rounded-lg shadow-lg flex justify-between items-center">
                    <div class="flex space-x-4">
                        <a href="{{route('product.index')}}" class="text-black hover:text-white">Home</a>
                        <a href="{{route('data-pesanan')}}" class="text-black hover:text-white">Cek Pesanan</a>
                        <a href="{{route('profil-perusahaan')}}" class="text-black hover:text-white">Profil Perusahaan</a>
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

            <!-- Sidebar Keranjang -->
            <div id="cartSidebar" class="fixed inset-y-0 right-0 bg-gray-800 bg-opacity-50 z-40 hidden">
                <div class="w-full max-w-md bg-cyan-200 p-4 border border-black h-full overflow-y-auto">
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
                        <button id="clearCartButton" class="bg-red-500 text-white px-4 py-2">Hapus</button>
                        <button id="checkoutButton" class="bg-cyan-400 text-black px-4 py-2">Bayar</button>
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
                    <div id="totalHargaContainer" class="mb-2 bg-yellow-400 text-white px-4 py-2 rounded text-center mt-4">
                        <span class="text-lg font-semibold">Total Harga: </span>
                        <span id="totalHarga" class="text-xl font-bold">Rp0</span>
                    </div>

                    <div>
                        <label for="payment_method" class="block font-medium mb-2">Mode Pembayaran</label>
                        <select name="payment_method" class="border border-gray-300 rounded-lg p-2 w-full">
                            <option value="">-- Pilih Jenis Pembayaran --</option>
                            <option value="dp">DP</option>
                            <option value="lunas">LUNAS</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-4">
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
                    <!-- Tombol Aksi -->
                    <div class="flex justify-center mt-4 space-x-4">
                        <button type="button" class="bg-cyan-400 text-white px-4 py-2 rounded" id="payButton">Bayar</button>
                        <button type="button" class="bg-red-400 text-white px-4 py-2 rounded" id="cancelButton">Cancel</button>
                    </div>
                </div>
            </div>

            <script>
                function updateCartItems() {
                        const cart = JSON.parse(localStorage.getItem('cart')) || [];
                        const cartItemsContainer = document.getElementById('cartItems');
                        const totalAmountElement = document.getElementById("totalAmount");

                        cartItemsContainer.innerHTML = '';

                        if (cart.length === 0) {
                            cartItemsContainer.innerHTML = '<p>Keranjang kosong</p>';
                            totalAmountElement.innerText = "0";
                            return;
                        }

                        let totalAmount = 0;
                        cart.forEach((item, index) => {
                            console.log("id_pesanan:",item.id_pesanan); 
                            if (item.harga && item.kuantitas && item.id_produk) {
                                totalAmount += item.harga * item.kuantitas;

                                const cartItem = `
                                    <div class="flex items-center mb-4" id="cart-item-${index}">
                                        <input class="w-6 h-6 mr-4 checkbox-item" type="checkbox" data-order-id="${item.id_pesanan}" />
                                        <img alt="${item.nama_produk}" class="w-24 h-24 mr-4" src="${item.gambar}" />
                                        <div>
                                            <p class="text-lg font-bold">${item.nama_produk}</p>
                                            <p class="text-lg">Rp${item.harga.toLocaleString()}</p>
                                        </div>
                                        <div class="flex items-center ml-auto">
                                            <button class="w-8 h-8 border border-black" onclick="decreaseQuantity('${item.id_produk}')">-</button>
                                            <span class="w-8 h-8 flex items-center justify-center border-t border-b border-black">${item.kuantitas}</span>
                                            <button class="w-8 h-8 border border-black" onclick="increaseQuantity('${item.id_produk}')">+</button>
                                        </div>
                                    </div>
                                `;
                                cartItemsContainer.innerHTML += cartItem;
                            }
                        });
                        totalAmountElement.innerText = totalAmount.toLocaleString();
                    }
                    
                // Function to decrease product quantity
                function decreaseQuantity(productId) {
                            const cart = JSON.parse(localStorage.getItem('cart')) || [];
                            const productIndex = cart.findIndex(item => item.id_produk === productId);

                            if (productIndex !== -1 && cart[productIndex].kuantitas > 1) {
                                cart[productIndex].kuantitas--;
                                localStorage.setItem('cart', JSON.stringify(cart));
                                updateCartItems();
                            }
                        }

                    // Function to increase product quantity
                    function increaseQuantity(productId) {
                        const cart = JSON.parse(localStorage.getItem('cart')) || [];
                        const productIndex = cart.findIndex(item => item.id_produk === productId);

                        if (productIndex !== -1) {
                            cart[productIndex].kuantitas++;
                            localStorage.setItem('cart', JSON.stringify(cart));
                            updateCartItems();
                        }
                    }

                document.addEventListener("DOMContentLoaded", function() {

                    // Function to submit transactions
                    function submitAllTransactions() {
                        const checkboxes = document.querySelectorAll(".checkbox-item:checked");
                        const cart = JSON.parse(localStorage.getItem("cart")) || [];
                        console.log("Isi cart:", cart);
                        
                        // Mengambil item yang dipilih berdasarkan id_pesanan
                        const selectedItems = Array.from(checkboxes).map((checkbox) => {
                            const orderId = checkbox.dataset.orderId;
                            const item = cart.find((item) => item.id_pesanan === orderId);  // Temukan item berdasarkan id_pesanan
                            
                            // Jika item tidak ditemukan, log kesalahan
                            if (!item) {
                                console.error(`Item dengan id_pesanan ${orderId} tidak ditemukan di cart.`);
                            }
                            return item;  // Bisa undefined jika tidak ditemukan
                        }).filter(item => item !== undefined);  // Menyaring item undefined

                        if (selectedItems.length === 0) {
                            alert("Pilih item yang ingin dibayar!");
                            return;
                        }

                        const paymentMethod = document.querySelector("select[name='payment_method']").value;
                        const bankName = document.querySelector("select[name='bank_name']").value;
                        const proofOfPayment = document.querySelector("input[name='proof_of_payment']").files[0];
                        const customImage = document.querySelector("input[name='custom_image']").files[0];

                        if (!paymentMethod || !bankName || !proofOfPayment) {
                            alert("Lengkapi semua informasi pembayaran!");
                            return;
                        }

                        const formData = new FormData();
                        formData.append("payment_method", paymentMethod);
                        formData.append("bank_name", bankName);
                        formData.append("proof_of_payment", proofOfPayment);
                        formData.append("custom_image", customImage);

                        selectedItems.forEach((item, index) => {
                            if (!item.id_pesanan) {
                                console.error(`Order ID untuk produk ${item.id_produk} tidak valid!`);
                                return;  // Jika id_pesanan kosong, hentikan pengiriman
                            }

                            formData.append(`cart[${index}][product_id]`, item.id_produk);  // Menggunakan id_produk
                            formData.append(`cart[${index}][order_id]`, item.id_pesanan);  // Menggunakan id_pesanan
                            formData.append(`cart[${index}][kuantitas]`, item.kuantitas);
                            formData.append(`cart[${index}][total_harga]`, item.harga * item.kuantitas);
                            formData.append(`cart[${index}][total_pembayaran]`, item.harga * item.kuantitas);
                        });

                        // Log untuk melihat data yang akan dikirimkan
                        console.log("FormData yang dikirim:", formData);

                        fetch("/transaction/cart", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                            body: formData,
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                alert("Pembayaran berhasil!");
                                window.location.href = "{{ route('data-pesanan') }}";
                            } else {
                                alert("Gagal melakukan pembayaran.");
                            }
                        })
                        .catch((error) => console.error("Error:", error));
                    }

                    const payButton = document.getElementById("payButton");
                    if (payButton) {
                        payButton.addEventListener("click", submitAllTransactions);
                    }


                    

                    document.getElementById("clearCartButton").addEventListener("click", function() {
                        const selectedOrderIds = []; // Menyimpan id pesanan yang dipilih untuk dihapus

                        const checkboxes = document.querySelectorAll(".checkbox-item:checked");
                        checkboxes.forEach((checkbox) => {
                            const orderId = checkbox.dataset.orderId; // Ambil ID pesanan dari checkbox
                            selectedOrderIds.push(orderId); // Menambahkan id pesanan yang dipilih
                        });

                        if (selectedOrderIds.length === 0) {
                            alert("Pilih item yang ingin dihapus!");
                            return;
                        }

                        // Ambil cart dari localStorage
                        let cart = JSON.parse(localStorage.getItem("cart")) || [];

                        // Filter cart untuk menghapus item dengan id_pesanan yang dipilih
                        cart = cart.filter(item => !selectedOrderIds.includes(item.id_pesanan));

                        // Simpan kembali cart yang sudah diupdate
                        localStorage.setItem("cart", JSON.stringify(cart));

                        // Perbarui tampilan keranjang
                        updateCartItems();

                        // Kirim request ke server untuk menghapus pesanan
                        fetch('/order/multipledestroy', {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ order_ids: selectedOrderIds })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                alert(data.message);
                            } else {
                                alert('Gagal menghapus pesanan.');
                            }
                        })
                        .catch((error) => {
                            console.error('Terjadi kesalahan:', error);
                            alert('Terjadi kesalahan saat menghapus pesanan.');
                        });
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

                    document.getElementById('checkoutButton').addEventListener('click', () => {
                        const checkboxes = document.querySelectorAll(".checkbox-item:checked");
                        const cart = JSON.parse(localStorage.getItem('cart')) || [];
                        console.log("Isi cart setelah menambahkan produk:", cart);

                        const selectedItems = Array.from(checkboxes).map(checkbox => {
                            const orderId = checkbox.dataset.orderId;
                            console.log("Mencari item dengan id_pesanan:", orderId); // Verifikasi ID yang dipilih
                            const item = cart.find(item => item.id_pesanan === orderId);
                            
                            if (!item) {
                                console.error(`Item dengan id_pesanan ${orderId} tidak ditemukan di cart.`);
                            }
                            return item;  // Bisa undefined jika tidak ditemukan
                        }).filter(item => item !== undefined);  // Menyaring item undefined

                        if (selectedItems.length === 0) {
                            alert("Pilih item yang ingin dibayar!");
                            return;
                        }

                        console.log("Selected items untuk pembayaran:", selectedItems);
                        openPaymentModal(selectedItems);  // Fungsi yang membuka modal pembayaran
                    });

                    // Fungsi untuk membuka modal pembayaran
                    function openPaymentModal(selectedItems) {
                        const modalProductCards = document.getElementById("modalProductCards");
                        const paymentModal = document.getElementById("paymentModal");
                        const totalHargaElement = document.getElementById("totalHarga");

                        modalProductCards.innerHTML = "";
                        let totalHarga = 0;

                        selectedItems.forEach((item) => {
                            if (item && item.harga && item.kuantitas) {
                                const hargaProduk = item.harga * item.kuantitas;
                                totalHarga += hargaProduk;

                                const cardHTML = `
                                    <div class="bg-white p-4 border border-gray-300 rounded-lg">
                                        <h2 class="text-lg font-bold">Pesanan: ${item.nama_produk}</h2>
                                        <div class="grid grid-cols-2 gap-4 mt-4">
                                            <input type="hidden" name="order_id" value="${item.id_pesanan}">
                                            <input type="hidden" name="product_id" value="${item.id_produk}">
                                            <div>
                                                <label class="block mb-2">Nama Lengkap</label>
                                                <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" class="w-full p-2 border border-gray-300 rounded" disabled>
                                            </div>
                                            <div>
                                                <label class="block mb-2">Alamat</label>
                                                <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" class="w-full p-2 border border-gray-300 rounded" disabled>
                                            </div>
                                            <div>
                                                <label class="block mb-2">Nomor Telepon/WA</label>
                                                <input type="text" name="telepon" value="{{ Auth::user()->telepon }}" class="w-full p-2 border border-gray-300 rounded" disabled>
                                            </div>
                                            <div>
                                                <label class="block mb-2">Upload Custom Gambar</label>
                                                <input type="file" name="custom_image" accept="image/*" class="w-full p-2 border border-gray-300 rounded">
                                            </div>
                                            <div>
                                                <label class="block mb-2">Jumlah Produk</label>
                                                <input type="number" name="quantity" value="${item.kuantitas}" class="w-full p-2 border border-gray-300 rounded" disabled>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                modalProductCards.innerHTML += cardHTML;
                            } else {
                                console.error(`Item dengan id_produk ${item.id_produk} tidak memiliki harga atau kuantitas yang valid.`);
                            }
                        });

                        totalHargaElement.innerText = totalHarga;

                        // Menampilkan modal pembayaran
                        paymentModal.classList.remove("hidden");

                        // Menangani pembayaran sukses
                        const payButton = document.getElementById('payButton');
                        payButton.addEventListener('click', () => {
                            // Hapus produk yang sudah dibayar dari keranjang
                            const cart = JSON.parse(localStorage.getItem('cart')) || [];
                            const selectedOrderIds = selectedItems.map(item => item.id_pesanan);  // Ambil id_pesanan dari selectedItems

                            // Filter cart untuk menghapus item dengan id_pesanan yang dipilih
                            const remainingItems = cart.filter(item => !selectedOrderIds.includes(item.id_pesanan));
                            localStorage.setItem('cart', JSON.stringify(remainingItems));

                            alert("Pembayaran berhasil dan produk telah dihapus dari keranjang.");

                            // Tutup modal setelah pembayaran
                            paymentModal.classList.add("hidden");

                            // Perbarui tampilan keranjang
                            updateCartItems();

                            // Perbarui tampilan keranjang atau halaman lainnya
                            location.reload();  // Jika ingin me-refresh halaman setelah transaksi
                        });
                    }

                });
            </script>


        @endif
    @endguest
 

    <!-- Main Content Section -->
    <main class="w-full mx-auto">
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="w-full mx-auto flex flex-column justify-between items-center bg-gray-700 p-4 text-center mt-8"> <!-- Footer dengan w-[95%] dan mx-auto -->
        <p class="text-gray-300">&copy; 2024 DR AKTA PERCETAKAN. All rights reserved.</p>
        <div class="flex justify-center space-x-4 mt-2">
            <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
        </div>
    </footer>
</body>
</html>
