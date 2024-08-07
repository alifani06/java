@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .klasifikasi-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .klasifikasi-header:hover {
            background-color: #f0f0f0;
        }
        .klasifikasi-header.active {
            background-color: #e0e0e0;
        }
        .produk-table {
            display: none;
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
                    <h1 class="m-0">Pengiriman Barang Jadi</h1>
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
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
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
                
                <!-- /.card-header -->
                <div class="card-body">
                    

                    <form action="{{ url('admin/pengiriman_barangjadi') }}" method="POST">
                        @csrf
                        <input type="hidden" name="toko_id" > <!-- Assuming $toko is passed from the controller -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <div class="col-md-3 mb-3">
                                            <select class="custom-select form-control" id="toko" name="toko_id">
                                                <option value="">- Pilih Toko -</option>
                                                @foreach ($tokos as $toko)
                                                    <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </thead>
                                    <tbody>
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <tr class="klasifikasi-header" data-klasifikasi-id="{{ $klasifikasi->id }}">
                                                <th>{{ $klasifikasi->nama }}</th>
                                            </tr>
                                            <tr class="produk-table" id="produk-table-{{ $klasifikasi->id }}">
                                                <td colspan="1">
                                                    <table class="table table-bordered" style="font-size: 13px;">
                                                        <div class="col-sm-12 text-right">
                                                            <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." style="display: inline-block; width: auto; margin-bottom: 10px;">
                                                        </div>
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Kode Produk</th>
                                                                <th>Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($klasifikasi->produks as $produk)
                                                                <tr class="produk-row">
                                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                                    <td class="kode-produk">{{ $produk->kode_produk }}</td>
                                                                    <td>
                                                                        <input type="number" class="form-control" id="produk-{{ $produk->id }}" name="produk[{{ $produk->id }}][jumlah]" min="0" style="width: 100px; height: 30px;">
                                                                    </td>
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
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                    
             
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
    </section>

    <script>
        $(document).ready(function(){
            $('.klasifikasi-header').click(function(){
                var klasifikasiId = $(this).data('klasifikasi-id');
                var $produkTable = $('#produk-table-' + klasifikasiId);
    
                if ($produkTable.is(':visible')) {
                    $produkTable.hide();
                    $(this).removeClass('active');
                } else {
                    // Hide all produk tables and remove active class from all headers
                    $('.produk-table').hide();
                    $('.klasifikasi-header').removeClass('active');
    
                    // Show the selected produk table and add active class to the clicked header
                    $produkTable.show();
                    $(this).addClass('active');
                }
            });
    
           
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                var searchValue = searchInput.value.toLowerCase();
                var produkRows = document.querySelectorAll('.produk-row');
                
                produkRows.forEach(function(row) {
                    var kodeProduk = row.querySelector('.kode-produk').textContent.toLowerCase();
                    if (kodeProduk.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
    

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                var searchValue = searchInput.value.toLowerCase();
                var produkRows = document.querySelectorAll('.produk-row');
                
                produkRows.forEach(function(row) {
                    var kodeProduk = row.querySelector('.kode-produk').textContent.toLowerCase();
                    var namaProduk = row.querySelector('.nama-produk').textContent.toLowerCase();
                    if (kodeProduk.includes(searchValue) || namaProduk.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection