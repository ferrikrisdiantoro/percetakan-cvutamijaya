<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\DetailTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{

    public function index()
    {
        // Ambil data pesanan dan transaksi yang terkait dengan pelanggan yang sedang login
        $orders = Transaction::where('id_pelanggan', Auth::user()->id_pelanggan)
                            ->with('product', 'user')  // Pastikan relasi produk dan user dimuat
                            ->get();
    
        // Kirim data pesanan ke view
        return view('pelanggan.data-pesanan', compact('orders'));
    }

    public function store(Request $request)
    {
        $userId = Auth::user()->id_pelanggan;
        
        try {
            // Validasi request
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id_pesanan',
                'payment_method' => 'required|string',
                'bank_name' => 'required|string',
                'total_pembayaran' => 'required|numeric',
                'custom_image' => 'nullable|image|max:2048',
                'proof_of_payment' => 'nullable|image|max:2048',
            ]);
    
            $totalPembayaran = preg_replace('/[^0-9]/', '', $request->total_pembayaran);
    
            // Menyimpan gambar dokumen tambahan jika ada
            $customImagePath = $request->hasFile('custom_image') ? $request->file('custom_image')->store('images/dokumen_tambahan', 'public') : null;
            
            // Menyimpan bukti pembayaran jika ada
            $proofOfPaymentPath = $request->hasFile('proof_of_payment') ? $request->file('proof_of_payment')->store('images/bukti_pembayaran', 'public') : null;
    
            // Ambil ID transaksi terakhir dan buat ID baru
            $lastOrder = Transaction::orderBy('id_transaksi', 'desc')->first();
            $lastNumber = $lastOrder ? (int)substr($lastOrder->id_transaksi, 2) : 0;
            $transactionId = 'TR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            // Membuat transaksi baru
            $transaction = new Transaction();
            $transaction->id_transaksi = $transactionId;  // Gunakan ID yang sudah di-generate
            $transaction->id_pelanggan = $userId;
            $transaction->id_pesanan = $request->order_id;
            $transaction->mode_pembayaran = $request->payment_method;
            $transaction->transfer = $request->bank_name;
            $transaction->total_pembayaran = $totalPembayaran;
            $transaction->bukti_pembayaran = $proofOfPaymentPath;
            $transaction->status = 'pending';
            $transaction->tanggal_transaksi = now();
            $transaction->save();

            // Menyimpan data pada tabel DetailTransaction (transaction_orders)
            $transactionOrder = new DetailTransaction();
            $transactionOrder->id_transaksi = $transaction->id_transaksi;  // Menggunakan id_transaksi yang sudah ada
            $transactionOrder->id_pesanan = $request->order_id;
            $transactionOrder->dokumen_tambahan = $customImagePath;
            $transactionOrder->save();
            

            return response()->json(['message' => 'Transaksi berhasil'], 200);
        } catch (\Throwable $e) {
            Log::error('Error during transaction store: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan pada server'], 500);
        }
    }
    
    public function updateStatus(Request $request)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|array',
            'mode_pembayaran' => 'required|array',
        ]);
    
        // Perbarui status dan mode pembayaran untuk setiap transaksi
        foreach ($request->status as $transactionId => $status) {
            $transaction = Transaction::where('id_transaksi', $transactionId)->first();
            if ($transaction) {
                // Update status pemesanan
                $transaction->status = $status;
                
                // Update mode pembayaran
                if (isset($request->mode_pembayaran[$transactionId])) {
                    $transaction->mode_pembayaran = $request->mode_pembayaran[$transactionId];
                }
    
                $transaction->save();
            }
        }
    
        return redirect()->back()->with('success', 'Status dan mode pembayaran berhasil diperbarui.');
    }
    

    public function search(Request $request)
    {
        $query = DB::table('transactions')
            ->join('orders', 'transactions.id_pesanan', '=', 'orders.id_pesanan')
            ->select(
                'transactions.*',
                'orders.kuantitas',
                DB::raw('DATE(transactions.created_at) as tanggal_transaksi')
            );

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$request->start_date, $request->end_date]);
        }

        if ($request->filled('status')) {
            $query->where('transactions.status', $request->status);
        }

        if ($request->filled('transaction_id')) {
            $query->where('transactions.id_transaksi', 'like', "%{$request->transaction_id}%");
        }

        $transactions = $query->get();

        return view('admin.laporan', compact('transactions'));
    }

    public function cart(Request $request)
    {
        Log::info('Order ID diterima:', ['order_id' => $request->order_id]);
        $userId = Auth::user()->id_pelanggan;
        Log::info('Request data:', ['request_data' => $request->all()]);
    
        try {
            Log::info('Memulai validasi transaksi', $request->all());
    
            $validated = $request->validate([
                'payment_method' => 'required|string',
                'bank_name' => 'required|string',
                'proof_of_payment' => 'nullable|image|max:2048',
                'custom_image' => 'nullable|image|max:2048',
                'cart' => 'required|array',
                'cart.*.product_id' => 'required|exists:products,id_produk',
                'cart.*.order_id' => 'required|exists:orders,id_pesanan',
                'cart.*.total_pembayaran' => 'required|numeric',
            ]);
    
            // Upload gambar (jika ada)
            $proofOfPaymentPath = $request->hasFile('proof_of_payment') 
                ? $request->file('proof_of_payment')->store('images/bukti_pembayaran', 'public') 
                : null;
    
            $customImagePath = $request->hasFile('custom_image') 
                ? $request->file('custom_image')->store('images/dokumen_tambahan', 'public') 
                : null;
    
            // Buat transaksi utama (ID otomatis di-generate oleh model)
            $lastOrder = Transaction::orderBy('id_transaksi', 'desc')->first();
            $lastNumber = $lastOrder ? (int)substr($lastOrder->id_transaksi, 2) : 0;
            $transactionId = 'TR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            
            $transaction = new Transaction();
            $transaction->id_transaksi = $transactionId;  // Set ID transaksi yang di-generate
            $transaction->id_pelanggan = $userId;
            $transaction->id_pesanan = $request->cart[0]['order_id'];
            $transaction->mode_pembayaran = $request->payment_method;
            $transaction->transfer = $request->bank_name;
            $transaction->total_pembayaran = collect($request->cart)->sum('total_pembayaran');
            $transaction->bukti_pembayaran = $proofOfPaymentPath ?? null;
            $transaction->status = 'pending';
            $transaction->tanggal_transaksi = now();
            $transaction->save();
    
            Log::info('Transaksi utama berhasil disimpan', ['id_transaksi' => $transaction->id_transaksi]);
    
            // Buat detail transaksi untuk setiap item dalam cart
            foreach ($request->cart as $item) {
                $detailTransaction = new DetailTransaction();
                $detailTransaction->id_transaksi = $transaction->id_transaksi;  // Set ID transaksi yang sama
                $detailTransaction->id_pesanan = $item['order_id'];
                $detailTransaction->dokumen_tambahan = $customImagePath;
                $detailTransaction->save();
    
                Log::info('Detail transaksi berhasil disimpan', ['id_pesanan' => $item['order_id'], 'id_transaksi' => $transaction->id_transaksi]);
            }
    
            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil'], 200);
        } catch (\Throwable $e) {
            Log::error('Error while processing transaction', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan pada server'], 500);
        }
    }
    
    

    


}


