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
        $transactions = Transaction::with('detailTransactions.order.product')
            ->where('id_user', Auth::id())
            ->get();

        return view('pelanggan.data-pesanan', compact('transactions'));
    }

}


