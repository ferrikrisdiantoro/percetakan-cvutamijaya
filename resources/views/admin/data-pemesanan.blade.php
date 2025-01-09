@extends('layouts.app')

@section('content')

<div class="mt-4 w-full bg-teal-100 p-8 rounded-lg shadow-lg">
    <h1 class="text-center text-2xl font-bold mb-4">DATA PEMESANAN</h1>
    
    <div class="flex justify-between items-center mb-4">
        <!-- Show Entries -->
        <div>
            <label for="show-entries" class="mr-2">Show</label>
            <select id="show-entries" class="p-2 border border-black rounded" onchange="changeEntries()">
                <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
            </select>
            <span>entries</span>
        </div>

        <!-- Search -->
        <div>
            <form action="{{ route('data-pemesanan') }}" method="GET">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search..." 
                    class="p-2 border border-black rounded" 
                    value="{{ request('search') }}">
                <button type="submit" class="bg-teal-200 text-black font-bold py-2 px-4 rounded">Search</button>
            </form>
        </div>
    </div>

    <form action="{{ route('transaction.update-status') }}" method="POST">
    @csrf
        <table class="w-full border-collapse border border-black">
            <thead>
                <tr>
                    <th class="border border-black p-2">ID Transaksi</th>
                    <th class="border border-black p-2">ID Pelanggan</th>
                    <th class="border border-black p-2">ID Produk</th>
                    <th class="border border-black p-2">Quantity</th>
                    <th class="border border-black p-2">Total Pembayaran</th>
                    <th class="border border-black p-2">Status Pemesanan</th>
                    <th class="border border-black p-2">Mode Pembayaran</th>
                    <th class="border border-black p-2">Dokumen Tambahan</th>
                    <th class="border border-black p-2">Bukti Pembayaran</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td class="border border-black p-2 text-center">{{ $transaction->id_transaksi }}</td>
                    <td class="border border-black p-2 text-center">{{ $transaction->id_user }}</td>
                    <td class="border border-black p-2 text-center">
                        @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->id_produk }} </p>
                        @endforeach
                    </td>
                    <td class="border border-black p-2 text-center">
                        @foreach($transaction->detailTransactions as $detail)
                            <p>{{ $detail->order->kuantitas }} </p>
                        @endforeach
                    </td>
                    <td class="border border-black p-2 text-center">Rp. {{ number_format($transaction->total_pembayaran, 0, ',', '.') }}</td>
                    <td class="border border-black p-2">
                        <select name="status[{{ $transaction->id_transaksi }}]" class="w-full p-2 border border-black rounded-lg">
                            <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="process" {{ $transaction->status === 'process' ? 'selected' : '' }}>Proses</option>
                            <option value="completed" {{ $transaction->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </td>
                    <td class="border border-black p-2">
                        <select name="mode_pembayaran[{{ $transaction->id_transaksi }}]" class="w-full p-2 border border-black rounded-lg">
                            <option value="dp" {{ $transaction->mode_pembayaran === 'dp' ? 'selected' : '' }}>DP</option>
                            <option value="lunas" {{ $transaction->mode_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </td>
                    <td class="border border-black p-2 ">
                        <div class="flex flex-col items-start gap-2">
                            @foreach($transaction->detailTransactions as $detail)
                                @if($detail->dokumen_tambahan)
                                    <a href="{{ Storage::url($detail->dokumen_tambahan) }}" 
                                    target="_blank" 
                                    class="w-full rounded-md bg-green-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-green-700 focus:shadow-none active:bg-green-700 hover:bg-green-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                    Lihat Dokumen
                                    </a>
                                @else
                                    <p class="text-gray-500">Tidak ada dokumen</p>
                                @endif
                            @endforeach
                        </div>
                    </td>

                    <td class="border border-black p-2">
                        <div class="flex flex-col gap-2 mt-2">
                            @if($transaction->bukti_pembayaran)
                                <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                target="_blank" 
                                class="rounded-md bg-teal-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-teal-700 focus:shadow-none active:bg-teal-700 hover:bg-teal-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                Lihat Bukti
                                </a>
                            @else
                                <p>Tidak ada bukti</p>
                            @endif
                        </div>
                    </td>
                </tr>
             @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $transactions->appends(request()->except('page'))->links() }}
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-teal-200 text-black font-bold py-2 px-4 rounded">Simpan</button>
        </div>
    </form>
</div>

<script>
    function changeEntries() {
        const perPage = document.getElementById('show-entries').value;
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('perPage', perPage);
        window.location.search = urlParams.toString();
    }
</script>

@endsection
