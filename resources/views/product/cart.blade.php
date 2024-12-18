@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>
    @if (empty($cart))
        <p class="text-gray-500">Keranjang Anda kosong.</p>
    @else
        <table class="w-full bg-white rounded-lg shadow-lg">
            <thead>
                <tr class="bg-teal-500 text-white">
                    <th class="px-4 py-2">Produk</th>
                    <th class="px-4 py-2">Harga</th>
                    <th class="px-4 py-2">Jumlah</th>
                    <th class="px-4 py-2">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $item['name'] }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td class="px-4 py-2">{{ $item['quantity'] }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
