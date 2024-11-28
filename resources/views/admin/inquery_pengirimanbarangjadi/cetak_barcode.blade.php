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
            box-sizing: border-box;
        }

        /* Kontainer untuk satu baris dengan 3 barcode */
        .row {
            display: flex;
            flex-wrap: wrap; /* Agar baris baru otomatis dimulai */
            justify-content: space-between;
            margin-bottom: 10px; /* Jarak antar baris */
        }

        /* Mengatur kotak untuk barcode dan teks */
        .box {
            display: flex; /* Mengatur elemen dalam baris horizontal */
            flex-direction: row;
            align-items: center; /* Menyelaraskan teks di tengah barcode secara vertikal */
            width: 32%; /* Ukuran fleksibel untuk tiga kolom */
            margin: 0;
        }

        .box img {
            width: 50px; /* Ukuran barcode */
            height: 50px;
            margin-right: 10px; /* Jarak antara barcode dan teks */
        }

        .text-container {
            font-size: 6px;
            margin-top: -45px;
            margin-left: 50px;
        }

        .box1 {
            display: flex; /* Mengatur elemen dalam baris horizontal */
            flex-direction: row;
            align-items: center; /* Menyelaraskan teks di tengah barcode secara vertikal */
            width: 32%; /* Ukuran fleksibel untuk tiga kolom */
            margin: 0;
        }

        .box1 img {
            width: 50px; 
            height: 50px;
            margin-left: 140px; 
            margin-top: -50px;
        }

        .text-container1 {
            font-size: 6px;
            margin-top: -64px;
            margin-left: 190px;
        }

        .box2 {
            display: flex; 
            flex-direction: row;
            align-items: center; 
            width: 32%; 
            margin: 0;
        }

        .box2 img {
            width: 50px; 
            height: 50px;
            margin-left: 270px; 
            margin-top: -70px;
        }

        .text-container2 {
            font-size: 6px;
            margin-top: -88px;
            margin-left: 320px;
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

        /* Menambahkan pemisah halaman setelah baris penuh */
        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>
    @foreach ($dataProduk as $produkData)
        @for ($i = 1; $i <= $produkData['jumlah']; $i++)
                
        @if (($i - 1) % 3 == 0)
            <div class="row">
        @endif

            <div class="box">
                <img src="data:image/png;base64,{{ $produkData['qrcodeData'] }}" alt="QR Code Produk" />

                <div class="text-container">
                    <p class="bold-text">{{ $produkData['produk']->kode_lama }}</p>

                    @php
                        $chunks = str_split($produkData['produk']->nama_produk, 15);
                    @endphp

                    @foreach ($chunks as $chunk)
                        <p class="bold-text truncate">{{ $chunk }}</p>
                    @endforeach

                    <p class="bold-text">Rp. {{ number_format($produkData['produk']->harga, 0, ',', '.') }}</p>

                    <p class="bold-text">
                        @if (is_array($produkData['kodeProduksi']))
                            @foreach ($produkData['kodeProduksi'] as $kode)
                                {{ $kode }}<br>
                            @endforeach
                        @else
                            {{ $produkData['kodeProduksi'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="box1">
                <img src="data:image/png;base64,{{ $produkData['qrcodeData'] }}" alt="QR Code Produk" />

                <div class="text-container1">
                    <p class="bold-text">{{ $produkData['produk']->kode_lama }}</p>

                    @php
                        $chunks = str_split($produkData['produk']->nama_produk, 15);
                    @endphp

                    @foreach ($chunks as $chunk)
                        <p class="bold-text truncate">{{ $chunk }}</p>
                    @endforeach

                    <p class="bold-text">Rp. {{ number_format($produkData['produk']->harga, 0, ',', '.') }}</p>

                    <p class="bold-text">
                        @if (is_array($produkData['kodeProduksi']))
                            @foreach ($produkData['kodeProduksi'] as $kode)
                                {{ $kode }}<br>
                            @endforeach
                        @else
                            {{ $produkData['kodeProduksi'] }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="box2">
                <img src="data:image/png;base64,{{ $produkData['qrcodeData'] }}" alt="QR Code Produk" />

                <div class="text-container2">
                    <p class="bold-text">{{ $produkData['produk']->kode_lama }}</p>

                    @php
                        $chunks = str_split($produkData['produk']->nama_produk, 15);
                    @endphp

                    @foreach ($chunks as $chunk)
                        <p class="bold-text truncate">{{ $chunk }}</p>
                    @endforeach

                    <p class="bold-text">Rp. {{ number_format($produkData['produk']->harga, 0, ',', '.') }}</p>

                    <p class="bold-text">
                        @if (is_array($produkData['kodeProduksi']))
                            @foreach ($produkData['kodeProduksi'] as $kode)
                                {{ $kode }}<br>
                            @endforeach
                        @else
                            {{ $produkData['kodeProduksi'] }}
                        @endif
                    </p>
                </div>
            </div>
            @if ($i % 3 == 0 || $i == $produkData['jumlah'])
        </div> <!-- Menutup baris -->
    @endif

    @if ($i % 3 == 0 && $i != $produkData['jumlah'])
        <div class="page-break"></div> <!-- Memaksa pindah halaman setelah 3 produk -->
    @endif

    @if ($i == $produkData['jumlah'] && !$loop->last)
        <div class="page-break"></div> <!-- Memaksa pindah halaman setelah produk terakhir -->
    @endif
    @endfor
    
    @endforeach
</body>

</html>
