<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Deposit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center; font-size: 28px;">LAPORAN DEPOSIT</h2>
    <p style="text-align: center;">
        Periode: {{ $startDate }} - {{ $endDate }}<br>
        Toko: {{ $tokos->find(request()->toko_id)->nama_toko ?? 'Semua Toko' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Deposit</th>
                <th>Tanggal Deposit</th>
                <th>Pelanggan</th>
                <th>Kasir</th>
                <th>Metode Bayar</th>
                <th>Fee Deposit</th>
                <th>Nominal Deposit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inquery as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->kode_dppemesanan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->pemesananproduk->tanggal_pemesanan)->format('d-m-Y') }}</td>
                    <td>
                        @if($item->pemesananproduk->kode_pelanggan)
                            {{ $item->pemesananproduk->kode_pelanggan }} / {{ $item->pemesananproduk->nama_pelanggan }}
                        @else
                            {{ $item->pemesananproduk->nama_pelanggan }}
                        @endif
                    </td>
                    <td>{{ $item->pemesananproduk->toko->nama_toko }}</td>
                    <td>
                        @if($item->pemesananproduk->metodePembayaran)
                            {{ $item->pemesananproduk->metodePembayaran->nama_metode }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->fee_deposit }}</td>
                    <td>{{ $item->nominal_deposit }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    

</body>
</html>
