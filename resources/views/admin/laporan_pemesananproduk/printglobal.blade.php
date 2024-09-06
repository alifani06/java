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
            text-align: center;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .header .period {
            font-size: 14px;
            margin-top: 10px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">LAPORAN PEMESANAN PRODUK GLOBAL</h1>

        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp

        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}<br>
            {{-- Tanggal Cetak: {{ $currentDateTime }} --}}
        </p>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $totalSubtotal = 0;
            @endphp
            @foreach ($groupedData as $klasifikasi => $items)
                @foreach ($items as $key => $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data['klasifikasi'] }}</td>
                        <td>{{ $data['kode_lama'] }}</td>
                        <td>{{ $data['nama_produk'] }}</td>
                        <td>{{ $data['benjaran'] }}</td>
                        <td>{{ $data['tegal'] }}</td>
                        <td>{{ $data['slawi'] }}</td>
                        <td>{{ $data['pemalang'] }}</td>
                        <td>{{ $data['bumiayu'] }}</td>
                        <td>{{ $data['cilacap'] }}</td>
                        <td>{{ $data['subtotal'] }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="10"><strong>Total</strong></td>
                <td><strong>{{ $totalSubtotal }}</strong></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
