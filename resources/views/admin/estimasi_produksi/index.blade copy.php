@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .permintaan-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .permintaan-header:hover {
            background-color: #f0f0f0;
        }
        .permintaan-header.active {
            background-color: #e0e0e0;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 10); // Adjust the delay time as needed
        });
    </script>

    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Estimasi Produksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            </script>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('error') }}
                </div>
            @endif

        <div class="card">
                <div class="card-header">
                    <div class="float-Left">
                        <h3 class="card-title">Atas Pesanan</h3>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Tabel -->
                    <table id="data" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Produk</th>
                                <th>Kode Produk</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemesananProduk as $produkId => $tokoDetails)
                                @php
                                    $produk = $tokoDetails->first()['produk'];
                                    $totalJumlah = $tokoDetails->sum('jumlah');
                                @endphp
                                <tr class="dropdown" data-pemesanan-id="{{ $produkId }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->kode_produk }}</td>
                                    <td>{{ $totalJumlah }}</td>
                                </tr>
                                <tr class="permintaan-details1" id="details1-{{ $produkId }}" style="display: none;">
                                    <td colspan="6">
                                        <table class="table table-bordered" style="font-size: 13px;">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Cabang</th>
                                                    <th>Tanggal</th>
                                                    <th>Kode Pemesanan</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tokoDetails as $tokoDetail)
                                                    @foreach ($tokoDetail['detail'] as $detail)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($detail->pemesananProduk->tanggal_kirim)->format('d-m-Y H:i') }}</td>
                                                            <td>{{ $detail->pemesananProduk->kode_pemesanan }}</td>
                                                            <td>{{ $detail->jumlah }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    
                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog"
                        aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                    <h4 class="mt-2">Sedang Menyimpan...</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="float-Left">
                    <h3 class="card-title">Atas Permintaan</h3>
                </div>
            </div>
            <div class="card-body">
            
                <table id="datatables67" class="table table-bordered" style="font-size: 13px">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Produk</th>
                            <th>Kode Produk</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permintaanProduks as $produkId => $tokoDetails)
                        @php
                        // Cek apakah $tokoDetails memiliki data dan apakah produk ada
                        $firstDetail = $tokoDetails->first();
                        $produk = $firstDetail ? $firstDetail['produk'] : null;
                        $totalJumlah = $tokoDetails->sum('jumlah');
                    @endphp
                    <tr class="dropdown" data-permintaan-id="{{ $produkId }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($produk)
                                {{ $produk->nama_produk }}
                            @else
                                Produk Tidak Ditemukan
                            @endif
                        </td>
                        <td>
                            @if ($produk)
                                {{ $produk->kode_produk }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>{{ $totalJumlah }}</td>
                    </tr>
                    
                        <tr class="permintaan-details" id="details-{{ $produkId }}" style="display: none;">
                            <td colspan="4">
                                <table class="table table-bordered" style="font-size: 13px;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Cabang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tokoDetails as $tokoDetail)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                                <td>{{ $tokoDetail['jumlah'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    
                    </tbody>
                </table>
                
            </div>
        </div>
    </section>

<script>
    $(document).ready(function() {
    $('tbody tr.dropdown').click(function(e) {
        // Memeriksa apakah yang diklik adalah checkbox
        if ($(e.target).is('input[type="checkbox"]')) {
            return; // Jika ya, hentikan eksekusi
        }

        // Menyembunyikan detail untuk baris yang tidak dipilih
        $('tbody tr.dropdown').not(this).removeClass('selected').css('background-color', '');
        $('.permintaan-details').not('#details-' + $(this).data('permintaan-id')).hide();

        // Toggle visibility untuk detail baris yang dipilih
        var detailRowId = $(this).data('permintaan-id');
        var detailRow = $('#details-' + detailRowId);
        var isActive = detailRow.is(':visible');

        // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
        $('tr.dropdown').removeClass('selected').css('background-color', '');
        
        if (isActive) {
            detailRow.hide(); // Menyembunyikan detail jika sudah ditampilkan
        } else {
            $(this).addClass('selected').css('background-color', '#b0b0b0'); // Menambahkan kelas 'selected' dan mengubah warna latar belakangnya
            detailRow.show(); // Menampilkan detail jika belum ditampilkan
        }

        // Menyembunyikan dropdown pada baris lain yang tidak dipilih
        $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

        // Mencegah event klik menyebar ke atas (misalnya, saat mengklik dropdown)
        e.stopPropagation();
    });

    $('tbody tr.dropdown').contextmenu(function(e) {
        // Memeriksa apakah baris ini memiliki kelas 'selected'
        if ($(this).hasClass('selected')) {
            // Menampilkan dropdown saat klik kanan
            var dropdownMenu = $(this).find('.dropdown-menu');
            dropdownMenu.show();

            // Mendapatkan posisi td yang diklik
            var clickedTd = $(e.target).closest('td');
            var tdPosition = clickedTd.position();

            // Menyusun posisi dropdown relatif terhadap td yang di klik
            dropdownMenu.css({
                'position': 'absolute',
                'top': tdPosition.top + clickedTd.height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                'left': tdPosition.left // Menempatkan dropdown di sebelah kiri td yang di klik
            });

            // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
            e.stopPropagation();
            e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
        }
    });

    // Menyembunyikan dropdown saat klik di tempat lain
    $(document).click(function() {
        $('.dropdown-menu').hide();
        $('tr.dropdown').removeClass('selected').css('background-color', ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
    });
});
</script>

<script>
    $(document).ready(function() {
    $('tbody tr.dropdown').click(function(e) {
        // Memeriksa apakah yang diklik adalah checkbox
        if ($(e.target).is('input[type="checkbox"]')) {
            return; // Jika ya, hentikan eksekusi
        }

        // Menyembunyikan detail untuk baris yang tidak dipilih
        $('tbody tr.dropdown').not(this).removeClass('selected').css('background-color', '');
        $('.permintaan-details1').not('#details1-' + $(this).data('pemesanan-id')).hide();

        // Toggle visibility untuk detail baris yang dipilih
        var detailRowId = $(this).data('pemesanan-id');
        var detailRow = $('#details1-' + detailRowId);
        var isActive = detailRow.is(':visible');

        // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
        $('tr.dropdown').removeClass('selected').css('background-color', '');
        
        if (isActive) {
            detailRow.hide(); // Menyembunyikan detail jika sudah ditampilkan
        } else {
            $(this).addClass('selected').css('background-color', '#b0b0b0'); // Menambahkan kelas 'selected' dan mengubah warna latar belakangnya
            detailRow.show(); // Menampilkan detail jika belum ditampilkan
        }

        // Menyembunyikan dropdown pada baris lain yang tidak dipilih
        $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

        // Mencegah event klik menyebar ke atas (misalnya, saat mengklik dropdown)
        e.stopPropagation();
    });

    $('tbody tr.dropdown').contextmenu(function(e) {
        // Memeriksa apakah baris ini memiliki kelas 'selected'
        if ($(this).hasClass('selected')) {
            // Menampilkan dropdown saat klik kanan
            var dropdownMenu = $(this).find('.dropdown-menu');
            dropdownMenu.show();

            // Mendapatkan posisi td yang diklik
            var clickedTd = $(e.target).closest('td');
            var tdPosition = clickedTd.position();

            // Menyusun posisi dropdown relatif terhadap td yang di klik
            dropdownMenu.css({
                'position': 'absolute',
                'top': tdPosition.top + clickedTd.height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                'left': tdPosition.left // Menempatkan dropdown di sebelah kiri td yang di klik
            });

            // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
            e.stopPropagation();
            e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
        }
    });

    // Menyembunyikan dropdown saat klik di tempat lain
    $(document).click(function() {
        $('.dropdown-menu').hide();
        $('tr.dropdown').removeClass('selected').css('background-color', ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
    });
});
</script>



@endsection

