@extends('layouts.app')

@section('content')

<div class="mt-4 w-full bg-cyan-200 p-8 rounded-lg shadow-lg mb-4">
    <h1 class="text-center text-xl font-bold mb-4">Laporan Penjualan</h1>

    <div class="mb-4 text-right">
        <button 
            onclick="window.open('{{ route('print') }}', '_blank').print();"
            class="bg-cyan-300 p-2 border border-gray-400 rounded">
            Cetak Laporan
        </button>
    </div>


    <div class="grid grid-cols-3 gap-4 mb-4">
        <form action="{{ route('transaction.search') }}" method="GET" class="grid grid-cols-3 gap-4 w-full">
            <!-- Form Filter -->
            <div>
                <label for="startDate" class="block mb-2">Tanggal Mulai</label>
                <input type="date" id="startDate" name="start_date" class="border border-gray-400 p-2 w-full">
            </div>
            <div>
                <label for="endDate" class="block mb-2">Tanggal Selesai</label>
                <input type="date" id="endDate" name="end_date" class="border border-gray-400 p-2 w-full">
            </div>
            <div>
                <label for="status" class="block mb-2">Status</label>
                <select id="status" name="status" class="border border-gray-400 p-2 w-full">
                    <option value="">-- Pilih Status --</option>
                    <option value="pending">Pending</option>
                    <option value="process">Proses</option>
                    <option value="completed">Selesai</option>
                </select>
            </div>
            <div class="col-span-2">
                <label for="transactionId" class="block mb-2">Cari ID Transaksi</label>
                <input type="text" id="transactionId" name="transaction_id" class="border border-gray-400 p-2 w-full">
            </div>
            <div class="flex items-center mt-7">
                <button type="submit" class="bg-cyan-300 p-2 border border-gray-400">Cari</button>
            </div>
        </form>
    </div>

    <!-- Table Laporan -->
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-700 text-white">
                <th class="border border-gray-600 p-2">ID Transaksi</th>
                <th class="border border-gray-600 p-2">ID Produk</th>
                <th class="border border-gray-600 p-2">ID Pelanggan</th>
                <th class="border border-gray-600 p-2">ID Pesanan</th>
                <th class="border border-gray-600 p-2">Jumlah Barang</th>
                <th class="border border-gray-600 p-2">Tanggal Transaksi</th>
                <th class="border border-gray-600 p-2">Total Bayar</th>
                <th class="border border-gray-600 p-2">Status Pembayaran</th>
                <th class="border border-gray-600 p-2">Status Pesanan</th>
            </tr>
        </thead>
        <tbody>
        @if ($transactions->isEmpty())
            <tr>
                <td colspan="10" class="text-center text-gray-500">Tidak ada data ditemukan</td>
            </tr>
        @else
            @foreach ($transactions as $transaction)
                <tr class="bg-gray-800 text-white">
                    <td class="border border-gray-600 p-2">{{ $transaction->id }}</td>
                    <td class="border border-gray-600 p-2 text-center">
                        @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->id_produk }} </p>
                        @endforeach
                    </td>
                    <td class="border border-gray-600 p-2">{{ $transaction->id_user }}</td>
                    <td class="border border-gray-600 p-2">
                        @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->id }} </p>
                        @endforeach
                    </td>
                    <td class="border border-gray-600 p-2 text-center">
                        @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->kuantitas }} </p>
                        @endforeach
                    </td>
                    <td class="border border-gray-600 p-2">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y') }}</td>
                    <td class="border border-gray-600 p-2">{{ $transaction->total_pembayaran }}</td>
                    <td class="border border-gray-600 p-2">LUNAS</td>
                    <td class="border border-gray-600 p-2">{{ $transaction->status }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

@endsection
