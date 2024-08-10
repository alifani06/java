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
            width: 100%;
            border-bottom: 1px solid #000;
            margin-top: 20px;
            padding: 0 10px;
        }
        .header td {
            border: none;
        }
        .header .title {
            font-weight: bold;
            font-size: 14px;
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
            margin-bottom: 10px;
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
        }
        th {
            background-color: #f2f2f2;
        }
        .signature-container {
            margin-top: 60px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin: 0 20px;
        }
        .signaturea {
            flex: 1;
            text-align: left;
            margin: 0 10px; /* Space between signatures */
        }
        .signatureb {
            flex: 1;
            text-align: center;
            margin: 0 10px;
            margin-top: -200px; /* Space between signatures */
        }
        .signaturec {
            flex: 1;
            text-align: right;
            margin: 0 10px; 
            margin-top: -200px; /* Space between signatures */
        }
        .signature p {
            margin: 0;
            margin-top: 10px;
        }
        .row p {
            margin: 0;
        }
        .total-row {
            font-weight: bold;
        }

        /* CSS untuk tampilan cetak */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .container {
                width: 100%;
                margin: 0;
            }
            .header {
                border-bottom: 1px solid #000;
                page-break-inside: avoid;
            }
            .divider {
                border: 0.5px solid #000;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                padding: 6px;
                border: 1px solid #000;
            }
            .change-header {
                page-break-before: always;
            }
            .signature-container {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <table class="header" style="margin-top: 2px;">
        <tr>
            <td style="text-align: left;">
                <div>
                    <span class="title">PT JAVA BAKERY</span><br>
                    <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
                </div>
            </td>
            <td style="text-align: center;">
                <div>
                    <p style="color: white">Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
                </div>                
            </td>
            <td style="text-align: left;">
                <div class="title">JAVA BAKERY</div>
                <p style="margin-bottom: 2px;">Cabang : {{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
                <p>{{ $firstItem->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
            </td>
        </tr>
    </table>
    {{-- <hr class="divider"> --}}

    <!-- Judul Surat -->
    <div class="change-header">SURAT PENGIRIMAN BARANG JADI</div>

    <!-- Informasi Permintaan -->
    <div>
        <p style="margin-bottom: 2px;">
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Pengiriman</strong></span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $pengirimanBarangJadi->first()->kode_pengiriman }}</span>
        </p>
        <p>
            <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal</strong> </span>
            <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</span>
        </p>
    </div>

    <!-- Detail Produk -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Produk</th>
                <th>Kategori</th>
                <th>Produk</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengirimanBarangJadi as $key => $detail)
            <tr>
                <td>{{ $key + 1 }}</td> 
                <td>{{ $detail->produk->kode_produk }}</td>
                <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                <td>{{ $detail->produk->nama_produk }}</td>
                <td>{{ $detail->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>

    <div class="signature-container">
        <div class="signature-row">
            <div class="signaturea">
                {{-- <p style="margin-left: 30px;"><strong>Gudang</strong></p><br><br> --}}
                <p>____________________</p>
                {{-- <p>Nama Gudang</p> --}}
            </div>
            <div class="signatureb">
                {{-- <p><strong>Accounting</strong></p><br><br> --}}
                <p>____________________</p>
                {{-- <p>Nama Accounting</p> --}}
            </div>
            <div class="signaturec">
                {{-- <p style="margin-right: 60px;"><strong>Baker</strong></p><br><br> --}}
                <p>____________________</p>
                {{-- <p>Nama Karyawan</p> --}}
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
