<?php

use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DataPesananController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DataPemesananAdminController;
use App\Http\Controllers\HomeAdminController;

// Route utama
Route::get('/', function () {
    return auth()->check() ? redirect()->route('product.index') : redirect()->route('product.index');
})->name('home');

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
});

Route::post('/create-transaction', [OrderController::class, 'createTransaction'])->name('create.transaction');

// Route untuk Admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/data-pemesanan', [DataPemesananAdminController::class, 'index'])->name('data-pemesanan');
    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/admin/home', [HomeAdminController::class, 'index'])->name('HomeAdmin');

    Route::post('/transaction/update-status', [TransactionController::class, 'updateStatus'])->name('transaction.update-status');
    Route::get('/transaction/search', [TransactionController::class, 'search'])->name('transaction.search');

    //CRUD PRODUK
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::get('/product/edit/{id_produk}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::put('/product/update/{id_produk}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/destroy/{id_produk}', [ProductController::class, 'destroy'])->name('product.destroy');
});



