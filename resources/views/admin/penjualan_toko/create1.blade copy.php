@extends('layouts.app')

@section('title', 'Pemesanan Produk')

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
                    <h1 class="m-0">Penjualan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">penjualan Produk</a></li>
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
            <form action="{{ url('admin/penjualan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
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
                            <a href="{{ route('admin.penjualan_produk.pelunasan') }}"  class="btn btn-primary btn-sm">Pelunasan Pemesanan
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
       
                 <div class="modal fade" id="tableDeposit" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Data Deposit</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table id="datatables6" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Kode Deposit</th>
                                            <th>Kode Pemesanan</th>
                                            <th>Alamat</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dppemesanans as $item)
                                            <tr onclick="getSelectedDataDeposit('{{ $item->kode_dppemesanan }}', '{{ $item->dp_pemesanan }}', '{{ $item->kekurangan_pemesanan }}')">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_dppemesanan }}</td>
                                                <td>{{ $item->dp_pemesanan }}</td>
                                                <td>{{ $item->kekurangan_pemesanan }}</td>
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
                                            <th>Nama Pelanggan</th>
                                            <th>No Telpon</th>
                                            <th>Alamat</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pelanggans as $item)
                                            <tr onclick="getSelectedDataPemesanan('{{ $item->nama_pelanggan }}', '{{ $item->kode_pelanggan }}','{{ $item->telp }}', '{{ $item->alamat }}')">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_pelanggan }}</td>
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
                                        <tr class="pilih-btn" 
                                            data-id="{{ $item->id }}"
                                            data-kode="{{ $item->kode_produk }}"
                                            data-kode="{{ $item->catatanproduk }}"
                                            data-nama="{{ $item->nama_produk }}"
                                            data-member="{{ $item->tokoslawi->first()->member_harga_slw }}"
                                            data-diskonmember="{{ $item->tokoslawi->first()->member_diskon_slw }}"
                                            data-nonmember="{{ $item->tokoslawi->first()->non_harga_slw }}"
                                            data-diskonnonmember="{{ $item->tokoslawi->first()->non_diskon_slw }}">>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_produk }}</td>
                                            <td>{{ $item->nama_produk }}</td>
                                            <td>
                                                <span class="member_harga_slw">{{$item->tokoslawi->first()->member_harga_slw}}</span>
                                            </td>
                                            <td>
                                                <span class="member_diskon_slw">{{ $item->tokoslawi->first()->member_diskon_slw }}</span>
                                            </td>
                                            <td>
                                                <span class="non_harga_slw">{{$item->tokoslawi->first()->non_harga_slw}}</span>
                                            </td>
                                            <td>
                                                <span class="non_diskon_slw">{{ $item->tokoslawi->first()->non_diskon_slw }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-kode="{{ $item->kode_produk }}"
                                                    data-kode="{{ $item->catatanproduk }}"
                                                    data-nama="{{ $item->nama_produk }}"
                                                    data-member="{{ $item->tokoslawi->first()->member_harga_slw }}"
                                                    data-diskonmember="{{ $item->tokoslawi->first()->member_diskon_slw }}"
                                                    data-nonmember="{{ $item->tokoslawi->first()->non_harga_slw }}"
                                                    data-diskonnonmember="{{ $item->tokoslawi->first()->non_diskon_slw }}">
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
                                        <label for="sub_total" class="mr-2">Sub Total</label>
                                        <input type="text" class="form-control large-font" id="sub_total" name="sub_total" value="Rp0" oninput="validateNumberInput(event); showPaymentFields()">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="bayar" class="mr-2">Uang Bayar</label>
                                        <input type="text" class="form-control large-font" id="bayar" name="bayar" value="{{ old('bayar') }}" oninput="formatAndUpdateKembali()">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3 d-flex align-items-center">
                                        <label for="kembali" class="mr-2">Kembali</label>
                                        <input type="text" class="form-control large-font" id="kembali" name="kembali" value="{{ old('kembali') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        {{-- <div class="card">
                            <div class="card-header">
                                <label class="form-label" for="metodebayar">Metode Pembayaran</label>
                                <select class="form-control" id="metodebayar" name="metodebayar" onchange="showPaymentFields()">
                                    <option value="">- Pilih -</option>
                                    <option value="mesinedc">MESIN EDC</option>
                                    <option value="gobiz">GO-BIZ</option>
                                    <option value="transfer">TRANSFER</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="card">
                            <div class="card-header">
                                <label class="form-label" for="metodebayar">Metode Pembayaran</label>
                                <select class="form-control" id="metodebayar" name="metodebayar" onchange="showPaymentFields()">
                                    <option value="">- Pilih -</option>
                                    @foreach($metodes as $metode)
                                        <option value="{{ $metode->id }}">{{ $metode->nama_metode }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="payment-fields">
                            <!-- Form untuk GO-BIZ -->
                            <div id="gobiz-fields" class="payment-field" hidden>
                                <div class="form-group">
                                    <label for="gobiz_code">No GoFood</label>
                                    <input type="text" id="gobiz_code" name="ket_gobiz" class="form-control" placeholder="Masukkan kode GO-BIZ">
                                    <input type="hidden" id="metode_bayar_hidden" name="metodebayar" value="tunai">
                                </div>
                                <div class="form-group">
                                    <label for="gobiz_fee">Fee (20%)</label>
                                    <input type="text" id="gobiz_fee" name="gobiz_fee" class="form-control" placeholder="Masukkan fee" readonly>
                                </div>
                            </div>
                        
                            <!-- Form untuk MESIN EDC -->
                            <div id="mesinedc-fields" class="payment-field" hidden>
                                <div class="form-group">
                                    <label for="struk_edc">No Struk EDC</label>
                                    <input type="text" id="struk_edc" name="ket_edc" class="form-control" placeholder="Masukkan No Struk EDC">
                                </div>
                                <div class="form-group">
                                    <label for="struk_edc_fee">Fee (1%)</label>
                                    <input type="text" id="struk_edc_fee" name="struk_edc_fee" class="form-control" readonly>
                                </div>
                            </div>
                        
                            <!-- Form untuk TRANSFER -->
                            <div id="transfer-fields" class="payment-field" hidden>
                                <div class="form-group">
                                    <label for="no_rek">No Rekening</label>
                                    <input type="text" id="no_rek" name="ket_rekening" value="362800800" class="form-control">
                                </div>
                            </div>
                        
                            <!-- Form untuk QRIS -->
                            <div id="qris-fields" class="payment-field" hidden>
                                <div class="form-group">
                                    <label for="qris_code">No Referensi</label>
                                    <input type="text" id="qris_code" name="ket_qris" value="713072924254" class="form-control" placeholder="Masukkan kode QRIS">
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

    {{-- <script>
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
            let bayarElement = document.getElementById('bayar');
            let kembaliElement = document.getElementById('kembali');
    
            // Mengambil nilai sub_total
            let subTotal = removeRupiahFormat(subTotalElement.value);
    
            // Format dan ambil nilai bayar
            let bayarValue = bayarElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
            let bayar = parseFloat(bayarValue) || 0; // Jika tidak valid, set 0
    
            // Format input 'bayar'
            bayarElement.value = formatRupiah(bayarValue);
    
            // Hitung kembalian
            let kembali = bayar - subTotal;
    
            // Format hasil kembalian sebagai Rupiah
            kembaliElement.value = kembali >= 0 ? formatRupiah(kembali) : 'Rp. 0';
        }
    
        // Panggil fungsi ini saat halaman dimuat untuk format sub_total yang mungkin sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            let subTotalElement = document.getElementById('sub_total');
            let subTotal = removeRupiahFormat(subTotalElement.value);
            subTotalElement.value = formatRupiah(subTotal);
        });
    
        document.querySelector('form').addEventListener('submit', function(event) {
            let subTotalElement = document.getElementById('sub_total');
            let bayarElement = document.getElementById('bayar');
            let kembaliElement = document.getElementById('kembali');
    
            // Menghapus format Rupiah dari input sebelum submit
            subTotalElement.value = removeRupiahFormat(subTotalElement.value);
            bayarElement.value = removeRupiahFormat(bayarElement.value);
            kembaliElement.value = removeRupiahFormat(kembaliElement.value);
    
            // Formulir akan disubmit dengan nilai numerik
        });
    
        let originalSubTotal = 0;
    
        function cleanSubTotal(subTotal) {
            // Hapus "Rp" dan titik
            return parseFloat(subTotal.replace(/Rp|\.|,/g, '')) || 0;
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
    
            // Sembunyikan field Uang Bayar dan Kembali
            document.getElementById('bayar').parentElement.parentElement.style.display = 'none';
            document.getElementById('kembali').parentElement.parentElement.style.display = 'none';
    
            // Update hidden input field
            const metodeBayarHidden = document.getElementById('metode_bayar_hidden');
            metodeBayarHidden.value = metodebayar || 'tunai'; // Default to 'tunai'
    
            if (metodebayar === "gobiz") {
                document.getElementById('gobiz-fields').hidden = false;
                const feeField = document.getElementById('gobiz_fee');
                const fee = Math.round(subTotal * 0.20); // Fee 20%
                feeField.value = formatRupiah(fee.toString());
    
                // Kurangi subTotal dengan fee
                subTotalField.value = formatRupiah((originalSubTotal + fee).toString());
            } else if (metodebayar === "mesinedc") {
                document.getElementById('mesinedc-fields').hidden = false;
                const feeField = document.getElementById('struk_edc_fee');
                const fee = Math.round(subTotal * 0.01); // Fee 1%
                feeField.value = formatRupiah(fee.toString());
    
                // Kurangi subTotal dengan fee
                subTotalField.value = formatRupiah((originalSubTotal + fee).toString());
            } else if (metodebayar === "transfer") {
                document.getElementById('transfer-fields').hidden = false;
                subTotalField.value = formatRupiah(originalSubTotal.toString());
            } else if (metodebayar === "qris") {
                document.getElementById('qris-fields').hidden = false;
                subTotalField.value = formatRupiah(originalSubTotal.toString());
            } else {
                // Tampilkan field Uang Bayar dan Kembali jika tunai
                document.getElementById('bayar').parentElement.parentElement.style.display = 'block';
                document.getElementById('kembali').parentElement.parentElement.style.display = 'block';
                subTotalField.value = formatRupiah(originalSubTotal.toString());
            }
        }
    </script> --}}
    
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
            let bayarElement = document.getElementById('bayar');
            let kembaliElement = document.getElementById('kembali');
    
            // Mengambil nilai sub_total
            let subTotal = removeRupiahFormat(subTotalElement.value);
    
            // Format dan ambil nilai bayar
            let bayarValue = bayarElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
            let bayar = parseFloat(bayarValue) || 0; // Jika tidak valid, set 0
    
            // Format input 'bayar'
            bayarElement.value = formatRupiah(bayarValue);
    
            // Hitung kembalian
            let kembali = bayar - subTotal;
    
            // Format hasil kembalian sebagai Rupiah
            kembaliElement.value = kembali >= 0 ? formatRupiah(kembali) : 'Rp. 0';
        }
    
        // Panggil fungsi ini saat halaman dimuat untuk format sub_total yang mungkin sudah ada
        document.addEventListener('DOMContentLoaded', function() {
            let subTotalElement = document.getElementById('sub_total');
            let subTotal = removeRupiahFormat(subTotalElement.value);
            subTotalElement.value = formatRupiah(subTotal);
        });
    
        document.querySelector('form').addEventListener('submit', function(event) {
            let subTotalElement = document.getElementById('sub_total');
            let bayarElement = document.getElementById('bayar');
            let kembaliElement = document.getElementById('kembali');
            
            // Menghapus format Rupiah dari input sebelum submit
            subTotalElement.value = removeRupiahFormat(subTotalElement.value);
            bayarElement.value = removeRupiahFormat(bayarElement.value);
            kembaliElement.value = removeRupiahFormat(kembaliElement.value);
            
            // Hapus format Rupiah dari fee sebelum submit
            let gobizFeeElement = document.getElementById('gobiz_fee');
            let strukEdcFeeElement = document.getElementById('struk_edc_fee');
            
            if (gobizFeeElement) {
                gobizFeeElement.value = removeRupiahFormat(gobizFeeElement.value);
            }
            if (strukEdcFeeElement) {
                strukEdcFeeElement.value = removeRupiahFormat(strukEdcFeeElement.value);
            }
        });
    
        let originalSubTotal = 0;
    
        function cleanSubTotal(subTotal) {
            // Hapus "Rp" dan titik
            return parseFloat(subTotal.replace(/Rp|\.|,/g, '')) || 0;
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
    
            // Sembunyikan field Uang Bayar dan Kembali
            document.getElementById('bayar').parentElement.parentElement.style.display = 'none';
            document.getElementById('kembali').parentElement.parentElement.style.display = 'none';
    
            // Update hidden input field
            const metodeBayarHidden = document.getElementById('metode_bayar_hidden');
            metodeBayarHidden.value = metodebayar || 'tunai'; // Default to 'tunai'
    
            if (metodebayar === "gobiz") {
                document.getElementById('gobiz-fields').hidden = false;
                const feeField = document.getElementById('gobiz_fee');
                const fee = Math.round(subTotal * 0.20); // Fee 20%
                feeField.value = formatRupiah(fee.toString());
    
                // Kurangi subTotal dengan fee
                subTotalField.value = formatRupiah((originalSubTotal + fee).toString());
            } else if (metodebayar === "mesinedc") {
                document.getElementById('mesinedc-fields').hidden = false;
                const feeField = document.getElementById('struk_edc_fee');
                const fee = Math.round(subTotal * 0.01); // Fee 1%
                feeField.value = formatRupiah(fee.toString());
    
                // Kurangi subTotal dengan fee
                subTotalField.value = formatRupiah((originalSubTotal + fee).toString());
            } else if (metodebayar === "transfer") {
                document.getElementById('transfer-fields').hidden = false;
                subTotalField.value = formatRupiah(originalSubTotal.toString());
            } else if (metodebayar === "qris") {
                document.getElementById('qris-fields').hidden = false;
                subTotalField.value = formatRupiah(originalSubTotal.toString());
            } else {
                // Tampilkan field Uang Bayar dan Kembali jika tunai
                document.getElementById('bayar').parentElement.parentElement.style.display = 'block';
                document.getElementById('kembali').parentElement.parentElement.style.display = 'block';
                subTotalField.value = formatRupiah(originalSubTotal.toString());
            }
        }
    </script>
    

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
            // menghide form inputan
            document.addEventListener('DOMContentLoaded', function() {
                var kategoriSelect = document.getElementById('kategori');
                var namaPelangganRow = document.getElementById('namaPelangganRow');
                var telpRow = document.getElementById('telpRow');
                var alamatRow = document.getElementById('alamatRow');
                var namaPelangganInput = document.getElementById('nama_pelanggan');


                kategoriSelect.addEventListener('change', function() {
                    if (kategoriSelect.value === 'member') {
                    
                        kodePelangganRow.hidden = false;
                        namaPelangganInput.readOnly = true;
                        namaPelangganRow.style.display = 'block';
                        telpRow.hidden = false;
                        alamatRow.hidden = false;
                    } else if (kategoriSelect.value === 'nonmember') {
                        
                        kodePelangganRow.hidden = true;
                        namaPelangganInput.readOnly = false;
                        namaPelangganRow.style.display = 'none';
                        telpRow.hidden = true;
                        alamatRow.hidden = true;
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
        
            function getSelectedDataPemesanan(nama_pelanggan, kode_pelanggan, telp, alamat) {
                document.getElementById('nama_pelanggan').value = nama_pelanggan;
                document.getElementById('kode_pelanggan').value = kode_pelanggan;
                document.getElementById('telp').value = telp;
                document.getElementById('alamat').value = alamat;
                $('#tableMarketing').modal('hide');
            }

            function showCategoryModaldeposit() {
                $('#tableDeposit').modal('show');
            }
        
            function getSelectedDataDeposit(kode_dppemesanan, dp_pemesanan, kekurangan_pemesanan) {
                document.getElementById('kode_dppemesanan').value = kode_dppemesanan;
                document.getElementById('dp_pemesanan').value = dp_pemesanan;
                document.getElementById('kekurangan_pemesanan').value = kekurangan_pemesanan;
                $('#tableDeposit').modal('hide');
            }
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
                let bayarElement = document.getElementById('bayar');
                let kembaliElement = document.getElementById('kembali');

                // Mengambil nilai sub_total
                let subTotal = removeRupiahFormat(subTotalElement.value);

                // Format dan ambil nilai bayar
                let bayarValue = bayarElement.value.replace(/[^0-9,-]/g, '').replace(',', '.');
                let bayar = parseFloat(bayarValue) || 0; // Jika tidak valid, set 0

                // Format input 'bayar'
                bayarElement.value = formatRupiah(bayarValue);

                // Hitung kembalian
                let kembali = bayar - subTotal;
                
                // Format hasil kembalian sebagai Rupiah
                kembaliElement.value = kembali >= 0 ? formatRupiah(kembali) : 'Rp. 0';
            }

            // Panggil fungsi ini saat halaman dimuat untuk format sub_total yang mungkin sudah ada
            document.addEventListener('DOMContentLoaded', function() {
                let subTotalElement = document.getElementById('sub_total');
                let subTotal = removeRupiahFormat(subTotalElement.value);
                subTotalElement.value = formatRupiah(subTotal);
            });

            document.querySelector('form').addEventListener('submit', function(event) {
                let subTotalElement = document.getElementById('sub_total');
                let bayarElement = document.getElementById('bayar');
                let kembaliElement = document.getElementById('kembali');

                // Menghapus format Rupiah dari input sebelum submit
                subTotalElement.value = removeRupiahFormat(subTotalElement.value);
                bayarElement.value = removeRupiahFormat(bayarElement.value);
                kembaliElement.value = removeRupiahFormat(kembaliElement.value);

                // Formulir akan disubmit dengan nilai numerik
            });

    </script>



    <script>
        document.getElementById('kategori1').addEventListener('change', function() {
            var selectedValue = this.value;

            if (selectedValue === 'penjualan') {
                window.location.href = "{{ route('admin.penjualan_produk.create') }}"; // Ganti dengan route yang sesuai untuk Penjualan
            } else if (selectedValue === 'pelunasan') {
                window.location.href = "{{ route('admin.penjualan_produk.pelunasan') }}"; // Ganti dengan route yang sesuai untuk Pelunasan
            }
        });
    </script>



    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Panggil fungsi itemPembelian dengan baris default
        // itemPembelian(1, 0); // Misalnya, menambahkan satu baris default

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Mencegah aksi default dari tombol Enter
                addPesanan(); // Memanggil addPesanan saat tombol Enter ditekan
            }
            if (event.key === 'F1') { // Misalnya, F1 untuk menampilkan modal produk
                event.preventDefault(); // Mencegah aksi default dari tombol F1
                var urutan = $('#tabel-pembelian tr').length; // Ambil urutan terakhir atau default
                showCategoryModal(urutan); // Menampilkan modal produk
            }
        });
    });

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
        document.getElementById('jumlah-' + urutan).focus();
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
        $('#sub_total').val(formatRupiah(subTotal));
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
        var kode_produk = '';
        var nama_produk = '';
        var jumlah = '';
        var diskon = '';
        var harga = '';
        var total = '';

        if (value !== null) {
            produk_id = value.produk_id;
            kode_produk = value.kode_produk;
            nama_produk = value.nama_produk;
            jumlah = value.jumlah;
            diskon = value.diskon;
            harga = value.harga;
            total = value.total;
        }

        var item_pembelian = '<tr id="pembelian-' + urutan + '">';
        item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>'; 
        item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
        item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')" onkeydown="handleEnter(event, ' + urutan + ')"></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')" style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" readonly id="diskon-' + urutan + '" name="diskon[]" value="' + diskon + '" ></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + harga + '"></div></td>';
        item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + total + '"></div></td>';
        item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
        item_pembelian += '</tr>';

        $('#tabel-pembelian').append(item_pembelian);
    }
    </script>


@endsection