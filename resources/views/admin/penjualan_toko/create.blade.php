    @extends('layouts.app')

    @section('title', 'Penjualan')
    
    @section('content')
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Penjualan Toko</h1>
                    </div><!-- /.col -->
    
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    
        <section class="content">
            <div class="container-fluid">
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
        
    
            <form id="setoranForm" action="{{ url('admin/penjualan_toko') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <input class="form-control" id="tanggal_penjualan" name="tanggal_penjualan" type="date"
                                    value="{{ Request::get('tanggal_penjualan') }}" max="{{ date('Y-m-d') }}" />
                                <label for="tanggal_penjualan">(Dari Tanggal)</label>
                            </div>
                            <div class="col-md-3 mb-3">
                                <select class="custom-select form-control" id="toko" name="toko_id">
                                    <option value="">- Semua Toko -</option>
                                    @foreach ($tokos as $toko)
                                        <option value="{{ $toko->id }}" {{ Request::get('toko_id') == $toko->id ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                                    @endforeach
                                </select>
                                <label for="toko">(Pilih Toko)</label>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
            
                    <div class="card-body">
                        <!-- Tempat untuk menampilkan Penjualan Kotor -->
                        <div class="form-group row mb-3">
                            <label for="penjualan_kotor" class="col-sm-3 col-form-label">
                                <a href="{{ url('link-yang-dituju') }}" target="_blank" class="text-decoration-none">Penjualan Kotor</a>
                            </label>
                            <div class="col-sm-3">
                                <!-- Tampilkan penjualan_kotor yang sudah diformat -->
                                <input type="text" class="form-control" id="penjualan_kotor" name="penjualan_kotor" value="{{ $penjualanKotorFormatted }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>   
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
            
            
            

            </div>
            
        </section>
  
        

        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                // Fungsi untuk mengirimkan request AJAX
                function getPenjualanKotor() {
                    var tanggal_penjualan = $('#tanggal_penjualan').val();
                    var tanggal_akhir = $('#tanggal_akhir').val();
                    var toko_id = $('#toko').val();
                    
                    $.ajax({
                        url: '{{ url('admin/penjualan_toko/penjualan_kotor') }}',
                        method: 'GET',
                        data: {
                            tanggal_penjualan: tanggal_penjualan,
                            toko_id: toko_id
                        },
                        success: function(response) {
                            $('#penjualan_kotor').val(response.penjualan_kotor);
                        }
                    });

                }
        
                // Ketika ada perubahan pada filter, panggil AJAX
                $('#tanggal_penjualan, #tanggal_akhir, #toko').on('change', function() {
                    getPenjualanKotor();
                });
            });
        </script>
    
    @endsection
    
    
    