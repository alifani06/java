<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemindahan Barang Jadi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px; /* Ukuran font lebih kecil untuk tabel */
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pemindahan Barang Jadi</h1>
        @if($tanggal_input && $tanggal_akhir)
            <p>Periode: {{ \Carbon\Carbon::parse($tanggal_input)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
        @elseif($tanggal_input)
            <p>Periode Mulai: {{ \Carbon\Carbon::parse($tanggal_input)->format('d/m/Y') }}</p>
        @elseif($tanggal_akhir)
            <p>Periode Hingga: {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}</p>
        @else
            <p>Periode: Hari Ini</p>
        @endif

        @php
        \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
        $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    </div>

    <p>
        <span style="float: right; font-style: italic; font-size: 10px;">{{ $currentDateTime }}</span>
    </p>
    

    @if($stokBarangJadi->isEmpty())
        <p>Data tidak ditemukan untuk filter yang diberikan.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Pemindahan</th>
                    <th>Divisi</th>
                    <th>Nama Produk</th>
                    <th>Dari</th>
                    <th>Ke</th>
                    <th>Jumlah</th>
                    {{-- <th>Tanggal Input</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach($stokBarangJadi->flatten() as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->kode_pemindahan }}</td>
                        <td>{{ $item->produk->klasifikasi->nama }}</td>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>{{ $item->toko->nama_toko }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->jumlah }}</td>
                        {{-- <td>{{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
