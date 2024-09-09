{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permintaan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .logo img {
            width: 150px;
            height: 75px;
            margin-top: 1px;

        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 20px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px; /* Menetapkan ukuran font kecil untuk tabel */
        }
        th, td {
            border: 1px solid #000; /* Menetapkan warna border hitam untuk tabel */
            padding: 4px; /* Menyesuaikan padding agar lebih kecil */
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
    <table>
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
            $totalPerToko = array_fill_keys($tokoData->pluck('nama_toko')->toArray(), 0); // Inisialisasi array untuk total per toko
            $grandTotal = 0; // Inisialisasi total grand total
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

                        // Tambahkan ke total per toko dan grand total
                        $totalPerToko[$detail->toko->nama_toko] += $detail->jumlah;
                        $grandTotal += $detail->jumlah;
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
                    <td style="text-align: right">{{ $data['jumlah'][$toko->nama_toko] }}</td>
                @endforeach
                <td style="text-align: right">{{ $data['total'] }}</td> <!-- Tampilkan total per produk -->
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: right; font-weight: bold;">Total</td>
            @foreach ($tokoData as $toko)
                <td style="text-align: right; font-weight: bold;">{{ $totalPerToko[$toko->nama_toko] }}</td>
            @endforeach
            <td style="text-align: right; font-weight: bold;">{{ $grandTotal }}</td> <!-- Tampilkan total keseluruhan -->
        </tr>
    </tfoot>
</table>


</body>
</html> --}}


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Permintaan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .logo img {
            width: 150px;
            height: 75px;
            margin-top: 1px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 20px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px; /* Menetapkan ukuran font kecil untuk tabel */
        }
        th, td {
            border: 1px solid #000; /* Menetapkan warna border hitam untuk tabel */
            padding: 4px; /* Menyesuaikan padding agar lebih kecil */
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
    <table>
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Kode Permintaan</th>
            <th>Divisi</th>
            {{-- <th>Kategori</th> --}}
            {{-- <th>Subklasifikasi</th> --}}
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
            $totalPerToko = []; // Total untuk setiap toko
            $grandTotal = 0; // Total keseluruhan
        @endphp

        @foreach ($permintaanProduk as $permintaan)
            @foreach ($permintaan->detailpermintaanproduks as $detail)
                @php
                    $produk = $detail->produk;
                    $klasifikasi = $produk->klasifikasi;
                    $subklasifikasi = $klasifikasi->subklasifikasi;

                    // Total per toko
                    $totalPerToko[$detail->toko_id] = ($totalPerToko[$detail->toko_id] ?? 0) + $detail->jumlah;

                    // Data produk
                    $produkData[$detail->produk_id]['produk'] = $detail->produk->nama_produk;
                    $produkData[$detail->produk_id]['kode_permintaan'] = $permintaan->kode_permintaan;
                    $produkData[$detail->produk_id]['kategori'] = $klasifikasi->nama ?? '-';
                    $produkData[$detail->produk_id]['total'] = ($produkData[$detail->produk_id]['total'] ?? 0) + $detail->jumlah;
                    $produkData[$detail->produk_id]['detail'][$detail->toko_id] = ($produkData[$detail->produk_id]['detail'][$detail->toko_id] ?? 0) + $detail->jumlah;

                    // Total keseluruhan
                    $grandTotal += $detail->jumlah;
                @endphp
            @endforeach
        @endforeach

        @foreach ($produkData as $produkId => $data)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $data['kode_permintaan'] }}</td>
                {{-- <td>{{ $data['divisi'] }}</td> --}}
                <td>{{ $data['kategori'] }}</td>
                {{-- <td>{{ $data['subklasifikasi'] }}</td> --}}
                <td>{{ $data['produk'] }}</td>
                @foreach ($tokoData as $toko)
                    <td class="text-center">{{ $data['detail'][$toko->id] ?? 0 }}</td>
                @endforeach
                <td class="text-center">{{ $data['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-center">Total</th>
            @foreach ($tokoData as $toko)
                <th class="text-center">{{ $totalPerToko[$toko->id] ?? 0 }}</th>
            @endforeach
            <th class="text-center">{{ $grandTotal }}</th>
        </tr>
    </tfoot>
    </table>
</body>
</html>


