<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pemesanan Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .section table th, .section table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .signatures {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
        }
        .signature p {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>JAVA BAKERY</h1>
            <p>Jl. HOS. Cokro Aminoto No.5,</p>
            <p>Telepon: Nomor Telepon Perusahaan Anda</p>
        </div>
        
        <div class="section">
            <h2>Data Pemesanan Produk</h2>
            <p><strong>Kode Pemesanan:</strong> {{ $pemesanan->kode_pemesanan }}</p>
            <p><strong>Nama Pelanggan:</strong> {{ $pemesanan->nama_pelanggan }}</p>
            <p><strong>No Telepon:</strong> {{ $pemesanan->telp }}</p>
            <p><strong>Alamat Tujuan:</strong> {{ $pemesanan->alamat }}</p>
            
            <h3>Detail Pemesanan</h3>
            @if($pemesanan->detailpemesananproduk->isEmpty())
                <p>Tidak ada detail pemesanan produk.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach($pemesanan->detailpemesananproduk as $detail)
                            <tr>
                                <td>{{ $detail->kode_produk }}</td>
                                <td>{{ $detail->nama_produk }}</td>
                                <td>{{ $detail->jumlah }}</td>
                                <td>{{ $detail->harga }}</td>
                                <td>{{ $detail->total }}</td>
                            </tr>
                            
                        @endforeach
                        <tr>
                            <td colspan="4" style="text-align: right;"><strong>Total Bayar</strong></td>
                            <td>{{ $pemesanan->sub_total }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>
        
        <div class="signatures">
            <div class="signature">
                <p>Tanda Tangan Pelanggan</p>
                <br><br><br>
                {{$pemesanan->nama_pelanggan}}
                <p>__________________</p>
            </div>
            <div class="signature">
                <p>Tanda Tangan Admin</p>
                <br><br><br>
                {{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}
                <p>__________________</p>
            </div>
           
            <div class="signature">
                <p>Tanda Tangan Pemilik</p>
                <!-- Placeholder for owner signature -->
            </div>
        </div>
    </div>
</body>
</html>
