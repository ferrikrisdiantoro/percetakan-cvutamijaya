<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Pastikan Anda sudah memiliki model Transaction

class DataPemesananAdminController extends Controller
{
    public function index()
    {
        // Mengambil data transaksi
        $transactions = Transaction::all(); // Jika ada relasi product/customer
        return view('admin.data-pemesanan', compact('transactions')); // Kirim data ke view
    }
}
