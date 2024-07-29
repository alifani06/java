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
                <th>Tanggal penjualan</th>
                <th>Cabang</th>
                <th>Produk</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach ($inquery as $item)
                @php $grandTotal += $item->sub_total; @endphp
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
            <tr>
                <td colspan="5" class="text-right"><strong>Grand Total</strong></td>
                <td>{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
