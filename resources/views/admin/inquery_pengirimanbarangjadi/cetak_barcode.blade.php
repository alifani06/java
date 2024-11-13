<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Produk</title>

    <style type="text/css">
        /* Reset all margins and padding */
        * {
            margin: 0;
            padding: 0;
        }

        /* Kontainer untuk satu baris dengan 3 barcode */
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px; /* Jarak antar baris */
        }

        /* Menjaga format dan ukuran setiap barcode sesuai cetakan */
        .box {
            width: 50%; /* Lebar setiap barcode */
            margin-left: 0px;
            margin-top: 0px;
        }

        /* Kondisi khusus untuk barcode pertama, kedua dan ketiga */
        .barcode-first {
            margin-top: 0px;
        }

        .barcode-second {
            margin-top: 50px; /* Set margin-top lebih besar untuk barcode kedua */
        }

        .barcode-third {
            margin-top: 50px; /* Set margin-top lebih besar untuk barcode ketiga */
        }

        .text-container {
            position: relative;
            margin-right: 760px;
            margin-left: 6px;
            margin-top: 4px;
            transform: rotate(90deg);
        }

        .text {
            font-size: 7px;
        }

        .bold-text {
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
        }

        .truncate {
            width: 70px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        /* Menambahkan pemisah halaman setelah setiap 3 barcode */
        .page-break {
            page-break-after: always;
        }
    </style>

</head>

{{-- <body>
    @foreach (range(1, $jumlah) as $i)
        @if ($i % 3 == 1) <!-- Baris baru dimulai setelah barcode pertama dalam grup 3 -->
            <div class="row">
        @endif

        <div>
            <div class="box
                @if ($i % 3 == 1) barcode-first 
                @elseif ($i % 3 == 2) barcode-second 
                @elseif ($i % 3 == 0) barcode-third @endif">
                <!-- Menampilkan gambar QR code -->
                <img src="data:image/png;base64,{{ $qrcodeData }}" />
            </div>
            
            <div class="text-container
                @if ($i % 3 == 2) barcode-second 
                @elseif ($i % 3 == 0) barcode-third @endif">
                <div class="text">
                    <p class="bold-text">{{ $produk->kode_lama }}</p>

                    @php
                        // Membagi nama produk menjadi blok-blok yang berisi maksimal 15 karakter
                        $chunks = str_split($produk->nama_produk, 15);
                    @endphp

                    @foreach ($chunks as $chunk)
                        <p style="font-size: 6px;" class="bold-text truncate">{{ $chunk }}</p>
                    @endforeach

                    <p style="font-size: 7px;" class="bold-text">Rp. {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    <p class="bold-text">C3</p>
                </div>
            </div>
        </div>

        @if ($i % 3 == 0) <!-- Tutup baris setelah setiap 3 barcode -->
            </div> <!-- Menutup baris -->
            @if ($i != $jumlah) <!-- Jangan menambahkan pemisah halaman untuk barcode terakhir -->
                <div class="page-break"></div> <!-- Pemisah halaman hanya setelah setiap 3 barcode -->
            @endif
            @if ($i != $jumlah) <!-- Jangan mulai baris baru jika sudah barcode terakhir -->
                <div class="row">
            @endif
        @elseif ($i == $jumlah) <!-- Jika sudah barcode terakhir, pastikan baris terakhir ditutup tanpa pemisah halaman -->
            </div> <!-- Menutup baris terakhir -->
        @endif
    @endforeach
</body> --}}

<body>
    @foreach ($dataProduk as $produkData)
        {{-- Mengulangi cetakan sesuai jumlah yang diminta untuk setiap produk --}}
        @for ($i = 1; $i <= $produkData['jumlah']; $i++)
            
            {{-- Mulai baris baru setiap 3 item --}}
            @if (($i - 1) % 3 == 0)
                <div class="row">
            @endif

            <div class="box
                @if ($i % 3 == 1) barcode-first 
                @elseif ($i % 3 == 2) barcode-second 
                @elseif ($i % 3 == 0) barcode-third @endif">
                
                <img src="data:image/png;base64,{{ $produkData['qrcodeData'] }}" />
            </div>
            
            <div class="text-container
                @if ($i % 3 == 1) barcode-first 
                @elseif ($i % 3 == 2) barcode-second 
                @elseif ($i % 3 == 0) barcode-third @endif">
                
                <div class="text">
                    <p class="bold-text">{{ $produkData['produk']->kode_lama }}</p>

                    @php
                        // Membagi nama produk ke dalam potongan teks agar tidak terlalu panjang
                        $chunks = str_split($produkData['produk']->nama_produk, 15);
                    @endphp

                    @foreach ($chunks as $chunk)
                        <p style="font-size: 6px;" class="bold-text truncate">{{ $chunk }}</p>
                    @endforeach

                    <p style="font-size: 7px;" class="bold-text">Rp. {{ number_format($produkData['produk']->harga, 0, ',', '.') }}</p>
                    <p class="bold-text">C3</p>
                </div>
            </div>

            {{-- Tutup baris setiap 3 item atau jika sudah mencapai jumlah produk --}}
            @if ($i % 3 == 0 || $i == $produkData['jumlah'])
                </div> <!-- Menutup baris -->
            @endif

            {{-- Tambahkan page break setelah setiap 3 produk --}}
            @if ($i % 3 == 0 && $i != $produkData['jumlah'])
                <div class="page-break"></div> <!-- Memaksa pindah halaman setelah 3 produk -->
            @endif

            {{-- Tambahkan page break setelah produk terakhir --}}
            @if ($i == $produkData['jumlah'] && !$loop->last)
                <div class="page-break"></div> <!-- Memaksa pindah halaman setelah produk terakhir -->
            @endif
        @endfor
    @endforeach
</body>





</html>
