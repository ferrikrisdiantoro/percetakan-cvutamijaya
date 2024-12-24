<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction; // Pastikan Anda sudah memiliki model Transaction

class DataPemesananAdminController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data transaksi
        $transactions = Transaction::all(); // Jika ada relasi product/customer
        $perPage = $request->input('perPage', 10); // Default show 10 entries
        $search = $request->input('search');
    
        $transactions = Transaction::with(['product', 'order'])
            ->when($search, function ($query, $search) {
                return $query->where('id_transaksi', 'like', "%$search%")
                             ->orWhere('id_pelanggan', 'like', "%$search%")
                             ->orWhereHas('product', function ($q) use ($search) {
                                 $q->where('id_produk', 'like', "%$search%");
                             });
            })
            ->paginate($perPage);
        return view('admin.data-pemesanan', compact('transactions')); // Kirim data ke view
    }
}
