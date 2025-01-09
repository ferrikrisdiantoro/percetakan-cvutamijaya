<?php

use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DataPemesananAdminController;
use App\Http\Controllers\HomeAdminController;

// Route utama
Route::get('/', function () {
    return auth()->check() ? redirect()->route('pelanggan.home') : redirect()->route('pelanggan.home');
})->name('home');

Route::get('/pelanggan/home', [HomeController::class, 'index'])->name('pelanggan.home');
// Route untuk login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Route untuk Produk
Route::get('/product', [ProductController::class, 'index'])->name('product.index');

Route::post('/transaksi/store', [TransactionController::class, 'store'])->name('transaksi.store');

// Route untuk Produk
Route::get('/profil-perusahaan', [CompanyProfileController::class, 'index'])->name('profil-perusahaan');


Route::middleware(['auth'])->group(function () {
    // Menggunakan route dengan parameter id_produk
    Route::get('/product/buy/{id_produk}', [ProductController::class, 'buy'])->name('product.buy');
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::post('/transaction/cart', [TransactionController::class, 'cart'])->name('transaction.cart');
    Route::post('/add-to-cart', [OrderController::class, 'addToCart']);
    Route::delete('/order/destroy/{id_pesanan}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::delete('/order/multipledestroy', [OrderController::class, 'multipledestroy'])->name('order.multipledestroy');
    Route::get('/data-pesanan', [TransactionController::class, 'index'])->name('data-pesanan');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profile'); // Halaman profil
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('edit.profile'); // Halaman edit profil
    Route::post('/profil/update', [ProfilController::class, 'update'])->name('update.profile'); // Simpan perubahan profil
    Route::post('/cart/store', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart/user', [CartController::class, 'getCartByUser']);
    Route::put('/cart/{cart}/increase', [CartController::class, 'increaseQuantity']);
    Route::put('/cart/{cart}/decrease', [CartController::class, 'decreaseQuantity']);
    Route::delete('/cart/{cartId}', [CartController::class, 'destroy']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);
    Route::put('/cart/update/{cartId}', [CartController::class, 'update']);
});

Route::post('/create-transaction', [OrderController::class, 'createTransaction'])->name('create.transaction');

// Route untuk Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/data-pemesanan', [DataPemesananAdminController::class, 'index'])->name('data-pemesanan');
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/print', [TransactionController::class, 'printReport'])->name('print');
    Route::get('/admin/home', [HomeAdminController::class, 'index'])->name('HomeAdmin');
    Route::get('/users', [UserController::class, 'index'])->name('manajemen-user');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('user-edit');
    Route::put('/users/{id}/update', [UserController::class, 'update'])->name('user-update');
    Route::delete('/users/{id}/destroy', [UserController::class, 'destroy'])->name('user-destroy');
    Route::get('/beranda/edit', [BerandaController::class, 'edit'])->name('beranda-edit');
    Route::put('/beranda/update', [BerandaController::class, 'update'])->name('beranda-update');
    Route::get('/profil-perusahaan/edit', [CompanyProfileController::class, 'edit'])->name('profil-perusahaan-edit');
    Route::put('/profil-perusahaan/update', [CompanyProfileController::class, 'update'])->name('profil-perusahaan-update');

    Route::post('/transaction/update-status', [TransactionController::class, 'updateStatus'])->name('transaction.update-status');
    Route::get('/transaction/search', [TransactionController::class, 'search'])->name('transaction.search');

    //CRUD PRODUK
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::get('/product/edit/{id_produk}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::put('/product/update/{id_produk}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/destroy/{id_produk}', [ProductController::class, 'destroy'])->name('product.destroy');
});



