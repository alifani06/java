@extends('layouts.app')

@section('title', 'Tambah Setoran')

@section('content')

<style>
    .custom-checkbox {
    margin-right: 10px; /* Atur nilai sesuai kebutuhan */
}
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pelunasan Penjualan</h1>
                </div><!-- /.col -->
             
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ url('admin/setoran_pelunasan') }}" method="POST" enctype="multipart/form-data" id="myForm">
            @csrf
                    <div class="card">
                        {{-- <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                        value="{{ Request::get('tanggal_penjualan') }}" onchange="updateModalLink()" />
                                        <label for="tanggal_penjualan">(Tanggal Penjualan)</label>

                                </div>
                                <div class="col-md-3 mb-3">
                                    <select class="custom-select form-control" id="toko" name="toko_id" onchange="updateModalLink()">
                                        <option value="">- Semua Toko -</option>
                                        @foreach ($tokos as $toko)
                                            <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                        @endforeach
                                    </select>
                                    <label for="tanggal_penjualan">(Pilih Toko)</label>

                                </div>
                                <div class="col-md-3 mb-3">
                                    <button type="button" id="btnCari" class="btn btn-outline-primary">Cari</button>
                                </div>
                            </div>
                             --}}


                            {{-- <div class="input-group mb-3">
                                <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="no_fakturpenjualantoko" name="no_fakturpenjualantoko" value="{{ old('no_fakturpenjualantoko') }}">
                                <button class="btn btn-outline-primary" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                    <i class="fas fa-search">Cari Faktur</i>
                                </button>
                            </div> --}}
                          
                        </div>
                
                        <div class="card-body">
                            <div class="form-group row mb-3">
                                <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                    <a id=""  data-toggle="modal"  class="text-decoration-none">No. Faktur Penjualan Toko </a>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" id="no_fakturpenjualantoko" name="no_fakturpenjualantoko" class="form-control" readonly />
                                </div>
                                <button class="btn btn-outline-primary" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>

                                <input type="text" id="setoran_id" name="id" class="form-control" hidden/>
                                {{-- <input type="text" id="tanggal_penjualan" name="tanggal_penjualan" class="form-control" hidden/> --}}
                          
                            {{-- <div class="form-group row mb-3">
                                <label for="no_fakturpenjualantoko" class="col-sm-3 col-form-label">
                                    <a  class="text-decoration-none">No. Faktur Penjualan Toko </a>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="no_fakturpenjualantoko" name="no_fakturpenjualantoko" readonly>
                                </div>
                          
                            </div> --}}
                            <div class="form-group row mb-3">
                                <label for="tanggal_setoran" class="col-sm-3 col-form-label">
                                    <a  class="text-decoration-none">Tanggal Penjualan</a>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" readonly>
                                </div>
                          
                            </div>


                            <div class="form-group row mb-3">
                                <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Penjualan Kotor</a>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="penjualan_kotor" name="penjualan_kotor" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="penjualan_kotor1" name="penjualan_kotor1">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="penjualan_selisih" name="penjualan_selisih" placeholder="" readonly>
                                    <small id="penjualan_keterangan" class="text-muted"></small>
                                </div>
                            </div>
                

                            <div class="form-group row mb-3">
                                <label for="diskon_penjualan" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Diskon Penjualan</a>
                                </label>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="diskon_penjualan" name="diskon_penjualan" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="diskon_penjualan1" name="diskon_penjualan1">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="diskon_selisih" name="diskon_selisih" placeholder="" readonly>
                                    <small id="diskon_keterangan" class="text-muted"></small>
                                </div>
                            </div>

                                <div class="col-sm-3 offset-sm-3">
                                    <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                                </div>

                            <div class="form-group row mb-3">
                                <label for="penjualan_bersih" class="col-sm-3 col-form-label">
                                    <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Penjualan Bersih</a>
                                </label>
                                                        
                                <div class="col-sm-3">
                                        <input type="text" class="form-control" id="penjualan_bersih" name="penjualan_bersih" readonly>
                                </div>
                                <div class="col-sm-3">
                                        <input type="text" class="form-control" id="penjualan_bersih1" name="penjualan_bersih1" >
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="penjualanbersih_selisih" name="penjualanbersih_selisih" placeholder="" readonly>
                                    <small id="penjualanbersih_keterangan" class="text-muted"></small>
                                </div>
                              
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="deposit_keluar" class="col-sm-3 col-form-label">
                                        <a id="deposit_keluar_link" href="#" data-toggle="modal" data-target="#depositKeluarModal" class="text-decoration-none">Deposit Keluar</a>
                                    </label>
                                                              
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="deposit_keluar" name="deposit_keluar" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="deposit_keluar1" name="deposit_keluar1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="depositkeluar_selisih" name="depositkeluar_selisih" placeholder="" readonly>
                                        <small id="depositkeluar_keterangan" class="text-muted"></small>
                                    </div>
                                 
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="deposit_masuk" class="col-sm-3 col-form-label">
                                        <a id="deposit_masuk_link" href="#" data-toggle="modal" data-target="#depositMasukModal" class="text-decoration-none">Deposit Masuk</a>
                                    </label>
                                                             
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="deposit_masuk" name="deposit_masuk" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="deposit_masuk1" name="deposit_masuk1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="depositmasuk_selisih" name="depositmasuk_selisih" placeholder="" readonly>
                                        <small id="depositmasuk_keterangan" class="text-muted"></small>
                                    </div>
                            </div>

                            <div class="col-sm-3 offset-sm-3">
                                    <hr style="border: 1px solid #000;"> <!-- Ubah nilai 2px sesuai ketebalan yang diinginkan -->
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="total_penjualan" class="col-sm-3 col-form-label">
                                        <a id="penjualan_kotor_link" href="#" data-toggle="modal" data-target="#penjualanKotorModal" class="text-decoration-none">Total Penjualan</a>
                                    </label>
                                                           
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="total_penjualan" name="total_penjualan" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="total_penjualan1" name="total_penjualan1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="totalpenjualan_selisih" name="totalpenjualan_selisih" placeholder="" readonly>
                                        <small id="totalpenjualan_keterangan" class="text-muted"></small>
                                    </div>
                                  
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="mesin_edc" class="col-sm-3 col-form-label">
                                        <a id="penjualan_mesinedc_link" href="#" data-toggle="modal" data-target="#penjualanMesinedcModal" class="text-decoration-none">Mesin EDC</a>
                                    </label>
                                                               
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="mesin_edc" name="mesin_edc" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="mesin_edc1" name="mesin_edc1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="mesinedc_selisih" name="mesinedc_selisih" placeholder="" readonly>
                                        <small id="mesinedc_keterangan" class="text-muted"></small>
                                    </div>
                                 
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="qris" class="col-sm-3 col-form-label">
                                        <a id="penjualan_qris_link" href="#" data-toggle="modal" data-target="#penjualanQrisModal" class="text-decoration-none">Qris</a>
                                    </label>
                                                              
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="qris" name="qris" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="qris1" name="qris1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="qris_selisih" name="qris_selisih" placeholder="" readonly>
                                        <small id="qris_keterangan" class="text-muted"></small>
                                    </div>
                                   
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="gobiz" class="col-sm-3 col-form-label">
                                        <a id="penjualan_gobiz_link" href="#" data-toggle="modal" data-target="#penjualanGobizModal" class="text-decoration-none">Gobiz</a>
                                    </label>
                                                             
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="gobiz" name="gobiz" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="gobiz1" name="gobiz1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="gobiz_selisih" name="gobiz_selisih" placeholder="" readonly>
                                        <small id="gobiz_keterangan" class="text-muted"></small>
                                    </div>
                                  
                            </div>

                            <div class="form-group row mb-3">
                                    <label for="transfer" class="col-sm-3 col-form-label">
                                        <a id="penjualan_transfer_link" href="#" data-toggle="modal" data-target="#penjualanTransferModal" class="text-decoration-none">Transfer</a>
                                    </label>
                                                               
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="transfer" name="transfer" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="transfer1" name="transfer1" >
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="transfer_selisih" name="transfer_selisih" placeholder="" readonly>
                                        <small id="transfer_keterangan" class="text-muted"></small>
                                    </div>
                                  
                            </div>


                            <div class="col-sm-3 offset-sm-3">
                                <hr style="border: 1px solid #000;"> 
                            </div>

                            <div class="form-group row mb-3">
                                <label for="total_setoran" class="col-sm-3 col-form-label">
                                    <a class="text-decoration-none">Total Setoran</a>
                                </label>
                                                        
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="total_setoran" name="total_setoran" readonly>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="total_setoran1" name="total_setoran1" >
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="totalsetoran_selisih" name="totalsetoran_selisih" placeholder="" readonly>
                                    <small id="totalsetoran_keterangan" class="text-muted"></small>
                                </div>
                             
                            </div>

                            <div class="card-footer text-right mt-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>                             
                                
                            </div>
                        </div>       
                    </div>  
                    
                  
        </form>

        </div>

        <div class="modal fade" id="tableMarketing" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Pelunasan Penjualan</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <table id="datatables4" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>No. Faktur</th>
                                    <th>Tanggal Penjualan</th>
                                    
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($setoranPenjualans as $item)
                                    <tr onclick="getSelectedDataPemesanan('{{ $item->no_fakturpenjualantoko }}', '{{ $item->penjualan_kotor }}', '{{ $item->diskon_penjualan }}'
                                    , '{{ $item->penjualan_bersih }}', '{{ $item->deposit_masuk }}', '{{ $item->deposit_keluar }}', '{{ $item->total_penjualan }}', '{{ $item->mesin_edc }}'
                                    , '{{ $item->qris }}', '{{ $item->gobiz }}', '{{ $item->transfer }}', '{{ $item->total_setoran }}', '{{ $item->tanggal_penjualan }}')">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                             
                                        <td>{{ $item->no_fakturpenjualantoko }}</td>
                                        <td>{{ $item->tanggal_penjualan }}</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm" >
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    

        <div class="modal fade" id="penjualanKotorModal" tabindex="-1" role="dialog" aria-labelledby="penjualanKotorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanKotorModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.penjualantoko.kotor') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a>

                            <a href="{{ route('print.fakturpenjualantoko') }}" 
                            id="faktur_penjualan_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="depositKeluarModal" tabindex="-1" role="dialog" aria-labelledby="depositKeluarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositKeluarModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p>Silakan pilih jenis laporan yang ingin ditampilkan:</p> --}}
                        <div class="d-flex justify-content-around">
                            {{-- <a href="{{ route('print.penjualantoko.kotor') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a> --}}

                            <a href="{{ route('print.fakturdepositkeluartoko') }}" 
                            id="faktur_deposit_keluar_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="depositMasukModal" tabindex="-1" role="dialog" aria-labelledby="depositMasukModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="depositMasukModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <p>Silakan pilih jenis laporan yang ingin ditampilkan:</p> --}}
                        <div class="d-flex justify-content-around">
                            {{-- <a href="{{ route('print.penjualantoko.kotor') }}" 
                            id="penjualan_kotor_link_modal" 
                            class="btn btn-primary" 
                            target="_blank">Barang Keluar</a> --}}

                            <a href="{{ route('print.fakturdepositmasuktoko') }}" 
                            id="faktur_deposit_masuk_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanMesinedcModal" tabindex="-1" role="dialog" aria-labelledby="penjualanMesinedcModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanMesinedcModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualanmesinedc') }}" 
                            id="penjualan_mesinedc_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesananmesinedc') }}" 
                            id="pemesanan_mesinedc_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanQrisModal" tabindex="-1" role="dialog" aria-labelledby="penjualanQrisModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanQrisModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualanqris') }}" 
                            id="penjualan_qris_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesananqris') }}" 
                            id="pemesanan_qris_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanTransferModal" tabindex="-1" role="dialog" aria-labelledby="penjualanTransferModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanTransferModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualantransfer') }}" 
                            id="penjualan_transfer_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesanantransfer') }}" 
                            id="pemesanan_transfer_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="penjualanGobizModal" tabindex="-1" role="dialog" aria-labelledby="penjualanGobizModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="penjualanGobizModalLabel">Pilih</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('print.fakturpenjualangobiz') }}" 
                            id="penjualan_gobiz_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Penjualan</a>

                            <a href="{{ route('print.fakturpemesanangobiz') }}" 
                            id="pemesanan_gobiz_link_modal" 
                            class="btn btn-secondary"
                            target="_blank">Faktur Deposit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>


