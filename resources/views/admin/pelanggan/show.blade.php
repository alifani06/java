@extends('layouts.app')

@section('title', 'Lihat Pelanggan')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Kartu Member</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/pelanggan') }}">Pelanggan</a>
                        </li>
                        <li class="breadcrumb-item active">Lihat</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
   
        .card {
            width: 500px;
            height: 270px; 
            background-color: rgb(183, 181, 181);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 0 auto;
            position: relative; /* Membuat posisi relatif untuk dapat mengatur absolut */
        }
        .qr-code {
            position: absolute;
            bottom: 20px ; /* Jarak dari atas */
            right: 20px; /* Jarak dari kanan */
        }
        .qr-code img {
            max-width: 80px; /* Ukuran maksimum QR Code */
        }
        .info {
            margin-bottom: 10px;
        }
        .info label {
            position: absolute;
            top: 10px ; /* Jarak dari atas */
            right: 20px; /* Jarak dari kanan */
            font-weight: bold;
            font-size: 200%;
        
        }
        .info1 {
            position: absolute;
            top: 150px ; /* Jarak dari atas */
            left: 50px; /* Jarak dari kanan */
            font-weight: bold;
            font-size: 200%;
        }
        .info2 {
            position: absolute;
            top: 190px ; /* Jarak dari atas */
            left: 50px; /* Jarak dari kanan */
            font-weight: bold;
            font-size: 130%;
            font-family: monospace
        }
        .logo {
            position: absolute;
            top: 2px; /* Jarak dari atas */
            left: 5px; /* Jarak dari kiri */
        }
        .logo img {
            max-width: 100px; /* Atur ukuran maksimum logo */
        }

        .info3 {
            position: absolute;
            top: 60px; /* Jarak dari atas */
            left: 20px; /* Jarak dari kiri */
            right: 20px; /* Jarak dari kanan */
            font-weight: bold;
            font-size: 200%;
            border-bottom: 2px solid black; /* Menambahkan garis bawah */
            padding-bottom: 5px; /* Jarak antara teks dan garis bawah */
            display: flex;
            justify-content: space-between;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="logo">
                        <img  src="{{ asset('storage/uploads/icon/bakery.png') }}">
                    </div>
                  
                    <div class="info">
                        <label for="kode_pelanggan">MEMBER CARD</label>
                 <div class="info3">

                 </div>
                    <div class="info1">
                        <span>{{ $pelanggan->nama_pelanggan }}</span>
                    </div>
    
                    <div class="info2">
                        <span>{{ $pelanggan->kode_pelanggan }}</span>
                    </div>
    
                    <div class="qr-code" data-bs-toggle="modal" data-bs-target="#modal-qrcode-{{ $pelanggan->id }}"
                        style="display: inline-block;">
                        {!! DNS2D::getBarcodeHTML("$pelanggan->qrcode_pelanggan", 'QRCODE', 2, 2) !!}
                    </div>
                </div>

            </div>     
      </div>
    </section>
@endsection



            


    {{-- <body>
            <div class="card">

                <div class="logo">
                    <img  src="{{ asset('storage/uploads/icon/bakery.png') }}">
                </div>
              
                <div class="info">
                    <label for="kode_pelanggan">MEMBER CARD</label>
             
                <div class="info1">
                    <span>{{ $pelanggan->nama_pelanggan }}</span>
                </div>

                <div class="info2">
                    <span>{{ $pelanggan->kode_pelanggan }}</span>
                </div>

                <div class="qr-code" data-bs-toggle="modal" data-bs-target="#modal-qrcode-{{ $pelanggan->id }}"
                    style="display: inline-block;">
                    {!! DNS2D::getBarcodeHTML("$pelanggan->qrcode_pelanggan", 'QRCODE', 2, 2) !!}
                </div>
    
            </div>
        </body> --}}