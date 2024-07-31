{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 40px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
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
        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 10px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: left;
            width: 20%; /* Atur lebar kolom ke nilai persentase yang sesuai */
        }
        th {
            background-color: #f2f2f2;
        }
        table th:nth-child(1),
        table td:nth-child(1) { width: 5%; } /* Lebar untuk kolom No */

        table th:nth-child(2),
        table td:nth-child(2) { width: 15%; } /* Lebar untuk kolom Divisi */

        table th:nth-child(3),
        table td:nth-child(3) { width: 65%; } /* Lebar untuk kolom Kode Produk */

        table th:nth-child(4),
        table td:nth-child(4) { width: 15%; } /* Lebar untuk kolom Produk */

        /* table th:nth-child(5),
        table td:nth-child(5) { width: 10%; } Lebar untuk kolom Jumlah */
        .signature-container {
            margin-top: 60px;
            text-align: center;
        }
        .signature {
            display: inline-block;
            margin: 0 30px;
            text-align: center;
        }
        .signature p {
            margin: 0;
        }
        .row p {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
        }
        .info-div {
            margin-top: 10px;
        }
        .info-div p {
            margin: 2px 0;
        }
        .info-div span {
            display: inline-flex;
            align-items: center;
        }
        .info-div .label {
            min-width: 100px;
            font-weight: bold;
        }
        .info-div .value {
            min-width: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div>
            <div>
                <span class="title">PT JAVA BAKERY</span><br>
                <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span><br>
                <span class="contact">Telp / Fax, Email :</span>
            </div>
            <hr class="divider">
        </div>

        <!-- Judul Surat -->
        <div class="change-header">SURAT PERMINTAAN PRODUK</div>



        <!-- Informasi Permintaan -->
        <div class="info-div">
            <p>
                <span class="label">Kode Permintaan</span>
                <span class="value">: {{ $permintaanProduk->kode_permintaan }}</span>
            </p>
            <p>
                <span class="label">Tanggal</span>
                <span class="value">: {{ $permintaanProduk->created_at->format('d-m-Y') }}</span>
            </p>
        </div>

        <!-- Detail Produk -->
        @foreach ($produkByDivisi as $divisi => $produks)
            <div class="section-title">{{ $divisi }}</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produks as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail->produk->kode_produk }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td>{{ $detail->jumlah }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>{{ $totalPerDivisi[$divisi] }}</td>
                    </tr>
                </tbody>
            </table><br>
        @endforeach

       
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 40px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
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
        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 40px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: left;
            width: 20%; /* Atur lebar kolom ke nilai persentase yang sesuai */
        }
        th {
            background-color: #f2f2f2;
        }
        table th:nth-child(1),
        table td:nth-child(1) { width: 5%; } /* Lebar untuk kolom No */

        table th:nth-child(2),
        table td:nth-child(2) { width: 25%; } /* Lebar untuk kolom Divisi */

        table th:nth-child(3),
        table td:nth-child(3) { width: 25%; } /* Lebar untuk kolom Kode Produk */

        table th:nth-child(4),
        table td:nth-child(4) { width: 30%; } /* Lebar untuk kolom Produk */

        table th:nth-child(5),
        table td:nth-child(5) { width: 10%; } /* Lebar untuk kolom Jumlah */
        .signature-container {
            margin-top: 60px;
            text-align: center;
        }
        .signature {
            display: inline-block;
            margin: 0 30px;
            text-align: center;
        }
        .signature p {
            margin: 0;
        }
        .row p {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div>
            <div>
                <span class="title">PT JAVA BAKERY</span><br>
                @if($toko)
                <span class="toko-name">Cabang: {{ $toko->nama_toko }}</span><br>
                <span class="address">{{$toko->alamat}}</span><br>
                @endif

            </div>
            <hr class="divider">
        </div>

        <!-- Judul Surat -->
        <div class="change-header">SURAT PERMINTAAN PRODUK</div>

        <!-- Tanggal Surat -->
        {{-- <div class="tanggal">
            Tanggal : {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
        </div> --}}

        <!-- Informasi Permintaan -->
        <div>
            <p>
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Permintaan</strong></span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->kode_permintaan }}</span>
            </p>
            <p>
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal</strong> </span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->created_at->format('d-m-Y') }}</span>
            </p>
          
        </div>

        <!-- Detail Produk -->
        {{-- @foreach ($produkByDivisi as $divisi => $produks)
            <div class="section-title">{{ $divisi }}</div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Subklasifikasi</th>
                        <th>Kode Produk</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produks as $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{'subklasifikasi' }}</td>
                            <td>{{ $detail->produk->kode_produk }}</td>
                            <td>{{ $detail->produk->nama_produk }}</td>
                            <td>{{ $detail->jumlah }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3">Total</td>
                        <td>{{ $totalPerDivisi[$divisi] }}</td>
                    </tr>
                </tbody>
            </table><br>
        @endforeach --}}
        @foreach ($produkByDivisi as $divisi => $produks)
        <div class="section-title">{{ $divisi }}</div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Kode Produk</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                $no = 1; 
                @endphp
                @foreach ($produks->groupBy(function($item) {
                    return $item->produk->subklasifikasi->nama; 
                    }) as $subklasifikasi => $produkList)
                        @foreach ($produkList as $detail)
                            <tr>
                                <td>{{ $no++ }}</td> 
                                <td>{{ $subklasifikasi }}</td>
                                <td>{{ $detail->produk->kode_produk }}</td>
                                <td>{{ $detail->produk->nama_produk }}</td>
                                <td>{{ $detail->jumlah }}</td>
                            </tr>
                        @endforeach
                    {{-- Menampilkan total untuk subklasifikasi --}}
                    {{-- <tr class="total-row">
                        <td colspan="4">Total {{ $subklasifikasi }}</td>
                        <td>{{ $produkList->sum('jumlah') }}</td>
                    </tr> --}}
                @endforeach
            </tbody>

            {{-- Menampilkan total untuk divisi --}}
            <tfoot>
                <tr class="total-row">
                    <td colspan="4">Total </td>
                    <td>{{ $produks->sum('jumlah') }}</td>
                </tr>
            </tfoot>
        </table><br>
    @endforeach
    


       
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>
