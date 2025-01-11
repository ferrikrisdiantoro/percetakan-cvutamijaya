<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class CartController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari frontend
        $validated = $request->validate([
            'id_produk' => 'required|string|exists:products,id_produk',
            'kuantitas' => 'required|integer|min:1',
            'subtotal' => 'required|numeric|min:0',
        ]);

        // Ambil ID user dari sesi
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['error' => 'User tidak ditemukan'], 401);
        }

        // Periksa apakah produk sudah ada di keranjang
        $existingCart = Cart::where('id_user', $userId)
                            ->where('id_produk', $validated['id_produk'])
                            ->first();

        if ($existingCart) {
            // Jika produk sudah ada, tambahkan kuantitas
            $existingCart->kuantitas += $validated['kuantitas'];
            $existingCart->subtotal = $existingCart->kuantitas * $existingCart->product->harga; // Total baru
            $existingCart->save();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui di keranjang',
                'cart' => $existingCart,
            ]);
        } else {
            // Jika belum ada, buat entri baru
            $cart = Cart::create([
                'id_user' => $userId,
                'id_produk' => $validated['id_produk'],
                'kuantitas' => $validated['kuantitas'],
                'subtotal' => $validated['subtotal'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart' => $cart,
            ]);
        }
    }


    public function getCartByUser()
{
    try {
        $userId = Auth::id();
        if (!Auth::id()) {
            return response()->json(['message' => 'User belum login.'], 401);
        }

        $cartItems = Cart::with(['product' => function($query) {
            $query->select('id_produk', 'nama_produk', 'harga', 'gambar');
        }])->where('id_user', $userId)->get();

        // Transform the data with correct image paths
        $transformedItems = $cartItems->map(function ($item) {
            if (!$item->product) {
                return null;
            }

            // Remove any duplicate 'images/' in the path
            $imagePath = $item->product->gambar;
            if (strpos($imagePath, 'images/') === 0) {
                $imagePath = $item->product->gambar;
            } else {
                $imagePath = 'images/' . $item->product->gambar;
            }

            // Check if file exists
            if (!file_exists(public_path($imagePath))) {
                $imagePath = 'images/placeholder.png'; // Change to your actual placeholder image
            }

            return [
                'id_cart' => $item->id_cart,
                'id_user' => $item->id_user,
                'id_produk' => $item->id_produk,
                'kuantitas' => $item->kuantitas,
                'product' => [
                    'id_produk' => $item->product->id_produk,
                    'nama_produk' => $item->product->nama_produk,
                    'harga' => $item->product->harga,
                    'gambar' => asset($imagePath)
                ]
            ];
        })->filter();

        return response()->json($transformedItems);

    } catch (\Exception $e) {
        \Log::error('Error pada getCartByUser:', ['error' => $e->getMessage()]);
        return response()->json([
            'message' => 'Terjadi kesalahan saat memuat keranjang.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function checkout(Request $request)
    {
        try {
            // Ambil data keranjang pengguna
            $cartItems = DB::table('carts')
                ->where('id_user', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Keranjang kosong!'], 422);
            }

            $totalAmount = 0;
            $orderData = [];

            // Menghitung total dan menyiapkan data untuk order
            foreach ($cartItems as $item) {
                $product = Product::findOrFail($item->id_produk);
                $subtotal = $item->kuantitas * $product->harga; // Menghitung subtotal per produk
                $totalAmount += $subtotal;

                // Menyimpan data pesanan yang akan dimasukkan ke tabel orders
                $orderData[] = [
                    'id_produk' => $item->id_produk,
                    'id_user' => Auth::id(),
                    'kuantitas' => $item->kuantitas,
                    'total_pembayaran' => $subtotal,
                ];
            }

            DB::beginTransaction();

            // Buat data order baru di tabel orders
            foreach ($orderData as $data) {
                Order::create([
                    'id_produk' => $data['id_produk'],
                    'id_user' => $data['id_user'],
                    'kuantitas' => $data['kuantitas'],
                    'total_pembayaran' => $data['total_pembayaran'],
                ]);
            }

            // Hapus data dari tabel keranjang setelah checkout
            DB::table('carts')->where('id_user', Auth::id())->delete();

            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'total_pembayaran' => $totalAmount,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saat checkout:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan, coba lagi.'], 500);
        }
    }

    public function destroy($cartId)
    {
        // Cari item keranjang berdasarkan ID
        $cartItem = Cart::find($cartId);

        if ($cartItem) {
            $cartItem->delete();  // Menghapus item dari keranjang
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan'], 404);
        }
    }

    public function update(Request $request, $cartId)
    {
        try {
            $cart = Cart::findOrFail($cartId);
            
            // Validate the request
            $validated = $request->validate([
                'kuantitas' => 'required|integer|min:1'
            ]);

            // Update the cart quantity
            $cart->kuantitas = $validated['kuantitas'];
            
            // Recalculate subtotal
            $cart->subtotal = $cart->kuantitas * $cart->product->harga;
            
            $cart->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Kuantitas berhasil diupdate',
                'data' => $cart->load('product')
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error updating cart:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate kuantitas'
            ], 500);
        }
    }

}

