@extends('layouts.app')

@section('title', 'Edit Pengiriman Barang Jadi')

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
                    <h1 class="m-0">Edit Pengiriman Barang Jadi (Permintaan)</h1>
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
                        <select class="form-control" id="kategori1" name="kategori">
                            <option value="">- Pilih -</option>
                            <option value="permintaan" {{ old('kategori1') == 'permintaan' ? 'selected' : '' }}>Pengiriman Permintaan</option>
                            <option value="pemesanan" {{ old('kategori1') == 'pemesanan' ? 'selected' : '' }}>Pengiriman Pesanan</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    
                    <form action="{{ url('admin/inquery_pengirimanbarangjadi/update/' . $uniqueStokBarangjadi->first()->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="toko_id" value="{{ $uniqueStokBarangjadi->first()->toko_id }}">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <div class="col-md-3 mb-3">
                                            <select class="custom-select form-control" id="toko" name="toko_id">
                                                <option value="">- Pilih Toko -</option>
                                                @foreach ($tokos as $toko)
                                                    <option value="{{ $toko->id }}" {{ $uniqueStokBarangjadi->first()->toko_id == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </thead>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><span></span></h3>
                                            <div class="float-right">
                                                <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <table class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th style="font-size:14px" class="text-center">No</th>
                                                                <th style="font-size:14px">Kode Produk</th>
                                                                <th style="font-size:14px">Nama Produk</th>
                                                                <th style="font-size:14px">Jumlah</th>
                                                                <th style="font-size:14px; text-align:center">Opsi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tabel-pembelian">
                                                            @foreach ($uniqueStokBarangjadi as $item)
                                                                <tr id="pembelian-{{ $loop->iteration }}">
                                                                    <td style="width: 70px; font-size:14px" class="text-center" id="urutan-{{ $loop->iteration }}">{{ $loop->iteration }}</td>
                                                                    <td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-{{ $loop->iteration }}" name="produk_id[]" value="{{ $item->produk_id }}"></div></td>
                                                                    <td onclick="showCategoryModal({{ $loop->iteration }})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-{{ $loop->iteration }}" name="kode_produk[]" value="{{ $item->kode_produk }}"></div></td>
                                                                    <td onclick="showCategoryModal({{ $loop->iteration }})"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-{{ $loop->iteration }}" name="nama_produk[]" value="{{ $item->nama_produk }}"></div></td>
                                                                    <td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-{{ $loop->iteration }}" name="jumlah[]" value="{{ $item->jumlah }}" oninput="hitungTotal({{ $loop->iteration }})"></div></td>
                                                                    <td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal({{ $loop->iteration }})"><i class="fas fa-plus"></i></button></td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="3" style="font-size:14px">Total</td>
                                                                <td><div class="form-group"><input type="number" class="form-control" id="total-jumlah" name="total_jumlah" readonly></div></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    <script>
        function showCategoryModal(rowId) {
            // Implement your modal logic here
        }
    </script>
@endsection
