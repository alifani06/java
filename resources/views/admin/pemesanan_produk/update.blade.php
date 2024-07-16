@extends('layouts.app')

@section('title', 'Pemesanan Produk')

@section('content')

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
                    <h1 class="m-0">UBAHPemesanan Produk</h1>
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
            <form action="{{ route('pemesanan_produk.update', $pemesananProduk->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('PUT')
                <div class="card">

                    <div class="card-header">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3 mt-2">
                                <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                <input readonly type="text" class="form-control" id="kategori" name="kategori" value="{{ old('kategori', $pemesananProduk->kategori) }}"> 
                            </div>  
                            <div class="col-md-3 mt-2">
                                <label class="form-label" for="kode_pemesanan">Kode Pemesanan</label>
                                <input readonly type="text" class="form-control" id="kode_pemesanan" name="kode_pemesanan" value="{{ old('kode_pemesanan', $pemesananProduk->kode_pemesanan) }}">
                            </div>   
                        </div>
                   
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label" for="nama_pelanggan">Nama Pelanggan</label>
                                <input  type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan', $pemesananProduk->nama_pelanggan) }}">
                            </div>   
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label" for="telp">No Telepon</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>  
                                    <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{ old('telp', $pemesananProduk->telp) }}">
                                </div>                            </div>   
                        </div>
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label" for="nama_pelanggan">Alamat</label>
                                <textarea placeholder="Masukan alamat tujuan" type="text" class="form-control" id="alamat" name="alamat">{{ old('alamat', $pemesananProduk->alamat) }}</textarea>
                            </div>   
                        </div>
                        <div class="row mb-3 align-items-center" id="telpRow" hidden>
                            <div class="col-md-4 mb-3">
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
                            <div class="col-md-4 mb-3">
                                <label for="catatan">Alamat Tujuan</label>
                                <textarea placeholder="Masukan alamat tujuan" type="text" class="form-control" id="alamat" name="alamat">{{ old('alamat') }}</textarea>
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
                                    {{-- <tbody>
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
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><span></span></h3>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
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
                                            <th style="font-size:14px">Kode Barang</th>
                                            <th style="font-size:14px">Nama Barang</th>
                                            <th style="font-size:14px">Jumlah</th>
                                            <th style="font-size:14px">Diskon</th>
                                            <th style="font-size:14px">Harga</th>
                                            <th style="font-size:14px">Total</th>
                                            <th style="font-size:14px; text-align:center">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabel-pembelian"></tbody>
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
                                    {{-- <tbody>
                                        @foreach ($produks as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_produk }}</td>
                                            <td>{{ $item->nama_produk }}</td>
                                            <td>
                                                <span class="member_harga_slw">{{ number_format($item->tokoslawi->first()->member_harga_slw, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                <span class="member_diskon_slw">{{ $item->tokoslawi->first()->member_diskon_slw }}</span>
                                            </td>
                                            <td>
                                                <span class="non_harga_slw">{{ number_format($item->tokoslawi->first()->non_harga_slw, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                <span class="non_diskon_slw">{{ $item->tokoslawi->first()->non_diskon_slw }}</span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-primary btn-sm pilih-btn"
                                                    data-id="{{ $item->id }}"
                                                    data-kode="{{ $item->kode_produk }}"
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
                                    </tbody> --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
         
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-4 mb-3 ml-auto">
                                <label for="sub_total">Sub Total</label>
                                <input readonly type="text" class="form-control large-font" id="sub_total" name="sub_total" value="{{ old('sub_total', $pemesananProduk->sub_total) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">

                            <div class="col-md-5 mb-3">
                                <label for="catatan">Bagian Input :</label>
                                <input type="text" class="form-control" readonly value="{{ ucfirst(auth()->user()->karyawan->nama_lengkap) }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right mt-3">
                    <button type="reset" class="btn btn-secondary" id="btnReset">Reset</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                    <div id="loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Sedang Menyimpan...
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- <script>
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
    
            // if (kategoriSelect.value === 'nonmember') {
            //     namaPelangganRow.hidden = true;
            //     telpRow.hidden = true;
            //     alamatRow.hidden = true;
            // }
        });
    </script> --}}

 
    {{-- <script>
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
    </script> --}}

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
    
        document.addEventListener('DOMContentLoaded', function() {
            var kategoriSelect = document.getElementById('kategori');
            var pilihBtns = document.querySelectorAll('.pilih-btn');
    
            kategoriSelect.addEventListener('change', function() {
                var kategori = kategoriSelect.value;
    
                pilihBtns.forEach(function(btn) {
                    btn.onclick = function() {
                        var id = btn.getAttribute('data-id');
                        var kode = btn.getAttribute('data-kode');
                        var nama = btn.getAttribute('data-nama');
                        var memberHarga = btn.getAttribute('data-member');
                        var memberDiskon = btn.getAttribute('data-diskonmember');
                        var nonmemberHarga = btn.getAttribute('data-nonmember');
                        var nonmemberDiskon = btn.getAttribute('data-diskonnonmember');
                        var harga = kategori === 'member' ? memberHarga : nonmemberHarga;
                        var diskon = kategori === 'member' ? memberDiskon : nonmemberDiskon;
    
                        getSelectedData(id, kode, nama, harga, diskon);
                    };
                });
            });
        });
    
        function getSelectedData(id, kode, nama, harga, diskon) {
            console.log('ID:', id, 'Kode:', kode, 'Nama:', nama, 'Harga:', harga, 'Diskon:', diskon);
        }
    
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
        $('#harga-' + urutan).val(formatRupiah(harga));
        $('#diskon-' + urutan).val(diskon);
        // Hitung total
        hitungTotal(urutan);
        // Tutup modal
        $('#tableProduk').modal('hide');
    }

    // Fungsi untuk menghitung total berdasarkan harga dan jumlah
    function hitungTotal(urutan) {
        var harga = parseFloat($('#harga-' + urutan).val().replace(/[^0-9]/g, '')) || 0;
        var diskon = parseFloat($('#diskon-' + urutan).val()) || 0;
        var jumlah = parseFloat($('#jumlah-' + urutan).val()) || 0;

        var hargaSetelahDiskon = harga - (harga * (diskon / 100));
        var total = hargaSetelahDiskon * jumlah;

        // Format total ke dalam format rupiah dan set nilai input total
        $('#total-' + urutan).val(formatRupiah(total));
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
        item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
        item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')"></div></td>';
        item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" readonly id="diskon-' + urutan + '" name="diskon[]" value="' + diskon + '" ></div></td>';
        item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="harga-' + urutan + '" name="harga[]" value="' + formatRupiah(harga) + '"></div></td>';
        item_pembelian += '<td><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="total-' + urutan + '" name="total[]" value="' + formatRupiah(total) + '"></div></td>';
        item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
        item_pembelian += '</tr>';

        $('#tabel-pembelian').append(item_pembelian);
    }

    function formatRupiah(angka, prefix = '') {
        var number_string = angka.toString().replace(/[^,\d]/g, '');
        var split = number_string.split(',');
        var sisa = split[0].length % 3;
        var rupiah = split[0].substr(0, sisa);
        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix + rupiah;
    }
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    var delayTimer;

    $(document).ready(function() {
        $('#kode_pelanggan').on('input', function() {
            var qrcode_pelanggan = $(this).val().trim();

            // Hapus timer sebelumnya jika ada
            clearTimeout(delayTimer);

            // Tunggu sebentar sebelum mengambil data
            delayTimer = setTimeout(function() {
                if (qrcode_pelanggan !== '') {
                    // Periksa apakah data sudah terisi sebelumnya
                    if ($('#nama_pelanggan').val() === '' && $('#telp').val() === '' && $('#alamat').val() === '') {
                        getData(qrcode_pelanggan);
                    }
                } else {
                    // Handle jika qrcode_pelanggan kosong
                    $('#nama_pelanggan').val('');
                    $('#telp').val('');
                    $('#alamat').val('');
                    $('#telpRow').hide(); // Sembunyikan row jika data tidak tersedia
                    $('#alamatRow').hide();
                }
            }, 200); // Waktu penundaan dalam milidetik (misalnya 500ms)
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

                // Tampilkan row yang sebelumnya hidden
                $('#telpRow').show();
                $('#alamatRow').show();
            },
            error: function(xhr, status, error) {
                // Handle error jika ada
                console.error('Error:', error);
            }
        });
    }
</script>

@endsection
