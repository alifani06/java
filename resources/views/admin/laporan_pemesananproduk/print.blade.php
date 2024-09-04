<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemesanan Produk</title>
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
            padding: 4px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: white;
        }
        .header {
            text-align: center;
            margin-top: 3px;
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
        .table-container {
            margin-bottom: 10px;
        }
        .summary-table {
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
            border: none;
        }
        .summary-table td {
            padding: 8px;
            font-size: 12px;
            border: none !important;
        }
        .summary-table .text-right {
            text-align: right;
        }
        .summary-table .text-left {
            text-align: left;
        }
        .summary-table .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN PEMESANAN PRODUK</h1>
    </div>

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

    @foreach ($groupedByKlasifikasi as $klasifikasi => $items)
    <div class="table-container">
        <h2>{{ $klasifikasi }}</h2>
        <table>
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Kode Pemesanan</th>
                    <th>Kategori</th>
                    <th>Produk</th>
                    <th>Catatan</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalJumlah = 0; // Inisialisasi total jumlah
                @endphp
                @foreach ($items as $item)
                    @foreach ($item->detailpemesananproduk as $detail)
                        @php
                            $subKlasifikasi = $detail->produk->klasifikasi->subklasifikasi->where('klasifikasi_id', $detail->produk->klasifikasi->id)->first(); // Access subklasifikasi
                            $grandTotalJumlah += $detail->jumlah; // Hitung total jumlah produk
                        @endphp
                        <tr>
                            <td class="text-center">{{ $loop->parent->iteration }}</td>
                            <td>{{ $item->kode_pemesanan }}</td>
                            <td>{{ $subKlasifikasi->nama ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td>{{ $item->catatan }}</td>
                            <td style="text-align: right">
                                {{ number_format($detail->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="5" class="text-right"><strong>Total Jumlah</strong></td>
                    <td style="text-align: right">{{ number_format($grandTotalJumlah, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endforeach



</body>
</html>
