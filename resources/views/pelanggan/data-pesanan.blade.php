@extends('layouts.app')

@section('title', 'Data Pesanan')

@section('content')

@if($orders->isEmpty())
    <div class="mt-4">
        <p class="text-center text-gray-500">Belum ada pesanan.</p>
    </div>
@else
    @foreach($orders as $order)
    <div class="bg-cyan-100 p-4 rounded-lg shadow-lg w-full mt-4 mb-4">
        <div class="flex justify-between items-center">
            <div class="flex-1 cursor-pointer" onclick="toggleDetails('{{ $order->id_transaksi }}')">
                <h1 class="text-lg font-semibold">Pesanan: {{ $order->id_transaksi }}</h1>
                <span class="text-sm">Klik untuk melihat detail</span>
            </div>
            <input type="checkbox" id="select-{{ $order->id_transaksi }}" class="order-checkbox" data-id="{{ $order->id_transaksi }}">
        </div>

        <div class="mt-4 hidden transition-all duration-300 ease-in-out" id="details-{{ $order->id_transaksi }}">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ basename (Auth::user()->nama_lengkap) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div>
                    <label class="block mb-2">Alamat</label>
                    <input type="text" name="alamat" value="{{ basename (Auth::user()->alamat) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div>
                    <label class="block mb-2">Nomor Telepon/WA</label>
                    <input type="text" name="telepon" value="{{ basename (Auth::user()->telepon) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div>
                    <label for="payment_method" class="block font-medium mb-2">Mode Pembayaran</label>
                    <input value="{{basename($order->mode_pembayaran) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div>
                    <label class="block mb-2">Total Pembayaran</label>
                    <input value="{{basename($order->total_pembayaran) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div>
                    <label for="bank_name" class="block font-medium mb-2">Pilih Bank</label>
                    <input value="{{basename($order->transfer) }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                </div>
                <div>
                    <label for="proof_of_payment" class="block font-medium mb-2">Bukti Pembayaran</label>
                    @if($order->bukti_pembayaran)
                        <p class="border border-gray-300 rounded-lg p-2 w-full">Nama file: {{ basename($order->bukti_pembayaran) }}</p>
                    @else
                        <p>Belum Ada Bukti Pembayaran yang diUpload.</p>
                    @endif
                </div>
            </div>
            
            <!-- Bagian produk yang dibeli -->
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Produk yang Dibeli:</h2>
                <div class="space-y-4 mt-4">
                    @foreach($order->detailTransactions as $detail)
                        <div class="bg-teal-600 p-4 text-white rounded-lg shadow-md">
                            <h3 class="font-bold text-lg">
                            <label class="block mb-2">Nama Produk : {{ basename ($detail->order->product->nama_produk) }}</label>
                            </h3>
                            <div class="grid gap-2">
                            <div>
                                <img src="{{ asset('' . $detail->order->product->gambar) }}" alt="Gambar Produk" class="w-32 h-32 object-cover rounded-lg">
                            </div>
                                <div>
                                    <label class="block font-medium mb-2">Total Harga :</label>
                                    <p class="border border-gray-300 rounded-lg p-2 w-full">Rp {{ number_format($detail->order->total_pembayaran, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="block font-medium mb-2">Jumlah Pesan :</label>
                                    <p class="border border-gray-300 rounded-lg p-2 w-full">{{ basename($detail->order->kuantitas) }} Buah</p>
                                </div>
                                <div>
                                    <label class="block font-medium mb-2">Dokumen Tambahan :</label>
                                    @if($detail->dokumen_tambahan)
                                        <p class="border border-gray-300 rounded-lg p-2 w-full">Nama file: {{ basename($detail->dokumen_tambahan) }}</p>
                                    @else
                                        <p>Belum Ada Bukti Pembayaran yang diUpload.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr class="border-black my-4">
            <div class="flex justify-center mt-4 space-x-4">
                <button type="submit" class="bg-cyan-400 text-white px-4 py-2 rounded" value="status">
                    {{ $order->status }}
                </button>
            </div>

        </div>

        <script>
            function toggleDetails(id) {
                const details = document.getElementById(`details-${id}`);
                if (details.classList.contains('hidden')) {
                    details.classList.remove('hidden');
                } else {
                    details.classList.add('hidden');
                }
            }
        </script>

    </div>
    @endforeach
@endif

@endsection
