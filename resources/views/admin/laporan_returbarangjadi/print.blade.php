<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Retur Barang Jadi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            font-size: 14px;
        }
        .date-range {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .tfoot {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>JAVA BAKERY</h1>
    <h1>Laporan Retur Barang Jadi</h1>
</div>

<div class="date-range">
    @if($tanggal_retur && $tanggal_akhir)
        <p>Periode Tanggal: {{ \Carbon\Carbon::parse($tanggal_retur)->format('d-m-Y') }} hingga {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d-m-Y') }}</p>
    @elseif($tanggal_retur)
        <p>Periode Tanggal: Mulai {{ \Carbon\Carbon::parse($tanggal_retur)->format('d-m-Y') }}</p>
    @elseif($tanggal_akhir)
        <p>Periode Tanggal: Sampai {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d-m-Y') }}</p>
    @else
        <p>Periode Tanggal: Hari Ini</p>
    @endif

    @php
        \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
        $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
    @endphp
</div>
<p>
    <span style="float: right; font-style: italic">{{ $currentDateTime }}</span>
</p>
<table class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Divisi</th>
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
            $totalKeseluruhan = 0;
        @endphp
        @foreach ($stokBarangJadi as $kode_retur => $returs)
            @foreach ($returs as $index => $retur)
                @php
                    $total = $retur->jumlah * $retur->produk->harga;
                    $totalJumlah += $retur->jumlah;
                    $totalKeseluruhan += $total;
                @endphp
                <tr>
                    <td>{{ $loop->parent->iteration }}</td>
                    <td>{{ $retur->produk->klasifikasi->nama }}</td>
                    <td>{{ $retur->produk->kode_produk }}</td>
                    <td>{{ $retur->produk->nama_produk }}</td>
                    <td>{{ $retur->jumlah }}</td>
                    <td>{{ number_format($retur->produk->harga, 0, ',', '.') }}</td>
                    <td>{{ 'Rp. '. number_format($total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot class="tfoot">
        <tr>
            <td colspan="4"><strong>Total Keseluruhan:</strong></td>
            <td><strong>{{ $totalJumlah }}</strong></td>
            <td></td>
            <td><strong>{{ 'Rp. '. number_format($totalKeseluruhan, 0, ',', '.') }}</strong></td>
        </tr>
    </tfoot>
</table>

</body>
</html>
