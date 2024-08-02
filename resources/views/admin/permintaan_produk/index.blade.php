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
                    <h1 class="m-0">Permintaan -- Produuk</h1>
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
                        <a href="{{ url('admin/permintaan_produk/create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> 
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- Tabel -->
<table id="datatables66" class="table table-bordered" style="font-size: 13px">
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Kode Permintaan</th>
            <th>Tanggal Permintaan</th>
            <th>Jumlah Produk</th>
            <th>Opsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($permintaanProduks as $permintaan)
            <tr class="permintaan-header" data-permintaan-id="{{ $permintaan->id }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $permintaan->kode_permintaan }}</td>
                <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                <td>{{ $permintaan->detailpermintaanproduks->count() }}</td>
                <td class="text-center">
                    <a href="{{ url('admin/permintaan_produk/' . $permintaan->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-print"></i>
                    </a>
                    {{-- @if ($permintaan->status == 'posting')
                    <button type="button" class="btn btn-success btn-sm">
                        <i class="fas fa-check"></i> 
                    </button>
                        @elseif ($permintaan->status == 'unpost')
                            <button type="button" class="unpost-btn" data-memo-id="{{ $permintaan->id }}">
                                <i class="fas fa-times"></i> 
                            </button>
                            <button type="button" class="btn btn-primary btn-sm edit-btn" data-memo-id="{{ $permintaan->id }}">
                                <i class="fas fa-edit"></i> 
                            </button>
                        @endif --}}
                </td>
            </tr>
            <tr class="permintaan-details" id="details-{{ $permintaan->id }}" style="display: none;">
                <td colspan="5">
                    <table class="table table-bordered" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Divisi</th>
                                <th>Kode Produk</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permintaan->detailpermintaanproduks as $detail)
                            <tr >
                                <td>{{ $loop->iteration }}</td>
                                    <td>{{ $detail->produk->klasifikasi->nama }}</td>
                                    <td>{{ $detail->produk->kode_produk }}</td>
                                    <td>{{ $detail->produk->nama_produk }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>

                    
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
       document.addEventListener("DOMContentLoaded", function() {
    // Handle click event for permintaan-header
    const permintaanHeaders = document.querySelectorAll('.permintaan-header');
    
    permintaanHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const permintaanId = header.dataset.permintaanId;
            const detailsRow = document.getElementById(`details-${permintaanId}`);
            
            // Hide all details rows and remove active class from all headers
            const allDetailsRows = document.querySelectorAll('.permintaan-details');
            const allHeaders = document.querySelectorAll('.permintaan-header');
            
            // Check if the clicked row is already open
            const isActive = header.classList.contains('active');

            allDetailsRows.forEach(row => row.style.display = 'none');
            allHeaders.forEach(h => h.classList.remove('active'));
            
            // Toggle the clicked row only if it wasn't already active
            if (!isActive) {
                detailsRow.style.display = '';
                header.classList.add('active');
            }
        });
    });

    // Handle click event for show-btn
    document.querySelectorAll('.show-btn').forEach(button => {
        button.addEventListener('click', function() {
            const permintaanId = this.dataset.permintaanId;
            const href = this.dataset.href;

            // Redirect to the specified URL
            window.location.href = href;
        });
    });
});

    </script>

{{-- 
<script>
    $(document).ready(function() {
        $('.unpost-btn').click(function() {
            var memoId = $(this).data('memo-id');
            $(this).addClass('disabled');

            // Tampilkan modal loading saat permintaan AJAX diproses
            $('#modal-loading').modal('show');

            // Kirim permintaan AJAX untuk melakukan unpost
            $.ajax({
                url: "{{ url('admin/permintaanproduk/unpost_permintaanproduk/') }}/" + memoId,
                type: 'GET',
                data: {
                    id: memoId
                },
                success: function(response) {
                    // Sembunyikan modal loading setelah permintaan selesai
                    $('#modal-loading').modal('hide');

                    if (response.success) {
                        // Tampilkan pesan sukses atau lakukan tindakan lain sesuai kebutuhan
                        console.log(response.message);

                        // Tutup modal setelah berhasil unpost
                        $('#modal-posting-' + memoId).modal('hide');

                        // Ubah status tombol
                        var $btn = $('.unpost-btn[data-memo-id="' + memoId + '"]');
                        $btn.removeClass('btn-danger').addClass('btn-success');
                        $btn.html('<i class="fas fa-check"></i>');
                        $btn.attr('title', 'Posted');
                        $btn.removeClass('unpost-btn');

                        // Reload the page to refresh the table
                        // location.reload(); // Uncomment if you want to reload the page
                    } else {
                        console.log(response.message);
                        alert('Error: ' + response.message);
                    }
                },
                error: function(error) {
                    // Sembunyikan modal loading setelah permintaan selesai
                    $('#modal-loading').modal('hide');

                    // Tampilkan pesan error atau lakukan tindakan lain sesuai kebutuhan
                    console.log(error);
                }
            });
        });
    });
</script> --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.unpost-btn').click(function() {
            var id = $(this).data('memo-id');

            $.ajax({
                url: '/admin/permintaan_produk/' + id + '/unpost',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Update the button and status display
                        $('button[data-memo-id="' + id + '"]').closest('td').find('.btn-success').removeClass('d-none');
                        $('button[data-memo-id="' + id + '"]').remove();
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });
    });
</script>


{{-- <script>
    $(document).ready(function() {
        $('tbody tr.dropdown').click(function(e) {
            // Memeriksa apakah yang diklik adalah checkbox
            if ($(e.target).is('input[type="checkbox"]')) {
                return; // Jika ya, hentikan eksekusi
            }

            // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
            $('tr.dropdown').removeClass('selected').css('background-color', '');

            // Menambahkan kelas 'selected' ke baris yang dipilih dan mengubah warna latar belakangnya
            $(this).addClass('selected').css('background-color', '#b0b0b0');

            // Menyembunyikan dropdown pada baris lain yang tidak dipilih
            $('tbody tr.dropdown').not(this).find('.dropdown-menu').hide();

            // Mencegah event klik menyebar ke atas (misalnya, saat mengklik dropdown)
            e.stopPropagation();
        });

        $('tbody tr.dropdown').contextmenu(function(e) {
            // Memeriksa apakah baris ini memiliki kelas 'selected'
            if ($(this).hasClass('selected')) {
                // Menampilkan dropdown saat klik kanan
                var dropdownMenu = $(this).find('.dropdown-menu');
                dropdownMenu.show();

                // Mendapatkan posisi td yang diklik
                var clickedTd = $(e.target).closest('td');
                var tdPosition = clickedTd.position();

                // Menyusun posisi dropdown relatif terhadap td yang di klik
                dropdownMenu.css({
                    'position': 'absolute',
                    'top': tdPosition.top + clickedTd
                        .height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                    'left': tdPosition
                        .left // Menempatkan dropdown di sebelah kiri td yang di klik
                });

                // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
                e.stopPropagation();
                e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
            }
        });

        // Menyembunyikan dropdown saat klik di tempat lain
        $(document).click(function() {
            $('.dropdown-menu').hide();
            $('tr.dropdown').removeClass('selected').css('background-color',
                ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
        });
    });
</script> --}}
@endsection
