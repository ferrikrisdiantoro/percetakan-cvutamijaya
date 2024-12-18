@extends('layouts.app')

@section('content')


<div class="mt-4 grid grid-cols-3 gap-4">
        <div class="bg-cyan-100 p-4 border border-black text-center">
            <div class="border-b border-black mb-2">Jumlah Pelanggan</div>
            <div class="text-2xl">{{ $jumlahPelanggan }}</div>
        </div>
        <div class="bg-cyan-100 p-4 border border-black text-center">
            <div class="border-b border-black mb-2">Transaksi Down Payment</div>
            <div class="text-2xl">{{ $statusDp }}</div>
        </div>
        <div class="bg-cyan-100 p-4 border border-black text-center">
            <div class="border-b border-black mb-2">Transaksi Lunas</div>
            <div class="text-2xl">{{ $statusLunas }}</div>
        </div>
        <div class="bg-cyan-100 p-4 border border-black text-center">
            <div class="border-b border-black mb-2">Transaksi Pending</div>
            <div class="text-2xl">{{ $statusPending }}</div>
        </div>
        <div class="bg-cyan-100 p-4 border border-black text-center">
            <div class="border-b border-black mb-2">Transaksi Process</div>
            <div class="text-2xl">{{ $statusProses }}</div>
        </div>
        <div class="bg-cyan-100 p-4 border border-black text-center">
            <div class="border-b border-black mb-2">Transaksi Selesai</div>
            <div class="text-2xl">{{ $statusSelesai }}</div>
        </div>
    </div>

    @endsection