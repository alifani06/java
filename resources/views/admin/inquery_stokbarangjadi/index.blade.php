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
                    <h1 class="m-0">Inquery Stok Barang Jadi</h1>
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
                <div class="card-header">
                    <div class="float-right">
                        <a href="{{ url('admin/stok_barangjadi/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Tabel -->
                    <table id="datatables66" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Divisi</th>
                                <th>Kode Produk</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokBarangJadi as $index => $stok)
                            <tr class="dropdown"{{ $stok->id }}>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $stok->produk->klasifikasi->nama }}</td>
                                <td>{{ $stok->produk->kode_produk }}</td>
                                <td>{{ $stok->produk->nama_produk }}</td>
                                <td>
                                    @if ($stok->status == 'unpost')
                                        0
                                    @else
                                        {{ $stok->stok }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($stok->status == 'posting')
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if ($stok->status == 'unpost')
                                    <button type="button" class="unpost-btn btn btn-danger btn-sm" data-memo-id="{{ $stok->id }}">
                                        <i class="fas fa-times"></i> 
                                    </button>
                                    
                                    
                                    @endif
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if ($stok->status == 'unpost')
                                            <a class="dropdown-item posting-btn"
                                                data-memo-id="{{ $stok->id }}">Posting</a>
                                            <a class="dropdown-item"
                                                href="{{ url('admin/inquery_pemesananproduk/' . $stok->id . '/edit') }}">Update</a>
                                            <a class="dropdown-item"
                                                href="{{ url('/admin/pemesanan_produk/' . $stok->id ) }}">Show</a>
                                        @endif
                                        @if ($stok->status == 'posting')
                                            <a class="dropdown-item unpost-btn"
                                                data-memo-id="{{ $stok->id }}">Unpost</a>
                                            <a class="dropdown-item"
                                                href="{{ url('/admin/pemesanan_produk/' . $stok->id ) }}">Show</a>
                                        @endif
                                    </div>
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
    </section>

    <script>
        $(document).ready(function() {
            $('tbody tr.dropdown').click(function(e) {
                if ($(e.target).is('input[type="checkbox"]')) {
                    return;
                }

                $('tr.dropdown').removeClass('selected').css('background-color', '');

                $(this).addClass('selected').css('background-color', '#b0b0b0');

                $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

                e.stopPropagation();
            });

            $('tbody tr.dropdown').contextmenu(function(e) {
                if ($(this).hasClass('selected')) {
                    var dropdownMenu = $(this).find('.dropdown-menu');
                    dropdownMenu.show();

                    var clickedTd = $(e.target).closest('td');
                    var tdPosition = clickedTd.position();

                    dropdownMenu.css({
                        'position': 'absolute',
                        'top': tdPosition.top + clickedTd.height(),
                        'left': tdPosition.left
                    });

                    e.stopPropagation();
                    e.preventDefault();
                }
            });

            $(document).click(function() {
                $('.dropdown-menu').hide();
                $('tr.dropdown').removeClass('selected').css('background-color', '');
            });
        });
    </script>

    {{-- unpost stok  --}}
    <script>
        $(document).ready(function() {
            $('.unpost-btn').click(function() {
                var memoId = $(this).data('memo-id');
                $(this).addClass('disabled');

                $('#modal-loading').modal('show');

                $.ajax({
                    url: "{{ url('admin/inquery_stokbarangjadi/unpost_stokbarangjadi/') }}/" + memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        $('#modal-loading').modal('hide');
                        console.log(response);
                        $('#modal-posting-' + memoId).modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        $('#modal-loading').modal('hide');
                        console.log(error);
                    }
                });
            });
        });
    </script>

    {{-- posting stok --}}
    <script>
        $(document).ready(function() {
            $('.posting-btn').click(function() {
                var memoId = $(this).data('memo-id');
                $(this).addClass('disabled');

                $('#modal-loading').modal('show');

                $.ajax({
                    url: "{{ url('admin/inquery_stokbarangjadi/posting_stokbarangjadi/') }}/" + memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        $('#modal-loading').modal('hide');
                        console.log(response);
                        $('#modal-posting-' + memoId).modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        $('#modal-loading').modal('hide');
                        console.log(error);
                    }
                });
            });
        });
    </script>

  
@endsection
