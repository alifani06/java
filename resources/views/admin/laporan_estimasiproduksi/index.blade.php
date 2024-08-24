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
                    <h1 class="m-0">Laporan Estimasi Produksi</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Laporan Estimasi Produksi</li>
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
                    <h3 class="card-title">Estimasi Produksi</h3>
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
                                <input class="form-control" id="tanggal_awal" name="tanggal_awal" type="date"
                                    value="{{ Request::get('tanggal_awal') }}" />
                                <label for="tanggal_awal">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_akhir" name="tanggal_akhir" type="date"
                                    value="{{ Request::get('tanggal_akhir') }}" />
                                <label for="tanggal_akhir">(Sampai Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="table_type" name="table_type">
                                    <option value="all" {{ Request::get('table_type') == 'all' ? 'selected' : '' }}>All Data</option>
                                    <option value="permintaan" {{ Request::get('table_type') == 'permintaan' ? 'selected' : '' }}>Atas Permintaan</option>
                                    <option value="pemesanan" {{ Request::get('table_type') == 'pemesanan' ? 'selected' : '' }}>Atas Pesanan</option>
                                </select>
                                <label for="table_type">(Pilih Tabel)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button type="button" class="btn btn-primary btn-block" onclick="printReport()" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </button>
                            </div>
                        </div>
                    </form>
                    
<!-- Tabel Permintaan -->
<div class="card-body">
    @if($tableType == '' || $tableType == 'permintaan' || $tableType == 'all')
    <h4>Atas Permintaan</h4>
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
            @forelse($permintaanProduks as $produkId => $tokoDetails)
                @php
                    $firstDetail = $tokoDetails->first();
                    $produk = $firstDetail['produk'] ?? null;
                    $totalJumlah = $tokoDetails->sum('jumlah');
                @endphp
                <tr class="dropdown" data-permintaan-id="{{ $produkId }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $produk->kode_produk ?? 'N/A' }}</td>
                    <td>{{ $totalJumlah }}</td>
                </tr>
                <tr class="permintaan-details" id="details-{{ $produkId }}" style="display: none;">
                    <td colspan="4">
                        <table class="table table-bordered" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cabang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Permintaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokoDetails as $tokoDetail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                        <td>{{ $tokoDetail['jumlah'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($tokoDetail['tanggal_permintaan'])->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</div>

<!-- Tabel Pemesanan -->
<div class="card-body">
    @if($tableType == '' || $tableType == 'pemesanan' || $tableType == 'all')
    <h4>Atas Pemesanan</h4>
    <table id="data" class="table table-bordered" style="font-size: 13px">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Produk</th>
                <th>Kode Produk</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemesananProduk as $produkId => $tokoDetails)
                @php
                    $firstDetail = $tokoDetails->first();
                    $produk = $firstDetail['produk'] ?? null;
                    $totalJumlah = $tokoDetails->sum('jumlah');
                @endphp
                <tr class="dropdown" data-pemesanan-id="{{ $produkId }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                    <td>{{ $produk->kode_produk ?? 'N/A' }}</td>
                    <td>{{ $totalJumlah }}</td>
                </tr>
                <tr class="pemesanan-details" id="details-{{ $produkId }}" style="display: none;">
                    <td colspan="4">
                        <table class="table table-bordered" style="font-size: 13px;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cabang</th>
                                    <th>Kode Pemesanan</th>
                                    <th>Tanggal Kirim</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tokoDetails as $tokoDetail)
                                    @foreach ($tokoDetail['detail'] as $detail)
                                        <tr>
                                            <td>{{ $loop->parent->iteration }}</td>
                                            <td>{{ $tokoDetail['toko']->nama_toko }}</td>
                                            <td>{{ $detail->pemesananProduk->kode_pemesanan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tokoDetail['tanggal_kirim'])->format('d-m-Y H:i') }}</td>
                                            <td>{{ $detail->jumlah }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</div>

       
            </div>
        </div>
    </section>

<script>
        document.getElementById('tanggal_awal').addEventListener('change', function() {
        let tanggalAwal = this.value;
        let tanggalAkhir = document.getElementById('tanggal_akhir');
        
        if (!tanggalAwal) {
            tanggalAkhir.readOnly = true;
        } else {
            tanggalAkhir.readOnly = false;
            tanggalAkhir.value = new Date().toISOString().split('T')[0];
            tanggalAkhir.setAttribute('min', tanggalAwal);
        }
    });

    document.getElementById('form-action').addEventListener('submit', function() {
        this.action = "{{ url('admin/laporan_estimasiproduksi') }}";
    });

    </script>

<script>
    function printReport() {
    const form = document.getElementById('form-action');
    form.action = "{{ url('admin/printReport') }}";
    form.target = "_blank";
    form.submit();
}

</script>
   
   <script>
    $(document).ready(function() {
    $('tbody tr.dropdown').click(function(e) {
        // Memeriksa apakah yang diklik adalah checkbox
        if ($(e.target).is('input[type="checkbox"]')) {
            return; // Jika ya, hentikan eksekusi
        }

        // Menyembunyikan detail untuk baris yang tidak dipilih
        $('tbody tr.dropdown').not(this).removeClass('selected').css('background-color', '');
        $('.permintaan-details').not('#details-' + $(this).data('permintaan-id')).hide();

        // Toggle visibility untuk detail baris yang dipilih
        var detailRowId = $(this).data('permintaan-id');
        var detailRow = $('#details-' + detailRowId);
        var isActive = detailRow.is(':visible');

        // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
        $('tr.dropdown').removeClass('selected').css('background-color', '');
        
        if (isActive) {
            detailRow.hide(); // Menyembunyikan detail jika sudah ditampilkan
        } else {
            $(this).addClass('selected').css('background-color', '#b0b0b0'); // Menambahkan kelas 'selected' dan mengubah warna latar belakangnya
            detailRow.show(); // Menampilkan detail jika belum ditampilkan
        }

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
                'top': tdPosition.top + clickedTd.height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                'left': tdPosition.left // Menempatkan dropdown di sebelah kiri td yang di klik
            });

            // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
            e.stopPropagation();
            e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
        }
    });

    // Menyembunyikan dropdown saat klik di tempat lain
    $(document).click(function() {
        $('.dropdown-menu').hide();
        $('tr.dropdown').removeClass('selected').css('background-color', ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
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

        // Menyembunyikan detail untuk baris yang tidak dipilih
        $('tbody tr.dropdown').not(this).removeClass('selected').css('background-color', '');
        $('.pemesanan-details').not('#details-' + $(this).data('pemesanan-id')).hide();

        // Toggle visibility untuk detail baris yang dipilih
        var detailRowId = $(this).data('pemesanan-id');
        var detailRow = $('#details-' + detailRowId);
        var isActive = detailRow.is(':visible');

        // Menghapus kelas 'selected' dan mengembalikan warna latar belakang ke warna default dari semua baris
        $('tr.dropdown').removeClass('selected').css('background-color', '');
        
        if (isActive) {
            detailRow.hide(); // Menyembunyikan detail jika sudah ditampilkan
        } else {
            $(this).addClass('selected').css('background-color', '#b0b0b0'); // Menambahkan kelas 'selected' dan mengubah warna latar belakangnya
            detailRow.show(); // Menampilkan detail jika belum ditampilkan
        }

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
                'top': tdPosition.top + clickedTd.height(), // Menempatkan dropdown sedikit di bawah td yang di klik
                'left': tdPosition.left // Menempatkan dropdown di sebelah kiri td yang di klik
            });

            // Mencegah event klik kanan menyebar ke atas (misalnya, saat mengklik dropdown)
            e.stopPropagation();
            e.preventDefault(); // Mencegah munculnya konteks menu bawaan browser
        }
    });

    // Menyembunyikan dropdown saat klik di tempat lain
    $(document).click(function() {
        $('.dropdown-menu').hide();
        $('tr.dropdown').removeClass('selected').css('background-color', ''); // Menghapus warna latar belakang dari semua baris saat menutup dropdown
    });
});
</script>

@endsection
