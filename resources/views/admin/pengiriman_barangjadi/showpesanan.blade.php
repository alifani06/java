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
            display: flex;
            margin-top: 20px;
            border-bottom: 1px solid #000;
        }
        .header .col {
            padding: 0 10px;
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
            margin-bottom: 1px;
        }
        .change-header1 {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
        }

        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 0px;
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
            padding: 4px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: white;
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
        .total-row {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="header row">
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
        {{-- <hr class="divider"> --}}

        <!-- Judul Surat -->
        <div class="change-header">SURAT PENGIRIMAN BARANG JADI - PESANAN</div>
        <div class="change-header1">
            <p style="margin-bottom: 2px; font-size: 18px;">{{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
            <p>{{ $firstItem->toko->alamat ?? 'Alamat tidak tersedia' }}</p>
        </div>
        <!-- Informasi Permintaan -->
        <div>
            <p style="margin-bottom: 2px;">
                <strong>Kode Pengiriman:</strong> {{ $firstItem->kode_pengiriman }}
            </p>
            <p style="margin-bottom: 2px;">
                <strong>Tanggal Kirim:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y H:m') }}
            </p>
        </div>

        <!-- Detail Produk -->
        @foreach($groupedByKlasifikasi as $klasifikasi => $items)
            <div class="section-title">{{ $klasifikasi }}</div>
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
                    @foreach($items as $key => $detail)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $detail->produk->kode_lama }}</td>
                        <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                        <td>{{ $detail->produk->nama_produk }}</td>
                        <td>{{ $detail->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;"><strong>Total</strong></td>
                        <td><strong>{{ $items->sum('jumlah') }}</strong></td>
                    </tr>
                </tfoot>
            </table><br>
        @endforeach

        

        <div class="d-flex justify-content-between">
            <div>
                <a href="{{ url('admin/pengiriman_barangjadi') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Kembali
                </a>
            </div>
            <div>
                <a href="{{ route('pengiriman_barangjadi.print', $firstItem->id) }}" id="printButton" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-print"></i> Cetak 
                </a>
            </div>  
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
