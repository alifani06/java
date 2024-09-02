<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok Toko</title>
    <style>
          body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 100px; /* Increased padding-bottom for signatures */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #f2f2f2;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="change-header">LAPORAN STOK BARANG JADI</div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produkWithStok as $produk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->kode_lama }}</td>
                    <td>{{ $produk->nama_produk }}</td>
                    <td style="text-align: right">{{ $produk->jumlah }}</td>
                    <td style="text-align: right">{{ number_format($produk->harga, 0, ',', '.') }}</td>
                    <td style="text-align: right">{{ number_format($produk->subTotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th style="text-align: right">{{ $totalStok }}</th>
                <th></th>
                <th style="text-align: right">{{ 'Rp. ' . number_format($totalSubTotal, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
