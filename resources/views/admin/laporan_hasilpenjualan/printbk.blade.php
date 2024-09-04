<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>
    <style>
        /* Tambahkan style sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .text {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
            font-size: 10px;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        tfoot th {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1 class="text-center">LAPORAN BK</h1>

    <div class="text" style="text-align: center;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia

            $formattedStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : null;
            $formattedEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : null;
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
                <th>Tanggal Penjualan</th>
                <th>Kode Lama</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Diskon</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($finalResults as $produk)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($produk['tanggal_penjualan'])->translatedFormat('d F Y') }}</td>
                    <td>{{ $produk['kode_lama'] }}</td>
                    <td>{{ $produk['nama_produk'] }}</td>
                    <td style="text-align: right">{{ number_format($produk['harga'], 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ $produk['jumlah'] }}</td>
                    <td style="text-align: right">{{ number_format($produk['diskon'], 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($produk['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $totalJumlah = collect($finalResults)->sum('jumlah');
                $grandTotal = collect($finalResults)->sum('total');
                $totalDiskon = collect($finalResults)->sum('diskon');
            @endphp
            <tr>
                <th colspan="4">Total</th>
                <th>{{ $totalJumlah }}</th>
                <th>{{ number_format($totalDiskon, 0, ',', '.') }}</th>
                <th>{{ number_format($grandTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
