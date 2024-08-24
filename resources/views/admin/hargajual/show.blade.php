
@extends('layouts.app')

@section('title', 'Harga Jual')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Harga Jual yang Diperbarui Hari Ini</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Harga Jual</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Form Filter --}}
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="toko" class="form-label">Pilih Toko:</label>
                            <select class="form-control" id="toko" name="toko">
                                <option value="tokoslawi" @if(request()->input('toko', 'tokoslawi') == 'tokoslawi') selected @endif>Toko Slawi</option>
                                <option value="tokobanjaran" @if(request()->input('toko') == 'tokobanjaran') selected @endif>Toko Banjaran</option>
                                <option value="tokotegal" @if(request()->input('toko') == 'tokotegal') selected @endif>Toko Tegal</option>
                                <option value="tokopemalang" @if(request()->input('toko') == 'tokopemalang') selected @endif>Toko Pemalang</option>
                                <option value="tokobumiayu" @if(request()->input('toko') == 'tokobumiayu') selected @endif>Toko Bumiayu</option>
                                <option value="tokocilacap" @if(request()->input('toko') == 'tokocilacap') selected @endif>Toko Cilacap</option>
                            </select>
                        </div>
                    
                    </div>
                    <div class="float-right">
                        <button class="btn btn-primary" onclick="printPdf()">Cetak PDF</button>
                    </div>
                </div>
                
            {{-- Tampilkan Tabel Tokoslawi --}}
            <div id="tokoslawiTable" @if(request()->input('toko', 'tokoslawi') != 'tokoslawi') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokoslawi->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual Tokoslawi yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                      
                            <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode produk</th>
                                        <th>Nama produk</th>
                                        <th>Harga produk awal</th>
                                        <th colspan="4" style="text-align: center;">Toko Slawi</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Member</th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;">Non Member</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($produk as $index => $item)
                                        @if($item->tokoslawi->isNotEmpty())
                                            @php
                                                $memberHarga = $item->tokoslawi->first()->member_harga_slw;
                                                $nonMemberHarga = $item->tokoslawi->first()->non_harga_slw;
                                                $hargaAwal = $item->tokoslawi->first()->harga_awal;
                                                $memberDiskon = $item->tokoslawi->first()->member_diskon_slw;
                                                $nonMemberDiskon = $item->tokoslawi->first()->non_diskon_slw;
                                
                                                // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                             ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                            @endphp
                                
                                            @if($isChanged)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_produk }}</td>
                                                    <td>{{ $item->nama_produk }}</td>
                                                    <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                                
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Tokoslawi yang diperbarui hari ini.
                    </div>
                @endif
            </div>

            {{-- Tampilkan Tabel tokobanjaran --}}
            <div id="tokobanjaranTable" @if(request()->input('toko', 'tokobanjaran') != 'tokobanjaran') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokobanjaran->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual tokobanjaran yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                      
                            <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode produk</th>
                                        <th>Nama produk</th>
                                        <th>Harga produk awal</th>
                                        <th colspan="4" style="text-align: center;">Toko Benjaran</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Member</th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;">Non Member</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $index => $item)
                                        @if($item->tokobanjaran->isNotEmpty())
                                            @php
                                                $memberHarga = $item->tokobanjaran->first()->member_harga_bnjr;
                                                $nonMemberHarga = $item->tokobanjaran->first()->non_harga_bnjr;
                                                $hargaAwal = $item->tokobanjaran->first()->harga_awal;
                                                $memberDiskon = $item->tokobanjaran->first()->member_diskon_bnjr;
                                                $nonMemberDiskon = $item->tokobanjaran->first()->non_diskon_bnjr;
                                
                                                // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                             ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                            @endphp
                                
                                            @if($isChanged)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_produk }}</td>
                                                    <td>{{ $item->nama_produk }}</td>
                                                    <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Tokoslawi yang diperbarui hari ini.
                    </div>
                @endif
            </div>

            {{-- Tampilkan Tabel Tokotegal --}}
            <div id="tokotegalTable" @if(request()->input('toko', 'tokotegal') != 'tokotegal') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokotegal->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual tokotegal yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                      
                            <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode produk</th>
                                        <th>Nama produk</th>
                                        <th>Harga produk awal</th>
                                        <th colspan="4" style="text-align: center;">Toko Tegal</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Member</th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;">Non Member</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $index => $item)
                                        @if($item->tokotegal->isNotEmpty())
                                            @php
                                                $memberHarga = $item->tokotegal->first()->member_harga_tgl;
                                                $nonMemberHarga = $item->tokotegal->first()->non_harga_tgl;
                                                $hargaAwal = $item->tokotegal->first()->harga_awal;
                                                $memberDiskon = $item->tokotegal->first()->member_diskon_tgl;
                                                $nonMemberDiskon = $item->tokotegal->first()->non_diskon_tgl;
                                
                                                // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                             ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                            @endphp
                                
                                            @if($isChanged)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_produk }}</td>
                                                    <td>{{ $item->nama_produk }}</td>
                                                    <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Tokoslawi yang diperbarui hari ini.
                    </div>
                @endif
            </div>

             {{-- Tampilkan Tabel TokoPemlaang --}}
             <div id="tokopemalangTable" @if(request()->input('toko', 'tokopemalang') != 'tokopemalang') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokopemalang->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual tokopemalang yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                      
                            <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode produk</th>
                                        <th>Nama produk</th>
                                        <th>Harga produk awal</th>
                                        <th colspan="4" style="text-align: center;">Toko Tegal</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Member</th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;">Non Member</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $index => $item)
                                        @if($item->tokopemalang->isNotEmpty())
                                            @php
                                                $memberHarga = $item->tokopemalang->first()->member_harga_pml;
                                                $nonMemberHarga = $item->tokopemalang->first()->non_harga_pml;
                                                $hargaAwal = $item->tokopemalang->first()->harga_awal;
                                                $memberDiskon = $item->tokopemalang->first()->member_diskon_pml;
                                                $nonMemberDiskon = $item->tokopemalang->first()->non_diskon_pml;
                                
                                                // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                             ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                            @endphp
                                
                                            @if($isChanged)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_produk }}</td>
                                                    <td>{{ $item->nama_produk }}</td>
                                                    <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Toko Pemalang yang diperbarui hari ini.
                    </div>
                @endif
            </div>

              {{-- Tampilkan Tabel TokoBumiayu --}}
              <div id="tokobumiayuTable" @if(request()->input('toko', 'tokobumiayu') != 'tokobumiayu') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokobumiayu->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual tokobumiayu yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                      
                            <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode produk</th>
                                        <th>Nama produk</th>
                                        <th>Harga produk awal</th>
                                        <th colspan="4" style="text-align: center;">Toko Bumiayu</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Member</th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;">Non Member</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $index => $item)
                                        @if($item->tokobumiayu->isNotEmpty())
                                            @php
                                                $memberHarga = $item->tokobumiayu->first()->member_harga_bmy;
                                                $nonMemberHarga = $item->tokobumiayu->first()->non_harga_bmy;
                                                $hargaAwal = $item->tokobumiayu->first()->harga_awal;
                                                $memberDiskon = $item->tokobumiayu->first()->member_diskon_bmy;
                                                $nonMemberDiskon = $item->tokobumiayu->first()->non_diskon_bmy;
                                
                                                // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                             ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                            @endphp
                                
                                            @if($isChanged)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_produk }}</td>
                                                    <td>{{ $item->nama_produk }}</td>
                                                    <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Toko Pemalang yang diperbarui hari ini.
                    </div>
                @endif
            </div>

               {{-- Tampilkan Tabel TokoCilacap --}}
               <div id="tokocilacapTable" @if(request()->input('toko', 'tokocilacap') != 'tokocilacap') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokocilacap->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual tokocilacap yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                      
                            <table id="datatable" class="table table-sm table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode produk</th>
                                        <th>Nama produk</th>
                                        <th>Harga produk awal</th>
                                        <th colspan="4" style="text-align: center;">Toko Cilacap</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Member</th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;"></th>
                                        <th style="text-align: center;">Non Member</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                        <th style="text-align: center;">Harga</th>
                                        <th style="text-align: center;">Diskon (%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $index => $item)
                                        @if($item->tokocilacap->isNotEmpty())
                                            @php
                                                $memberHarga = $item->tokocilacap->first()->member_harga_clc;
                                                $nonMemberHarga = $item->tokocilacap->first()->non_harga_clc;
                                                $hargaAwal = $item->tokocilacap->first()->harga_awal;
                                                $memberDiskon = $item->tokocilacap->first()->member_diskon_clc;
                                                $nonMemberDiskon = $item->tokocilacap->first()->non_diskon_clc;
                                
                                                // Cek apakah ada perubahan pada harga member atau diskon member atau harga non-member atau diskon non-member
                                                $isChanged = ($memberHarga != $hargaAwal) || ($memberDiskon != 0) || 
                                                             ($nonMemberHarga != $hargaAwal) || ($nonMemberDiskon != 0);
                                            @endphp
                                
                                            @if($isChanged)
                                                <tr>
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $item->kode_produk }}</td>
                                                    <td>{{ $item->nama_produk }}</td>
                                                    <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                                                    <td style="text-align: center;">
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $memberHarga != $hargaAwal || $memberDiskon != 0 ? $memberDiskon : '-' }}
                                                    </td>
                                                    <td style="text-align: center;">
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') : '-' }}
                                                    </td>
                                                    <td>
                                                        {{ $nonMemberHarga != $hargaAwal || $nonMemberDiskon != 0 ? $nonMemberDiskon : '-' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach 
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Toko Cilacap yang diperbarui hari ini.
                    </div>
                @endif
            </div>

        </div>
    </section>

    <script>
        // Script untuk menampilkan tabel yang sesuai berdasarkan pilihan pengguna
        document.addEventListener('DOMContentLoaded', function() {
            var toko = document.getElementById('toko').value;
            showTable(toko);
        });

        document.getElementById('toko').addEventListener('change', function() {
            var toko = this.value;
            showTable(toko);
        });

        function showTable(toko) {
            if (toko === 'tokoslawi') {
                document.getElementById('tokoslawiTable').style.display = 'block';
                document.getElementById('tokobanjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            } else if (toko === 'tokobanjaran') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobanjaranTable').style.display = 'block';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokotegal') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobanjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'block';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokopemalang') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobanjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'block';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokobumiayu') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobanjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'block';
                document.getElementById('tokocilacapTable').style.display = 'none';

            }else if (toko === 'tokocilacap') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobanjaranTable').style.display = 'none';
                document.getElementById('tokotegalTable').style.display = 'none';
                document.getElementById('tokopemalangTable').style.display = 'none';
                document.getElementById('tokobumiayuTable').style.display = 'none';
                document.getElementById('tokocilacapTable').style.display = 'block';
            }
        
        }
        function printPdf() {
        var toko = document.getElementById('toko').value;
        var url = '{{ route("cetak.pdf") }}' + '?toko=' + toko;
        window.open(url, '_blank');

     
    }
    </script>
@endsection


