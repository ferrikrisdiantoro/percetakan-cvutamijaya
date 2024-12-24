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
        $orders = Transaction::with('detailTransactions.order.product')
        ->where('id_pelanggan', Auth::id())
        ->get();

        return view('pelanggan.data-pesanan', compact('orders'));
    }
}


