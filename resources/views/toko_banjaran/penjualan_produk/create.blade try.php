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
                <div class="col-sm-6">
                    <h1 class="m-0">Penjualan Produk Banjaran</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                 
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
                {{-- detail pelanggan --}}
                <div class="card">
                    <div class="card-header">
                        <div class="float-right">
                            <select class="form-control" id="kategori1" name="kategori">
                                <option value="">- Pilih -</option>
                                <option value="penjualan" {{ old('kategori1') == 'penjualan' ? 'selected' : '' }}>Penjualan Produk</option>
                                <option value="pelunasan" {{ old('kategori1') == 'pelunasan' ? 'selected' : '' }}>Pelunasan Pemesanan Produk</option>
                            </select>
                        </div>
                        {{-- <div class="float-right">
                            <a href="{{ route('toko_slawi.penjualan_produk.pelunasan') }}"  class="btn btn-primary btn-sm">Pelunasan Pemesanan
                            </a>
                        </div> --}}
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
                                <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" onclick="showCategoryModalpemesanan()">
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
                            <input type="text" id="searchInput" placeholder="Cari produk...">
                        </div>
                    
                        <!-- Tabel Produk -->
                        <table id="datatables5" class="table table-bordered table-striped" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th hidden>Kode Produk</th>
                                    <th>Kode Lama</th>
                                    <th>Nama Produk</th>
                                    <th hidden>QR Code Produk</th> <!-- Tambahkan kolom QR Code -->
                                    <th>Harga Member</th>
                                    <th>Diskon Member</th>
                                    <th>Harga Non Member</th>
                                    <th>Diskon Non Member</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                    @php
                                    $tokobanjaran = $item->tokobanjaran->first();
                                    $stok_tokobanjaran = $item->stok_tokobanjaran ? $item->stok_tokobanjaran->jumlah : 0; // Jika stok ada, tampilkan, jika tidak tampilkan 0
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td hidden>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->kode_lama }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td hidden>{{ $item->qrcode_produk }}</td> <!-- Tampilkan QR Code Produk -->
                                        <td>
                                            <span class="member_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_harga_bnjr : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="member_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_harga_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_harga_bnjr : '' }}</span>
                                        </td>
                                        <td>
                                            <span class="non_diskon_bnjr">{{ $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : '' }}</span>
                                        </td>
                                        <td class="text-center">
                                            {{ $stok_tokobanjaran }} <!-- Tampilkan stok produk -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        
                        <table id="tabel-pembelian" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="font-size:14px" class="text-center">No</th>
                                    <th hidden style="font-size:14px">Kode Produk</th>
                                    <th style="font-size:14px">Kode Lama</th>
                                    <th style="font-size:14px">Nama Produk</th>
                                    <th style="font-size:14px">Jumlah</th>
                                    <th style="font-size:14px">Diskon</th>
                                    <th hidden style="font-size:14px">Nominal Diskon</th>
                                    <th style="font-size:14px">Harga</th>
                                    <th style="font-size:14px">Total</th>
                                    <th style="font-size:14px; text-align:center">Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
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

<script>
 $(document).ready(function() {
    // Inisialisasi DataTable tanpa pagination dan tanpa pencarian dan info entri
    $('#datatables5').DataTable({
        paging: false, // Nonaktifkan pagination
        searching: false, // Nonaktifkan pencarian
        info: false, // Nonaktifkan informasi entri
        lengthChange: false // Nonaktifkan perubahan jumlah entri yang ditampilkan
    });

    // Sembunyikan semua baris di tabel produk
    $('#datatables5 tbody tr').hide();

    // Fungsi untuk menangani pencarian
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        
        // Tampilkan baris yang cocok dengan pencarian, sembunyikan yang lain
        if (value) {
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
            $('#datatables5 tbody tr').hide(); // Jika input kosong, sembunyikan semua baris
        }
    });

    // Ketika baris pada tabel produk diklik
    $('#datatables5 tbody').on('click', 'tr', function() {
        addRowToPurchaseTable($(this)); // Menambahkan baris ke tabel pembelian saat baris dipilih
        $('#searchInput').val(''); // Kosongkan input pencarian setelah memilih produk
        $('#datatables5 tbody tr').show(); // Tampilkan kembali semua baris
    });

    // Menangani event Enter pada input pencarian
    $('#searchInput').on('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Mencegah form dari submit
            var selectedRow = $('#datatables5 tbody tr:visible').first(); // Ambil baris pertama yang terlihat
            if (selectedRow.length) {
                // Jika ada baris yang terlihat, tambahkan ke tabel pembelian
                addRowToPurchaseTable(selectedRow);
                $(this).val(''); // Kosongkan input pencarian setelah menambahkan produk
                $('#datatables5 tbody tr').show(); // Tampilkan kembali semua baris
            }
        }
    });

    // Fungsi untuk menambahkan baris ke tabel pembelian
    function addRowToPurchaseTable(row) {
        var kodeProduk = row.find('td').eq(1).text();
        var kodeLama = row.find('td').eq(2).text();
        var namaProduk = row.find('td').eq(3).text();
        var harga = parseFloat(row.find('td').eq(4).text()) || 0; // Ubah menjadi float
        var diskon = row.find('td').eq(5).text();

        // Buat baris baru untuk tabel pembelian
        var newRow = `
            <tr>
                <td class="text-center">${$('#tabel-pembelian-body tr').length + 1}</td>
                <td hidden>${kodeProduk}</td>
                <td>${kodeLama}</td>
                <td>${namaProduk}</td>
                <td><input type="number" class="form-control jumlah" value="1" min="1"></td>
                <td>${diskon}</td>
                <td hidden>${diskon}</td>
                <td>${diskon}</td>
                <td class="total">${harga}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm delete-row">Hapus</button>
                </td>
            </tr>
        `;

        // Tambahkan baris ke tabel pembelian
        $('#tabel-pembelian-body').append(newRow);

        // Hitung total
        updateTotal();
    }

    // Fungsi untuk menghapus baris dari tabel pembelian
    $('#tabel-pembelian-body').on('click', '.delete-row', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    // Fungsi untuk menghitung ulang total ketika jumlah diubah
    $('#tabel-pembelian-body').on('input', '.jumlah', function() {
        var jumlah = $(this).val();
        var harga = parseFloat($(this).closest('tr').find('td').eq(7).text()) || 0;
        var total = jumlah * harga;
        $(this).closest('tr').find('.total').text(total);

        updateTotal();
    });

    // Fungsi untuk menghitung total keseluruhan
    function updateTotal() {
        var totalAll = 0;
        $('#tabel-pembelian-body tr').each(function() {
            totalAll += parseFloat($(this).find('.total').text()) || 0;
        });
        $('#total-sum').text('Total: ' + totalAll);
    }
});

