<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Deposit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        <h1>Laporan Deposit</h1>
        <p><strong>Periode:</strong> {{ $tanggal_pemesanan ? $tanggal_pemesanan . ' s/d ' . $tanggal_akhir : 'Hari Ini' }}</p>
        <p><strong>Status Pelunasan:</strong> {{ $status_pelunasan == 'diambil' ? 'Diambil' : ($status_pelunasan == 'belum_diambil' ? 'Belum Diambil' : 'Semua') }}</p>
        <p><strong>Toko:</strong> {{ $toko_id ? $tokos->find($toko_id)->nama_toko : 'Semua Toko' }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Cabang</th>
                <th>Kode Deposit</th>
                <th>Nama Pelanggan</th>
                <th>No HP</th>
                <th>Alamat</th>
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
                    <td>{{ $deposit->pemesananproduk->telp ?? 'Tidak Ada No HP' }}</td>
                    <td>{{ $deposit->pemesananproduk->alamat ?? 'Tidak Ada Alamat' }}</td>
                    <td>{{ 'Rp ' . number_format($deposit->dp_pemesanan, 0, ',', '.') }}</td>
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
</body>
</html>
