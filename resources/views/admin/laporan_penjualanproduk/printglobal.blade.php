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
            margin-top: 3px ;
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

        /* CSS untuk nomor halaman */
        @page {
            margin: 20mm;
            @bottom-right {
                content: "Halaman " counter(page);
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    {{-- <div class="header">
        <div>
            <span class="title">PT JAVA BAKERY FACTORY</span>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <hr class="divider">
        <hr class="divider">
    </div> --}}
    <div class="container">
        <h1 style="text-align: center; margin-bottom: 5px;">LAPORAN PENJUALAN PRODUK</h1>
        <p style="text-align: center; font-size: 28px; font-weight: bold; margin-top: 0;">GLOBAL</p>
    </div>
    
    {{-- <div class="text">
        @if ($startDate && $endDate)
            <p>Periode: {{ $startDate }} s/d {{ $endDate }} &nbsp;&nbsp;&nbsp; Cabang: {{ $branchName }}</p>
        @else
            <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan. &nbsp;&nbsp;&nbsp; Cabang: {{ $branchName }}</p>
        @endif
    </div> --}}
    <div class="text">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($startDate && $endDate)
            <p>
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }} &nbsp;&nbsp;&nbsp; Cabang: {{ $branchName }}
                <span style="float: right;">{{ $currentDateTime }}</span>
            </p>
        @else
            <p>
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan. &nbsp;&nbsp;&nbsp; Cabang: {{ $branchName }}
                <span style="float: right;">{{ $currentDateTime }}</span>
            </p>
        @endif
    </div>
    
    
    </div>

    <!-- Tabel utama -->
    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode penjualan</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th>Kode Deposit</th>
                <th>Nominal</th>
                <th>Metode Pembayaran</th>
                <th>Fee Penjualan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody style="font-size: 10px;">
            @php
                $grandTotal = 0;
                $grandTotalFee = 0;
                $totalDeposit = 0;
            @endphp
            @foreach ($inquery as $item)
                @php
                    $grandTotal += $item->sub_total;
    
                    // Menghapus semua karakter kecuali angka dari fee
                    $total_fee = preg_replace('/[^\d]/', '', $item->total_fee);
                    $total_fee = (float) $total_fee;
                    $grandTotalFee += $total_fee;
    
                    // Menambahkan deposit jika ada
                    $deposit = $item->dppemesanan->dp_pemesanan ?? 0;
                    $totalDeposit += $deposit;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_penjualan }}</td>
                    <td>{{ $item->kasir ?? '-' }}</td>
                    <td>
                        @if($item->kode_pelanggan)
                            {{ $item->kode_pelanggan }} / {{ $item->nama_pelanggan }}
                        @else
                            Non Member
                        @endif
                    </td>
                    <td>{{ $item->dppemesanan->kode_dppemesanan ?? '-' }}</td>
                    <td>
                        {{ $deposit > 0 ? 'Rp. ' . number_format($deposit, 0, ',', '.') : '-' }}
                    </td>
                    <td>{{ $item->metodepembayaran->nama_metode ?? 'Tunai' }}</td>
                    <td>
                        @if ($total_fee == 0)
                            -
                        @else
                            {{ 'Rp. ' . number_format($total_fee, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>{{ 'Rp. ' .  number_format($item->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Tabel total penjualan fee, total deposit, dan grand total -->
    <table style="width: 60%; margin-left: auto; margin-right: 0; background-color: yellow">
        <tbody>
            <tr>
                <td style="text-align: right;  width: 60%;">Total Fee Penjualan</td>
                <td style="text-align: left; font-weight: bold; width: 40%;">{{ 'Rp. ' .  number_format($grandTotalFee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-align: right;">Total Penjualan</td>
                <td style="text-align: left; font-weight: bold;">{{ 'Rp. ' .  number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-align: right;">Total Deposit</td>
                <td style="text-align: left; font-weight: bold;">{{ 'Rp. ' .  number_format($totalDeposit, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="text-align: right;">Total </td>
                <td style="text-align: left; font-weight: bold;">{{ 'Rp. ' .  number_format($grandTotal - $totalDeposit, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    
    
    
    
</body>
</html>
