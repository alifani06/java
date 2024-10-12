@extends('layouts.app')

@section('title', 'Produks')

@section('content')
    <div id="loadingSpinner" style="display: flex; align-items: center; justify-content: center; height: 100vh;">
        <i class="fas fa-spinner fa-spin" style="font-size: 3rem;"></i>
    </div>
    <style>
        .permintaan-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .permintaan-header:hover {
            background-color: #f0f0f0;
        }
        .permintaan-header.active {
            background-color: #e0e0e0;
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
                    <h1 class="m-0">Estimasi Produksi</h1>
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
                <div class="card-body">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md">
                            <button class="btn btn-outline-primary mb-3 btn-sm" type="button" id="searchButton" onclick="showCategoryModalpermintaan()">
                                <i class="fas fa-search" style=""></i>Cari 
                            </button> 
                        </div>      
                        <div class="col-md-6 mb-3 "> 
                            <input readonly placeholder="Kode Permintaan" type="text" class="form-control" id="kode_permintaan" name="kode_permintaan" value="{{ old('kode_permintaan') }}">
                        </div>     
                    </div>
                        <table id="datatables67" class="table table-bordered" style="font-size: 13px">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Kode Produk</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                        <tbody>

                        </tbody>
                        </table>
                    
                        <div class="modal fade" id="tablePermintaan" data-backdrop="static">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Data Permintaan</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    
                                    <div class="modal-body">
                                        <table id="datatables4" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th>Kode Permintaan</th>
                                                    <th>Toko</th>
                                                    <th>Tanggal Permintaan</th>
                                                    <th>Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permintaanProduks as $item)
                                                    <tr onclick="getSelectedDataPermintaan('{{ $item->kode_permintaan }}')">
                                                        <td class="text-center">{{ $loop->iteration }}</td>
                                                        <td>{{ $item->kode_permintaan }}</td>
                                                        <td>{{ optional($item->detailPermintaanProduks->first()->toko)->nama_toko }}</td>
                                                        <td>{{ $item->tanggal_permintaan }}</td>
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
                        </div><br>
                        <button class="btn btn-success btn-sm" type="button" id="updateButton" onclick="updateData()">
                            <i class="fas fa-save"></i> Update
                        </button>
                        
            

                    <!-- Modal Loading -->
                    <div class="modal fade" id="modal-loading" tabindex="-1" role="dialog" aria-labelledby="modal-loading-label" aria-hidden="true" data-backdrop="static">
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
                
            <!-- /.card-body -->
            
            </div>
            </div>
            
    </section>

<script>
    function showCategoryModalpermintaan() {
        $('#tablePermintaan').modal('show');
            }

    function getSelectedDataPermintaan(kode_permintaan) {
        document.getElementById('kode_permintaan').value = kode_permintaan;

        $('#tablePermintaan').modal('hide');
    }

</script>
       
<script>
    function getSelectedDataPermintaan(kode_permintaan) {
    document.getElementById('kode_permintaan').value = kode_permintaan;
    $('#tablePermintaan').modal('hide');

    // Panggil Ajax untuk mendapatkan data detail permintaan produk
    $.ajax({
        url: '{{ route("getDetailPermintaanProduk") }}', // Gunakan fungsi route() di sini
        type: 'GET',
        data: { kode_permintaan: kode_permintaan },
        success: function(response) {
    console.log(response); // Tambahkan ini untuk debug
    
    // Kosongkan tabel sebelum mengisi data baru
    $('#datatables67 tbody').empty();

    // Iterasi melalui response untuk menampilkan data di tabel
    $.each(response, function(index, item) {
    $('#datatables67 tbody').append(
        `<tr data-produk-id="${item.produk.id}" data-permintaanproduk-id="${item.permintaanproduk_id}"> <!-- Simpan produk_id dan permintaanproduk_id di data atribut -->
            <td>${index + 1}</td>
            <td>${item.produk.nama_produk}</td>
            <td>${item.produk.kode_lama}</td>
            <td>
                <input type="number" class="form-control form-control-sm" name="jumlah[${index}]" value="${item.jumlah}" min="1" id="jumlah_${index}" style="width: 80px;">
            </td>
        </tr>`
        );
    });

        },

                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        }

</script>


{{-- <script>
    function updateData() {
        // Buat array untuk menyimpan data yang akan diupdate
        let updateData = [];

        // Iterasi melalui setiap row untuk mendapatkan data yang diinputkan
        $('#datatables67 tbody tr').each(function(index, row) {
        let produkId = $(row).data('produk-id'); // Ambil produk_id
        let permintaanProdukId = $(row).data('permintaanproduk-id'); // Ambil permintaanproduk_id
        let jumlahBaru = $(row).find('input[name^="jumlah"]').val(); // Ambil jumlah baru

        updateData.push({
            produk_id: produkId, // Kirim produk_id
            permintaanproduk_id: permintaanProdukId, // Kirim permintaanproduk_id
            jumlah: jumlahBaru
        });
        });


        // Kirim data ke backend melalui AJAX
        $.ajax({
            url: '{{ route("updateDetailPermintaanProduk") }}', // Gunakan fungsi route() di sini
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Token CSRF untuk keamanan
                updateData: updateData
            },
            success: function(response) {
                if (response.success) {
                    alert('Data berhasil diperbarui!');
                } else {
                    alert('Gagal memperbarui data.');
                }
            },
            error: function(xhr) {
                console.log('Error:', xhr.responseText);
            }
        });
    }
</script> --}}

<script>
    function updateData() {
    // Buat array untuk menyimpan data yang akan diupdate
    let updateData = [];
    let permintaanProdukId; // Deklarasikan variabel untuk menyimpan permintaanproduk_id

    // Iterasi melalui setiap row untuk mendapatkan data yang diinputkan
    $('#datatables67 tbody tr').each(function(index, row) {
        let produkId = $(row).data('produk-id'); // Ambil produk_id
        permintaanProdukId = $(row).data('permintaanproduk-id'); // Ambil permintaanproduk_id dari baris pertama
        let jumlahBaru = $(row).find('input[name^="jumlah"]').val(); // Ambil jumlah baru

        updateData.push({
            produk_id: produkId, // Kirim produk_id
            permintaanproduk_id: permintaanProdukId, // Kirim permintaanproduk_id
            jumlah: jumlahBaru
        });
    });

    // Kirim data ke backend melalui AJAX
    $.ajax({
        url: '{{ route("updateDetailPermintaanProduk") }}', // Gunakan fungsi route() di sini
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}', // Token CSRF untuk keamanan
            updateData: updateData,
            permintaanproduk_id: permintaanProdukId // Kirim permintaanproduk_id ke server
        },
        success: function(response) {
            if (response.success) {
                alert('Data berhasil diperbarui!');
                // Alihkan ke halaman show setelah berhasil
                window.location.href = response.redirectUrl; // Arahkan ke URL yang diterima dari response
            } else {
                alert('Gagal memperbarui data.');
            }
        },
        error: function(xhr) {
            console.log('Error:', xhr.responseText);
        }
    });
}

</script>
@endsection

