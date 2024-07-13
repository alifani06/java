@extends('layouts.app')

@section('title', 'Data produk')

@section('content')
<style>
    .context-menu {
        display: none;
        position: absolute;
        z-index: 1000;
        width: 150px;
        background-color: white;
        border: 1px solid #ccc;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    }
    .context-menu ul {
        list-style: none;
        padding: 5px 0;
        margin: 0;
    }
    .context-menu ul li {
        padding: 8px 12px;
        cursor: pointer;
    }
    .context-menu ul li:hover {
        background-color: #f2f2f2;
    }
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data pemesanan produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data pemesanan produk</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
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
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <a href="{{ url('admin/pemesanan_produk/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    {{-- <table id="datatables1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode pemesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>qty</th>
                                <th>Harga</th>
                                <th>Qrcode</th>
                                <th>opsi</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemesanans as $produk)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $produk->pemesananproduk->kode_pemesanan }}</td>
                                    <td>{{ $produk->pemesananproduk->nama_pelanggan }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->harga }}</td>
                                    <td>{{ $produk->jumlah }}</td>
                                    <td>{{ $produk->total }}</td>
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $produk->id }}"
                                        style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML($produk->pemesananproduk->qrcode_pemesanan, 'QRCODE', 1, 1) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="float-right">
                                            <a href="{{ route('admin.pemesanan_produk.cetak-pdf', $produk->pemesananproduk_id) }}" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-print"></i> 
                                            </a>
                                        </div>
                                    </td>
                                    
                                </tr>

                                <div class="modal fade" id="modal-qrcode-{{ $produk->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $produk->pemesananproduk->kode_pemesanan }}</p>
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML($produk->pemesananproduk->qrcode_pemesanan, 'QRCODE', 10, 10) !!}
                                                    </div>
                                                    <p style="font-size:20px; font-weight: bold;">
                                                        {{ $produk->pemesananproduk->nama_pelanggan }}</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/karyawan/cetak-pdf/' . $produk->id) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table> --}}
                    <table id="datatables1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode pemesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>qty</th>
                                <th>Harga</th>
                                <th>Qrcode</th>
                                <th>opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemesanans as $produk)
                                <tr data-id="{{ $produk->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $produk->pemesananproduk->kode_pemesanan }}</td>
                                    <td>{{ $produk->pemesananproduk->nama_pelanggan }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>{{ $produk->harga }}</td>
                                    <td>{{ $produk->jumlah }}</td>
                                    <td>{{ $produk->total }}</td>
                                    <td data-toggle="modal" data-target="#modal-qrcode-{{ $produk->id }}" style="text-align: center;">
                                        <div style="display: inline-block;">
                                            {!! DNS2D::getBarcodeHTML($produk->pemesananproduk->qrcode_pemesanan, 'QRCODE', 1, 1) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="float-right">
                                            <a href="{{ route('admin.pemesanan_produk.cetak-pdf', $produk->pemesananproduk_id) }}" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-print"></i> 
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modal-qrcode-{{ $produk->id }}">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Gambar QR Code</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div style="text-align: center;">
                                                    <p style="font-size:20px; font-weight: bold;">{{ $produk->pemesananproduk->kode_pemesanan }}</p>
                                                    <div style="display: inline-block;">
                                                        {!! DNS2D::getBarcodeHTML($produk->pemesananproduk->qrcode_pemesanan, 'QRCODE', 10, 10) !!}
                                                    </div>
                                                    <p style="font-size:20px; font-weight: bold;">{{ $produk->pemesananproduk->nama_pelanggan }}</p>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                    <a href="{{ url('admin/karyawan/cetak-pdf/' . $produk->id) }}" class="btn btn-primary btn-sm">
                                                        <i class=""></i> Cetak
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                <!-- Context Menu -->
                <div class="context-menu" id="context-menu">
                    <ul>
                        <li id="edit">Ubah</li>
                        <li id="delete">Hapus</li>
                    </ul>
                </div>

                <script>
                    $(document).ready(function() {
                        let currentRowId;
                        $('#datatables1 tbody tr').on('contextmenu', function(e) {
                    e.preventDefault();
                    currentRowId = $(this).data('id');
                    
                    // Mendapatkan koordinat klik
                    var posX = e.pageX;
                    var posY = e.pageY;
                    
                    // Menyesuaikan posisi dropdown agar muncul di samping kursor
                    $('#context-menu').css({
                        display: 'block',
                        position: 'fixed',
                        left: posX + 'px',
                        top: posY + 'px'
                    });
                    
                        return false; // Mencegah munculnya konteks menu browser bawaan
                    });

                        // Hide context menu on clicking outside
                        $(document).on('click', function(e) {
                            if (!$(e.target).closest('#context-menu').length) {
                                $('#context-menu').hide();
                            }
                        });
            
                        // Handle context menu options
                        $('#edit').on('click', function() {
                            window.location.href = '/admin/pemesanan_produk/edit/' + currentRowId;
                        });
            
                        $('#delete').on('click', function() {
                            if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
                                window.location.href = '/admin/pemesanan_produk/delete/' + currentRowId;
                            }
                        });
                    });
                </script>
                        </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection

