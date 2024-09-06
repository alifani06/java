<!DOCTYPE html>
<html lang="id">
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
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: white;
        }
        .text-center {
            text-align: center;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DEPOSIT</h1>
        <p><strong>Periode:</strong> {{ $tanggal_pemesanan ? $tanggal_pemesanan . ' s/d ' . $tanggal_akhir : 'Hari Ini' }}</p>
        <p><strong>Toko:</strong> {{ $toko_id ? $tokos->find($toko_id)->nama_toko : 'Semua Toko' }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Cabang</th>
                <th>Kode Deposit</th>
                <th>Nama Pelanggan</th>
                <th>Metode Bayar</th>
                <th>Fee Deposit</th>
                <th>Nominal</th>
                <th>Status</th> <!-- Tambahkan kolom Status -->
            </tr>
        </thead>
        <tbody>
            @foreach ($inquery as $deposit)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $deposit->pemesananproduk->toko->nama_toko ?? 'Tidak Ada Toko' }}</td> <!-- Akses nama_toko -->
                    <td>{{ $deposit->kode_dppemesanan }}</td>
                    <td>{{ $deposit->pemesananproduk->nama_pelanggan ?? 'Tidak Ada Nama' }}</td>
                    <td>
                        @if($deposit->pemesananproduk->metodePembayaran)
                            {{ $deposit->pemesananproduk->metodePembayaran->nama_metode }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $deposit->pemesananproduk->total_fee == 'Rp0' ? '-' : $item->pemesananproduk->total_fee }}</td>

                    <td style="text-align: right">{{number_format($deposit->dp_pemesanan, 0, ',', '.') }}</td>
                    <td>
                        @if($deposit->pelunasan)
                            <span>Diambil</span>
                        @else
                            <span>Belum Diambil</span>
                        @endif
                    </td> <!-- Tampilkan status diambil/belum diambil -->
                </tr>
            @endforeach
        </tbody>
    </table>
   <!-- Tampilkan Total Deposit, Fee Deposit, dan Sub Total dengan format yang benar -->
<table style="width: 60%; margin-left: auto; margin-right: 0; background-color: rgb(248, 248, 6);" border="0">
    <tbody>
        <tr style="border: none;">
            <td style="text-align: right; border: none;">Total Deposit :</td>
            <td style="text-align: right">{{number_format($totalDeposit, 0, ',', '.') }}</td>
        </tr>
        <tr style="border: none;">
            <td style="text-align: right; width: 60%; border: none;">Fee Deposit :</td>
            <td style="text-align: right">{{number_format($totalFee, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid black;">
            <td style="text-align: right; border: none;">Sub Total :</td>
            <td style="text-align: right">{{ 'Rp ' . number_format($subTotal, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

    
</body>
</html>
