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

    
}

