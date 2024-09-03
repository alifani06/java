<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN BM</title>
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
            padding: 6px;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="text-center">LAPORAN BM</h1>
    <div class="text" style="text-align: center;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
        
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
        
        @if ($startDate && $endDate)
            <p>
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}<br><br>
                Cabang: {{ $branchName }}
            </p>
        @else
            <p>
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan.<br>
                Cabang: {{ $branchName }}
            </p>
        @endif
        
        <p style="text-align: right; margin-top: -20px;">{{ $currentDateTime }}</p>
    </div>
    
    
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pengiriman</th>
                <th>Kode Produk</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
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
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d/m/Y H:i') }}</td>
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
                <th colspan="4" class="text-center">Total</th>
                <th style="text-align: right">{{ $totalJumlah }}</th>
                <th></th>
                <th style="text-align: right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
