@extends('layouts.app')

@section('title', 'Pemesanan Produk')

@section('content')
<style>
    .label-width {
        width: 100px; /* Atur sesuai kebutuhan */
    }

    .input-width {
        flex: 1;
    }

    .large-font {
        font-size: 1rem; /* Atur ukuran font sesuai kebutuhan */
    }
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
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
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pemesanan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">Pemesanan Produk</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <style>
        .large-font {
            font-size: 1.5em; /* Atur ukuran font sesuai kebutuhan */
            font-weight: bold;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <form action="{{ url('admin/pemesanan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                {{-- detail pelanggan --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pelanggan</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-2 mt-2">
                                <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="">- Pilih -</option>
                                    <option value="member" {{ old('kategori') == 'member' ? 'selected' : null }}>Member</option>
                                    <option value="nonmember" {{ old('kategori') == 'nonmember' ? 'selected' : null }}>Non Member</option>
                                </select>
                            </div>
                            <div class="col-md-2 mt-2">
                                <label class="form-label" for="toko">Pilih Cabang</label>
                                <select class="form-control" id="toko" name="toko">
                                    <option value="">- Pilih -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ old('toko') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                            </div>
                       
                            <div class="col-md-3 mt-2" id="kodePelangganRow" hidden>
                                <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="scan kode Pelanggan" onchange="getData(this.value)">
                            </div>
                        </div>
                    
                        <div class="row mb-3 align-items-center" id="namaPelangganRow" style="display: none;">
                            <div class="col-md">
                                <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                    <i class="fas fa-search" style=""></i>Cari pelanggan
                                </button> 
                            </div>      
                            <div class="col-md-6 mb-3 "> 
                                <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" >
                            </div>     
                        </div>

                        <div class="row  align-items-center" id="telpRow" hidden>
                            <div class="col-md-6 mb-3">
                                <label for="telp">No. Telepon</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{ old('telp') }}">
                                </div>
                            </div>
                        </div>
                    
                        <div class="row mb-3 align-items-center" id="alamatRow" hidden>
                            <div class="col-md-6 mb-3">
                                <label for="catatan">Alamat</label>
                                <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{ old('alamat') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="tableMarketing" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Pelanggan</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table id="datatables4" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Nama Pelanggan</th>
                                            <th>No Telpon</th>
                                            <th>Alamat</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelanggans as $item)
                                            <tr onclick="getSelectedDataPemesanan('{{ $item->nama_pelanggan }}', '{{ $item->telp }}', '{{ $item->alamat }}')">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_pelanggan }}</td>
                                                <td>{{ $item->telp }}</td>
                                                <td>{{ $item->alamat }}</td>
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

                 <div class="modal fade" id="tableCatatan" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Catatan Produk</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="modalCatatanInput">Catatan:</label>
                                    <input type="text" class="form-control" id="modalCatatanInput">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="saveCatatan()">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
                

                {{-- detail pengiriman --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Pengiriman</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-4 mb-3">
                            <label for="tanggal_kirim">Tanggal Pengiriman:</label>
                            <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                <input type="text" id="tanggal_kirim" name="tanggal_kirim"
                                       class="form-control datetimepicker-input"
                                       data-target="#reservationdatetime"
                                       value="{{ old('tanggal_kirim') }}"
                                       placeholder="DD/MM/YYYY HH:mm">
                                <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6 -auto" id="" >
                                <label for="nama_penerima">Nama Penerima </label> <span style="font-size: 10px;">(kosongkan jika sama dengan nama pelanggan)</span>
                                <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" placeholder="masukan nama Penerima" value="{{ old('nama_penerima') }}">
                            </div>
                        </div>
                        <div class="row  align-items-center" id="telp_penerima" >
                            <div class="col-md-6">
                                <label for="telp_penerima">No. Telepon</label> <span style="font-size: 10px;">(kosongkan jika sama dengan Nomer telepon pelanggan)</span>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input type="number" id="telp_penerima" name="telp_penerima" class="form-control" placeholder="Masukan nomor telepon" value="{{ old('telp_penerima') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center" id="alamat_penerima" >
                            <div class="col-md-6 mb-3">
                                <label for="alamat_penerima">Alamat Penerima</label><span style="font-size: 10px;"> (kosongkan jika sama dengan alamat pelanggan)</span>
                                <textarea placeholder="Masukan alamat penerima" type="text" class="form-control" id="alamat_penerima" name="alamat_penerima">{{ old('alamat_penerima') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><span></span></h3>
                        <div class="float-right">
                            <button  type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
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
                                            <th style="font-size:14px">Diskon</th>
                                            <th style="font-size:14px">Harga</th>
                                            <th style="font-size:14px">Total</th>
                                            <th style="font-size:14px; text-align:center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembelian">
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="modal fade" id="tableProduk" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Produk</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table id="datatables5" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Kode Produk</th>
                                            <th>Nama Produk</th>
                                            <th>Harga Member</th>
                                            <th>Diskon Member</th>
                                            <th>Harga Non Member</th>
                                            <th>Diskon Non Member</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produks as $item)
                                            @php
                                                $tokoslawi = $item->tokoslawi->first();
                                            @endphp
                                            <tr class="pilih-btn"
                                                data-id="{{ $item->id }}"
                                                data-kode="{{ $item->kode_produk }}"
                                                data-catatan="{{ $item->catatanproduk }}"
                                                data-nama="{{ $item->nama_produk }}"
                                                data-member="{{ $tokoslawi ? $tokoslawi->member_harga_slw : '' }}"
                                                data-diskonmember="{{ $tokoslawi ? $tokoslawi->member_diskon_slw : '' }}"
                                                data-nonmember="{{ $tokoslawi ? $tokoslawi->non_harga_slw : '' }}"
                                                data-diskonnonmember="{{ $tokoslawi ? $tokoslawi->non_diskon_slw : '' }}">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_produk }}</td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>
                                                    <span class="member_harga_slw">{{ $tokoslawi ? $tokoslawi->member_harga_slw : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="member_diskon_slw">{{ $tokoslawi ? $tokoslawi->member_diskon_slw : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="non_harga_slw">{{ $tokoslawi ? $tokoslawi->non_harga_slw : '' }}</span>
                                                </td>
                                                <td>
                                                    <span class="non_diskon_slw">{{ $tokoslawi ? $tokoslawi->non_diskon_slw : '' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                        data-id="{{ $item->id }}"
                                                        data-kode="{{ $item->kode_produk }}"
                                                        data-catatan="{{ $item->catatanproduk }}"
                                                        data-nama="{{ $item->nama_produk }}"
                                                        data-member="{{ $tokoslawi ? $tokoslawi->member_harga_slw : '' }}"
                                                        data-diskonmember="{{ $tokoslawi ? $tokoslawi->member_diskon_slw : '' }}"
                                                        data-nonmember="{{ $tokoslawi ? $tokoslawi->non_harga_slw : '' }}"
                                                        data-diskonnonmember="{{ $tokoslawi ? $tokoslawi->non_diskon_slw : '' }}">
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
         
                

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_total" class="label-width mr-2">Sub Total</label>
                                        <input type="text" class="form-control large-font input-width" id="sub_total" name="sub_total" value="Rp0" oninput="validateNumberInput(event); showPaymentFields()">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="dp_pemesanan" class="label-width mr-4">DP</label>
                                        <input type="text" class="form-control large-font input-width" id="dp_pemesanan" name="dp_pemesanan" value="{{ old('dp_pemesanan') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kekurangan_pemesanan" class="label-width mr-2">Kekurangan</label>
                                        <input type="text" class="form-control large-font input-width" id="kekurangan_pemesanan" name="kekurangan_pemesanan" value="{{ old('kekurangan_pemesanan') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
        
                    <div class="col-md-6">
                        <div class="form-group" style="flex: 8;">
                            <label for="metode_id">Jenis Pembayaran</label>
                            <select class="select2bs4 select2-hidden-accessible" name="metode_id" style="width: 100%;" id="nama_metode" onchange="getData1()">
                                <option value="">- Pilih -</option>
                                @foreach ($metodes as $metode)
                                    <option value="{{ $metode->id }}" data-fee="{{ $metode->fee }}" {{ old('metode_id') == $metode->id ? 'selected' : '' }}>
                                        {{ $metode->nama_metode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="payment-fields" class="form-group" style="display: none; margin-top: 20px;">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="fee">Fee (%)</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="fee" readonly name="fee" placeholder="" value="{{ old('fee') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="total_fee">Total Fee</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="total_fee" name="total_fee" placeholder="" value="{{ old('total_fee') }}" readonly>
                                </div>
                            </div>
                        
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <label for="keterangan">Keterangan</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="" value="{{ old('keterangan') }}">
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="catatan">Catatan</label>
                                <textarea placeholder="" type="text" class="form-control" id="catatan" name="catatan">{{ old('catatan') }}</textarea>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="catatan">Bagian Input :</label>
                                <input type="text" class="form-control" readonly value="{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}">
                            </div> 
                        </div>     
                    </div>
                </div>
                <div class="card-footer text-right mt-3">
                    <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="" class="btn btn-primary" id="simpanButton">Simpan</button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            </form>
        </div>
    </section>


    <script>
        function showCategoryModalCatatan(urutan) {
            // Tampilkan modal
            $('#tableCatatan').modal('show');
    
            // Simpan urutan yang dipilih di elemen tersembunyi di modal
            $('#tableCatatan').data('urutan', urutan);
    
            // Kosongkan input catatan di modal
            $('#modalCatatanInput').val('');
        }
    
        function saveCatatan() {
            var urutan = $('#tableCatatan').data('urutan');
            var catatan = $('#modalCatatanInput').val();
    
            // Masukkan catatan ke input yang sesuai
            $('#catatanproduk-' + urutan).val(catatan);
    
            // Tutup modal
            $('#tableCatatan').modal('hide');
        }
    </script>
    
    <script>
        $('#tableCatatan').on('show.bs.modal', function (event) {
            var urutan = $(event.relatedTarget).data('urutan');
            $(this).data('urutan', urutan);
    
            // Kosongkan input catatan di modal
            $('#modalCatatanInput').val('');
        });
    
        function showCategoryModalCatatan(urutan) {
            // Tampilkan modal dan set data urutan
            $('#tableCatatan').data('urutan', urutan).modal('show');
        }
    
        function saveCatatan() {
            var urutan = $('#tableCatatan').data('urutan');
            var catatan = $('#modalCatatanInput').val();
    
            // Masukkan catatan ke input yang sesuai
            $('#catatanproduk-' + urutan).val(catatan);
    
            // Tutup modal
            $('#tableCatatan').modal('hide');
        }
    </script>
    

<script>
    let originalSubTotal = 0;

    function cleanSubTotal(subTotal) {
        // Hapus "Rp" dan titik
        return parseFloat(subTotal.replace(/Rp|\.|,/g, '')) || 0;
    }

    function formatRupiah(angka) {
        // Format angka ke format Rupiah
        const numberString = angka.toString();
        const sisa = numberString.length % 3;
        let rupiah = numberString.substr(0, sisa);
        const ribuan = numberString.substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            const separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return 'Rp' + rupiah;
    }

    function showPaymentFields() {
        const paymentFields = document.querySelectorAll('.payment-field');
        paymentFields.forEach(field => field.hidden = true);

        const metodebayar = document.getElementById('metodebayar').value;
        const subTotalField = document.getElementById('sub_total');
        let subTotal = cleanSubTotal(subTotalField.value);

        console.log('Sub Total:', subTotal);

        // Simpan nilai asli dari subTotal saat pertama kali metode pembayaran dipilih
        if (originalSubTotal === 0 && subTotal > 0) {
            originalSubTotal = subTotal;
        }

        if (metodebayar === "gobiz") {
            document.getElementById('gobiz-fields').hidden = false;
            const feeField = document.getElementById('gobiz_fee');
            const fee = Math.round(subTotal * 0.20);
            feeField.value = fee;
            subTotalField.value = formatRupiah(subTotal + fee);
        } else if (metodebayar === "mesinedc") {
            document.getElementById('mesinedc-fields').hidden = false;
            const feeField = document.getElementById('struk_edc_fee');
            const fee = Math.round(subTotal * 0.01);
            feeField.value = fee;
            subTotalField.value = formatRupiah(subTotal + fee);
        } else if (metodebayar === "transfer") {
            document.getElementById('transfer-fields').hidden = false;
        } else if (metodebayar === "qris") {
            document.getElementById('qris-fields').hidden = false;
        } else if (metodebayar === "tunai") {
            document.getElementById('tunai-fields').hidden = false;
        } else if (metodebayar === "voucher") {
            document.getElementById('voucher-fields').hidden = false;
        } else {
            // Kembalikan nilai subTotal ke nilai asli jika metode pembayaran dikosongkan
            subTotalField.value = formatRupiah(originalSubTotal);
            originalSubTotal = 0;
        }
    }

    function validateNumberInput(event) {
        const input = event.target;
        const value = input.value;
        const sanitizedValue = value.replace(/[^0-9.,Rp]/g, '');

        if (sanitizedValue !== value) {
            input.value = sanitizedValue;
        }
    }

    document.getElementById('sub_total').addEventListener('input', validateNumberInput);
    document.getElementById('sub_total').addEventListener('input', showPaymentFields);
    window.onload = showPaymentFields;
</script>

   <script>
        $(function () {
            $('#reservationdatetime').datetimepicker({
                format: 'DD/MM/YYYY HH:mm',
                icons: {
                    time: 'fa fa-clock',
                    date: 'fa fa-calendar',
                    up: 'fa fa-arrow-up',
                    down: 'fa fa-arrow-down',
                    previous: 'fa fa-chevron-left',
                    next: 'fa fa-chevron-right',
                    today: 'fa fa-calendar-check-o',
                    clear: 'fa fa-trash',
                    close: 'fa fa-times'
                }
            });
        });
    </script>

    <script>
        function handleEnter(event, urutan) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Mencegah form dari submit jika ada
                addPesanan(urutan);
            }
        }

        function addPesanan(urutan) {
            // Logika untuk menambah pesanan
            console.log("Pesanan ditambahkan untuk urutan " + urutan);
        }

        function simpanPesanan() {
            // Logika untuk menyimpan pesanan
            console.log("Pesanan disimpan");
        }

        // Contoh: Tambahkan event listener untuk tombol simpan
        document.getElementById('simpanButton').addEventListener('click', function() {
            simpanPesanan();
        });
    </script>

    <script>
        // menghide form inputan
        document.addEventListener('DOMContentLoaded', function() {
            var kategoriSelect = document.getElementById('kategori');
            var namaPelangganRow = document.getElementById('namaPelangganRow');
            var telpRow = document.getElementById('telpRow');
            var alamatRow = document.getElementById('alamatRow');
            var namaPelangganInput = document.getElementById('nama_pelanggan');


            kategoriSelect.addEventListener('change', function() {
                if (kategoriSelect.value === 'member') {
                    // namaPelangganRow.hidden = false;
                    // namaPelangganRow.readOnly = false;
                    kodePelangganRow.hidden = false;
                    namaPelangganInput.readOnly = true;
                    namaPelangganRow.style.display = 'block';
                    telpRow.hidden = false;
                    alamatRow.hidden = false;
                } else if (kategoriSelect.value === 'nonmember') {
                    // namaPelangganRow.hidden = false;
                    // namaPelangganRow.readonly = false;
                    kodePelangganRow.hidden = true;
                    namaPelangganInput.readOnly = false;
                    namaPelangganRow.style.display = 'block';
                    telpRow.hidden = false;
                    alamatRow.hidden = false;
                } else {
                    namaPelangganRow.style.display = 'none';
                    namaPelangganRow.readonly = true;
                    telpRow.hidden = true;
                    alamatRow.hidden = true;
                    kodePelangganRow.hidden = true;

                }
            });

        });
    </script>

    <script>
        //    memunculkan button utk mencari pelanggan yg sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            var kategoriSelect = document.getElementById('kategori');
            var searchButtonRow = document.querySelector('.col-md');

            kategoriSelect.addEventListener('change', function() {
                if (kategoriSelect.value === 'member') {
                    searchButtonRow.hidden = false;
                } else {
                    searchButtonRow.hidden = true;
                }
            });

            if (kategoriSelect.value === 'nonmember') {
                searchButtonRow.hidden = true;
            }
        });
    </script>

    <script>
        // memunculkan datatable pelaanggan dan produk
        $(document).ready(function() {
            // Inisialisasi datatables
            var pelangganTable = $('#datatables4').DataTable();
            var produkTable = $('#datatables5').DataTable();
    
            $('#tableMarketing').on('shown.bs.modal', function () {
                pelangganTable.columns.adjust().draw();
            });
    
            $('#tableProduk').on('shown.bs.modal', function () {
                produkTable.columns.adjust().draw();
            });
        });
    
        function showCategoryModalpemesanan() {
            $('#tableMarketing').modal('show');
        }
    
        function getSelectedDataPemesanan(nama_pelanggan, telp, alamat) {
            document.getElementById('nama_pelanggan').value = nama_pelanggan;
            document.getElementById('telp').value = telp;
            document.getElementById('alamat').value = alamat;
            $('#tableMarketing').modal('hide');
        }
    </script>

    <script>
        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 0;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, value);
            });
        }

        // Fungsi untuk menampilkan modal barang
        function showCategoryModal(urutan) {
            $('#tableProduk').modal('show');
            // Simpan urutan untuk menyimpan data ke baris yang sesuai
            $('#tableProduk').attr('data-urutan', urutan);
        }
       
        // Event listener for pilih-btn
        $(document).on('click', '.pilih-btn', function() {
            var id = $(this).data('id');
            var kode = $(this).data('kode');
            var nama = $(this).data('nama');
            var member = $(this).data('member');
            var diskonmember = $(this).data('diskonmember');
            var nonmember = $(this).data('nonmember');
            var diskonnonmember = $(this).data('diskonnonmember');
            
            getSelectedData(id, kode, nama, member, diskonmember, nonmember, diskonnonmember);
        });

        // Fungsi untuk memilih data barang dari modal
        function getSelectedData(id, kode_produk, nama_produk, member, diskonmember, nonmember, diskonnonmember) {
            var urutan = $('#tableProduk').attr('data-urutan');
            var kategori = $('#kategori').val();
            var harga = kategori === 'member' ? member : nonmember;
            var diskon = kategori === 'member' ? diskonmember : diskonnonmember;

            // Set nilai input pada baris yang sesuai
            $('#produk_id-' + urutan).val(id);
            $('#kode_produk-' + urutan).val(kode_produk);
            $('#nama_produk-' + urutan).val(nama_produk);
            $('#harga-' + urutan).val(harga);
            $('#diskon-' + urutan).val(diskon);
            // Hitung total
            hitungTotal(urutan);
            // Tutup modal
            $('#tableProduk').modal('hide');


                // Setelah menambahkan data dari modal, fokuskan ke input jumlah
            var InputJumlah =  document.getElementById('jumlah-' + urutan).focus();

            // InputJumlah.addEventListener('keydown', function(event){
            //     if(event.key === 'Enter'){

            //         event.preventDefault();
                    
            //         addPesanan();

            //     }

            // });

        }

        // Fungsi untuk menghitung total berdasarkan harga dan jumlah
        function hitungTotal(urutan) {
            var harga = parseFloat($('#harga-' + urutan).val().replace(/[^0-9]/g, '')) || 0;
            var diskon = parseFloat($('#diskon-' + urutan).val()) || 0;
            var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;

            var hargaSetelahDiskon = harga - (harga * (diskon / 100));
            var total = hargaSetelahDiskon * jumlah;

            // Format total ke dalam format rupiah dan set nilai input total
            $('#total-' + urutan).val(total);
            // Hitung subtotal setiap kali total di baris berubah
            hitungSubTotal();
        }

        // Fungsi untuk menghitung subtotal semua barang
        function hitungSubTotal() {
            var subTotal = 0;
            $('[id^=total-]').each(function() {
                var total = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
                subTotal += total;
            });
            $('#sub_total').val(subTotal);
        }

        function addPesanan() {
            jumlah_ban = jumlah_ban + 1;
            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            }
            itemPembelian(jumlah_ban, jumlah_ban - 1);
        }

        function removeBan(params) {
            jumlah_ban = jumlah_ban - 1;
            var tabel_pesanan = document.getElementById('tabel-pembelian');
            var pembelian = document.getElementById('pembelian-' + params);
            tabel_pesanan.removeChild(pembelian);
            if (jumlah_ban === 0) {
                var item_pembelian = '<tr>';
                item_pembelian += '<td class="text-center" colspan="5">- Barang Jadi belum ditambahkan -</td>';
                item_pembelian += '</tr>';
                $('#tabel-pembelian').html(item_pembelian);
            } else {
                var urutan = document.querySelectorAll('#urutan');
                for (let i = 0; i < urutan.length; i++) {
                    urutan[i].innerText = i + 1;
                }
            }
            hitungSubTotal();
        }

        function itemPembelian(urutan, key, value = null) {
            var produk_id = '';
            var catatanproduk = '';
            var kode_produk = '';
            var nama_produk = '';
            var jumlah = '';
            var diskon = '';
            var harga = '';
            var total = '';

            if (value !== null) {
                produk_id = value.produk_id;
                catatanproduk = value.catatanproduk;
                kode_produk = value.kode_produk;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
                diskon = value.diskon;
                harga = value.harga;
                total = value.total;
            }

            var item_pembelian = '<tr  id="pembelian-' + urutan + '">';
            item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>'; 
            item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
            item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="catatanproduk-' + urutan + '" name="catatanproduk[]"></div></td>';
            item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
            item_pembelian += '<td onclick="showCategoryModalCatatan(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
            item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')" onkeydown="handleEnter(event, ' + urutan + ')"></div></td>';
            item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')" style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" readonly id="diskon-' + urutan + '" name="diskon[]" value="' + diskon + '" ></div></td>';
            item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + harga + '"></div></td>';
            item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + total + '"></div></td>';
            item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelian').append(item_pembelian);
        }

        // function formatRupiah(angka, prefix = '') {
        //     var number_string = angka.toString().replace(/[^,\d]/g, '');
        //     var split = number_string.split(',');
        //     var sisa = split[0].length % 3;
        //     var rupiah = split[0].substr(0, sisa);
        //     var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        //     if (ribuan) {
        //         var separator = sisa ? '.' : '';
        //         rupiah += separator + ribuan.join('.');
        //     }

        //     rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        //     return prefix + rupiah;
        // }
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        var delayTimer;
        var scanned = false; // Tambahkan variabel untuk menandai apakah sudah discan atau belum
    
        $(document).ready(function() {
            // Fokuskan input saat halaman dimuat
            $('#qrcode_pelanggan').focus();
    
            $('#qrcode_pelanggan').on('input', function() {
                var qrcode_pelanggan = $(this).val().trim();
    
                // Hapus timer sebelumnya jika ada
                clearTimeout(delayTimer);
    
                // Tunggu sebentar sebelum mengambil data
                delayTimer = setTimeout(function() {
                    if (qrcode_pelanggan !== '') {
                        // Periksa apakah sudah discan sebelumnya
                        if (!scanned) {
                            getData(qrcode_pelanggan);
                        } else {
                            // Data sudah discan sebelumnya, tidak perlu melakukan apa-apa
                            console.log('Data sudah discan sebelumnya.');
                        }
                    } else {
                        // Handle jika qrcode_pelanggan kosong
                        $('#nama_pelanggan').val('');
                        $('#telp').val('');
                        $('#alamat').val('');
                        scanned = false; // Reset status scanned
                    }
                }, 200); // Waktu penundaan dalam milidetik (misalnya 200ms)
            });
        });
    
        function getData(qrcode_pelanggan) {
            // Ajax request untuk mengambil data dari backend
            $.ajax({
                url: '{{ route("get.customer.data") }}', // Menggunakan route() untuk mengambil URL endpoint
                method: 'GET',
                data: { qrcode_pelanggan: qrcode_pelanggan },
                success: function(response) {
                    // Isi nilai nama pelanggan, telepon, dan alamat berdasarkan respons dari backend
                    $('#nama_pelanggan').val(response.nama_pelanggan);
                    $('#telp').val(response.telp);
                    $('#alamat').val(response.alamat);
                    scanned = true; // Tandai bahwa sudah discan
                },
                error: function(xhr, status, error) {
                    // Handle error jika ada
                    console.error('Error:', error);
                }
            });
        }
    </script>
    

    <script>
        // Fungsi untuk menghapus format Rupiah dan mengembalikan nilai numerik
        function removeRupiahFormat(value) {
            return parseFloat(value.replace(/[^0-9,-]/g, '').replace(',', '.')) || 0;
        }

        // Format angka menjadi format Rupiah
        function formatRupiah(value) {
            let numberString = value.toString().replace(/[^,\d]/g, ''),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return split[1] !== undefined ? 'Rp. ' + rupiah + ',' + split[1] : 'Rp. ' + rupiah;
        }
        
        // Format input dan update kembalian
        function formatAndUpdateKembali() {
            let subTotalElement = document.getElementById('sub_total');
            let bayarElement = document.getElementById('dp_pemesanan');
            let kembaliElement = document.getElementById('kekurangan_pemesanan');

            // Mengambil nilai sub_total
            let subTotal = removeRupiahFormat(subTotalElement.value);

            // Format dan ambil nilai bayar
            let bayarValue = bayarElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
            let bayar = parseFloat(bayarValue) || 0; // Jika tidak valid, set 0

            // Format input 'bayar'
            bayarElement.value = formatRupiah(bayarValue);

            // Hitung kembalian
            let kembali = subTotal - bayar;
            
            // Format hasil kembalian sebagai Rupiah
            kembaliElement.value = kembali >= 0 ? formatRupiah(kembali) : 'Rp. 0';
        }

        // Panggil fungsi ini saat halaman dimuat untuk format sub_total yang mungkin sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            let subTotalElement = document.getElementById('sub_total');
            let subTotal = removeRupiahFormat(subTotalElement.value);
            subTotalElement.value = formatRupiah(subTotal);
        });
    </script>
@endsection