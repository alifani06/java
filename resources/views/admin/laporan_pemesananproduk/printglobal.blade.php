<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemesanan Global</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
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
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header .title1 {
            margin-top: 5px;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .header .title2 {
            font-weight: bold;
            font-size: 18px;
        }
        .header .period {
            font-size: 12px;
            margin-top: 10px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 5px;
            margin-bottom: 5px;        }
        .right-align {
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">JAVA BAKERY</h1>
        <p class="title1">Cabang: {{ strtoupper($selectedCabang) }}</p>
        <div class="divider"></div>

        <h1 class="title2">LAPORAN PEMESANAN PRODUK GLOBAL</h1>
    
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
            $formattedStartDate = $startDate ? Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');
            // Variabel untuk cabang yang dipilih
            $selectedCabang = $cabang ?? 'Semua Toko'; // Default 'Semua Toko' jika cabang tidak dipilih
        @endphp
    
        <p class="period">
            Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
        </p>
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
        
    </div>
    

    {{-- <div class="divider"></div> --}}

   {{-- <table>
    <thead>
        <tr>
            <th>No</th>
            <th>Divisi</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            @foreach ($tokoFieldMap as $tokoField)
                <th>{{ ucfirst($tokoField) }}</th>
            @endforeach
            @if ($toko_id == '0') <!-- Menampilkan kolom Subtotal hanya jika semua toko dipilih -->
                <th>Subtotal</th>
            @endif
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
                    @if ($toko_id == '0') <!-- Menampilkan subtotal jika semua toko dipilih -->
                        <td style="text-align: right">{{ $data['subtotal'] }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </tbody>
    @if ($toko_id == '0') <!-- Menampilkan total subtotal jika semua toko dipilih -->
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;"><strong>Total:</strong></td>
                @foreach ($tokoFieldMap as $tokoField)
                    <td style="text-align: right"><strong>{{ $totalPerToko[$tokoField] }}</strong></td>
                @endforeach
                <td style="text-align: right"><strong>{{ $totalSubtotal }}</strong></td>
            </tr>
        </tfoot>
    @endif
</table> --}}
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
            @if ($toko_id == '0')
                <th>Subtotal</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php 
            $no = 1; 
            $totalPerToko = array_fill_keys(array_keys($tokoFieldMap), 0);
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
                    @if ($toko_id == '0')
                        <td style="text-align: right">{{ $data['subtotal'] }}</td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total</th>
            @foreach ($tokoFieldMap as $tokoField)
                <th style="text-align: right">{{ $totalPerToko[$tokoField] }}</th>
            @endforeach
            @if ($toko_id == '0')
                <th style="text-align: right">{{ $totalSubtotal }}</th>
            @endif
        </tr>
    </tfoot>
</table>
</body>
</html>
