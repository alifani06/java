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
                    <h1 class="m-0">Inquery Pengiriman Barang Jadi</h1>
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
            
                
                <div class="card-body">
                    <!-- Tabel -->
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
                                <input class="form-control" id="tanggal_pengiriman" name="tanggal_pengiriman" type="date"
                                    value="{{ Request::get('tanggal_pengiriman') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_pengiriman">(Dari Tanggal)</label>
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
                    <table id="datatables66" class="table table-bordered" style="font-size: 13px">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th>Kode Pengiriman</th>
                                <th>Tanggal Pengiriman</th>
                                <th>Status</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stokBarangJadi as $kodeInput => $stokBarangJadiItems)
                            @php
                                $firstItem = $stokBarangJadiItems->first();
                            @endphp
                                <tr class="dropdown"{{$firstItem->id }}>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $firstItem->kode_pengiriman }}</td>
                                <td>{{ \Carbon\Carbon::parse($firstItem->tanggal_pengiriman)->format('d/m/Y H:i') }}
                                    <td class="text-center">
                                    @if ($firstItem->status == 'posting')
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if ($firstItem->status == 'unpost')
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                 
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @if ($firstItem->status == 'unpost')
                                           
                                                <a class="dropdown-item posting-btn"
                                                    data-memo-id="{{ $firstItem->id }}">Posting</a>
                                         
                                                <a class="dropdown-item"
                                                    href="{{ url('toko_bumiayu/inquery_pengirimanbarangjadi/' . $firstItem->id . '/edit') }}">Update</a>
                                            
                                                <a class="dropdown-item"
                                                href="{{ url('/toko_bumiayu/inquery_pengirimanbarangjadi/' . $firstItem->id ) }}">Show</a>
                                                @endif
                                        @if ($firstItem->status == 'posting')
                                                <a class="dropdown-item unpost-btn"
                                                    data-memo-id="{{ $firstItem->id }}">Unpost</a>
                                                <a class="dropdown-item"
                                                href="{{ url('toko_bumiayu/inquery_pengirimanbarangjadi/' . $firstItem->id ) }}">Show</a>
                                        @endif
                                       
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

    <script>
        var tanggalAwal = document.getElementById('tanggal_pengiriman');
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
            form.action = "{{ url('toko_bumiayu/inquery_pengirimanbarangjadi') }}";
            form.submit();
        }

    </script>



    {{-- unpost stok  --}}
    <script>
        $(document).ready(function() {
            $('.unpost-btn').click(function() {
                var memoId = $(this).data('memo-id');
                $(this).addClass('disabled');

                $('#modal-loading').modal('show');

                $.ajax({
                    url: "{{ url('toko_bumiayu/inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/') }}/" + memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        $('#modal-loading').modal('hide');
                        console.log(response);
                        $('#modal-posting-' + memoId).modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        $('#modal-loading').modal('hide');
                        console.log(error);
                    }
                });
            });
        });
    </script>

    {{-- posting stok --}}
    <script>
        $(document).ready(function() {
            $('.posting-btn').click(function() {
                var memoId = $(this).data('memo-id');
                $(this).addClass('disabled');

                $('#modal-loading').modal('show');

                $.ajax({
                    url: "{{ url('toko_bumiayu/inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/') }}/" + memoId,
                    type: 'GET',
                    data: {
                        id: memoId
                    },
                    success: function(response) {
                        $('#modal-loading').modal('hide');
                        console.log(response);
                        $('#modal-posting-' + memoId).modal('hide');
                        location.reload();
                    },
                    error: function(error) {
                        $('#modal-loading').modal('hide');
                        console.log(error);
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
