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
            background-color: #f2f2f2;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 20px;
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
        .right-align {
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">LAPORAN PEMESANAN PRODUK GLOBAL</h1>

        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $formattedStartDate = $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');
            // Variabel untuk cabang yang dipilih
            $selectedCabang = $cabang ?? 'Semua Toko';
        @endphp

        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 12px;">
            {{ $currentDateTime }}
        </p>
    </div>

    {{-- <div class="divider"></div> --}}

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                @foreach ($tokoFieldMap as $tokoField)
                    <th>{{ ucfirst($tokoField) }}</th>
                @endforeach
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1; 
                $totalPerToko = array_fill_keys($tokoFieldMap, 0); 
            @endphp
            @foreach ($groupedData as $klasifikasi => $items)
                @foreach ($items as $data)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $data['klasifikasi'] }}</td>
                        <td>{{ $data['kode_lama'] }}</td>
                        <td>{{ $data['nama_produk'] }}</td>
                        @foreach ($tokoFieldMap as $tokoField)
                            <td style="text-align: right">{{ $data[$tokoField] }}</td>
                            @php
                                $totalPerToko[$tokoField] += $data[$tokoField];
                            @endphp
                        @endforeach
                        <td style="text-align: right">{{ $data['subtotal'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                @foreach ($tokoFieldMap as $tokoField)
                    <td style="text-align: right"><strong>{{ $totalPerToko[$tokoField] }}</strong></td>
                @endforeach
                <td style="text-align: right"><strong>{{ $totalSubtotal }}</strong></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
