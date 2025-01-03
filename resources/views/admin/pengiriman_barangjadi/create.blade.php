@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .klasifikasi-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .klasifikasi-header:hover {
            background-color: #f0f0f0;
        }
        .klasifikasi-header.active {
            background-color: #e0e0e0;
        }
        .produk-table {
            display: none;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("loadingSpinner").style.display = "none";
                document.getElementById("mainContent").style.display = "block";
                document.getElementById("mainContentSection").style.display = "block";
            }, 10); // Adjust the delay time as needed
        });
    </script>
    <!-- Content Header (Page header) -->
    <div class="content-header" style="display: none;" id="mainContent">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Pengiriman Barang Jadi (Permintaan)</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="display: none;" id="mainContentSection">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-check"></i> Success!
                    </h5>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    {{ session('error') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="float-right">
                        <select class="form-control" id="kategori1" name="kategori">
                            <option value="">- Pilih -</option>
                            <option value="permintaan" {{ old('kategori1') == 'permintaan' ? 'selected' : '' }}>Pengiriman Permintaan</option>
                            <option value="pemesanan" {{ old('kategori1') == 'pemesanan' ? 'selected' : '' }}>Pengiriman Pesanan</option>
                        </select>
                    </div>

                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    
                    <form action="{{ url('admin/pengiriman_barangjadi') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="toko_id" value="{{ old('toko_id', $toko_id) }}"> <!-- Assuming $toko_id is passed from the controller -->
                    
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="toko">Pilih Toko</label>
                                        <select class="custom-select form-control" id="toko" name="toko_id">
                                            <option value="">- Pilih -</option>
                                            @foreach ($tokos as $toko)
                                                <option value="{{ $toko->id }}" {{ old('toko_id') == $toko->id ? 'selected' : '' }}>
                                                    {{ $toko->nama_toko }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="tanggal_pengiriman">Tanggal Pengiriman:</label>
                                        <input type="date" class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" value="{{ old('tanggal_pengiriman') }}">
                                    </div>
                                </div>
                        
                                <div class="form-group">
                                    <label for="kode_produksi">Kode Produksi:</label>
                                    <div>
                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $huruf)
                                            <input type="checkbox" name="kode_produksi[]" value="{{ $huruf }}"> {{ $huruf }}
                                        @endforeach
                                    </div>
                                    <div>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <input type="checkbox" name="kode_produksi[]" value="{{ $i }}"> {{ $i }}
                                        @endfor
                                    </div>
                                </div>
                        
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <select id="klasifikasi-select" class="form-control" name="klasifikasi_id">
                                                <option value="">-- Pilih Divisi --</option>
                                                @foreach ($klasifikasis as $klasifikasi)
                                                    <option value="{{ $klasifikasi->id }}">{{ $klasifikasi->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                        
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Detail Produk
                                                            {{-- <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan kode produk..." style="margin-bottom: 10px;"></th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($klasifikasis as $klasifikasi)
                                                            <tr class="produk-table" id="produk-table-{{ $klasifikasi->id }}">
                                                                <td colspan="1">
                                                                    <table class="table table-bordered" style="font-size: 13px;">
                                                                        <div class="col-sm-12 text-right">
                                                                            <input type="text" id="searchInput" class="form-control" placeholder="Cari produk..." style="display: inline-block; width: auto; margin-bottom: 10px;">
                                                                        </div>
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Kode Produk</th>
                                                                                <th>Produk</th>
                                                                                <th>Jumlah</th>   
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($klasifikasi->produks as $produk)
                                                                                <tr class="produk-row">
                                                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                                                    <td class="kode-lama">{{ $produk->kode_lama }}</td>
                                                                                    <td class="nama-produk">{{ $produk->nama_produk }}</td>
                                                                                    <td>
                                                                                        <input type="number" class="form-control" id="produk-{{ $produk->id }}" name="produk[{{ $produk->id }}][jumlah]" min="0" style="width: 100px; height: 30px;">
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group text-right">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </form>

                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog"
                        aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <i class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                                    <h4 class="mt-2">Sedang Menyimpan...</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#klasifikasi-select').change(function() {
                var klasifikasiId = $(this).val();
                $('.produk-table').hide(); // Hide all produk tables
                if (klasifikasiId) {
                    $('#produk-table-' + klasifikasiId).show(); // Show the selected produk table
                }
            });

            // Handle Enter key press on input fields
            $('input[type="number"]').on('keypress', function(e) {
                if (e.which == 13) { // Enter key pressed
                    e.preventDefault(); // Prevent form submission
                    var inputs = $('input[type="number"]');
                    var index = inputs.index(this);
                    
                    if (index + 1 < inputs.length) {
                        $(inputs[index + 1]).focus();
                    }
                }
            });
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            var searchValue = searchInput.value.toLowerCase();
            var produkRows = document.querySelectorAll('.produk-row');
            
            produkRows.forEach(function(row) {
                var kodeLama = row.querySelector('.kode-lama').textContent.toLowerCase();
                var namaProduk = row.querySelector('.nama-produk').textContent.toLowerCase();
                if ( namaProduk.includes(searchValue)|| kodeLama.includes(searchValue))  {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
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
        // Event listener untuk input pencarian
        $("#searchProduk").on("keyup", function(e) {
            if (e.key === "Enter") {
                e.preventDefault(); // Mencegah submit default
                var value = $(this).val().toLowerCase();
                var visibleRow = $("#tableproduk tbody tr:visible").first();
    
                if (visibleRow.length) {
                    var id = visibleRow.data('id');
                    var kode = visibleRow.data('kode');
                    var nama = visibleRow.data('nama');
    
                    getSelectedData(id, kode, nama);
                }
            } else {
                var value = $(this).val().toLowerCase();
                $("#tableproduk tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            }
        });

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
       $('#searchProduk').focus();
   }
   
   // Event listener for pilih-btn
   $(document).on('click', '.pilih-btn', function() {
       var id = $(this).data('id');
       var kode = $(this).data('kode');
       var nama = $(this).data('nama');
   
       getSelectedData(id, kode, nama);
   });
   
   // Fungsi untuk memilih data barang dari modal
   function getSelectedData(id, kode_produk, nama_produk) {
       var urutan = $('#tableProduk').attr('data-urutan');
   
       // Set nilai input pada baris yang sesuai
       $('#produk_id-' + urutan).val(id);
       $('#kode_produk-' + urutan).val(kode_produk);
       $('#nama_produk-' + urutan).val(nama_produk);
   
       $('#tableProduk').modal('hide');
   
       // Setelah menambahkan data dari modal, fokuskan ke input jumlah
       document.getElementById('jumlah-' + urutan).focus();
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
   
       if (value !== null) {
           produk_id = value.produk_id;
           kode_produk = value.kode_produk;
           nama_produk = value.nama_produk;
           jumlah = value.jumlah;
       }
   
       var item_pembelian = '<tr id="pembelian-' + urutan + '">';
       item_pembelian += '<td style="width: 70px; font-size:14px" class="text-center" id="urutan-' + urutan + '">' + urutan + '</td>'; 
       item_pembelian += '<td hidden><div class="form-group"><input type="text" class="form-control" id="produk_id-' + urutan + '" name="produk_id[]" value="' + produk_id + '"></div></td>';
       item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="kode_produk-' + urutan + '" name="kode_produk[]" value="' + kode_produk + '"></div></td>';
       item_pembelian += '<td onclick="showCategoryModal(' + urutan + ')"><div class="form-group"><input type="text" class="form-control" style="font-size:14px" readonly id="nama_produk-' + urutan + '" name="nama_produk[]" value="' + nama_produk + '"></div></td>';
       item_pembelian += '<td style="width: 150px"><div class="form-group"><input type="number" class="form-control" style="font-size:14px" id="jumlah-' + urutan + '" name="jumlah[]" value="' + jumlah + '" oninput="hitungTotal(' + urutan + ')" onkeydown="handleEnter(event, ' + urutan + ')"></div></td>';
       item_pembelian += '<td style="width: 100px"><button type="button" class="btn btn-primary btn-sm" onclick="showCategoryModal(' + urutan + ')"><i class="fas fa-plus"></i></button><button style="margin-left:5px" type="button" class="btn btn-danger btn-sm" onclick="removeBan(' + urutan + ')"><i class="fas fa-trash"></i></button></td>';
       item_pembelian += '</tr>';
   
       $('#tabel-pembelian').append(item_pembelian);
   }
   </script> --}}

<script>
    document.getElementById('kategori1').addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue === 'permintaan') {
            window.location.href = "{{ route('admin.pengiriman_barangjadi.create') }}"; 
        } else if (selectedValue === 'pemesanan') {
            window.location.href = "{{ route('admin.pengiriman_barangjadipesanan.create') }}"; 
        }
    });
</script>

@endsection
