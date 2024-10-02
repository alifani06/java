{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Produk</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Sesuaikan dengan gaya CSS Anda -->
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            padding-left: 5%;
            align-items: center; 
        }
        .product-name {
            margin-right: 20px; 
        }
        .text-container {
            position: relative;
            /* margin-right: 760px; */
            margin-left: 57px;
      
        }

        .text {
            font-size: 10px;
            margin-top: -45px;

        }

        .bold-text {
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif
        }
        .box{
            margin-left: 10px;
            margin-top: 5px;
        }
        .box1 {
            margin-left: 10px;
            margin-top: 5px;
        }
        .box2 {
            margin-left: 10px;
            margin-top: 5px;
        }

    </style>
</head>
<body>


    <div class="box1">
        <?php
        use BaconQrCode\Renderer\ImageRenderer;
        use BaconQrCode\Writer;
        
        // Ubah tautan menjadi QR code
        $qrcode = new Writer(new ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(50), new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
        $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
        
        // Tampilkan gambar QR code
        echo '<img src="data:image/png;base64,' . base64_encode($qrcodeData) . '" />';
        ?>
    </div>
    <div class="text-container">
        <div class="text">
            <p class="product-name">{{ $produk->nama_produk }}</p>
        </div>
    </div>

    <div class="box">
        <?php
      
        // Ubah tautan menjadi QR code
        $qrcode = new Writer(new ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(50), new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
        $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
        
        // Tampilkan gambar QR code
        echo '<img src="data:image/png;base64,' . base64_encode($qrcodeData) . '" />';
        ?>
    </div>
    <div class="text-container">
        <div class="text">
            <p class="product-name">{{ $produk->nama_produk }}</p>
        </div>
    </div>

    <div class="box2">
        <?php
      
        // Ubah tautan menjadi QR code
        $qrcode = new Writer(new ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(50), new \BaconQrCode\Renderer\Image\SvgImageBackEnd()));
        $qrcodeData = $qrcode->writeString($produk->qrcode_produk);
        
        // Tampilkan gambar QR code
        echo '<img src="data:image/png;base64,' . base64_encode($qrcodeData) . '" />';
        ?>
    </div>
    <div class="text-container">
        <div class="text">
            <p class="product-name">{{ $produk->nama_produk }}</p>
        </div>
    </div>

    
</body>
</html> --}}


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Sesuaikan dengan gaya CSS Anda -->
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            width: 33%;
            text-align: center;
            padding: 10px;
            vertical-align: top;
        }
        .text {
            font-size: 10px;
            margin-top: 5px;
        }
        .product-name {
            font-weight: bold;
            font-size: 5px;
        }
        img {
            margin-bottom: 5px; /* Menambahkan jarak di bawah gambar */
        }
    </style>
</head>
<body>

    <table>
        <tr>
            <td>
                <img src="{{ $produk->qrcode_image }}" />
                <p class="product-name">{{ $produk->nama_produk }}</p>
            </td>
            <td>
                <img src="{{ $produk->qrcode_image }}" />
                <p class="product-name">{{ $produk->nama_produk }}</p>
            </td>
            <td>
                <img src="{{ $produk->qrcode_image }}" />
                <p class="product-name">{{ $produk->nama_produk }}</p>
            </td>
        </tr>
    </table>

</body>
</html>



