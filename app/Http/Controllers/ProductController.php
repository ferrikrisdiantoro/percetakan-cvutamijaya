<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
Use App\Models\Order;
Use App\Models\Transaction;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil semua data produk dari database
        $products = Product::with('orders')->get();
        return view('product.index', compact('products'));
    }

    public function create()
    {
    return view('product.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'bahan' => 'required',
            'ukuran' => 'required',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|max:2048',
        ]);
    
        // Create a new product instance with the input data (except gambar)
        $product = new Product($request->except('gambar'));
    
        if ($request->hasFile('gambar')) {
            // Get the uploaded image file
            $image = $request->file('gambar');
            // Generate a unique image name
            $imageName = time() . '-' . $image->getClientOriginalName();
            // Move the image to the public/images folder
            $image->move(public_path('images'), $imageName);
            // Save the image path in the database
            $product->gambar = 'images/' . $imageName;
        }
    
        // Save the product to the database
        $product->save();
    
        return redirect()->route('product.index')->with('success', 'Product added successfully.');
    }
    

    public function edit($id_produk)
    {
        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id_produk);

        // Kirim data produk ke view
        return view('product.edit', compact('product'));
    }

    public function update(Request $request, $id_produk)
    {
        // Validasi data yang diterima dari form
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'bahan' => 'required|string',
            'ukuran' => 'required|string',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id_produk);

        // Update data produk
        $product->nama_produk = $validated['nama_produk'];
        $product->deskripsi = $validated['deskripsi'];
        $product->harga = $validated['harga'];
        $product->bahan = $validated['bahan'];
        $product->ukuran = $validated['ukuran'];
        $product->stok = $validated['stok'];

        // Jika ada gambar baru, upload dan simpan
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama (opsional, jika kamu ingin mengganti gambar lama)
            if ($product->gambar && file_exists(public_path('storage/' . $product->gambar))) {
                unlink(public_path('storage/' . $product->gambar));
            }

            // Upload gambar baru dan simpan path-nya
            $imagePath = $request->file('gambar')->store('images', 'public');
            $product->gambar = $imagePath;
        }

        // Simpan perubahan ke database
        $product->save();

        // Redirect kembali ke halaman produk setelah update berhasil
        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id_produk)
    {
        // Cari produk berdasarkan ID
        $product = Product::findOrFail($id_produk);

        // Hapus produk
        $product->delete();

        // Redirect ke halaman produk setelah produk berhasil dihapus
        return redirect()->route('product.index')->with('success', 'Product deleted successfully');
    }


    // public function buy(Request $request, $id_produk)
    // {
    //     $product = Product::findOrFail($id_produk);  // Ambil produk berdasarkan ID
    //     $quantity = $request->get('kuantitas', 1);  // Ambil jumlah produk yang dibeli
    
    //     // Validasi stok produk
    //     if ($product->stock < $quantity) {
    //         return redirect()->back()->with('error', 'Stok tidak mencukupi.');
    //     }
    
    //     // Hitung total harga pesanan
    //     $totalPrice = $product->price * $quantity;
    
    //     // Simpan transaksi ke database terlebih dahulu
    //     $transaction = Transaction::create([
    //         'id_pelanggan' => auth()->user()->id_pelanggan,  // ID pelanggan (pastikan menggunakan 'id_pelanggan')
    //         'mode_pembayaran' => 'Pending',  // Status pembayaran (bisa diubah setelah pembayaran)
    //         'dokumen_tambahan' => '',  // Default kosong (bisa diisi jika ada)
    //         'total_pembayaran' => $totalPrice,  // Total harga pesanan
    //         'transfer' => '',  // Bisa diisi nanti jika ada transfer
    //         'bukti_pembayaran' => '',  // Bisa diisi nanti jika ada bukti pembayaran
    //     ]);
    
    //     // Simpan order ke database dengan reference ke transaksi
    //     $order = Order::create([
    //         'id_pelanggan' => auth()->user()->id_pelanggan,  // ID pelanggan yang login
    //         'id_transaksi' => $transaction->id_transaksi,  // ID transaksi yang baru dibuat
    //         'kuantitas' => $quantity,  // Jumlah produk yang dibeli
    //         'total_pembayaran' => $totalPrice,  // Total harga pesanan
    //     ]);
    
    //     // Kembalikan data pesanan untuk ditampilkan di halaman konfirmasi atau lainnya
    //     return view('product.index', [
    //         'order' => $order, 
    //         'products' => Product::all(),
    //         'totalPrice' => $totalPrice
    //     ])->with('success', 'Pesanan Anda berhasil dibuat! Silakan lanjutkan ke pembayaran.');
    // }
    



}