<script>
    // Fungsi untuk memperbarui URL link di dalam modal
    function updateModalLink() {
        const tanggalPenjualan = document.getElementById('tanggal_penjualan').value;
        const tokoId = document.getElementById('toko').value;

        // Base URL untuk Barang Keluar (link di dalam modal)
        const baseUrlBarangKeluar = "{{ route('print.penjualantoko.kotor') }}";
        const baseUrlFakturPenjualan = "{{ route('print.fakturpenjualantoko') }}"; 
        const baseUrlFakturDeposit = "{{ route('print.fakturdepositmasuktoko') }}"; 
        const baseUrlFakturDepositKeluar = "{{ route('print.fakturdepositkeluartoko') }}"; 
        const baseUrlFakturPenjualanMesinedc = "{{ route('print.fakturpenjualanmesinedc') }}"; 
        const baseUrlFakturPemesananMesinedc = "{{ route('print.fakturpemesananmesinedc') }}"; 
        const baseUrlFakturPenjualanQris = "{{ route('print.fakturpenjualanqris') }}"; 
        const baseUrlFakturPemesananQris = "{{ route('print.fakturpemesananqris') }}"; 
        const baseUrlFakturPenjualanTransfer = "{{ route('print.fakturpenjualantransfer') }}"; 
        const baseUrlFakturPemesananTransfer = "{{ route('print.fakturpemesanantransfer') }}"; 
        const baseUrlFakturPenjualanGobiz = "{{ route('print.fakturpenjualangobiz') }}"; 
        const baseUrlFakturPemesananGobiz = "{{ route('print.fakturpemesanangobiz') }}"; 


        // Perbarui URL untuk Barang Keluar
        const urlBarangKeluar = new URL(baseUrlBarangKeluar, window.location.origin);
        if (tanggalPenjualan) {
            urlBarangKeluar.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlBarangKeluar.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('penjualan_kotor_link_modal').href = urlBarangKeluar.toString();

        // Perbarui URL untuk Faktur Penjualan
        const urlFakturPenjualan = new URL(baseUrlFakturPenjualan, window.location.origin); // Perbaikan nama variabel
        if (tanggalPenjualan) {
            urlFakturPenjualan.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPenjualan.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('faktur_penjualan_link_modal').href = urlFakturPenjualan.toString();

        const urlFakturDeposit = new URL(baseUrlFakturDeposit, window.location.origin); // Perbaikan nama variabel
        if (tanggalPenjualan) {
            urlFakturDeposit.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturDeposit.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('faktur_deposit_masuk_link_modal').href = urlFakturDeposit.toString();

        const urlFakturDepositKeluar = new URL(baseUrlFakturDepositKeluar, window.location.origin); // Perbaikan nama variabel
        if (tanggalPenjualan) {
            urlFakturDepositKeluar.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturDepositKeluar.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('faktur_deposit_keluar_link_modal').href = urlFakturDepositKeluar.toString();

        const urlFakturPenjualanMesinedc = new URL(baseUrlFakturPenjualanMesinedc, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanMesinedc.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPenjualanMesinedc.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('penjualan_mesinedc_link_modal').href = urlFakturPenjualanMesinedc.toString();

        const urlFakturPemesananMesinedc = new URL(baseUrlFakturPemesananMesinedc, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPemesananMesinedc.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPemesananMesinedc.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('pemesanan_mesinedc_link_modal').href = urlFakturPemesananMesinedc.toString();

        const urlFakturPenjualanQris = new URL(baseUrlFakturPenjualanQris, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanQris.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPenjualanQris.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('penjualan_qris_link_modal').href = urlFakturPenjualanQris.toString();

        const urlFakturPemesananQris = new URL(baseUrlFakturPemesananQris, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPemesananQris.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPemesananQris.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('pemesanan_qris_link_modal').href = urlFakturPemesananQris.toString();

        const urlFakturPenjualanTransfer = new URL(baseUrlFakturPenjualanTransfer, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanTransfer.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPenjualanTransfer.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('penjualan_transfer_link_modal').href = urlFakturPenjualanTransfer.toString();

        const urlFakturPemesananTransfer = new URL(baseUrlFakturPemesananTransfer, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPemesananTransfer.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPemesananTransfer.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('pemesanan_transfer_link_modal').href = urlFakturPemesananTransfer.toString();

        const urlFakturPenjualanGobiz = new URL(baseUrlFakturPenjualanGobiz, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPenjualanGobiz.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPenjualanGobiz.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('penjualan_gobiz_link_modal').href = urlFakturPenjualanGobiz.toString();

        const urlFakturPemesananGobiz = new URL(baseUrlFakturPemesananGobiz, window.location.origin);
        if (tanggalPenjualan) {
            urlFakturPemesananGobiz.searchParams.set('tanggal_penjualan', tanggalPenjualan);
            }
            if (tokoId) {
                urlFakturPemesananGobiz.searchParams.set('toko_id', tokoId);
            }
        document.getElementById('penjualan_gobiz_link_modal').href = urlFakturPemesananGobiz.toString();

    }

    // Pastikan modal dipicu dengan tautan yang benar saat ditampilkan
    $('#penjualanKotorModal').on('show.bs.modal', function () {
        updateModalLink(); // Panggil fungsi untuk memperbarui link di dalam modal
    });

    // Inisialisasi pertama
    document.addEventListener("DOMContentLoaded", function () {
        updateModalLink();
    });
</script>

    
   
    

    <!-- Tambahkan script JQuery untuk Ajax -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
    $('#btnCari').on('click', function () {
        var tanggalPenjualan = $('#tanggal_penjualan').val();
        var tokoId = $('#toko').val();

        if (tanggalPenjualan) {
            $.ajax({
                url: "{{ url('admin/get-penjualan1') }}", // Sesuaikan URL sesuai rute Anda
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal_penjualan: tanggalPenjualan,
                    toko_id: tokoId // Kirim toko_id
                },
                success: function (response) {
                    // Isi field-form dengan data dari respons
                    $('#setoran_id').val(response.id); 
                    $('#tanggal_setoran').val(response.tanggal_setoran); 
                    $('#no_fakturpenjualantoko').val(response.no_fakturpenjualantoko); 
                    $('#penjualan_kotor').val(response.penjualan_kotor);
                    $('#diskon_penjualan').val(response.diskon_penjualan);
                    $('#penjualan_bersih').val(response.penjualan_bersih);
                    $('#deposit_keluar').val(response.deposit_keluar);
                    $('#deposit_masuk').val(response.deposit_masuk);
                    $('#mesin_edc').val(response.mesin_edc);
                    $('#qris').val(response.qris);
                    $('#gobiz').val(response.gobiz);
                    $('#transfer').val(response.transfer);
                    $('#total_penjualan').val(response.total_penjualan);
                    $('#total_setoran').val(response.total_setoran);
                    $('#nominal_setoran').val(response.nominal_setoran);
                    $('#plusminus').val(response.plusminus);
                },
                error: function (xhr) {
                    console.log(xhr.responseText); // Untuk debugging
                }
            });
        } else {
            alert("Silakan pilih tanggal penjualan terlebih dahulu.");
        }
    });
});

    </script>

    <script>
        $(document).ready(function() {
            // Tambahkan event listener pada tombol "Simpan"
            $('#btnSimpan').click(function() {
                // Sembunyikan tombol "Simpan" dan "Reset", serta tampilkan elemen loading
                $(this).hide();
                $('#btnReset').hide(); // Tambahkan id "btnReset" pada tombol "Reset"
                $('#loading').show();

                // Lakukan pengiriman formulir
                $('form').submit();
            });
        });
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Fungsi Format Rupiah
        function formatRupiah(angka) {
            let numberString = angka.replace(/[^,\d]/g, "").toString(),
                split = numberString.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            return split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
        }

        // Fungsi Hitung Selisih dan Beri Keterangan
        function hitungSelisih(input1, input2, output, keterangan) {
            let value1 = parseInt(input1.value.replace(/[^,\d]/g, "")) || 0;
            let value2 = parseInt(input2.value.replace(/[^,\d]/g, "")) || 0;
            let selisih = value1 - value2;

            output.value = formatRupiah(selisih.toString());

            // Tentukan Keterangan
            if (selisih > 0) {
                keterangan.textContent = "Kurang Bayar";
                keterangan.classList.remove("text-success");
                keterangan.classList.add("text-danger");
            } else if (selisih < 0) {
                keterangan.textContent = "Lebih Bayar";
                keterangan.classList.remove("text-danger");
                keterangan.classList.add("text-success");
            } else {
                keterangan.textContent = "Lunas";
                keterangan.classList.remove("text-danger", "text-success");
            }
        }

        // Element Inputs
        const inputs = [
            {
                input1: document.getElementById("penjualan_kotor"),
                input2: document.getElementById("penjualan_kotor1"),
                output: document.getElementById("penjualan_selisih"),
                keterangan: document.getElementById("penjualan_keterangan"),
            },
            {
                input1: document.getElementById("diskon_penjualan"),
                input2: document.getElementById("diskon_penjualan1"),
                output: document.getElementById("diskon_selisih"),
                keterangan: document.getElementById("diskon_keterangan"),
            },
            {
                input1: document.getElementById("penjualan_bersih"),
                input2: document.getElementById("penjualan_bersih1"),
                output: document.getElementById("penjualanbersih_selisih"),
                keterangan: document.getElementById("penjualanbersih_keterangan"),
            },
            {
                input1: document.getElementById("deposit_keluar"),
                input2: document.getElementById("deposit_keluar1"),
                output: document.getElementById("depositkeluar_selisih"),
                keterangan: document.getElementById("depositkeluar_keterangan"),
            },
            {
                input1: document.getElementById("deposit_masuk"),
                input2: document.getElementById("deposit_masuk1"),
                output: document.getElementById("depositmasuk_selisih"),
                keterangan: document.getElementById("depositmasuk_keterangan"),
            },
            {
                input1: document.getElementById("total_penjualan"),
                input2: document.getElementById("total_penjualan1"),
                output: document.getElementById("totalpenjualan_selisih"),
                keterangan: document.getElementById("totalpenjualan_keterangan"),
            },
            {
                input1: document.getElementById("mesin_edc"),
                input2: document.getElementById("mesin_edc1"),
                output: document.getElementById("mesinedc_selisih"),
                keterangan: document.getElementById("mesinedc_keterangan"),
            },
            {
                input1: document.getElementById("qris"),
                input2: document.getElementById("qris1"),
                output: document.getElementById("qris_selisih"),
                keterangan: document.getElementById("qris_keterangan"),
            },
            {
                input1: document.getElementById("gobiz"),
                input2: document.getElementById("gobiz1"),
                output: document.getElementById("gobiz_selisih"),
                keterangan: document.getElementById("gobiz_keterangan"),
            },
            {
                input1: document.getElementById("transfer"),
                input2: document.getElementById("transfer1"),
                output: document.getElementById("transfer_selisih"),
                keterangan: document.getElementById("transfer_keterangan"),
            },
            {
                input1: document.getElementById("total_setoran"),
                input2: document.getElementById("total_setoran1"),
                output: document.getElementById("totalsetoran_selisih"),
                keterangan: document.getElementById("totalsetoran_keterangan"),
            }
        ];

        // Tambahkan Event Listener ke Setiap Input
        inputs.forEach(({ input1, input2, output, keterangan }) => {
            input2.addEventListener("keyup", function () {
                this.value = formatRupiah(this.value, "");
                hitungSelisih(input1, input2, output, keterangan);
            });
        });
    });
</script>


<script>

    function showCategoryModalpemesanan() {
        $('#tableMarketing').modal('show');
    }

    function formatRibuan(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function getSelectedDataPemesanan(no_fakturpenjualantoko, penjualan_kotor,diskon_penjualan, penjualan_bersih,deposit_keluar,deposit_masuk,
        total_penjualan,mesin_edc,qris,gobiz,transfer,total_setoran,tanggal_penjualan) {
        document.getElementById('no_fakturpenjualantoko').value = no_fakturpenjualantoko;
        document.getElementById('tanggal_penjualan').value = tanggal_penjualan;

        document.getElementById('penjualan_kotor').value = formatRibuan(penjualan_kotor);   
        document.getElementById('diskon_penjualan').value = formatRibuan(diskon_penjualan);
        document.getElementById('penjualan_bersih').value = formatRibuan(penjualan_bersih);
        document.getElementById('deposit_masuk').value = formatRibuan(deposit_masuk);
        document.getElementById('deposit_keluar').value = formatRibuan(deposit_keluar);
        document.getElementById('total_penjualan').value = formatRibuan(total_penjualan);
        document.getElementById('mesin_edc').value = formatRibuan(mesin_edc);
        document.getElementById('qris').value = formatRibuan(qris);
        document.getElementById('gobiz').value = formatRibuan(gobiz);
        document.getElementById('transfer').value = formatRibuan(transfer);
        document.getElementById('total_setoran').value = formatRibuan(total_setoran);

        $('#tableMarketing').modal('hide');
    }
</script>

@endsection


