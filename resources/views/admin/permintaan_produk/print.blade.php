<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
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
            margin-top: 20px;
            margin-bottom: 20px;
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
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
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

        <!-- Tanggal Surat -->
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
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Divisi</th>
                    <th>Kode Produk</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailPermintaanProduks as $detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->produk->klasifikasi->nama }}</td>
                        <td>{{ $detail->produk->kode_produk }}</td>
                        <td>{{ $detail->produk->nama_produk }}</td>
                        <td>{{ $detail->jumlah }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

      
    </div>
</body>
</html>
