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
                    <h1 class="m-0">Pemesanan Produk</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/subklasifikasi') }}">Pemesanan Produk</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <style>
        .large-font {
            font-size: 1.5em;
            font-weight: bold;
        }
    </style>

    <section class="content">
        <div class="container-fluid">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <form action="{{ url('admin/pemesanan_produk') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3 align-items-center">
                            <div class="col-auto mt-2">
                                <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="">- Pilih -</option>
                                    <option value="member" {{ old('kategori') == 'member' ? 'selected' : null }}>Member</option>
                                    <option value="nonmember" {{ old('kategori') == 'nonmember' ? 'selected' : null }}>Non Member</option>
                                </select>
                            </div>
                            <div class="col-auto mt-2" id="kodePelangganRow" hidden>
                                <label class="form-label" for="kode_pelanggan">Scan Barcode</label>
                                <input placeholder="Masukan kode pelanggan" type="text" class="form-control" id="kode_pelanggan" name="kode_pelanggan" value="{{ old('kode_pelanggan') }}">
                            </div>
                        </div>
                        <div class="row mb-3 align-items-center" id="namaPelangganRow" style="display: none;">
                            <div class="col-md-4 mb-3">
                                <div class="col-md">
                                    <button class="btn btn-info mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpemesanan()">
                                        <i class="fas fa-search"> Cari pelanggan</i>
                                    </button>
                                </div>
                                <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}">
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
                                <label for="alamat">Alamat Tujuan</label>
                                <textarea placeholder="Masukan alamat tujuan" class="form-control" id="alamat" name="alamat">{{ old('alamat') }}</textarea>
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
                                                    <button type="button" class="btn btn-primary btn-sm">
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
                                            <th class="text-center">No</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Diskon</th>
                                            <th>Harga</th>
                                            <th>Total</th>
                                            <th class="text-center">Opsi</th>
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
                                    <tbody>
                                        @foreach ($produks as $item)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $item->kode_produk }}</td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td><span class="member_harga_slw">{{ number_format($item->tokoslawi->first()->member_harga_slw, 0, ',', '.') }}</span></td>
                                                <td><span class="member_diskon_slw">{{ $item->tokoslawi->first()->member_diskon_slw }}</span></td>
                                                <td><span class="non_harga_slw">{{ number_format($item->tokoslawi->first()->non_harga_slw, 0, ',', '.') }}</span></td>
                                                <td><span class="non_diskon_slw">{{ $item->tokoslawi->first()->non_diskon_slw }}</span></td>
                                                <td class="text-center">
                                                    <button class="btn btn-primary btn-sm" type="button" onclick="selectProduct('{{ $item->kode_produk }}', '{{ $item->nama_produk }}', {{ $item->tokoslawi->first()->member_harga_slw }}, {{ $item->tokoslawi->first()->member_diskon_slw }}, {{ $item->tokoslawi->first()->non_harga_slw }}, {{ $item->tokoslawi->first()->non_diskon_slw }})">
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
                <button type="submit" class="btn btn-primary btn-sm float-right">
                    <i class="fas fa-check"></i> Simpan
                </button>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#datatables4').DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#datatables4_wrapper .col-md-6:eq(0)');
        });

        function showCategoryModalpemesanan() {
            $('#tableMarketing').modal('show');
        }

        function getSelectedDataPemesanan(nama, telp, alamat) {
            $('#nama_pelanggan').val(nama);
            $('#telp').val(telp);
            $('#alamat').val(alamat);
            $('#tableMarketing').modal('hide');
        }

        $(document).ready(function() {
            $('#kategori').change(function() {
                var kategori = $(this).val();
                if (kategori == 'member') {
                    $('#kodePelangganRow').removeAttr('hidden');
                    $('#namaPelangganRow').show();
                    $('#telpRow').removeAttr('hidden');
                    $('#alamatRow').removeAttr('hidden');
                } else {
                    $('#kodePelangganRow').attr('hidden', true);
                    $('#namaPelangganRow').hide();
                    $('#telpRow').attr('hidden', true);
                    $('#alamatRow').attr('hidden', true);
                }
            });
        });

        function addPesanan() {
            $('#tableProduk').modal('show');
        }

        function selectProduct(kode, nama, hargaMember, diskonMember, hargaNonMember, diskonNonMember) {
            $('#tableProduk').modal('hide');
            var kategori = $('#kategori').val();
            var harga = kategori == 'member' ? hargaMember : hargaNonMember;
            var diskon = kategori == 'member' ? diskonMember : diskonNonMember;
            var total = harga - (harga * (diskon / 100));
            var newRow = `
                <tr>
                    <td class="text-center">${$('#tabel-pembelian tr').length + 1}</td>
                    <td>${kode}</td>
                    <td>${nama}</td>
                    <td><input type="number" name="jumlah[]" class="form-control jumlah" data-harga="${harga}" data-diskon="${diskon}" value="1" onchange="updateTotal(this)"></td>
                    <td>${diskon}%</td>
                    <td>${formatRupiah(harga.toString(), 'Rp')}</td>
                    <td class="total">${formatRupiah(total.toString(), 'Rp')}</td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm" type="button" onclick="removePesanan(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#tabel-pembelian').append(newRow);
        }

        function removePesanan(button) {
            $(button).closest('tr').remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            $('#tabel-pembelian tr').each(function(index, row) {
                $(row).find('td:first-child').text(index + 1);
            });
        }

        function updateTotal(input) {
            var row = $(input).closest('tr');
            var jumlah = $(input).val();
            var harga = $(input).data('harga');
            var diskon = $(input).data('diskon');
            var total = harga * jumlah - (harga * jumlah * (diskon / 100));
            row.find('.total').text(formatRupiah(total.toString(), 'Rp'));
        }

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
    </script>
@endsection
