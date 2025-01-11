<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id_produk',
                'kuantitas' => 'required|integer|min:1',
                'total_pembayaran' => 'required|numeric|min:1',
            ]);
    
            $product = Product::findOrFail($request->product_id);
    
            if ($product->stok < $request->kuantitas) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi untuk pesanan ini.'
                ], 422);
            }
    
            DB::beginTransaction();
    
            $order = new Order();
            $order->id_produk = $request->product_id;
            $order->id_user = Auth::id();
            $order->kuantitas = $request->kuantitas;
            $order->total_pembayaran = $request->total_pembayaran;
    
            $order->save();
    
            $product->stok -= $request->kuantitas;
            $product->save();
    
            DB::commit();
    
            return response()->json(['order_id' => $order->id_pesanan], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat membuat pesanan:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.',
            ], 500);
        }
    }
    
    
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id_produk',
            'kuantitas' => 'required|integer|min:1',
            'total_pembayaran' => 'required|numeric',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['id_produk'],
            'kuantitas' => $validated['kuantitas'],
            'total_pembayaran' => $validated['total_pembayaran'], // atau status sesuai kebutuhan
        ]);

        if ($order) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menambahkan ke keranjang'], 500);
        }
    }

    public function destroy(Request $request, $id_pesanan)
    {
        try {
            $order = Order::findOrFail($id_pesanan);

            // Change id_pelanggan to id_user to match your database structure
            if ($order->id_user !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus pesanan ini'
                ], 403);
            }

            DB::beginTransaction();
            try {
                // Return stock to product
                $product = Product::findOrFail($order->id_produk);
                $product->stok += $order->kuantitas;
                $product->save();

                // Delete the order
                $order->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dihapus'
                ], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function multipledestroy(Request $request)
    {
        try {
            // Validasi input, pastikan order_ids ada dan berupa array
            $validated = $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id_pesanan'
            ]);
    
            // Ambil semua pesanan yang dimiliki oleh user dan sesuai dengan order_ids
            $orders = Order::whereIn('id_pesanan', $validated['order_ids'])
                ->where('id_user', Auth::id()) // Filter hanya pesanan milik user yang sedang login
                ->get();
    
            if ($orders->isEmpty()) {
                return response()->json(['message' => 'Pesanan tidak ditemukan atau tidak dimiliki oleh Anda'], 403);
            }
    
            // Hapus pesanan dan kembalikan stok produk
            foreach ($orders as $order) {
                $product = Product::findOrFail($order->id_produk);
                $product->stok += $order->kuantitas;
                $product->save();
                $order->delete();
            }
    
            return response()->json(['message' => 'Pesanan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            \Log::error('Error in multipledestroy:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    } 
}

