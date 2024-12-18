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
                <h1 class="text-lg font-semibold">Pesanan: {{ $order->product->nama_produk }} ({{ $order->id_transaksi }})</h1>
                <span class="text-sm">Klik untuk melihat detail</span>
            </div>
            <input type="checkbox" id="select-{{ $order->id_transaksi }}" class="order-checkbox" data-id="{{ $order->id_transaksi }}">
        </div>
                <div class="mt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ Auth::user()->nama_lengkap }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                        <div>
                            <label class="block mb-2">Alamat</label>
                            <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                        <div>
                            <label class="block mb-2">Nomor Telepon/WA</label>
                            <input type="text" name="telepon" value="{{ Auth::user()->telepon }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                        <div>
                            <label for="payment_method" class="block font-medium mb-2">Mode Pembayaran</label>
                            <select name="payment_method" class="border border-gray-300 rounded-lg p-2 w-full">
                                <option value="cash" {{ $order->mode_pembayaran == 'cash' ? 'selected' : '' }}>DP</option>
                                <option value="transfer" {{ $order->mode_pembayaran == 'transfer' ? 'selected' : '' }}>LUNAS</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2">Jumlah Produk</label>
                            <input type="number" name="kuantitas" value="{{$order->order->kuantitas}}" class="w-full p-2 border border-gray-300 rounded">
                        </div>
                        <div>
                            <label class="block mb-2">Total Pembayaran</label>
                            <input type="number" name="total_pembayaran" value="{{ $order->total_pembayaran }}" class="w-full p-2 border border-gray-300 rounded" readonly>
                        </div>
                    </div>
                    <hr class="border-black my-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="bank_name" class="block font-medium mb-2">Pilih Bank</label>
                            <select name="bank_name" class="border border-gray-300 rounded-lg p-2 w-full">
                                <option value="BCA" {{ $order->transfer == 'BCA' ? 'selected' : '' }}>BCA</option>
                                <option value="Mandiri" {{ $order->transfer == 'Mandiri' ? 'selected' : '' }}>Mandiri</option>
                                <option value="BRI" {{ $order->transfer == 'BRI' ? 'selected' : '' }}>BRI</option>
                                <option value="BNI" {{ $order->transfer == 'BNI' ? 'selected' : '' }}>BNI</option>
                            </select>
                        </div>
                        <div>
                            <label for="proof_of_payment" class="block font-medium mb-2">Bukti Pembayaran</label>
                            @if($order->bukti_pembayaran)
                                <p class="border border-gray-300 rounded-lg p-2 w-full">Nama file: {{ basename($order->bukti_pembayaran) }}</p>
                            @else
                                <p>Belum Ada Bukti Pembayaran yang diUpload.</p>
                            @endif
                        </div>
                        <div>
                        <label for="custom_image" class="block font-medium mb-2">Dokumen Tambahan</label>
                            @if($order->dokumen_tambahan)
                                <p class="border border-gray-300 rounded-lg p-2 w-full">Nama file: {{ basename($order->dokumen_tambahan) }}</p>
                            @else
                                <p>Belum Ada Dokumen Tambahan yang diUpload.</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-center mt-4 space-x-4">
                        <button type="submit" class="bg-cyan-400 text-white px-4 py-2 rounded" value="status">
                            {{ $order->status }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection
