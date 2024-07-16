<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pemesanan Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            font-family: inherit;
            line-height: 1.6;
            padding: 10px;
            font-size: 10px;
            background-color: #f2f2f2;
        }
        .container {
            width: 80mm;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .text {
            display: flex;
            flex-direction: column;
        }

        .header .text h1 {
            margin-top: 10px;
            margin-bottom: 2px;
            padding: 0;
            font-size: 16px;
            color: #0c0c0c;
            text-transform: uppercase;
        }

        .header .text p {
            margin: 2px 0;
            font-size: 8px;
        }

        .header .logo img {
            margin-top: 5px;
            max-width: 80px; /* Adjust the size as needed */
            height: auto;

        }
        .divider {
            border: 0.5px solid;
            margin-top: 3px;
            margin-bottom: 1px;
            border-bottom: 1px solid #0f0e0e;
        }

        .section {
            margin-bottom: 10px;
        }
        .section h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            text-align: center;
            margin-bottom: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .section h2 {

            font-size: 12px;
        
        }
        .section table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .section table th, .section table td {
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 10px;
        }
        .signatures {
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            font-size: 7px;
        }
        .signature p {
            margin-top: 10px;
            line-height: 1.2;
        }
        .float-right {
            text-align: right;
            margin-top: 10px;
        }
        .float-right button {
            padding: 5px 10px;
            font-size: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 3px;
            box-shadow: 0px 1px 3px rgba(0,0,0,0.1);
        }
        .float-right button:hover {
            background-color: #0056b3;
        }
        .detail-info {
        display: flex;
        margin-top: -24px;
        flex-direction: column;
        }

        .detail-info p {
            margin: -1px 0;
            display: flex;
            justify-content: space-between;
        }

        .detail-info p strong {
            min-width: 50px; /* Sesuaikan dengan lebar maksimum label */
            font-size: 8px;
        }

        .detail-info p span {
            flex: 1;
            text-align: left;
            font-size: 8px;

        }
        .pemesanan p span{
            margin-top: 3px;
        }

        .pelanggan p span{
            margin-top: 3px;
        }
        .telepon p span{
            margin-top: 3px;
        }
        .alamat p span{
            margin-top: 3px;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="text">
                <h1>JAVA BAKERY</h1>
                <p>Jl. HOS. Cokro Aminoto No.5,</p>
                <p>Telepon: xxxxxx</p>
            </div>
            {{-- <div class="logo">
                <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
            </div> --}}
        </div>
            <hr class="divider">
            <hr class="divider">
        
        <div class="section">
            <h2>Surat Pemesanan Produk</h2>
            <p style="text-align: right; font-size: 8px;">
                <p style="text-align: right; font-size: 8px;">
                    {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->locale('id')->translatedFormat('l, d F Y H:i') }}
                </p>
            
            
            <div class="detail-info">
                <div class="pemesanan">
                    <p>No Pemesanan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; <span>{{ $pemesanan->kode_pemesanan }}</span></p>
                </div>
                <div class="pemesanan">
                    <p>Kasir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        :&nbsp; <span>{{ ucfirst(auth()->user()->karyawan->nama_lengkap)  }}</span></p>
                </div>
                <div class="pelanggan">
                    <p>Pelanggan&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp; <span>{{ $pemesanan->nama_pelanggan }}</span></p>
                </div>
                <div class="telepon">
                    <p>Telepon &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; <span>0{{ $pemesanan->telp }}</span></p>

                </div>
                <div class="alamat">
                    <p>Alamat Tujuan&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; <span>{{ $pemesanan->alamat }}</span></p>

                </div>
            </div>
            
            <h5 style="font-size: 12px " class="mt-2"><strong>Detail Pemesanan</strong></h5>
            @if($pemesanan->detailpemesananproduk->isEmpty())
                <p>Tidak ada detail pemesanan produk.</p>
            @else
            <table style="font-size: 12px; width: 100%;">
                <thead>
                    <tr>
                        <th style="font-size: 8px;">Kode Produk</th>
                        <th style="font-size: 8px;">Nama Produk</th>
                        <th style="font-size: 8px;">Jumlah</th>
                        <th style="font-size: 8px;">Diskon</th>
                        <th style="font-size: 8px;">Harga</th>
                        <th style="font-size: 8px;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                    @endphp
                    @foreach($pemesanan->detailpemesananproduk as $detail)
                        <tr>
                            <td style="font-size: 8px;">{{ $detail->kode_produk }}</td>
                            <td style="font-size: 8px;">{{ $detail->nama_produk }}</td>
                            <td style="font-size: 8px;">{{ $detail->jumlah }}</td>
                            <td style="font-size: 8px;">
                                @if ($detail->diskon > 0)
                                    {{ $detail->diskon }} %
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-size: 8px;">{{ $detail->harga }}</td>
                            <td style="font-size: 8px;">{{ $detail->total }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" style="text-align: right; font-size: 8px;"><strong>Total Bayar</strong></td>
                        <td style="font-size: 8px;">{{ $pemesanan->sub_total }}</td>
                    </tr>
                </tbody>
            </table>
            
            @endif
        </div>
        
        <div class="signatures">
            <div class="signature">
                <p>Pelanggan</p>
                <br><br>
                <p style="text-decoration: underline;">{{ $pemesanan->nama_pelanggan }}</p>
            </div>
            {{-- <div class="signature">
                <p>Admin</p>
                <br><br>
                <p style="text-decoration: underline;">{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}</p>
              
            </div> --}}
            <div class="signature">
                <p>Pemilik</p>
                <p>-</p>
                <p>__________________</p>
                <!-- Placeholder for owner signature -->
            </div>
        </div>
        {{-- <div class="float-right">
            <a href="{{ route('admin.pemesanan_produk.cetak-pdf', $pemesanan->id) }}" target="_blank" class="btn btn-primary">Cetak PDF</a>
        </div> --}}
        <div class="float-right">
            <a href="{{ route('admin.pemesanan_produk.cetak-pdf', $pemesanan->id) }}" target="_blank" class="btn btn-primary btn-sm">Cetak PDF</a>
                <i class="fas fa-print"></i> 
            </a>
        </div>
    </div>  
    <div class="float-left">
        <a href="{{ url('admin/pemesanan_produk') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus">Kembali</i>
        </a>
    </div>
</div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
