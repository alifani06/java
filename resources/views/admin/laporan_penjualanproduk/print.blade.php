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
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Penjualan Produk</h2>
        <p>{{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode penjualan</th>
                <th>Tanggal penjualan</th>
                <th>Cabang</th>
                <th>Produk</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inquery as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_penjualan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_penjualan)->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->toko->nama_toko }}</td>
                    <td>
                        @if ($item->detailpenjualanproduk->isNotEmpty())
                            {{ $item->detailpenjualanproduk->pluck('nama_produk')->implode(', ') }}
                        @else
                            tidak ada
                        @endif
                    </td>
                    <td>{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
