
@extends('layouts.app')

@section('title', 'Updated Items')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Harga Jual yang Diperbarui Hari Ini</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Updated Items</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            {{-- Form Filter --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="toko" class="form-label">Pilih Toko:</label>
                    <select class="form-control" id="toko" name="toko">
                        <option value="tokoslawi" @if(request()->input('toko', 'tokoslawi') == 'tokoslawi') selected @endif>Toko Slawi</option>
                        <option value="tokobenjaran" @if(request()->input('toko') == 'tokobenjaran') selected @endif>Toko Benjaran</option>
                    </select>
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
                            <table id="datatables1" class="table table-sm table-bordered table-striped table-hover">
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
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_produk }}</td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>{{'Rp. ' . number_format( $item->harga , 0, ',', '.') }}</td>
                                                <td hidden>{{ $item->diskon }}</td>
                                                <td style="text-align: center;">{{ 'Rp. ' . number_format($item->tokoslawi->first()->member_harga_slw - ($item->tokoslawi->first()->member_harga_slw * $item->tokoslawi->first()->member_diskon_slw / 100), 0, ',', '.') }}</td>
                                                <td>{{ $item->tokoslawi->first()->member_diskon_slw }}</td>
                                                <td style="text-align: center;">{{ 'Rp. ' . number_format($item->tokoslawi->first()->non_harga_slw - ($item->tokoslawi->first()->non_harga_slw * $item->tokoslawi->first()->non_diskon_slw / 100), 0, ',', '.') }}</td>
                                                <td>{{ $item->tokoslawi->first()->non_diskon_slw }}</td>
                                            </tr>
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

            {{-- Tampilkan Tabel Tokobenjaran --}}
            <div id="tokobenjaranTable" @if(request()->input('toko') == 'tokobenjaran') style="display: none;" @endif>
                @if($produk->filter(function($item) {
                        return $item->tokobenjaran->isNotEmpty();
                    })->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Harga Jual Tokobenjaran yang Diperbarui Hari Ini</h3>
                        </div>
                        <div class="card-body">
                            <table id="datatables2" class="table table-sm table-bordered table-striped table-hover">
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
                                        @if($item->tokobenjaran->isNotEmpty())
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_produk }}</td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>{{'Rp. ' . number_format( $item->harga , 0, ',', '.') }}</td>
                                                <td hidden>{{ $item->diskon }}</td>
                                                <td style="text-align: center;">{{  'Rp. ' . number_format($item->tokobenjaran->first()->member_harga_bnjr - ($item->tokobenjaran->first()->member_harga_bnjr * $item->tokobenjaran->first()->member_diskon_bnjr / 100), 0, ',', '.') }}</td>
                                                <td>{{ $item->tokobenjaran->first()->member_diskon_bnjr}}</td>
                                                <td style="text-align: center;">{{  'Rp. ' . number_format($item->tokobenjaran->first()->non_harga_bnjr - ($item->tokobenjaran->first()->non_harga_bnjr * $item->tokobenjaran->first()->member_diskon_bnjr / 100), 0, ',', '.') }}</td>
                                                <td>{{ $item->tokobenjaran->first()->non_diskon_bnjr}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        Tidak ada data Harga Jual Tokobenjaran yang diperbarui hari ini.
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
                document.getElementById('tokobenjaranTable').style.display = 'none';
            } else if (toko === 'tokobenjaran') {
                document.getElementById('tokoslawiTable').style.display = 'none';
                document.getElementById('tokobenjaranTable').style.display = 'block';
            }
        }
    </script>
@endsection


