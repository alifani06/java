<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-top: 3px ;
        }
        .header span {
            display: block;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <span class="title">PT JAVA BAKERY</span>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <hr class="divider">
        <hr class="divider">
    </div>
    <div class="container">
        <h1 style="text-align: center">LAPORAN PENJUALAN PRODUK</h1>
    </div>
    <div class="text">
        @if ($startDate && $endDate)
            <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
        @else
            <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan.</p>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode penjualan</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Metode Pembayaran</th>
                <th>Fee Penjualan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody style="font-size: 10px;">
            @php $grandTotal = 0; @endphp
            @foreach ($inquery as $item)
                @php $grandTotal += $item->sub_total; @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_penjualan }}</td>
                    <td>{{ $item->kasir ?? '-' }}</td>
                    <td>{{ $item->nama_pelanggan ?? 'Non Member' }}</td>
                    <td>{{ $item->metodepembayaran->nama_metode ?? 'Tunai' }}</td>
                    <td>
                        @php
                            // Menghapus semua karakter kecuali angka
                            $total_fee = preg_replace('/[^\d]/', '', $item->total_fee);
                            // Konversi ke tipe float
                            $total_fee = (float) $total_fee;
                        @endphp
                        @if ($total_fee == 0)
                            -
                        @else
                            {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
                        @endif
                    </td>
  
                    <td>{{ 'Rp. ' .  number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="6" class="text-right"><strong>Grand Total</strong></td>
                <td>{{ 'Rp. ' .  number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
