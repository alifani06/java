@extends('layouts.app')

@section('title', 'Produks')

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
                    <h1 class="m-0">Grafik Penjualan</h1>
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
                </div>
                <div class="card-body">
                <div class="content">
                    <div class="container-fluid">
                        <div class="row justify-content-center"> <!-- Tambahkan justify-content-center untuk memusatkan grafik -->
                            <div class="col-lg-8"> <!-- Kurangi lebar col-lg dari 12 ke 8 -->
                                <div class="card">
                                    <div class="card-header border-0">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="card-title">Penjualan</h3>
                                            {{-- <a href="javascript:void(0);">View Report</a> --}}
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="position-relative mb-4" style="max-width: 600px; margin: auto;"> <!-- Batasi lebar dengan max-width -->
                                            <canvas id="sales-chart" height="200"></canvas>
                                        </div>
            
                                        <div class="d-flex flex-row justify-content-end">
                                            <span class="mr-2">
                                                <i class="fas fa-square text-primary"></i> Penjualan Bersih
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.container-fluid -->
                </div>
            </div>
            </div>
            
            <!-- Tambahkan ini sebelum akhir tag body -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                var salesData = @json($finalResults);
            
                // Proses data penjualan bersih untuk setiap hari
                var labels = [];
                var dataPenjualanBersih = [];
            
                // Loop melalui data dan format untuk Chart.js
                for (var key in salesData) {
                    if (salesData.hasOwnProperty(key)) {
                        var penjualan = salesData[key];
                        labels.push(penjualan.tanggal_penjualan); // Tambahkan tanggal penjualan sebagai label
                        dataPenjualanBersih.push(penjualan.penjualan_bersih); // Tambahkan penjualan bersih sebagai data
                    }
                }
            
                // Konfigurasi untuk bar chart
                var ctx = document.getElementById('sales-chart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Penjualan Bersih',
                            data: dataPenjualanBersih,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Grafik Penjualan Bersih Harian'
                            }
                        }
                    }
                });
            </script>
            
            
    </section>

@endsection
