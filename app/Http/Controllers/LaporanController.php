<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $transactions = Transaction::all();
        return view('admin.laporan',compact('transactions')); // pastikan ada file 'company/profile.blade.php'
    }
}
