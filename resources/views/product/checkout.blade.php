@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
        <p>Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</p>
        <p>Jumlah: {{ $quantity }}</p>
        <p>Total Harga: Rp{{ number_format($totalPrice, 0, ',', '.') }}</p>
        <form action="{{ route('order.create') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}"> <!-- Pastikan total_price dikirim -->
            <p><strong>Alamat:</strong> {{ auth()->user()->alamat }}</p>
            <p><strong>Nomor Telepon:</strong> {{auth()->user()->phone }}</p>
            <button class="bg-teal-500 text-white px-4 py-2 rounded">Bayar Sekarang</button>
        </form>
        <form action="{{ route('order.create') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">

            <!-- Pilihan jenis pembayaran -->
            <label for="payment_method">Jenis Pembayaran:</label>
            <select name="payment_method" id="payment_method" onchange="toggleBankAndProof()">
                <option value="cash">Cash</option>
                <option value="transfer">Transfer</option>
            </select>

            <!-- Pilihan bank (hanya muncul jika transfer dipilih) -->
            <div id="bank_section" style="display: none;">
                <label for="bank_name">Bank:</label>
                <select name="bank_name" id="bank_name">
                    <option value="BCA">BCA</option>
                    <option value="Mandiri">Mandiri</option>
                    <option value="BNI">BNI</option>
                    <option value="BRI">BRI</option>
                </select>

                <!-- Kolom unggah file -->
                <label for="proof_of_payment">Unggah Bukti Pembayaran:</label>
                <input type="file" name="proof_of_payment" id="proof_of_payment">
            </div>

            <button type="submit" class="bg-teal-500 text-white px-4 py-2 rounded">Bayar Sekarang</button>
        </form>

        <script>
        function toggleBankAndProof() {
            const paymentMethod = document.getElementById('payment_method').value;
            const bankSection = document.getElementById('bank_section');
            bankSection.style.display = paymentMethod === 'transfer' ? 'block' : 'none';
        }
        </script>

    </div>
</div>
@endsection