</script>

{{-- <script>
    $(document).ready(function() {
    // Inisialisasi DataTable tanpa pagination dan tanpa pencarian dan info entri
    $('#datatables5').DataTable({
        paging: false, // Nonaktifkan pagination
        searching: false, // Nonaktifkan pencarian
        info: false, // Nonaktifkan informasi entri
        lengthChange: false // Nonaktifkan perubahan jumlah entri yang ditampilkan
    });

    // Sembunyikan semua baris di tabel produk
    $('#datatables5 tbody tr').hide();

    // Fungsi untuk menangani pencarian
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        
        // Tampilkan baris yang cocok dengan pencarian, sembunyikan yang lain
        if (value) {
            $('#datatables5 tbody tr').filter(function() {
                // Periksa apakah baris mengandung teks yang dicari di kolom yang relevan
                var kodeProduk = $(this).find('td').eq(1).text().toLowerCase();
                var kodeLama = $(this).find('td').eq(2).text().toLowerCase();
                var namaProduk = $(this).find('td').eq(3).text().toLowerCase();
                var qrcodeProduk = $(this).find('td').eq(4).text().toLowerCase(); // Ambil QR Code Produk

                // Tampilkan baris jika ada yang cocok di salah satu kolom
                $(this).toggle(kodeProduk.indexOf(value) > -1 || 
                            kodeLama.indexOf(value) > -1 || 
                            namaProduk.indexOf(value) > -1 || 
                            qrcodeProduk.indexOf(value) > -1);
            });
        } else {
            // Jika input kosong, sembunyikan semua baris
            $('#datatables5 tbody tr').hide();
        }
    });


    // Ketika baris pada tabel produk diklik
    $('#datatables5 tbody').on('click', 'tr', function() {
        addRowToPurchaseTable($(this)); // Menambahkan baris ke tabel pembelian saat baris dipilih
    });

    // Menangani event Enter pada input pencarian
    $('#searchInput').on('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Mencegah form dari submit
            var selectedRow = $('#datatables5 tbody tr:visible').first(); // Ambil baris pertama yang terlihat
            if (selectedRow.length) {
                // Jika ada baris yang terlihat, tambahkan ke tabel pembelian
                addRowToPurchaseTable(selectedRow);
            }
        }
    });

    // Fungsi untuk menambahkan baris ke tabel pembelian
    function addRowToPurchaseTable(row) {
        var kodeProduk = row.find('td').eq(1).text();
        var kodeLama = row.find('td').eq(2).text();
        var namaProduk = row.find('td').eq(3).text();
        var harga = parseFloat(row.find('td').eq(4).text()) || 0; // Ubah menjadi float
        var diskon = row.find('td').eq(5).text();

        // Buat baris baru untuk tabel pembelian
        var newRow = `
            <tr>
                <td class="text-center">${$('#tabel-pembelian-body tr').length + 1}</td>
                <td hidden>${kodeProduk}</td>
                <td>${kodeLama}</td>
                <td>${namaProduk}</td>
                <td><input type="number" class="form-control jumlah" value="1" min="1"></td>
                <td>${diskon}</td>
                <td hidden>${diskon}</td>
                <td>${harga}</td>
                <td class="total">${harga}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm delete-row">Hapus</button>
                </td>
            </tr>
        `;

        // Tambahkan baris ke tabel pembelian
        $('#tabel-pembelian-body').append(newRow);

        // Hitung total
        updateTotal();
    }

    // Fungsi untuk menghapus baris dari tabel pembelian
    $('#tabel-pembelian-body').on('click', '.delete-row', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    // Fungsi untuk menghitung ulang total ketika jumlah diubah
    $('#tabel-pembelian-body').on('input', '.jumlah', function() {
        var jumlah = $(this).val();
        var harga = parseFloat($(this).closest('tr').find('td').eq(7).text()) || 0;
        var total = jumlah * harga;
        $(this).closest('tr').find('.total').text(total);

        updateTotal();
    });

    // Fungsi untuk menghitung total keseluruhan
    function updateTotal() {
        var totalAll = 0;
        $('#tabel-pembelian-body tr').each(function() {
            totalAll += parseFloat($(this).find('.total').text()) || 0;
        });
        $('#total-sum').text('Total: ' + totalAll);
    }
    });

</script> --}}





    

@endsection
