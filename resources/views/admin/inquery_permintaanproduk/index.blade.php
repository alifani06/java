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
                    <h1 class="m-0">Inquery Permintaan Produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Inquery Permintaan Produk</li>
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
                    <h3 class="card-title">Permintaan Produk</h3>
                </div>
                <!-- /.card-header -->
                 
                <div class="card-body">
                    <form method="GET" id="form-action">
                        <div class="row">
                        <div class="col-md-3 mb-3">
                            <select class="custom-select form-control" id="status" name="status">
                                <option value="">- Semua Status -</option>
                                <option value="posting" {{ Request::get('status') == 'posting' ? 'selected' : '' }}>Posting</option>
                                <option value="unpost" {{ Request::get('status') == 'unpost' ? 'selected' : '' }}>Unpost</option>
                            </select>
                            <label for="status">(Pilih Status)</label>
                        </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_permintaan" name="tanggal_permintaan" type="date"
                                    value="{{ Request::get('tanggal_permintaan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_permintaan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-outline-primary btn-block" onclick="cari()">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                
                            </div>
                        </div>
                    </form>
                   
                    
                    <table id="datatables66" class="table table-bordered " style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Permintaan</th>
                                <th>Tanggal Permintaan</th>
                                <th>Jumlah Produk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permintaanProduks as $permintaan)
                                <tr class="permintaan-header" data-permintaan-id="{{ $permintaan->id }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $permintaan->kode_permintaan }}</td>
                                    <td>{{ $permintaan->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $permintaan->detailpermintaanproduks->count() }}</td>
                                </tr>
                                <tr class="permintaan-details" id="details-{{ $permintaan->id }}" style="display: none;">
                                    <td colspan="4">
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
                                                    <tr>
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
                                    <td class="text-center">
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item posting-btn" data-memo-id="">Posting</a>
                                            <a class="dropdown-item" href="">Update</a>
                                            <a class="dropdown-item" href="">Show</a>
                                            <a class="dropdown-item unpost-btn" data-memo-id="">Unpost</a>
                                            <a class="dropdown-item" href="">Show</a>
                                        </div>
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


    <!-- /.card -->
    <script>
        var tanggalAwal = document.getElementById('tanggal_permintaan');
        var tanggalAkhir = document.getElementById('tanggal_akhir');
        if (tanggalAwal.value == "") {
            tanggalAkhir.readOnly = true;
        }
        tanggalAwal.addEventListener('change', function() {
            if (this.value == "") {
                tanggalAkhir.readOnly = true;
            } else {
                tanggalAkhir.readOnly = false;
            };
            tanggalAkhir.value = "";
            var today = new Date().toISOString().split('T')[0];
            tanggalAkhir.value = today;
            tanggalAkhir.setAttribute('min', this.value);
        });
        var form = document.getElementById('form-action')

        function cari() {
            form.action = "{{ url('admin/inquery_permintaanproduk') }}";
            form.submit();
        }

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
        });
    </script>

<script>
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
</script>
@endsection
