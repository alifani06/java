@extends('layouts.app')

@section('title', 'Data produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Stok Barang Jadi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Stok Barang Jadi</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
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
                    <h3 class="card-title">Data Stok Barang Jadi</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Divisi</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produks as $produk)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $produk->klasifikasi->nama ?? 'N/A' }}</td> <!-- Handle null klasifikasi -->
                                    <td>{{ $produk->kode_produk }}</td>
                                    <td>{{ $produk->nama_produk }}</td>
                                    <td>
                                        @php
                                            $totalStok = 0;
                                            foreach ($produk->stok_barangjadii as $stokBarangjadi) {
                                                foreach ($stokBarangjadi->detail_stokbarangjadi as $detailStok) {
                                                    if ($detailStok->status === 'posting') {
                                                        $totalStok += $detailStok->stok;
                                                    }
                                                }
                                            }
                                            // Jika total stok adalah 0, pastikan tetap menampilkan 0
                                            $totalStok = $totalStok > 0 ? $totalStok : 0;
                                        @endphp
                                        {{ $totalStok }}
                                    </td>     
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection
