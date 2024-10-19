<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permintaan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        @page {     
            margin: 1cm;
            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }
        }   

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 80px; /* Tambahkan ruang ekstra untuk informasi admin */
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
            margin-top: 10px;
        }
        .header .title {
            font-weight: bold;
            font-size: 24px;
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
            font-size: 20px;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 5px;
        }
        .tanggal {
            text-align: left;
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
        }
        .section-title {
            margin-top: 5px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 9px;
        }
        th, td {
            padding: 4px;
            border: 1px solid black;
            text-align: left;
        }
        th {
            background-color: white;
        }
        /* Atur ukuran font di dalam tabel */
        table td, table th {
            font-size: 10px; /* Ukuran font tabel lebih kecil */
        }
        table th:nth-child(1),
        table td:nth-child(1) { width: 5%; } /* Lebar untuk kolom No */

        table th:nth-child(2),
        table td:nth-child(2) { width: 10%; } /* Lebar untuk kolom Kode Produk */

        table th:nth-child(3),
        table td:nth-child(3) { width: 25%; } /* Lebar untuk kolom Kategori */

        table th:nth-child(4),
        table td:nth-child(4) { width: 45%; } /* Lebar untuk kolom Produk */

        table th:nth-child(5),
        table td:nth-child(5) { width: 10%; } /* Lebar untuk kolom Jumlah */
        .signature-container {
            margin-top: 60px;
            text-align: center;
        }
       
    </style>
</head>
<body>
        <!-- Kop Surat -->
        <div class="header">
            <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div>
            <div>
                <span class="title">PT JAVA BAKERY FACTORY</span><br>
               
            </div>
            <hr class="divider">
        </div>

        <!-- Judul Surat -->
        <div class="change-header">SURAT PERINTAH PRODUKSI</div>

        <!-- Informasi Permintaan -->
        <div style="margin-top: 2px;">
            <p>
                {{-- <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Kode Permintaan</strong></span> --}}
                {{-- <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->kode_estimasi }}</span> --}}
            </p>
            <p>
                {{-- <span style="min-width: 100px; display: inline-flex; align-items: center;"><strong>Tanggal</strong> </span> --}}
                {{-- <span style="min-width: 50px; display: inline-flex; align-items: center;">: {{ $permintaanProduk->created_at->format('d-m-Y H:i') }}</span> --}}
            </p>
        </div>

        @foreach ($produkByDivisi as $divisi => $produks)
        <div class="section-title">{{ $divisi }}</div>
        
        <table >
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Kategori</th>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Realisasi</th>
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
                                <td>{{ $detail->produk->kode_lama }}</td>
                                <td>{{ $subklasifikasi }}</td>
                                <td>{{ $detail->produk->nama_produk }}</td>
                                <td style="text-align: right">{{ $detail->jumlah }}</td>
                                <td style="text-align: right"></td>
                            </tr>
                        @endforeach
                @endforeach
            </tbody>

            <tfoot>
                <tr class="total-row">
                    <th colspan="14" style="text-align: right;">Total:</th>
                    
                    <th style="text-align: right;">{{ $totalStok }}</th>
                    <th style="text-align: right;">{{ $totalPesanan }}</th>
                    <th style="text-align: right;">{{ $grandTotal }}</th>
                    <td colspan="5">Total </td>
                    
                </tr>
            </tfoot>
        </table><br>
        @endforeach
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>