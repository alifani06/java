@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Data Penjualan</h2>

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

    {{-- <form action="{{ route('inquery_penjualanproduk.update', $penjualan->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="kode_penjualan">Kode Penjualan</label>
            <input type="text" name="kode_penjualan" class="form-control" id="kode_penjualan" value="{{ $penjualan->kode_penjualan }}" required>
        </div>

        <div class="form-group">
            <label for="nama_pelanggan">Nama Pelanggan</label>
            <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" value="{{ $penjualan->nama_pelanggan }}" required>
        </div>

        <div class="form-group">
            <label for="kode_pelanggan">Kode Pelanggan</label>
            <input type="text" name="kode_pelanggan" class="form-control" id="kode_pelanggan" value="{{ $penjualan->kode_pelanggan }}" required>
        </div>

        <div class="form-group">
            <label for="telp">No. Telepon</label>
            <input type="text" name="telp" class="form-control" id="telp" value="{{ $penjualan->telp }}" required>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control" id="alamat" required>{{ $penjualan->alamat }}</textarea>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori</label>
            <input type="text" name="kategori" class="form-control" id="kategori" value="{{ $penjualan->kategori }}" required>
        </div>

        <!-- Tambahkan field lainnya jika diperlukan -->
        
        <button type="submit" class="btn btn-primary">Update Data</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
    </form> --}}
<div class="container-fluid">
<div class="card">
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
        </div>
    
        <div class="row mb-3 align-items-center" id="namaPelangganRow" >
     
            <div class="col-md-6 mb-3 "> 
                <label for="nama_pelanggan">Nama Pelanggan</label>
                <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan" value="{{ $penjualan->nama_pelanggan }}" required>
            </div>     
        </div>

        <div class="row  align-items-center" id="telpRow" >
            <div class="col-md-6 mb-3">
                <label for="telp">No. Telepon</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+62</span>
                    </div>
                    <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{ $penjualan->telp }}">
                </div>
            </div>
        </div>
    
        <div class="row mb-3 align-items-center" id="alamatRow" >
            <div class="col-md-6 mb-3">
                <label for="catatan">Alamat</label>
                <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{ $penjualan->alamat }}</textarea>
            </div>
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
</div>

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
    var totalasli = harga * jumlah;

    // Format total ke dalam format rupiah dan set nilai input total
    $('#total-' + urutan).val(total);
    $('#totalasli-' + urutan).val(totalasli);
    // Hitung subtotal setiap kali total di baris berubah
    hitungSubTotal();
}

// Fungsi untuk menghitung subtotal semua barang
function hitungSubTotal() {
    var subTotal = 0;
    var subTotalAsli = 0;

    $('[id^=total-]').each(function() {
        var total = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
        subTotal += total;
    });

    $('[id^=totalasli-]').each(function() {
        var totalAsli = parseFloat($(this).val().replace(/[^0-9]/g, '')) || 0;
        subTotalAsli += totalAsli;
    });

    $('#sub_total').val(formatRupiah(subTotal));
    $('#sub_totalasli').val(formatRupiah(subTotalAsli));
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
    var totalasli = '';

    if (value !== null) {
        produk_id = value.produk_id;
        kode_produk = value.kode_produk;
        nama_produk = value.nama_produk;
        jumlah = value.jumlah;
        diskon = value.diskon;
        harga = value.harga;
        total = value.total;
        totalasli = value.totalasli;
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
    item_pembelian += '<td hidden onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" hidden id="totalasli-' + urutan + '" name="totalasli[]" value="' + totalasli + '"></div></td>';
    item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
    item_pembelian += '</tr>';

    $('#tabel-pembelian').append(item_pembelian);
    }
</script>
@endsection
