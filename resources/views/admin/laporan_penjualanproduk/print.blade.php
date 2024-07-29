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
    </style>

</head>
<body>
   
    <div class="container">
        <h1 style="text-align: center">LAPORAN PENJUALAN PRODUK</h1>
    </div>
    <div class="text">
        @if ($startDate && $endDate)
            <p><strong>Periode: {{ $startDate }} s/d {{ $endDate }}</strong></p>
        @else
            <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan.</p>
        @endif
    </div>

    @php $globalCounter = 1; @endphp

    @foreach ($inquery->groupBy('kode_penjualan') as $kodePenjualan => $items)
        <div class="table-container">
            @php
                $firstItem = $items->first();
                $formattedDate = \Carbon\Carbon::parse($firstItem->tanggal_penjualan)->format('d/m/Y H:i');
                $paymentMethod = $firstItem->payment_method ?? 'N/A'; 
                $totalPayment = $firstItem->total_payment ?? 0; 
                $amountPaid = $firstItem->amount_paid ?? 0; 
                $change = $amountPaid - $totalPayment; 
            @endphp

            @foreach ($items as $item)
                <table class="no-border" cellpadding="2" cellspacing="0" style="width: 100%; margin-bottom: 0px;">
                    <tr>
                        <td style="text-align: left; font-size: 12px; width: 20%;">Kode Pelanggan</td>
                        <td style="text-align: left; font-size: 12px; width: 30%;">
                            <span class="content2">: {{ $item->kode_pelanggan ?? '-' }}</span>
                        </td>
                        <td style="text-align: left; font-size: 12px; width: 20%;">Alamat</td>
                        <td style="text-align: left; font-size: 12px; width: 30%;">
                            <span class="content2">: {{ $item->alamat ?? '-' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left; font-size: 12px; width: 20%;">Nama</td>
                        <td style="text-align: left; font-size: 12px; width: 30%;">
                            <span class="content2">: {{ $item->nama_pelanggan ? $item->nama_pelanggan : 'Non Member' }}</span>
                        </td>
                        <td style="text-align: left; font-size: 12px; width: 20%;">Handphone</td>
                        <td style="text-align: left; font-size: 12px; width: 30%;">
                            <span class="content2">: {{ $item->telp ?? '-' }}</span>
                        </td>
                    </tr>  
                </table>
            @endforeach


            <table>
                <tr>
                    <td colspan="3" class="text-left"><strong>Kode Penjualan : {{ $kodePenjualan }}</strong></td>
                    <td colspan="3" class="text-right"><strong>Tanggal : {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</strong></td>
                    <td colspan="1" class="text-right"><strong>{{ $items->first()->toko->nama_toko }}</strong></td>
                </tr>              
                <thead>
                    <tr>
                        <th class="text-center">No</th>
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
                                // Convert diskon to float for proper formatting
                                $diskon = floatval($detail->diskon);
                            @endphp
                            <tr>
                                <td class="text-center">{{ $globalCounter++ }}</td>
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
                        <td colspan="6" class="text-right"><strong>Sub Total</strong></td>
                        <td>{{'Rp. ' .  number_format($subTotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        @if($item->metode_id !== null)
                        <td colspan="6" class="text-right"><strong> Fee {{$item->metodepembayaran->fee}}%</strong></td>
                        <td>
                            @php
                                // Menghapus semua karakter kecuali angka
                                $total_fee = preg_replace('/[^\d]/', '', $item->total_fee);
                                // Konversi ke tipe float
                                $total_fee = (float) $total_fee;
                            @endphp
                            {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
                        </td>
                    @endif
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Metode Pembayaran</strong></td>
                        <td>{{ $item->metodePembayaran ? $item->metodePembayaran->nama_metode : 'Tunai' }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total Bayar</strong></td>
                        <td>{{'Rp. ' .  number_format($item->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @if($item->metode_id == Null)
                    <tr>
                        <td colspan="6" class="text-right"><strong>Uang Bayar</strong></td>
                        <td>{{'Rp. ' .  number_format($item->bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right"><strong>Kembali</strong></td>
                        <td>{{'Rp. ' .  number_format($item->kembali, 0, ',', '.') }}</td>
                    </tr>
                  @endif
                </tbody>
            </table>
        </div>
    @endforeach

    {{-- <div class="text">
        <h3>Grand Total</h3>
        <p>{{ number_format($inquery->sum('sub_total'), 0, ',', '.') }}</p>
    </div> --}}
</body>
</html>
