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
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div>
            <span class="title">PT JAVA BAKERY</span><br>
            @if(isset($tokoData) && $tokoData->isNotEmpty())
                <span class="toko-name">Cabang: {{ $tokoData->first()->nama_toko }}</span><br>
                <span class="address">{{ $tokoData->first()->alamat }}</span><br>
            @endif
        </div>
        <hr class="divider">
    </div>
    <div class="change-header">LAPORAN PERMINTAAN PRODUK</div>
    {{-- <p>Tanggal: {{ date('d-m-Y') }}</p> --}}
    <p>Periode: {{ $tanggal_permintaan ? date('d-m-Y', strtotime($tanggal_permintaan)) : 'Tidak ditentukan' }} - {{ $tanggal_akhir ? date('d-m-Y', strtotime($tanggal_akhir)) : 'Tidak ditentukan' }}</p>

    <table style="font-size: 9px;">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kode Permintaan</th>
                <th>Divisi</th>
                <th>Subklasifikasi</th>
                <th>Produk</th>
                @foreach ($tokoData as $toko)
                    <th>{{ $toko->nama_toko }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $produkData = [];
            @endphp
            @foreach ($permintaanProduk as $permintaan)
                @foreach ($permintaan->detailpermintaanproduks as $detail)
                    @php
                        $produkKey = $detail->produk->kode_produk . '-' . $detail->produk->klasifikasi_id . '-' . ($detail->produk->klasifikasi->subklasifikasi->first()->id ?? '');
                        if (!isset($produkData[$produkKey])) {
                            $produkData[$produkKey] = [
                                'kode_permintaan' => $permintaan->kode_permintaan,
                                'klasifikasi' => $detail->produk->klasifikasi->nama,
                                'subklasifikasi' => $detail->produk->klasifikasi->subklasifikasi->first()->nama ?? '-',
                                'nama_produk' => $detail->produk->nama_produk,
                                'jumlah' => array_fill_keys($tokoData->pluck('nama_toko')->toArray(), 0),
                            ];
                        }
                        $produkData[$produkKey]['jumlah'][$detail->toko->nama_toko] += $detail->jumlah;
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
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
