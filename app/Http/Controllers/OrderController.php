<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id_produk',
                'kuantitas' => 'required|integer|min:1',
                'total_pembayaran' => 'required|numeric',
            ]);
    
            $product = Product::findOrFail($request->product_id);
            if ($product->stok < $request->kuantitas) {
                return response()->json(['message' => 'Stok tidak mencukupi'], 422);
            }
    
            $order = new Order();
            $order->id_produk = $request->product_id;
            $order->id_pelanggan = Auth::id();
            $order->kuantitas = $request->kuantitas;
            $order->total_pembayaran = $request->total_pembayaran;
    
            if ($order->save()) {
                $product->stok -= $request->kuantitas;
                $product->save();
                return response()->json(['order_id' => $order->id_pesanan], 201);
            }
    
            return response()->json(['message' => 'Gagal menyimpan pesanan'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
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
    
            if ($order->id_pelanggan !== Auth::id()) {
                return response()->json(['message' => 'Akses ditolak'], 403);
            }
    
            $product = Product::findOrFail($order->id_produk);
            $product->stok += $order->kuantitas;
            $product->save();
    
            $order->delete();
    
            return response()->json(['message' => 'Pesanan berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    public function multipledestroy(Request $request)
    {
        try {
            // Log request untuk memeriksa data yang diterima
            \Log::info('Received order_ids:', $request->input('order_ids'));
    
            // Validasi input, pastikan order_ids ada dan berupa array
            $validated = $request->validate([
                'order_ids' => 'required|array',
                'order_ids.*' => 'exists:orders,id_pesanan'
            ]);
    
            // Ambil semua pesanan yang memiliki id_pesanan yang sesuai
            $orders = Order::whereIn('id_pesanan', $validated['order_ids'])->get();
    
            // Pastikan pesanan hanya dapat dihapus oleh pemiliknya
            foreach ($orders as $order) {
                if ($order->id_pelanggan !== Auth::id()) {
                    return response()->json(['message' => 'Akses ditolak untuk pesanan dengan ID ' . $order->id_pesanan], 403);
                }
            }
    
            // Hapus pesanan dan update stok produk
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

