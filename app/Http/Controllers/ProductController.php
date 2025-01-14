<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

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
    try {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'bahan' => 'required|string',
            'ukuran' => 'required|string',
            'stok' => 'required|integer',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $product = Product::findOrFail($id_produk);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($product->gambar && file_exists(public_path($product->gambar))) {
                unlink(public_path($product->gambar));
            }

            // Simpan gambar baru dengan cara yang sama seperti method store()
            $image = $request->file('gambar');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('images'), $imageName);
            $validated['gambar'] = 'images/' . $imageName;
        }

        $product->update($validated);

        return redirect()
            ->route('product.index')
            ->with('success', 'Product updated successfully');
            
    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Error updating product: ' . $e->getMessage());
    }
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
}

