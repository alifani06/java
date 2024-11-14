@extends('layouts.app')

@section('title', 'Laporan BK')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>

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
                    <h1 class="m-0">Penjualan Toko</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Penjualan Toko</li>
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
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                    value="{{ Request::get('tanggal_penjualan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_penjualan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id">
                                    <option value="">- Semua Toko -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                            
                            
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                               
                            </div>
                        </div>
                    </form>
            
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 13px">                        <thead >
                        <tr>
                            <th>No</th>
                            {{-- <th>Tanggal Penjualan</th> --}}
                            <th style="text-align: center">Kode Produk</th>
                            <th style="text-align: center; width: 200px;">Nama Produk</th> <!-- Lebar diperbesar -->
                            <th style="text-align: center">Jumlah</th>
                            <th style="text-align: center">Harga Satuan</th>
                            <th style="text-align: center">Penjualan Kotor</th>
                            <th style="text-align: center">Diskon</th>
                            <th style="text-align: center">Penjualan Bersih</th>
                        </tr>
                        
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($finalResults as $produk)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    {{-- <td>{{ \Carbon\Carbon::parse($produk['tanggal_penjualan'])->translatedFormat('d F Y') }}</td> --}}
                                    <td>{{ $produk['kode_lama'] }}</td>
                                    <td>{{ $produk['nama_produk'] }}</td>
                                    <td style="text-align: right">{{ $produk['jumlah'] }}</td>
                                    <td style="text-align: right">{{ number_format($produk['harga'], 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($produk['penjualan_kotor'], 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($produk['diskon'], 0, ',', '.') }}</td>
                                    <td style="text-align: right">{{ number_format($produk['penjualan_bersih'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            @php
                                $totalJumlah = collect($finalResults)->sum('jumlah');
                                $grandTotal = collect($finalResults)->sum('penjualan_bersih');
                                $totalDiskon = collect($finalResults)->sum('diskon');
                                $totalKotor = collect($finalResults)->sum('penjualan_kotor');
                            @endphp
                            <tr>
                                <th colspan="3">Total</th>
                                <th style="text-align: right">{{ $totalJumlah }}</th>
                                <th></th>
                                <th style="text-align: right">{{ number_format($totalKotor, 0, ',', '.') }}</th>
                                <th style="text-align: right">{{ number_format($totalDiskon, 0, ',', '.') }}</th>
                                <th style="text-align: right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
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
        function filterProduk() {
            var klasifikasiId = document.getElementById('klasifikasi').value;
            var produkSelect = document.getElementById('produk');
            var produkOptions = produkSelect.options;
    
            // Reset produk display first
            for (var i = 0; i < produkOptions.length; i++) {
                produkOptions[i].style.display = "none"; // Hide all options
            }
    
            // Show options based on klasifikasiId
            for (var i = 0; i < produkOptions.length; i++) {
                var option = produkOptions[i];
                // Tampilkan semua produk jika klasifikasiId tidak dipilih
                if (klasifikasiId === "" || option.getAttribute('data-klasifikasi') == klasifikasiId) {
                    option.style.display = "block"; // Show relevant options
                }
            }
    
            // Reset the selected value of the product select box
            produkSelect.selectedIndex = 0;
        }
    </script>
    

    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_penjualan');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
    
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
                var today = new Date().toISOString().split('T')[0];
                tanggalAkhir.value = today;
                tanggalAkhir.setAttribute('min', this.value);
            }
        });
    
        function cari() {
            var form = document.getElementById('form-action');
            form.action = "{{ url('admin/penjualan_toko') }}";
        form.submit();
     }
    </script>









@endsection
