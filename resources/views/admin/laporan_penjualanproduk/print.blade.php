<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Produk</title>
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
            padding: 8px;
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

    </style>
</head>
<body>
   
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 5px;">LAPORAN PENJUALAN PRODUK GLOBAL</h1>
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
    
    @php 
        $grandTotal = 0;
        $grandTotalFee = 0;
        $globalCounter = 1;
    @endphp

    @foreach ($inquery->groupBy('kode_penjualan') as $kodePenjualan => $items)
        <div class="table-container">
            @php
                $firstItem = $items->first();
                $formattedDate = \Carbon\Carbon::parse($firstItem->tanggal_penjualan)->format('d/m/Y H:i');
            @endphp

            <table>
                <tr>
                    <td colspan="3" class="text-left"><strong>Kode Penjualan : {{ $kodePenjualan }}</strong></td>
                    <td colspan="3" class="text-right"><strong>Tanggal : {{ $formattedDate}}</strong></td>
                    <td colspan="2" class="text-right"><strong>{{ $items->first()->toko->nama_toko }}</strong></td>
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
                        @foreach ($item->detailpenjualanproduk as $detail)
                            @php
                                $totalForDetail = $detail->jumlah * $detail->harga;
                                $subTotal += $totalForDetail;
                                $diskon = floatval($detail->diskon);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $globalCounter++ }}</td>
                                <td>
                                    @if ($item->detailpenjualanproduk->isNotEmpty())
                                        {{ $item->detailpenjualanproduk->pluck('produk.klasifikasi.nama')->implode(', ') }}
                                    @else
                                        tidak ada
                                    @endif
                                </td>  
                                <td>{{ $detail->kode_produk }}</td>
                                <td>{{ $detail->nama_produk }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                <td>{{ $detail->diskon}}</td>
                                <td>{{'Rp. ' .  number_format($totalForDetail, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td colspan="7" class="text-right"><strong>Sub Total</strong></td>
                        <td>{{'Rp. ' .  number_format($subTotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        @if($item->metode_id !== null)
                        <td colspan="7" class="text-right"><strong> Fee {{$item->metodepembayaran->fee}}%</strong></td>
                        <td>
                            @php
                                $total_fee = preg_replace('/[^\d]/', '', $item->total_fee);
                                $total_fee = (float) $total_fee;
                                $grandTotalFee += $total_fee; // Tambahkan ke grand total fee
                            @endphp
                            {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
                        </td>
                    @endif
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right"><strong>Metode Pembayaran</strong></td>
                        <td>{{ $item->metodePembayaran ? $item->metodePembayaran->nama_metode : 'Tunai' }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right"><strong>Total Bayar</strong></td>
                        <td>
                            @php
                                $totalBayar = $item->sub_total;
                                $grandTotal += $totalBayar; // Tambahkan total bayar ke grand total
                            @endphp
                            {{'Rp. ' .  number_format($totalBayar, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if($item->metode_id == Null)
                    <tr>
                        <td colspan="7" class="text-right"><strong>Uang Bayar</strong></td>
                        <td>{{'Rp. ' .  number_format($item->bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="7" class="text-right"><strong>Kembali</strong></td>
                        <td>{{'Rp. ' .  number_format($item->kembali, 0, ',', '.') }}</td>
                    </tr>
                  @endif
                </tbody>
            </table>
        </div>
    @endforeach

    <!-- Tabel total penjualan fee dan grand total -->
    <table style="width: 50%; margin-left: auto; margin-right: 0; background-color: yellow">
        <tbody>
            <tr style="border: none;">
                <td style="text-align: right; border: none;">Total Penjualan :</td>
                <td style="text-align: right; font-weight: bold; border: none;">{{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
            <tr style="border: none;">
                <td style="text-align: right; width: 60%; border: none;">Fee Penjualan :</td>
                <td style="text-align: right; font-weight: bold; width: 40%; border: none;">{{ number_format($grandTotalFee, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 2px solid black;">
                <td style="text-align: right; border: none;">Grand Total Penjualan :</td>
                <td style="text-align: right; font-weight: bold; border: none;">{{'Rp. ' .   number_format($grandTotal - $grandTotalFee, 0, ',', '.') }}</td>
            </tr>
          
        </tbody>
    </table>
</body>
</html>
