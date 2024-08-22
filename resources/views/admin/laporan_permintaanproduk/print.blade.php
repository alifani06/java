<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permintaan Produk</title>
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
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .divider {
            border: 0.5px solid;
            margin-top: 20px;
            margin-bottom: 20px;
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
            {{-- @if(isset($tokoData) && $tokoData->isNotEmpty())
                <span class="toko-name">Cabang: {{ $tokoData->first()->nama_toko }}</span><br>
                <span class="address">{{ $tokoData->first()->alamat }}</span><br>
            @endif --}}
        </div>
        <hr class="divider">
    </div>
    <div class="change-header">LAPORAN PERMINTAAN PRODUK</div>
    <div class="text" style="margin-bottom: 1px;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($startDate && $endDate)
            <p>
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }} &nbsp;&nbsp;&nbsp;
                <span style="float: right; font-style: italic">{{ $currentDateTime }}</span>
            </p>
        @else
            <p>
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan. &nbsp;&nbsp;&nbsp;
                <span style="float: right;">{{ $currentDateTime }}</span>
            </p>
        @endif
    </div>
    <table style="font-size: 9px;">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Permintaan</th>
                <th>Divisi</th>
                <th>Kategori</th>
                <th>Produk</th>
                @foreach ($tokoData as $toko)
                    <th>{{ $toko->nama_toko }}</th>
                @endforeach
                <th>Total</th> <!-- Tambahkan kolom untuk total per produk -->
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $produkData = [];
                $filterKlasifikasiId = $klasifikasi_id; // Ambil ID klasifikasi yang difilter dari controller
            @endphp
            @foreach ($permintaanProduk as $permintaan)
                @foreach ($permintaan->detailpermintaanproduks as $detail)
                    @php
                        $produkKey = $detail->produk->kode_produk . '-' . $detail->produk->klasifikasi_id . '-' . ($detail->produk->klasifikasi->subklasifikasi->first()->id ?? '');
    
                        // Hanya tambahkan data jika klasifikasi_id cocok dengan filter yang dipilih
                        if (!$filterKlasifikasiId || $detail->produk->klasifikasi_id == $filterKlasifikasiId) {
                            if (!isset($produkData[$produkKey])) {
                                $produkData[$produkKey] = [
                                    'kode_permintaan' => $permintaan->kode_permintaan,
                                    'klasifikasi' => $detail->produk->klasifikasi->nama,
                                    'subklasifikasi' => $detail->produk->klasifikasi->subklasifikasi->first()->nama ?? '-',
                                    'nama_produk' => $detail->produk->nama_produk,
                                    'jumlah' => array_fill_keys($tokoData->pluck('nama_toko')->toArray(), 0),
                                    'total' => 0, // Tambahkan field untuk total per produk
                                ];
                            }
                            $produkData[$produkKey]['jumlah'][$detail->toko->nama_toko] += $detail->jumlah;
                            $produkData[$produkKey]['total'] += $detail->jumlah; // Update total per produk
                        }
                    @endphp
                @endforeach
            @endforeach
        
            @foreach ($produkData as $produkKey => $data)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $data['kode_permintaan'] }}</td>
                    <td>{{ $data['klasifikasi'] }}</td>
                    <td>{{ $data['subklasifikasi'] }}</td>
                    <td>{{ $data['nama_produk'] }}</td>
                    @foreach ($tokoData as $toko)
                        <td>{{ $data['jumlah'][$toko->nama_toko] }}</td>
                    @endforeach
                    <td>{{ $data['total'] }}</td> <!-- Tampilkan total per produk -->
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <table style="font-size: 9px;">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Divisi</th>
                <th>Kategori</th>
                <th>Produk</th>
                @foreach ($tokoData as $toko)
                    <th colspan="2">{{ $toko->nama_toko }}</th>
                @endforeach
                <th>Total</th> <!-- Tambahkan kolom untuk total per produk -->
            </tr>
            <tr>
                @foreach ($tokoData as $toko)
                    <th>Stok</th>
                    <th>Pesanan</th>
                @endforeach
                <th></th> <!-- Kosongkan untuk menyesuaikan dengan total -->
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $produkData = [];
                $filterKlasifikasiId = $klasifikasi_id; // Ambil ID klasifikasi yang difilter dari controller
            @endphp
            @foreach ($permintaanProduk as $permintaan)
                @foreach ($permintaan->detailpermintaanproduks as $detail)
                    @php
                        $produkKey = $detail->produk->kode_produk . '-' . $detail->produk->klasifikasi_id . '-' . ($detail->produk->klasifikasi->subklasifikasi->first()->id ?? '');
            
                        // Hanya tambahkan data jika klasifikasi_id cocok dengan filter yang dipilih
                        if (!$filterKlasifikasiId || $detail->produk->klasifikasi_id == $filterKlasifikasiId) {
                            if (!isset($produkData[$produkKey])) {
                                $produkData[$produkKey] = [
                                    'kode_permintaan' => $permintaan->kode_permintaan,
                                    'klasifikasi' => $detail->produk->klasifikasi->nama,
                                    'subklasifikasi' => $detail->produk->klasifikasi->subklasifikasi->first()->nama ?? '-',
                                    'nama_produk' => $detail->produk->nama_produk,
                                    'stok' => array_fill_keys($tokoData->pluck('nama_toko')->toArray(), 0),
                                    'pesanan' => array_fill_keys($tokoData->pluck('nama_toko')->toArray(), 0),
                                    'total' => 0, // Tambahkan field untuk total per produk
                                ];
                            }
                            // Update stok dan pesanan
                            $produkData[$produkKey]['pesanan'][$detail->toko->nama_toko] += $detail->jumlah;
                            $produkData[$produkKey]['total'] += $detail->jumlah; // Update total per produk
                        }
                    @endphp
                @endforeach
            @endforeach
        
            @foreach ($produkData as $produkKey => $data)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $data['klasifikasi'] }}</td>
                    <td>{{ $data['subklasifikasi'] }}</td>
                    <td>{{ $data['nama_produk'] }}</td>
                    @foreach ($tokoData as $toko)
                        <td>{{ $data['stok'][$toko->nama_toko] }}</td>
                        <td>{{ $data['pesanan'][$toko->nama_toko] }}</td>
                    @endforeach
                    <td>{{ $data['total'] }}</td> <!-- Tampilkan total per produk -->
                </tr>
            @endforeach
        </tbody>
    </table> --}}
    
    
</body>
</html>
