@extends('layouts.app')

@section('title', 'Data Pemesanan Produk')

@section('content')
<style>
    /* Gaya untuk menu konteks */
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

<!-- Header Konten (halaman header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pemesanan Produk</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.inquery_pemesananproduk.index') }}">Data Pemesanan Produk</a></li>
                    <li class="breadcrumb-item active">Data yang Difilter</li>
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
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 1000,
                        showConfirmButton: false
                    });
                });
            </script>
        @endif

        <!-- Form filter -->
        <div class="row mb-3" style="font-size: 14px">
            <form action="{{ route('admin.inquery_pemesananproduk.index') }}" method="get" class="form-inline">
                <div class="col-mb-3 ml-2">
                    <label for="start_date" class="mr-2">Dari Tanggal:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control mr-2" value="{{ request('start_date') }}">
                </div>
                <div class="col-mb-3">
                    <label for="end_date" class="mr-2">Sampai Tanggal:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control mr-2" value="{{ request('end_date') }}">
                </div>
                <div class="col-mb-3" style="margin-top: 22px">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                </div>
            </form>
        </div>

        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <table id="datatables1" class="table table-bordered table-hover" style="font-size: 14px">
                    <thead class="table-secondary">
                        <tr>
                            <th>No</th>
                            <th>Kode Pemesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grandTotal = 0.0; // Reset grand total untuk perhitungan baru setelah filter
                        @endphp
                        @foreach ($pemesanans as $index => $pemesanan)
                            @foreach ($pemesanan->detailpemesananproduk as $detail)
                                <tr>
                                    <td class="text-center">{{ $loop->parent->iteration }}</td>
                                    <td>{{ $pemesanan->kode_pemesanan }}</td>
                                    <td>{{ $pemesanan->nama_pelanggan }}</td>
                                    <td>{{ $detail->nama_produk }}</td>
                                    <td>{{ number_format((float) $detail->harga, 0, ',', '.') }}</td> 
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>{{ number_format((float) $detail->total, 0, ',', '.') }}</td> 
                                </tr>
                                @php
                                    $grandTotal += (float) $detail->total; // Menambahkan $detail->total ke $grandTotal
                                @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="text-align: right;">Total Keseluruhan</th>
                            <th>{{ 'Rp. ' . number_format($grandTotal, 0, ',', '.') }}</th> <!-- Format grand total tanpa desimal dan pemisah ribuan titik (.) -->
                        </tr>
                    </tfoot>
                </table>

                <!-- Menu Konteks -->
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

                        // Sembunyikan menu konteks saat mengklik di luarnya
                        $(document).on('click', function(e) {
                            if (!$(e.target).closest('#context-menu').length) {
                                $('#context-menu').hide();
                            }
                        });

                        // Tangani opsi menu konteks
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

        </div>
    </div>
</section>

@endsection
