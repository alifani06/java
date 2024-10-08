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
            font-size: 10px;
        }
        th {
            background-color: white;
        }
        .text-center {
            text-align: center;
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
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
        }
        .admin-info {
            text-align: right;
            margin-top: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>


    <div class="header">
        <h1 class="title">JAVA BAKERY</h1>
        <p class="title1">Cabang: {{ strtoupper($branchName) }}</p>
        <div class="divider"></div>
    
        @if ($status_pelunasan == 'diambil')
            <h1 class="title2">LAPORAN PENGAMBILAN DEPOSIT</h1>
        @else
            <h1 class="title2">LAPORAN DEPOSIT</h1>
        @endif

        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
            $formattedStartDate = $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Tidak ada';
            $formattedEndDate = $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Tidak ada';
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        <p class="period">
            @if ($startDate && $endDate)
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }}
            @else
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan.
            @endif
        </p>
    
        <p class="period right-align" style="font-size: 10px; position: absolute; top: 0; right: 0; margin: 10px;">
            {{ $currentDateTime }}
        </p>
    </div>
  
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                {{-- <th>Cabang</th> --}}
                <th>Kode Deposit</th>
                @if ($status_pelunasan == 'diambil')
                <th>Tanggal Diambil</th>
                @endif
                <th>Nama Pelanggan</th>
                <th>Metode Bayar</th>
                <th>Fee Deposit</th>
                <th>Nominal</th>
              
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inquery as $deposit)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    {{-- <td>{{ $deposit->pemesananproduk->toko->nama_toko ?? 'Tidak Ada Toko' }}</td> --}}
                    <td>{{ $deposit->kode_dppemesanan }}</td>
                    @if ($status_pelunasan == 'diambil')
                    <td>{{ $deposit->created_at ? $deposit->created_at->format('d-m-Y') : '-' }}</td>
                    @endif
                    <td>{{ $deposit->pemesananproduk->nama_pelanggan ?? 'Tidak Ada Nama' }}</td>
                    <td>
                        @if($deposit->pemesananproduk->metodePembayaran)
                            {{ $deposit->pemesananproduk->metodePembayaran->nama_metode }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{ is_numeric($deposit->pemesananproduk->total_fee) ? number_format((float)$deposit->pemesananproduk->total_fee, 0, ',', '.') : '-' }}
                    </td>
                    <td style="text-align: right">
                        {{ is_numeric($deposit->dp_pemesanan) ? number_format((float)$deposit->dp_pemesanan, 0, ',', '.') : '-' }}
                    </td>
                    <td>
                        @if($deposit->pelunasan)
                            <span>Diambil</span>
                        @else
                            <span>Belum Diambil</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
   <!-- Tampilkan Total Deposit, Fee Deposit, dan Sub Total dengan format yang benar -->
   <table style="width: 60%; margin-left: auto; margin-right: 0; background-color: rgb(248, 248, 6);" border="0">
       <tbody>
           <tr style="border: none;">
               <td style="text-align: right; border: none;">Total Deposit :</td>
               <td style="text-align: right">{{ number_format($totalDeposit, 0, ',', '.') }}</td>
           </tr>
           <tr style="border: none;">
               <td style="text-align: right; width: 60%; border: none;">Fee Deposit :</td>
               <td style="text-align: right">{{ number_format($totalFee, 0, ',', '.') }}</td>
           </tr>
           <tr style="border-top: 2px solid black;">
               <td style="text-align: right; border: none;">Sub Total :</td>
               <td style="text-align: right">{{ 'Rp ' . number_format($subTotal, 0, ',', '.') }}</td>
           </tr>
       </tbody>
   </table>
   <div class="admin-info">
    <p><strong>Admin</strong></p><br><br>
    <p>{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}</p>

</div>
</body>
</html>
