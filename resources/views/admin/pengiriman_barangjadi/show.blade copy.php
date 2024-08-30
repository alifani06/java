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
        .header {
            display: flex;
            margin-top: 20px;
            border-bottom: 1px solid #000;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
        }
        .change-header1 {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
        <div class="header row">
            <div class="col-8 text-center">
                <span class="title">PT JAVA BAKERY FACTORY</span><br>
                <p>Jl. HOS. Cokro Aminoto No.5, Kagok, Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52411</p><br>
            </div>
        </div>

        <!-- Judul Surat -->
        <div class="change-header">SURAT PENGIRIMAN BARANG JADI</div>
        <div class="change-header1">
            <p style="margin-bottom: 2px;">Cabang : {{ $firstItem->toko->nama_toko ?? 'Nama toko tidak tersedia' }}</p>
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

        <!-- Looping Klasifikasi dan Detail Produk -->
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
                        <td>{{ $detail->produk->kode_produk }}</td>
                        <td>{{ $detail->produk->subklasifikasi->nama }}</td>
                        <td>{{ $detail->produk->nama_produk }}</td>
                        <td>{{ $detail->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align:right;"><strong>Total {{ $klasifikasi }}</strong></td>
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
