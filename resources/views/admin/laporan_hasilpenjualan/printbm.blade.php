<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan BM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 4px;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .header {
            text-align: center;
            margin-top: 3px;
        }
        .header span {
            display: block;
        }
        .header .title {
        font-weight: bold;
        font-size: 28px;
        margin-bottom: 5px;
        }
        .header .title1 {
        margin-top: 5px;
        font-size: 14px;
        margin-bottom: 5px;
        }
        .header .title2 {
            font-weight: bold;
            font-size: 18px;
        }
        .header .period {
            font-size: 12px;
            margin-top: 10px;
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
        <h1 class="title">JAVA BAKERY</h1>
        <p class="title1">Cabang: {{ strtoupper($branchName) }}</p>
        <div class="divider"></div>
    
        <h1 class="title2">LAPORAN BARANG MASUK</h1>
    
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
            $formattedStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        <p class="period">
            @if ($startDate && $endDate)
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
            @else
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan.
            @endif
        </p>
    
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
    </div>
    
    
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: left">No</th>
                <th style="width: 10%; text-align: left">Kode Produk</th>
                <th style="width: 50%; text-align: left">Produk</th>
                <th style="width: 5%; text-align: left">Jumlah</th>
                <th style="width: 15%; text-align: left">Harga</th>
                <th style="width: 15%; text-align: left">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalJumlah = 0;
                $grandTotal = 0;
            @endphp
            @foreach ($stokBarangJadi as $index => $item)
            @php
                $totalJumlah += $item->jumlah;
                $totalHarga = $item->jumlah * $item->produk->harga;
                $grandTotal += $totalHarga;
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->produk->kode_lama }}</td>
                <td>{{ $item->produk->nama_produk }}</td>
                <td style="text-align: right">{{ $item->jumlah }}</td>
                <td style="text-align: right">{{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                <td style="text-align: right">{{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-center">Total</th>
                <th style="text-align: right">{{ $totalJumlah }}</th>
                <th></th>
                <th style="text-align: right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
