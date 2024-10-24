@extends('layouts.app')

@section('title', 'Transaksi Penjualan')

@section('content')
<style>
    .form-group {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.form-group label {
    margin-right: 1rem;
    white-space: nowrap; /* Prevents label text from wrapping */
    width: 150px; /* Adjust based on your design */
}

.form-group input {
    flex: 1; /* Allows input to fill the remaining space */
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
                {{-- <div class="col-sm-6">
                    <h1 class="m-0">Penjualan Produk Banjaran</h1>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- /.content-header -->
    <style>
        .large-font {
            font-size: 1.5em; /* Atur ukuran font sesuai kebutuhan */
            font-weight: bold;
        }
    </style>
    <section class="content" onload="showPaymentFields()">
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
            <form id="penjualanForm" action="{{ url('toko_banjaran/penjualan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                {{-- <div class="card">
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
                            <div class="col-md-2 mt-2" hidden>
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
                                <input hidden type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                                <input hidden type="text" class="form-control" id="kode_pelangganlama" name="kode_pelangganlama" value="{{ old('kode_pelangganlama') }}" onclick="showCategoryModalpemesanan()">
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
                </div> --}}
             
                
                <!-- Bagian card form -->
                <div class="card" id="formContainer">
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
                
                            <div class="col-md-2 mt-2" hidden>
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
                
                            <div class="col-md-5 mt-4" id="namaPelangganRow" style="display: none;">
                                <label for="nama_pelanggan">Nama Pelanggan</label>
                                <div class="input-group mb-3">
                                    <input hidden type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}" onclick="showCategoryModalpemesanan()">
                                    <input hidden type="text" class="form-control" id="kode_pelangganlama" name="kode_pelangganlama" value="{{ old('kode_pelangganlama') }}" onclick="showCategoryModalpemesanan()">
                                    <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}">
                                    <button class="btn btn-outline-primary" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                
                        <div class="row align-items-center" id="telpRow" hidden>
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
                                            <th>Kode Pelanggan</th>
                                            <th>Kode Lama</th>
                                            <th>Nama Pelanggan</th>
                                            <th>No Telpon</th>
                                            <th>Alamat</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelanggans as $item)
                                            <tr onclick="getSelectedDataPemesanan('{{ $item->nama_pelanggan }}', '{{ $item->telp }}', '{{ $item->alamat }}', '{{ $item->kode_pelanggan }}', '{{ $item->kode_pelangganlama }}')">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_pelanggan }}</td>
                                                <td>{{ $item->kode_pelangganlama }}</td>
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
            
                <div class="card">
                    <div class="modal-body">
                        <!-- Form Pencarian -->
                        <div class="form-group">
                            <label for="searchInput">Cari Produk:</label>
                            <div class="input-group">
                                <input type="text" id="searchInput" placeholder="Cari produk..." class="form-control" onclick="checkCustomerType()">
                            </div>
                        </div>
                
                        <!-- Tabel Produk -->
                        <table id="datatables5" class="table table-bordered table-striped" style="font-size: 12px;">
                            <thead id="table-head" style="display: none;">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th hidden>Kode Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th hidden>QR Code Produk</th>
                                    <th>Harga Member</th>
                                    <th>Diskon Member</th>
                                    <th>Harga Non Member</th>
                                    <th>Diskon Non Member</th>
                                    <th>Stok</th>
                                    <th hidden>ID Produk</th> <!-- Kolom tersembunyi untuk ID produk -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                    @php
                                        $tokobanjaran = $item->tokobanjaran->first();
                                        $stok_tokobanjaran = $item->stok_tokobanjaran ? $item->stok_tokobanjaran->jumlah : 0;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td hidden>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td hidden>{{ $item->qrcode_produk }}</td>
                                        <td>
                                            <span class="member_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="member_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : 'N/A' }}</span>
                                        </td>
                                        <td class="text-center">{{ $stok_tokobanjaran }}</td>
                                        <td hidden>{{ $item->id }}</td> <!-- Tambahkan ID produk -->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                
                        <!-- Tabel Pembelian -->
                        <table id="tabel-pembelian" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th style="font-size:12px; width: 5%;" class="text-center">No</th>
                                    <th hidden style="font-size:12px">Kode Produk</th>
                                    <th style="font-size:12px; width: 15%;">Kode Produk</th>
                                    <th style="font-size:12px; width: 40%;">Nama Produk</th>
                                    <th style="font-size:12px; width: 10%;">Jumlah</th>
                                    <th style="font-size:12px; width: 10%;">Diskon</th>
                                    <th  style="font-size:12px">Nominal Diskon</th>
                                    <th style="font-size:12px; width: 10%;">Harga</th>
                                    <th style="font-size:12px; width: 10%;">Total</th>
                                    <th style="font-size:12px;; width: 10%; text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian-body"></tbody>
                        </table>
                
                        <!-- Total Pembelian -->
                        {{-- <div id="total-sum" style="font-weight: bold;">Total: 0</div> --}}
                    </div>
                </div>

                <div class="row mb-3">
                    {{-- <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_total" class="mr-2">Sub Total</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="Rp0" oninput="updateCalculations();">
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="Rp0" >
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="bayar" class="mr-2">Uang Bayar</label>
                                        <input type="text" class="form-control large-font" id="bayar" name="bayar" value="{{ old('bayar') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kembali" class="mr-2">Kembali</label>
                                        <input type="text" class="form-control large-font" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_total" class="mr-2">Sub Total</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="Rp0" readonly>
                                    </div>
                                </div>
                                <div class="row" hidden>
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="sub_totalasli" class="mr-2">Sub Total Asli</label>
                                        <input type="text" class="form-control large-font" id="sub_totalasli" name="sub_totalasli" value="Rp0" readonly>
                                    </div>
                                </div>
                                <div class="row" id="payment-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="bayar" class="mr-2">Uang Bayar</label>
                                        <input type="text" class="form-control large-font" id="bayar" name="bayar" value="{{ old('bayar') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row" id="change-row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kembali" class="mr-2">Kembali</label>
                                        <input type="text" class="form-control large-font" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
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

                </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="catatan">Catatan</label>
                                    <textarea placeholder="" type="text" class="form-control" id="catatan" name="catatan">{{ old('catatan') }}</textarea>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label for="kasir">Bagian Input :</label>
                                    <input type="text" class="form-control" readonly name="kasir" value="{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function checkCustomerType() {
        var kategori = document.getElementById("kategori").value;

        if (kategori === "") {
            Swal.fire({
                title: 'Pilih Tipe Pelanggan',
                text: 'Silakan pilih tipe pelanggan terlebih dahulu!',
                icon: 'warning',
                confirmButtonText: 'Ok'
            });
        } else {
            console.log("Searching for products...");
        }
    }
</script>

<script>
    $(document).ready(function() {
        // Event listener untuk perubahan tipe pelanggan
        $('#kategori').change(function() {
            var tipePelanggan = $(this).val(); // Ambil nilai yang dipilih
            updatePrices(tipePelanggan); // Panggil fungsi untuk mengupdate harga dan diskon
        });

        // Fungsi untuk memperbarui harga dan diskon
        function updatePrices(tipePelanggan) {
            $('#datatables5 tbody tr').each(function() {
                var hargaMember = parseFloat($(this).find('.member_harga_bnjr').text()) || 0;
                var diskonMember = parseFloat($(this).find('.member_diskon_bnjr').text()) || 0;
                var hargaNonMember = parseFloat($(this).find('.non_harga_bnjr').text()) || 0;
                var diskonNonMember = parseFloat($(this).find('.non_diskon_bnjr').text()) || 0;

                if (tipePelanggan === 'member') {
                    // Update harga dan diskon member
                    $(this).find('.member_harga_bnjr').text(hargaMember); // Harga member
                    $(this).find('.member_diskon_bnjr').text(diskonMember); // Diskon member
                } else if (tipePelanggan === 'nonmember') {
                    // Update harga dan diskon non-member
                    $(this).find('.non_harga_bnjr').text(hargaNonMember); // Harga non-member
                    $(this).find('.non_diskon_bnjr').text(diskonNonMember); // Diskon non-member
                }
            });
        }

        // Inisialisasi DataTable tanpa pagination, pencarian, dan info entri
        $('#datatables5').DataTable({
            paging: false,
            searching: false,
            info: false,
            lengthChange: false
        });

        // Sembunyikan semua baris di tabel produk di awal
        $('#datatables5 tbody tr').hide();

        // Menampilkan baris yang sesuai pencarian, menyembunyikan yang tidak sesuai
        $('#searchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            if (value) {
                $('#table-head').show(); // Tampilkan thead
                $('#datatables5 tbody tr').filter(function() {
                    var kodeProduk = $(this).find('td').eq(1).text().toLowerCase();
                    var kodeLama = $(this).find('td').eq(2).text().toLowerCase();
                    var namaProduk = $(this).find('td').eq(3).text().toLowerCase();
                    var qrcodeProduk = $(this).find('td').eq(4).text().toLowerCase();

                    $(this).toggle(kodeProduk.indexOf(value) > -1 || 
                                   kodeLama.indexOf(value) > -1 || 
                                   namaProduk.indexOf(value) > -1 || 
                                   qrcodeProduk.indexOf(value) > -1);
                });
            } else {
                $('#table-head').hide(); // Sembunyikan thead jika input kosong
                $('#datatables5 tbody tr').hide();
            }
        });

        // Tangkap event klik pada baris produk untuk menambahkan ke tabel pembelian
        $('#datatables5 tbody').on('click', 'tr', function() {
            addRowToPurchaseTable($(this)); // Tambahkan produk ke tabel pembelian
            $('#searchInput').val(''); // Kosongkan pencarian
            $('#datatables5 tbody tr').hide(); // Sembunyikan semua baris
            $('#table-head').hide(); // Sembunyikan header tabel
        });

        // Tangkap event tekan Enter pada input pencarian
        $('#searchInput').on('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                var selectedRow = $('#datatables5 tbody tr:visible').first(); // Ambil baris pertama yang terlihat
                if (selectedRow.length) {
                    addRowToPurchaseTable(selectedRow); // Tambahkan produk ke tabel pembelian
                    $(this).val(''); // Kosongkan pencarian
                    $('#datatables5 tbody tr').hide(); // Sembunyikan semua baris
                    $('#table-head').hide(); // Sembunyikan header tabel
                }
            }
        });

        function addRowToPurchaseTable(row) {
            // Ambil tipe pelanggan yang dipilih
            var tipePelanggan = $('#kategori').val();

            // Ambil data produk dari row yang diklik
            var kodeProduk = row.find('td').eq(1).text();
            var kodeLama = row.find('td').eq(2).text();
            var namaProduk = row.find('td').eq(3).text();
            var idProduk = row.find('td').eq(10).text();

            // Tentukan harga dan diskon berdasarkan tipe pelanggan
            var harga, diskon;
            if (tipePelanggan === 'member') {
                harga = parseFloat(row.find('.member_harga_bnjr').text()) || 0;
                diskon = parseFloat(row.find('.member_diskon_bnjr').text()) || 0;
            } else if (tipePelanggan === 'nonmember') {
                harga = parseFloat(row.find('.non_harga_bnjr').text()) || 0;
                diskon = parseFloat(row.find('.non_diskon_bnjr').text()) || 0;
            }

            // Ambil jumlah dari input
            var jumlah = 1; // Default nilai jumlah saat menambahkan produk baru

            // Hitung nominal diskon dan total per item
            var nominal_diskon = (harga * (diskon / 100)) * jumlah;
            var totalPerItem = (harga - (harga * (diskon / 100))) * jumlah;

            // Periksa apakah produk dengan kodeProduk sudah ada di tabel pembelian
            var existingRow = $('#tabel-pembelian-body tr').filter(function() {
                return $(this).find('td').eq(1).text() === kodeProduk;
            });

            if (existingRow.length > 0) {
                // Jika produk sudah ada, perbarui jumlah dan total
                var jumlahInput = existingRow.find('.jumlah');
                var jumlahSekarang = parseInt(jumlahInput.val()) || 1;
                var jumlahBaru = jumlahSekarang + 1;
                jumlahInput.val(jumlahBaru);

                // Hitung nominal_diskon dan total per item berdasarkan jumlah baru
                nominal_diskon = (harga * (diskon / 100)) * jumlahBaru; // Hitung nominal diskon
                totalPerItem = (harga - (harga * (diskon / 100))) * jumlahBaru; // Hitung total per item setelah diskon

                existingRow.find('.total').text(totalPerItem); // Update total per item
                existingRow.find('.nominal_diskon').text(nominal_diskon); // Update nominal diskon
            } else {
                // Jika produk belum ada, tambahkan row baru
                var newRow = `
                    <tr style="font-size: 13px;">
                        <td class="text-center">${$('#tabel-pembelian-body tr').length + 1}</td>
                        <td hidden>${kodeProduk}</td>
                        <td>${kodeLama}</td>
                        <td>${namaProduk}</td>
                        <td><input type="number" class="form-control-sm jumlah" value="1" min="1"></td>
                        <td>${diskon}%</td>
                        <td class="nominal_diskon">${nominal_diskon}</td> <!-- Menampilkan nominal diskon -->
                        <td>${harga}</td>
                        <td class="total">${totalPerItem}</td> <!-- Menampilkan total per item -->
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm delete-row"><i class="fas fa-trash"></i></button>
                        </td>
                        <td hidden>${idProduk}</td>
                    </tr>
                `;

                // Tambahkan row baru ke tabel pembelian
                $('#tabel-pembelian-body').append(newRow);
            }

            // Hitung total pembelian
            calculateTotal();

           // Penanganan untuk mengupdate total dan nominal_diskon saat jumlah diubah
            $('#tabel-pembelian-body').on('input', '.jumlah', function() {
                var jumlah = parseInt($(this).val()) || 0; // Ambil nilai jumlah dari input
                var hargaSatuan = parseFloat($(this).closest('tr').find('td').eq(7).text()) || 0; // Harga satuan
                var diskon = parseFloat($(this).closest('tr').find('td').eq(5).text()) || 0; // Ambil diskon dari kolom yang tepat

                // Hitung nominal_diskon dan total per item berdasarkan jumlah baru
                var nominal_diskon = (hargaSatuan * (diskon / 100)) * jumlah; // Hitung nominal diskon
                var total = (hargaSatuan - (hargaSatuan * (diskon / 100))) * jumlah; // Total per item setelah diskon

                // Update total dan nominal diskon di baris yang sama
                $(this).closest('tr').find('.total').text(total); // Update total per item
                $(this).closest('tr').find('.nominal_diskon').text(nominal_diskon); // Update nominal diskon

                calculateTotal(); // Hitung ulang total pembelian
            });


            // Event listener untuk tombol hapus baris
            $('#tabel-pembelian-body').on('click', '.delete-row', function() {
                $(this).closest('tr').remove(); // Hapus baris
                calculateTotal(); // Hitung ulang total pembelian
            });
        }


        function calculateTotal() {
            var subtotal = 0;
            $('#tabel-pembelian-body .total').each(function() {
                subtotal += parseFloat($(this).text()) || 0; // Tambah subtotal dari setiap total produk
            });

            // Format subtotal ke dalam format rupiah
            var formattedSubtotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(subtotal);

            // Update subtotal di elemen input
            $('#sub_total').val(formattedSubtotal);
            $('#sub_totalasli').val(subtotal); // Simpan nilai asli tanpa format

            calculateKembali(); // Panggil fungsi untuk menghitung kembali
        }
            // Fungsi untuk menghitung uang kembalian
            function calculateKembali() {
                var bayar = parseFloat($('#bayar').val().replace(/[^\d]/g, '')) || 0; // Ambil nilai bayar tanpa format
                var subtotal = parseFloat($('#sub_totalasli').val()) || 0; // Ambil subtotal tanpa format

                var kembali = bayar - subtotal; // Hitung kembalian

                // Format kembali ke dalam format rupiah
                var formattedKembali = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(kembali);

                // Update input kembali
                $('#kembali').val(formattedKembali);
            }

            // Fungsi untuk memformat input bayar saat pengguna selesai mengetik (saat blur)
            function formatAndUpdateKembali() {
                var bayar = $('#bayar').val().replace(/[^\d]/g, ''); // Hilangkan semua karakter kecuali angka
                if (bayar !== "") {
                    var formattedBayar = "Rp " + new Intl.NumberFormat('id-ID').format(bayar); // Tambahkan "Rp" dan format ke rupiah
                    $('#bayar').val(formattedBayar); // Set kembali ke input setelah selesai mengetik
                }
                calculateKembali(); // Hitung kembalian setelah pemformatan
            }

            // Event listener untuk format hanya saat input kehilangan fokus (blur)
            $('#bayar').on('blur', function() {
                formatAndUpdateKembali(); // Format hanya setelah input selesai
            });

            // Fungsi untuk menghilangkan format ketika pengguna fokus ke input (agar bisa mengetik angka tanpa gangguan)
            $('#bayar').on('focus', function() {
                var bayar = $(this).val().replace(/[^\d]/g, ''); // Hilangkan format saat fokus
                $(this).val(bayar); // Tampilkan angka tanpa format ketika input difokuskan
            });

            // Menghitung kembali setiap kali input bayar diubah (tanpa format langsung)
            $('#bayar').on('input', function() {
                calculateKembali(); // Hitung kembalian tanpa format langsung
            });


        // // Fungsi untuk menghitung total pembelian
        // function calculateTotal() {
        //     var total = 0;
        //     $('#tabel-pembelian-body .total').each(function() {
        //         total += parseFloat($(this).text()) || 0; // Tambah total tanpa format
        //     });
        //     $('#total-sum').text('Total: ' + total.toFixed(2)); // Update total tanpa format
        // }
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        var kategoriSelect = document.getElementById('kategori');
        var namaPelangganRow = document.getElementById('namaPelangganRow');
        var telpRow = document.getElementById('telpRow');
        var alamatRow = document.getElementById('alamatRow');
        var kodePelangganRow = document.getElementById('kodePelangganRow'); 
        var namaPelangganInput = document.getElementById('nama_pelanggan');
        
        kategoriSelect.addEventListener('change', function() {
            if (kategoriSelect.value === 'member') {

                kodePelangganRow.hidden = false; 
                namaPelangganInput.readOnly = true; 
                namaPelangganRow.style.display = 'block'; 
                telpRow.hidden = true; 
                alamatRow.hidden = true; 
            } else if (kategoriSelect.value === 'nonmember') {

                kodePelangganRow.hidden = true; 
                namaPelangganInput.readOnly = false; 
                namaPelangganRow.style.display = 'none'; 
                telpRow.hidden = true; 
                alamatRow.hidden = true; 
            } else {
                namaPelangganRow.style.display = 'none'; 
                telpRow.hidden = true; 
                alamatRow.hidden = true; 
                kodePelangganRow.hidden = true; 
            }
        });

        if (kategoriSelect.value === 'nonmember') {
            namaPelangganRow.style.display = 'none'; 
            telpRow.hidden = true; 
            alamatRow.hidden = true; 
        }
    });
</script>

<script>
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
    // Inisialisasi DataTable dan atur modal
    $(document).ready(function() {
        var pelangganTable = $('#datatables4').DataTable();

        $('#tableMarketing').on('shown.bs.modal', function () {
            pelangganTable.columns.adjust().draw();
        });
    });

    // Fungsi untuk menampilkan modal
    function showCategoryModalpemesanan() {
        $('#tableMarketing').modal('show');
    }

    // Fungsi untuk mendapatkan data pelanggan dari modal dan menyembunyikan modal
    function getSelectedDataPemesanan(nama_pelanggan, telp, alamat, kode_pelanggan, kode_pelangganlama) {
        // Masukkan data yang dipilih ke dalam input form
        document.getElementById('nama_pelanggan').value = nama_pelanggan;
        document.getElementById('kode_pelanggan').value = kode_pelanggan;
        document.getElementById('kode_pelangganlama').value = kode_pelangganlama; // Perbaikan: gunakan ID yang benar
        document.getElementById('telp').value = telp;
        document.getElementById('alamat').value = alamat;

        // Sembunyikan modal setelah data dipilih
        $('#tableMarketing').modal('hide');
    }
</script>




@endsection
