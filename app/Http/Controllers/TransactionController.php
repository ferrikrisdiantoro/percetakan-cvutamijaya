<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Order;
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
        $orders = Transaction::where('id_user', Auth::user()->id)
                            ->with('product', 'user')  // Pastikan relasi produk dan user dimuat
                            ->get();
    
        // Kirim data pesanan ke view
        return view('pelanggan.data-pesanan', compact('orders'));
    }

    public function store(Request $request)
    {
        Log::info('Store Transaction Called with data:', $request->all());
        try {
            // Validation
            $validated = $request->validate([
                'order_id' => 'required',
                'total_pembayaran' => 'required|numeric',
                'custom_image' => 'nullable|image|max:2048',
            ]);
    
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 401);
            }
    
            // Convert amount to integer (Midtrans requires amount in lowest denomination)
            $totalPembayaran = (int) $request->total_pembayaran;
            
            // Generate unique Transaction ID
            $transactionId = 'TR' . time() . rand(100, 999);
    
            $order = Order::with('product')
                ->where('id', $request->order_id)
                ->first();
    
            if (!$order) {
                Log::error('Order not found:', ['order_id' => $request->order_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Order tidak ditemukan.'
                ], 404);
            }
    
            // Prepare Midtrans request payload
            $payload = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => $totalPembayaran
                ],
                'credit_card' => [
                    'secure' => true
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->telepon,
                ],
                'item_details' => [
                    [
                        'id' => $order->product->id,
                        'price' => $totalPembayaran,
                        'quantity' => 1,
                        'name' => substr($order->product->nama_produk, 0, 50)
                    ]
                ],
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                    'mandiri_clickpay', 'gopay', 'shopeepay'
                ]
            ];
    
            Log::info('Midtrans payload:', $payload);
    
            try {
                // Create HTTP client
                $client = new \GuzzleHttp\Client();
    
                // Make request to Midtrans Snap API
                $response = $client->post('https://app.sandbox.midtrans.com/snap/v1/transactions', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':')
                    ],
                    'json' => $payload,
                    'verify' => false // Only for development
                ]);
    
                $result = json_decode($response->getBody()->getContents(), true);
                
                if (!isset($result['token'])) {
                    throw new \Exception('Invalid response from Midtrans');
                }
    
                $snapToken = $result['token'];
                Log::info('Snap Token generated:', ['token' => $snapToken]);
    
                DB::beginTransaction();
                
                $transaction = Transaction::create([
                    'id' => $transactionId,
                    'id_user' => $user->id,
                    'id_pesanan' => $request->order_id,
                    'total_pembayaran' => $totalPembayaran,
                    'snap_token' => $snapToken,
                    'status' => 'pending',
                    'tanggal_transaksi' => now()
                ]);
    
                if ($request->hasFile('custom_image')) {
                    $customImagePath = $request->file('custom_image')
                        ->store('images/dokumen_tambahan', 'public');
                }
    
                DetailTransaction::create([
                    'id_transaksi' => $transaction->id,
                    'id_pesanan' => $request->order_id,
                    'dokumen_tambahan' => $customImagePath ?? null
                ]);
    
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'transaction_id' => $transactionId
                ]);
    
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                DB::rollBack();
                $errorResponse = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;
                Log::error('Midtrans API Error:', [
                    'error' => $errorResponse ?? $e->getMessage(),
                    'payload' => $payload
                ]);
                throw new \Exception('Gagal membuat Snap Token: ' . ($errorResponse['error_messages'][0] ?? $e->getMessage()));
            }
    
        } catch (\Exception $e) {
            Log::error('General Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function updateStatus(Request $request)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|array',
        ]);
    
        // Perbarui status dan mode pembayaran untuk setiap transaksi
        foreach ($request->status as $transactionId => $status) {
            $transaction = Transaction::where('id', $transactionId)->first();
            if ($transaction) {
                // Update status pemesanan
                $transaction->status = $status;
                $transaction->save();
            }
        }
    
        return redirect()->back()->with('success', 'Status dan mode pembayaran berhasil diperbarui.');
    }
    

    public function search(Request $request)
    {
        $query = Transaction::with(['detailTransactions', 'detailTransactions.order']) // Memuat relasi
            ->join('orders', 'transactions.id_pesanan', '=', 'orders.id')
            ->select(
                'transactions.*',
                'orders.kuantitas',
                DB::raw('DATE(transactions.created_at) as tanggal_transaksi')
            );

        // Filter berdasarkan rentang tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(transactions.created_at)'), [$request->start_date, $request->end_date]);
        }

        // Filter berdasarkan status transaksi jika ada
        if ($request->filled('status')) {
            $query->where('transactions.status', $request->status);
        }

        // Filter berdasarkan ID Transaksi jika ada
        if ($request->filled('transaction_id')) {
            $query->where('transactions.id', 'like', "%" . $request->transaction_id . "%");
        }

        // Ambil data transaksi yang sudah difilter
        $transactions = $query->get();

        return view('admin.laporan', compact('transactions'));
    }

    public function cart(Request $request)
    {
        $userId = Auth::user()->id;
        $user = Auth::user();
        Log::info('Request data:', ['request_data' => $request->all()]);
    
        try {
            // Modified validation to match the incoming data structure
            $validated = $request->validate([
                'custom_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
                'cart' => 'required|array',
                'cart.*.user_id' => 'required|string',
                'cart.*.product_id' => 'required|string',
                'cart.*.order_id' => 'required|string',
                'cart.*.kuantitas' => 'required|integer|min:1',
                'cart.*.total_pembayaran' => 'required|numeric|min:0'
            ]);
    
            // Handle file upload
            try {
                $customImagePath = null;
                if ($request->hasFile('custom_image')) {
                    $file = $request->file('custom_image');
                    if ($file->isValid()) {
                        $customImagePath = $file->store('images/dokumen_tambahan', 'public');
                    }
                }
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengunggah file: ' . $e->getMessage()
                ], 422);
            }
    
            // Generate transaction ID
            $lastOrder = Transaction::orderBy('id', 'desc')->first();
            $lastNumber = $lastOrder ? (int)substr($lastOrder->id, 2) : 0;
            $transactionId = 'TR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    
            // Calculate total payment - updating this part
            $totalPembayaran = collect($request->cart)->sum(function($item) {
                return $item['total_pembayaran'] * $item['kuantitas'];
            });
            
    
            // Prepare items for Midtrans - updating this part
            $itemDetails = [];
            foreach ($request->cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $item['product_id']);
                }
                
                $itemDetails[] = [
                    'id' => $product->id,
                    'price' => (int)$item['total_pembayaran'],
                    'quantity' => (int)$item['kuantitas'],  // Convert to integer
                    'name' => substr($product->nama_produk, 0, 50)
                ];
            }
    
            // Prepare Midtrans payload
            $payload = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int)$totalPembayaran
                ],
                'credit_card' => [
                    'secure' => true
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->telepon,
                ],
                'item_details' => $itemDetails,
                'enabled_payments' => [
                    'credit_card', 'bca_va', 'bni_va', 'bri_va', 
                    'mandiri_clickpay', 'gopay', 'shopeepay'
                ],
                'callbacks' => [
                    'finish' => url('/product'),
                    'error' => url('/cart'),
                    'pending' => url('/product')
                ]
            ];
    
            // Verify total matches sum of items
            $itemsTotal = collect($itemDetails)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });

            if ($itemsTotal !== (int)$totalPembayaran) {
                throw new \Exception('Total pembayaran tidak sesuai dengan total item');
            }

            Log::info('Midtrans payload:', $payload);
    
            try {
                // Create HTTP client
                $client = new \GuzzleHttp\Client();
    
                // Make request to Midtrans Snap API
                $response = $client->post('https://app.sandbox.midtrans.com/snap/v1/transactions', [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Basic ' . base64_encode(config('midtrans.server_key') . ':')
                    ],
                    'json' => $payload,
                    'verify' => false // Only for development
                ]);
    
                $result = json_decode($response->getBody()->getContents(), true);
                
                if (!isset($result['token'])) {
                    throw new \Exception('Invalid response from Midtrans');
                }
    
                $snapToken = $result['token'];
                Log::info('Snap Token generated:', ['token' => $snapToken]);
    
                // Start database transaction
                DB::beginTransaction();
    
                // Create main transaction
                $transaction = new Transaction();
                $transaction->id = $transactionId;
                $transaction->id_user = $userId;
                $transaction->id_pesanan = $request->cart[0]['order_id'];
                $transaction->total_pembayaran = $totalPembayaran;
                $transaction->snap_token = $snapToken;
                $transaction->status = 'pending';
                $transaction->tanggal_transaksi = now();
                $transaction->save();
    
                // Create transaction details
                foreach ($request->cart as $item) {
                    $detailTransaction = new DetailTransaction();
                    $detailTransaction->id_transaksi = $transaction->id;
                    $detailTransaction->id_pesanan = $item['order_id'];
                    $detailTransaction->dokumen_tambahan = $customImagePath;
                    $detailTransaction->save();
                }
    
                DB::commit();
    
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'transaction_id' => $transactionId
                ]);
    
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                DB::rollBack();
                $errorResponse = $e->hasResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;
                Log::error('Midtrans API Error:', [
                    'error' => $errorResponse ?? $e->getMessage(),
                    'payload' => $payload
                ]);
                throw new \Exception('Gagal membuat Snap Token: ' . ($errorResponse['error_messages'][0] ?? $e->getMessage()));
            }
    
        } catch (\Exception $e) {
            Log::error('General Error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printReport(Request $request)
    {
        $query = Transaction::with('detailTransactions.order') // Memuat relasi detailTransactions dan order
            ->join('orders', 'transactions.id_pesanan', '=', 'orders.id')
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
            $query->where('transactions.id', 'like', "%{$request->transaction_id}%");
        }

        $transactions = $query->get();

        // Mengirim data transaksi ke view cetak
        return view('admin.transaction-print', compact('transactions'));
    }


    
    

    


}


