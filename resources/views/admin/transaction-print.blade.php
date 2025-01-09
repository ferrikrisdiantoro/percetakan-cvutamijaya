<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            /* Set halaman cetak menjadi portrait dengan margin kecil */
            @page {
                size: A4 portrait;
                margin: 15mm;  /* Mengurangi margin agar data muat dengan baik */
            }

            /* Pastikan body tidak ada margin */
            body {
                margin: 0;
                padding: 0;
                font-size: 12px;
            }

            /* Menyesuaikan ukuran tabel */
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            th, td {
                padding: 6px;
                text-align: left;
                border: 1px solid #ddd;
                word-wrap: break-word;
            }

            th {
                background-color: #f2f2f2;
            }

            /* Menghindari pemotongan teks */
            td {
                white-space: normal;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Menyembunyikan elemen-elemen yang tidak diperlukan dalam print */
            .no-print {
                display: none;
            }

            /* Untuk header, membuat lebih besar dan jelas */
            h1 {
                font-size: 20px;
                margin-bottom: 20px;
            }

            /* Menyesuaikan tampilan pada header dan footer */
            .container {
                padding: 0;
                margin: 0;
                width: 100%;
            }

            /* Menambahkan halaman baru jika tabel terlalu panjang */
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="flex flex-col bg-gray-100 font-sans">

    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl text-center font-semibold mb-6">Laporan Penjualan</h1>

        <!-- Tombol Print (Tersembunyi pada halaman cetak) -->
        <div class="mb-4 text-right no-print">
            <button onclick="window.print()" class="bg-cyan-300 p-2 border border-gray-400 rounded">
                Cetak Laporan
            </button>
        </div>

        <table class="min-w-full table-auto border-collapse mb-6">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border text-left">ID Transaksi</th>
                    <th class="px-4 py-2 border text-left">ID Produk</th>
                    <th class="px-4 py-2 border text-left">ID Pelanggan</th>
                    <th class="px-4 py-2 border text-left">ID Pesanan</th>
                    <th class="px-4 py-2 border text-left">Jumlah Barang</th>
                    <th class="px-4 py-2 border text-left">Tanggal Transaksi</th>
                    <th class="px-4 py-2 border text-left">Total Bayar</th>
                    <th class="px-4 py-2 border text-left">Tipe Pembayaran</th>
                    <th class="px-4 py-2 border text-left">Status Pembayaran</th>
                    <th class="px-4 py-2 border text-left">Status Pesanan</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="px-4 py-2 border">{{ $transaction->id_transaksi }}</td>
                        <td class="px-4 py-2 border">
                            @foreach($transaction->detailTransactions as $detail)
                                <p>{{ $detail->order->id_produk }} </p>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 border">{{ $transaction->id_pelanggan }}</td>
                        <td class="px-4 py-2 border">
                            @foreach($transaction->detailTransactions as $detail)
                                <p>{{ $detail->order->id_pesanan }} </p>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 border">
                            @foreach($transaction->detailTransactions as $detail)
                                <p>{{ $detail->order->kuantitas }} </p>
                            @endforeach
                        </td>
                        <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($transaction->tanggal_transaksi)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->total_pembayaran }}</td>
                        <td class="px-4 py-2 border">{{ $transaction->mode_pembayaran }}</td>
                        <td class="px-4 py-2 border">LUNAS</td>
                        <td class="px-4 py-2 border">{{ $transaction->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        window.print();
    </script>

</body>
</html>
