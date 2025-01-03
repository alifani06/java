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
                   
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables66" class="table table-bordered table-striped table-hover" style="font-size: 12px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th style="text-align: center">No Pemesanan</th>
                                <th style="text-align: center">Nama Kasir</th>
                                <th style="text-align: center">Nama Pelanggan</th>
                                <th style="text-align: center">Metode Pembayaran</th>
                                <th style="text-align: center">Fee Pemesanan</th>
                                <th style="text-align: center">Total Deposit</th>
                                <th style="text-align: center">Total Pemesanan</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                            @php
                                $grandTotal = 0;
                                $grandTotalFee = 0;
                                $grandTotalDp = 0; 

                            @endphp
                            @foreach ($inquery as $item)
                                @php
                                    // Konversi sub_total ke angka murni
                                    $sub_total = preg_replace('/[^\d]/', '', $item->sub_total); 
                                    $sub_total = (float) $sub_total; // Pastikan nilai float
                                    $grandTotal += $sub_total;
                        
                                    // Konversi total_fee ke angka murni
                                    $total_fee = preg_replace('/[^\d]/', '', $item->total_fee ?? 0);
                                    $total_fee = (float) $total_fee;
                                    $grandTotalFee += $total_fee;

                                    $dp_pemesanan = $item->dppemesanan->dp_pemesanan ?? 0; 
                                    $grandTotalDp += $dp_pemesanan; 
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('pemesananprodukclc.detailpemesanan', ['id' => $item->id]) }}" target="_blank">
                                            {{ $item->kode_pemesanan }}
                                        </a>
                                    </td>
                                    <td>{{ $item->kasir ?? '-' }}</td>
                                    <td>
                                        @if($item->kode_pelanggan)
                                            {{ $item->kode_pelanggan }} / {{ $item->nama_pelanggan }}
                                        @else
                                            Non Member
                                        @endif
                                    </td>
                                    <td>{{ $item->metodepembayaran->nama_metode ?? 'Tunai' }}</td>
                                    <td style="text-align: right">
                                        @if ($total_fee == 0)
                                            -
                                        @else
                                            {{ number_format($total_fee, 0, ',', '.') }}
                                        @endif
                                    </td>
                                    <td style="text-align: right">
                                        @php
                                            $dp_pemesanan = $item->dppemesanan->dp_pemesanan ?? 0; // Default ke 0 jika tidak ada data
                                        @endphp
                                        {{ number_format($dp_pemesanan, 0, ',', '.') }}
                                    </td>
                                    
                                    <td style="text-align: right">
                                        {{ number_format($sub_total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-center">Total</th>
                                <th style="text-align: right">{{ number_format($grandTotalFee, 0, ',', '.') }}</th>
                                <th style="text-align: right">{{ number_format($grandTotalDp, 0, ',', '.') }}</th> 
                                <th style="text-align: right">{{ number_format($grandTotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    

                    
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>


@endsection
