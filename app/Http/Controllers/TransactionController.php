<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id_produk',
                'order_id' => 'required|exists:orders,id_pesanan',
                'payment_method' => 'required|string',
                'bank_name' => 'required|string',
                'total_pembayaran' => 'required|numeric',
                'custom_image' => 'nullable|image|max:2048',
                'proof_of_payment' => 'nullable|image|max:2048',
            ]);
        
            $totalPembayaran = preg_replace('/[^0-9]/', '', $request->total_pembayaran);
        
            if ($request->hasFile('custom_image')) {
                $customImagePath = $request->file('custom_image')->store('images/dokumen_tambahan', 'public');
            }
            
            if ($request->hasFile('proof_of_payment')) {
                $proofOfPaymentPath = $request->file('proof_of_payment')->store('images/bukti_pembayaran', 'public');
                $proofOfPaymentPath = $request->file('proof_of_payment')->store('images/bukti_pembayaran', 'public');
            }
            
            Log::info('Custom Image:', ['exists' => $request->hasFile('custom_image')]);
            Log::info('Proof of Payment:', ['exists' => $request->hasFile('proof_of_payment')]);

        
            $transaction = new Transaction();
            $transaction->id_transaksi = 'TR' . strtoupper(Str::random(6));
            $transaction->id_pelanggan = $userId;
            $transaction->id_produk = $request->product_id;
            $transaction->id_pesanan = $request->order_id;
            $transaction->mode_pembayaran = $request->payment_method;
            $transaction->transfer = $request->bank_name;
            $transaction->total_pembayaran = $totalPembayaran;
            $transaction->dokumen_tambahan = $customImagePath ?? null;
            $transaction->bukti_pembayaran = $proofOfPaymentPath ?? null;
            $transaction->status = 'pending';
            $transaction->tanggal_transaksi = now();
            $transaction->save();

            return response()->json(['message' => 'Transaksi berhasil'], 200);
            } catch (\Throwable $e) {
        return response()->json(['error' => 'Terjadi kesalahan pada server'], 500);
        }
    }
    public function updateStatus(Request $request)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|array',
        ]);

        // Perbarui status untuk setiap transaksi
        foreach ($request->status as $transactionId => $status) {
            $transaction = Transaction::where('id_transaksi', $transactionId)->first();
            if ($transaction) {
                $transaction->status = $status;
                $transaction->save();
            }
        }

        return redirect()->back()->with('success', 'Status pemesanan berhasil diperbarui.');
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

            Log::info('Validasi berhasil, memproses transaksi.');
            
            if ($request->hasFile('proof_of_payment')) {
                Log::info('File uploaded:', ['file' => $request->file('proof_of_payment')->getClientOriginalName()]);
                $proofOfPaymentPath = $request->file('proof_of_payment')->store('images/bukti_pembayaran', 'public');
            }

            if ($request->hasFile('custom_image')) {
                $customImagePath = $request->file('custom_image')->store('images/dokumen_tambahan', 'public');
                Log::info('Custom Image path:', ['path' => $customImagePath]);
            }            
            

            // Proses setiap produk dalam cart
            foreach ($request->cart as $item) {
                $transaction = new Transaction();
                $transaction->id_transaksi = 'TR' . strtoupper(Str::random(6));
                $transaction->id_pelanggan = $userId;
                $transaction->id_produk = $item['product_id'];
                $transaction->id_pesanan = $item['order_id'];
                $transaction->mode_pembayaran = $request->payment_method;
                $transaction->transfer = $request->bank_name;
                $transaction->total_pembayaran = $item['total_pembayaran'];
                $transaction->dokumen_tambahan = $customImagePath ?? null;
                $transaction->bukti_pembayaran = $proofOfPaymentPath ?? null;
                $transaction->status = 'pending';
                $transaction->tanggal_transaksi = now();
                $transaction->save();
            }

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil'], 200);
        } catch (\Throwable $e) {
            Log::error('Error while processing transaction', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan pada server'], 500);
        }
    }


}


