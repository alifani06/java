{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Estimasi Produksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .logo img {
            width: 150px;
            height: 77px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .change-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
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
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div>
            <span class="title">PT JAVA BAKERY FACTORY</span><br><br>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span><br>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <hr class="divider">
    </div>
    <div class="change-header">LAPORAN ESTIMASI PRODUKSI</div>

    <!-- Tabel Permintaan -->
    @if($tableType == '' || $tableType == 'permintaan')

    <h3>Permintaan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaanProduks as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                        @endphp
                        <td>{{ $jumlah }}</td>
                    @endforeach
                    <td>{{ $totalJumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Tabel Pemesanan -->
    @if($tableType == '' || $tableType == 'pemesanan')
    <h3>Pemesanan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                        @endphp
                        <td>{{ $jumlah }}</td>
                    @endforeach
                    <td>{{ $totalJumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif


</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Estimasi Produksi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .logo img {
            width: 150px;
            height: 77px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .change-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
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
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div>
            <span class="title">PT JAVA BAKERY FACTORY</span><br><br>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span><br>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <hr class="divider">
    </div>
    <div class="change-header">LAPORAN ESTIMASI PRODUKSI</div>

    @if($tanggalAwal && $tanggalAkhir)
    <div class="date-range">
        Periode Tanggal: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}
    </div>
    @endif

    <!-- Tabel Permintaan -->
    @if($tableType == 'permintaan')
    <h3>Permintaan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaanProduks as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                        @endphp
                        <td>{{ $jumlah }}</td>
                    @endforeach
                    <td>{{ $totalJumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <!-- Tabel Pemesanan -->
    @if($tableType == 'pemesanan')
    <h3>Pemesanan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Produk</th>
                <th>Banjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Cilacap</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalJumlah = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $jumlah = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('jumlah');
                            $totalJumlah += $jumlah;
                        @endphp
                        <td>{{ $jumlah }}</td>
                    @endforeach
                    <td>{{ $totalJumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif


    @if($tableType == 'all')
    <h3>Gabungan Permintaan dan Pemesanan Produk</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Produk</th>
                @foreach(['Banjaran', 'Tegal', 'Slawi', 'Pemalang', 'Bumiayu', 'Cilacap'] as $store)
                    <th colspan="2">{{ $store }}</th>
                @endforeach
                <th>Total</th>
            </tr>
            <tr>
                <th colspan="3"></th>
                @foreach(['Banjaran', 'Tegal', 'Slawi', 'Pemalang', 'Bumiayu', 'Cilacap'] as $store)
                    <th>Pes</th>
                    <th>Stok</th>
                @endforeach
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($combinedData as $produkId => $tokoDetails)
                @php
                    $produk = $tokoDetails->first()['produk'];
                    $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
                    $totalPesanan = 0;
                    $totalPermintaan = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $klasifikasi }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                        @php
                            $pesanan = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('pesanan');

                            $permintaan = $tokoDetails->filter(function ($detail) use ($store) {
                                return strtolower($detail['toko']->nama_toko) === $store;
                            })->sum('permintaan');

                            $totalPesanan += $pesanan;
                            $totalPermintaan += $permintaan;
                        @endphp
                        <td>{{ $pesanan }}</td>
                        <td>{{ $permintaan }}</td>
                    @endforeach
                    <td>{{ $totalPesanan + $totalPermintaan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif



<!-- Tabel Gabungan (All Data) -->
{{-- @if($tableType == 'all')
<h3>Gabungan Permintaan dan Pemesanan Produk</h3>
<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Divisi</th>
            <th rowspan="2">Produk</th>
            @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                <th colspan="2">{{ ucfirst($store) }}</th>
            @endforeach
            <th rowspan="2">Total</th>
        </tr>
        <tr>
            @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                <th>Pes</th>
                <th>Stok</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($combinedData as $produkId => $tokoDetails)
        @php
        $tokoDetails = collect($tokoDetails); // Ubah array menjadi koleksi Laravel
        $produk = $tokoDetails->first()['produk'];
        $klasifikasi = $produk->klasifikasi->nama ?? 'Tidak Ditemukan';
        $totalJumlah = 0;
    @endphp
    
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $klasifikasi }}</td>
                <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                @foreach(['banjaran', 'tegal', 'slawi', 'pemalang', 'bumiayu', 'cilacap'] as $store)
                @php
                $jumlahPemesanan = $tokoDetails->filter(function ($detail) use ($store) {
                    return isset($detail['toko']) && strtolower($detail['toko']->nama_toko) === $store && isset($detail['pemesanan']);
                })->sum('pemesanan');
            
                $jumlahPermintaan = $tokoDetails->filter(function ($detail) use ($store) {
                    return isset($detail['toko']) && strtolower($detail['toko']->nama_toko) === $store && isset($detail['permintaan']);
                })->sum('permintaan');
            
                $totalJumlah += ($jumlahPemesanan + $jumlahPermintaan);
            @endphp
            
                    <td>{{ $jumlahPemesanan }}</td>
                    <td>{{ $jumlahPermintaan }}</td>
                @endforeach
                <td>{{ $totalJumlah }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif --}}




</body>

</html>