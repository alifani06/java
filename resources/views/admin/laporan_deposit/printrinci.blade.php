<!DOCTYPE html>
<html>
<head>
    <title>Laporan Deposit Rinci</title>
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
        }
        th {
            background-color: #f2f2f2;
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
            margin-bottom: 30px;
        }

        table.no-border {
            border-collapse: collapse;
            width: 100%;
        }

        table.no-border, 
        table.no-border tr, 
        table.no-border td {
            border: none !important;
        }

        /* Tabel untuk total fee penjualan dan grand total */
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

        .no-border {
        border: none;
    }
    </style>
</head>
<body>
   
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 5px;">LAPORAN DEPOSIT RINCI</h1>
    </div>
    
    <div class="text" style="text-align: center;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            // Gunakan variabel yang dikirim dari controller
            $formattedStartDate = isset($formattedStartDate) ? \Carbon\Carbon::parse($formattedStartDate)->translatedFormat('d F Y') : null;
            $formattedEndDate = isset($formattedEndDate) ? \Carbon\Carbon::parse($formattedEndDate)->translatedFormat('d F Y') : null;
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($formattedStartDate && $formattedEndDate)
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
    
    
    

    @php 
        $grandTotal = 0;
        $grandTotalFee = 0;
        $globalCounter = 1;
    @endphp

    @foreach ($inquery->groupBy('kode_dppemesanan') as $kodeDppemesanan => $items)
        <div class="table-container">
            @php
                $firstItem = $items->first();
                $formattedDate = \Carbon\Carbon::parse($firstItem->pemesananproduk->first()->tanggal_pemesanan)->format('d/m/Y');
                $formatambil = \Carbon\Carbon::parse($firstItem->pemesananproduk->first()->tanggal_kirim)->format('d/m/Y');
            @endphp

        <table class="no-border mb-1">
        
                    <tr>
                        <td class="text-left"><strong>Nama Pelanggan</strong></td>
                        <td>: {{ $firstItem->pemesananproduk->nama_pelanggan }}</td>
                        <td class="text-right" style="color: white"><strong>Alamat</strong></td>
                        <td class="text-right"><strong>Alamat</strong></td>
                        <td>: {{ $firstItem->pemesananproduk->alamat }}</td>
                    </tr>
                    <tr>
                        <td class="text-left"><strong>No Telepon</strong></td>
                        <td>: 0{{ $firstItem->pemesananproduk->telp }}</td>
                        <td class="text-right" style="color: white"><strong>ID Pelanggan</strong></td>
                        <td class="text-right"><strong>ID Pelanggan</strong></td>
                        <td>: {{ $firstItem->pemesananproduk->kode_pelanggan }}</td>
                    </tr>
        
        </table>


            <table>
                <tr>
                    <td colspan="3" class="text-left"><strong>No Deposit : {{ $kodeDppemesanan }}</strong></td>
                    <td colspan="3" class="text-right"><strong>Tanggal Deposit : {{ $formattedDate}}</strong></td>
                    <td colspan="2" class="text-right"><strong>Tanggal Ambil : {{ $formatambil}}</strong></td>
                </tr>              
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Divisi</th>
                        <th>Kode Produk</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subTotal = 0; @endphp
                    @foreach ($items as $item)
                        @foreach ($item->detailpemesananproduk as $detail)
                            @php
                                $totalForDetail = $detail->jumlah * $detail->harga;
                                $subTotal += $totalForDetail;
                                $grandTotal += $totalForDetail; // Tambahkan ke grand total
                                $diskon = floatval($detail->diskon);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $globalCounter++ }}</td>
                                <td>
                                    @if ($item->detailpemesananproduk->isNotEmpty())
                                        {{ $item->detailpemesananproduk->pluck('produk.klasifikasi.nama')->implode(', ') }}
                                    @else
                                        tidak ada
                                    @endif
                                </td>  
                                <td>{{ $detail->kode_lama }}</td>
                                <td>{{ $detail->nama_produk }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->diskon}}</td>
                                <td>{{number_format($totalForDetail, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td colspan="7" class="text-right"><strong>Sub Total</strong></td>
                        <td>{{number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                    
                    <tr>
                        <td colspan="7" class="text-right"><strong>Diskon</strong></td>
                        <td>{{number_format($detail->diskon, 0, ',', '.') }}</td>
                    </tr>
                 
                    <tr>
                        <td colspan="7" class="text-right"><strong>DP (Min.50%)</strong></td>
                        <td>{{number_format($item->dp_pemesanan, 0, ',', '.') }}</td>
                    </tr>
                 
         
                </tbody>
            </table>

            
        </div>
    @endforeach

 <!-- Tabel total penjualan fee dan grand total -->
 <table style="width: 50%; margin-left: auto; margin-right: 0; background-color: yellow">
    <tbody>
        <tr>
            <td style="text-align: right;  width: 70%;">Total Fee Penjualan</td>
            <td style="text-align: left; font-weight: bold; width: 30%;">{{ 'Rp. ' .  number_format($grandTotalFee, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="text-align: right; ">Grand Total</td>
            <td style="text-align: left; font-weight: bold;">{{ 'Rp. ' .  number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

</body>
</html>
