<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataPesananController extends Controller
{
    // Menampilkan semua data pesanan
    public function index()
    {
        // Mengambil semua transaksi berdasarkan pelanggan yang login
        $orders = Transaction::where('id_pelanggan', Auth::id())->get();
        dd($orders);

        return view('pelanggan.data-pesanan', compact('orders'));
    }
}


