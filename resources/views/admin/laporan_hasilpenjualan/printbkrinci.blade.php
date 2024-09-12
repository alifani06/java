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

    @foreach ($finalResults as $kode_penjualan => $result)
    <div class="table-container">
        <h3>No Penjualan: {{ $kode_penjualan }}</h3>
        <table>
            <tr>
                <td colspan="2" class="text-left"><strong>Kasir : {{ $result['penjualan']->kasir }}</strong></td>
                <td colspan="2" class="text-left">
                    <strong>Pelanggan : {{ $result['penjualan']->kategori == 'member' ? 'Member' : 'Non Member' }}</strong>
                </td>
                                <td colspan="2" class="text-left">
                    <strong>Tanggal : {{ \Carbon\Carbon::parse($result['penjualan']->tanggal_penjualan)->translatedFormat('d F Y H:i') }}</strong>
                </td>
            </tr> 
            <thead>
                <tr>
                    {{-- <th>No</th> --}}
                    {{-- <th>Tanggal Penjualan</th> --}}
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Diskon</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($result['detailProduk'] as $produk)
                    <tr>
                        {{-- <td class="text-center">{{ $no++ }}</td> --}}
                        {{-- <td>{{ \Carbon\Carbon::parse($produk['tanggal_penjualan'])->translatedFormat('d F Y') }}</td> --}}
                        <td>{{ $produk['kode_lama'] }}</td>
                        <td>{{ $produk['nama_produk'] }}</td>
                        <td style="text-align: right">{{ number_format($produk['harga'], 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ $produk['jumlah'] }}</td>
                        <td style="text-align: right">{{ number_format($produk['diskon'], 0, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($produk['total'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endforeach
    
</body>
</html>
