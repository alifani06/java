<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan BR</title>
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
            background-color: white;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="text-center">LAPORAN BARANG RETUR</h1>
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
                <th>Tanggal Retur</th>
                <th>Kode Retur</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($stokBarangJadi as $returGroup)
                @foreach($returGroup as $retur)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ \Carbon\Carbon::parse($retur['tanggal_retur'])->format('d/m/Y H:i') }}</td>
                    <td>{{ $retur->kode_retur }}</td>
                    <td>{{ $retur->produk->nama_produk }}</td>
                    <td style="text-align: right">{{ number_format($retur->jumlah, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($retur->produk->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($retur->jumlah * $retur->produk->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
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
