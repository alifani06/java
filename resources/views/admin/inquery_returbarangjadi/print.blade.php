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
        .tab {
            display: flex;
            margin-top: 20px;
            border-bottom: 1px solid #000;
        }
        .tab td {
            border: none;
        }
        .tab .col {
            padding: 0 10px;
        }
        .tab .title {
            font-weight: bold;
            font-size: 18px;
        }
        .tab .address, .tab .contact {
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
            .tab {
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
    <div class="tab row">
        <div class="col-2 text-right">
            <div class="logo">
                {{-- <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY"> --}}
            </div>
            {{-- <div>
                <span class="title">PT JAVA BAKERY FACTORY</span><br>
                <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
            
            </div> --}}
        </div>
    
        <div class="col-8 text-center">
            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div>
            <span class="title">PT JAVA BAKERY FACTORY</span><br>
            <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
        </div>
        {{-- <div class="col-2 text-left">
            <div class="title">JAVA BAKERY</div>
            <p>Cabang : {{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
            <p>{{ $firstItem->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
        </div> --}}
    </div>
    {{-- <table class="tab">
        <tr>
            <td style="text-align: left;">
                <div class="title">JAVA BAKERY</div>
                <p>Cabang: {{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
                <p>{{ $firstItem->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
            </td>
            <td style="text-align: center;">
                <div>
                    <p style="color: white">Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
                </div> 
             
            </td>
            <td style="text-align: right;">
              
                <div>
                    <span class="title">PT JAVA BAKERY FACTORY</span><br>
                    <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
                </div>
            </td>
        </tr>
    </table> --}}
    

        <!-- Judul Surat -->
        <div class="change-header">SURAT PEMUSNAHAN BARANG JADI</div>

        <!-- Informasi Permintaan -->
        <div>
            <p style="margin-bottom: 2px;">
                <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Retur</strong></span>
                <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $pengirimanBarangJadi->first()->kode_retur }}</span>
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
                    <th>Keterangan</th>
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
                    <td>{{ $detail->keterangan }}</td>
                    <td>{{ $detail->jumlah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table><br>
        <div class="signature-container">
            <div class="signature-row">
                <div class="signaturea">
                    {{-- <p style="margin-left: 30px;"><strong>Pengirim</strong></p><br><br> --}}
                    <p style="margin-bottom: 2px;">____________________</p>
                    {{-- <p>Admin Barang Jadi</p> --}}
                </div>
                <div class="signatureb">
                    {{-- <p><strong>Sopir</strong></p><br><br> --}}
                    <p style="margin-bottom: 2px;">____________________</p>
                    {{-- <p>Sopir</p> --}}
                </div>
                <div class="signaturec">
                    {{-- <p style="margin-right: 60px;"><strong>Penerima</strong></p><br><br> --}}
                    <p style="margin-bottom: 2px;">____________________</p>
                    {{-- <p style="margin-right: 49px;">Admin Toko</p> --}}
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>

