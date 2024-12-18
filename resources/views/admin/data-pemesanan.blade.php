@extends('layouts.app')

@section('content')
    
    <div class="mt-4 w-full bg-teal-100 p-8 rounded-lg shadow-lg">
        <h1 class="text-center text-2xl font-bold mb-4">DATA PEMESANAN</h1>
        <form action="{{ route('transaction.update-status') }}" method="POST">
        @csrf
            <table class="w-full border-collapse border border-black">
                <thead>
                    <tr>
                        <th class="border border-black p-2">ID Pelanggan</th>
                        <th class="border border-black p-2">ID Transaksi</th>
                        <th class="border border-black p-2">ID Produk</th>
                        <th class="border border-black p-2">Quantity</th>
                        <th class="border border-black p-2">Total Pembayaran</th>
                        <th class="border border-black p-2">Status Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="border border-black p-2">{{ $transaction->id_pelanggan }}</td>
                        <td class="border border-black p-2">{{ $transaction->id_transaksi }}</td>
                        <td class="border border-black p-2">{{ $transaction->product->id_produk }}</td>
                        <td class="border border-black p-2">{{ $transaction->order->kuantitas }}</td>
                        <td class="border border-black p-2">{{ number_format($transaction->total_pembayaran, 0, ',', '.') }}</td>
                        <td class="border border-black p-2">
                            <select name="status[{{ $transaction->id_transaksi }}]" class="w-full p-2 border border-black">
                                <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="process" {{ $transaction->status === 'process' ? 'selected' : '' }}>Proses</option>
                                <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </td>
                    </tr>
                 @endforeach
                </tbody>
            </table>
            <div class="flex justify-end mt-4">
            <button type="submit" class="bg-teal-200 text-black font-bold py-2 px-4 rounded">Simpan</button>
            </div>
        </form>
    </div>

    @endsection