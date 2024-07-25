<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pemesanan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .text {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .signature {
            margin-top: 40px;
            text-align: center;
        }
        .signature table {
            width: 100%;
        }
        .signature .separator {
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LAPORAN PEMESANAN PRODUK</h1>
    </div>
    <div class="text">
        @if ($startDate && $endDate)
            <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
        @else
            <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan.</p>
        @endif
    </div>

    @if ($toko_id == '0')
        <!-- Tabel Global -->
        <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Divisi</th>
                    <th>Tanggal Kirim/Ambil</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Benjaran</th>
                    <th>Tegal</th>
                    <th>Slawi</th>
                    <th>Pemalang</th>
                    <th>Bumiayu</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $totalSubtotal = 0;
                @endphp
                @foreach ($groupedData as $detail)
                    @php
                        $subtotal = $detail['benjaran'] + $detail['tegal'] + $detail['slawi'] + $detail['pemalang'] + $detail['bumiayu'];
                        $totalSubtotal += $subtotal;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $detail['klasifikasi'] }}</td>
                        <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                        <td>{{ $detail['kode_produk'] }}</td>
                        <td>{{ $detail['nama_produk'] }}</td>
                        <td>{{ $detail['benjaran'] }}</td>
                        <td>{{ $detail['tegal'] }}</td>
                        <td>{{ $detail['slawi'] }}</td>
                        <td>{{ $detail['pemalang'] }}</td>
                        <td>{{ $detail['bumiayu'] }}</td>
                        <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif ($toko_id == '1')
        <!-- Tabel Toko Benjaran -->
        <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Divisi</th>
                    <th>Kode Pemesanan</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Toko Benjaran</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                    $currentKodeProduk = null;
                    $totalPerProduk = 0;
                @endphp
                @foreach ($groupedData as $detail)
                    @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                        <tr>
                            <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                            <td>{{ $totalPerProduk }}</td>
                            <td colspan="2"></td>
                        </tr>
                        @php
                            $totalPerProduk = 0;
                        @endphp
                    @endif
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                        <td>{{ $detail['klasifikasi'] }}</td>
                        <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                        <td>{{ $detail['kode_produk'] }}</td>
                        <td>{{ $detail['nama_produk'] }}</td>
                        <td>{{ $detail['benjaran'] }}</td>
                        <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                    </tr>
                    @php
                        $currentKodeProduk = $detail['kode_produk'];
                        $totalPerProduk += $detail['benjaran'];
                    @endphp
                @endforeach
                @if ($currentKodeProduk)
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    @if ($toko_id == '2')
    <!-- Tabel Toko Tegal -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Tegal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['tegal'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['tegal'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '3')
    <!-- Tabel Toko Slawi -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Slawi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['slawi'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['slawi'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '4')
    <!-- Tabel Toko Bumiayu -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Bumiayu</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['bumiayu'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['bumiayu'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif

    @if ($toko_id == '5')
    <!-- Tabel Toko Pemalang -->
    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Tanggal Pemesanan</th>
                <th>Divisi</th>
                <th>Kode Pemesanan</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Toko Pemalang</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $totalPerProduk = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ $totalPerProduk }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @php
                        $totalPerProduk = 0;
                    @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail['tanggal_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_pemesanan'] ?? '-' }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['pemalang'] }}</td>
                    <td>{{ $detail['catatanproduk'] ?? '-' }}</td>
                </tr>
                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $totalPerProduk += $detail['pemalang'];
                @endphp
            @endforeach
            @if ($currentKodeProduk)
                <tr>
                    <td colspan="6" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ $totalPerProduk }}</td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
    @endif


    
</body>
</html>
