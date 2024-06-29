
{{-- @extends('layouts.app')

@section('title', 'Data produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Harga Jual</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Harga Jual</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Harga Jual</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Nama produk</th>
                                <th style="text-align: center;">Toko Slawi</th>
                                <th>Harga Jual Member</th>
                                <th>Harga Jual non Member</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($harga as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->produk->kode_produk }}</td>
                                <td>{{ $item->produk->nama_produk }}</td>

                       
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#harga-{{ $index }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div id="harga-{{ $index }}" class="collapse">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Member</th>
                                                    <th style="text-align: center;">Non Member</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                         <form action="{{ route('hargajual.update', $item->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <tr>
                                                <td>
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Harga</th>
                                                                <th>Diskon(%)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align: center;">
                                                                    <input type="text" style="width: 70px;" name="member_harga" id="member-harga-{{ $index }}" value="{{ $item->produk->harga }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" style="width: 70px;" name="diskon_member" id="diskon-member-{{ $index }}" >
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th>Harga</th>
                                                                <th>Diskon(%)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td style="text-align: center;">
                                                                    <input type="text" style="width: 70px;" name="non_member_harga" id="non-member-harga-{{ $index }}" value="{{ $item->produk->harga }}">
                                                                </td>
                                                                <td>
                                                                    <input type="text" style="width: 70px;" name="diskon_non_member" id="diskon-non-member-{{ $index }}" value="{{ $item->diskon }}">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </td>
                                            </tr>
                                        </form>
                                        
                                    </table>
                                </div>
                            </td>

               
                                <td id="harga-jual-{{ $index }}">{{ number_format($item->member_harga, 0, ',', '.') }}</td>
                                <td id="harga-jual-{{ $index }}">{{ number_format($item->non_member_harga, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ url('admin/hargajual/' . $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection --}}


{{-- @section('scripts')
<script>
    // Function to remove formatting and convert to integer
    function parseHarga(value) {
        return parseInt(value.replace(/[^0-9]/g, ''), 10);
    }

    // Function to format number as currency
    function formatHarga(value) {
        return value.toLocaleString('id-ID');
    }

    // Function to update Harga Jual based on Member and Non Member prices
    function updateHargaJual(index) {
        // Get values from inputs
        var memberHarga = parseHarga(document.getElementById('member-harga-' + index).value);
        var memberDiskon = parseHarga(document.getElementById('diskon-member-' + index).value) / 100;
        var nonMemberHarga = parseHarga(document.getElementById('non-member-harga-' + index).value);
        var nonMemberDiskon = parseHarga(document.getElementById('diskon-non-member-' + index).value) / 100;

        // Calculate harga jual after discount
        var memberHargaJual = memberHarga * (1 - memberDiskon);
        var nonMemberHargaJual = nonMemberHarga * (1 - nonMemberDiskon);

        // Update the displayed harga jual
        document.getElementById('harga-jual-member-' + index).innerText = formatHarga(memberHargaJual);
        document.getElementById('harga-jual-non-member-' + index).innerText = formatHarga(nonMemberHargaJual);
    }

    // Event listeners to update Harga Jual on input change
    @foreach ($harga as $index => $item)
    document.getElementById('member-harga-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    document.getElementById('diskon-member-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    document.getElementById('non-member-harga-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    document.getElementById('diskon-non-member-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    @endforeach
</script>
@endsection --}}


@extends('layouts.app')

@section('title', 'Data produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Data Harga Jual</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Data Harga Jual</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Harga Jual</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Nama produk</th>
                                <th style="text-align: center;">Toko Slawi</th>
                                <th style="text-align: center;">Toko Benjaran</th>
                                <th>Harga Jual Member</th>
                                <th>Harga Jual non Member</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($harga as $index => $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->produk->kode_produk }}</td>
                                <td>{{ $item->produk->nama_produk }}</td>

                                {{-- Slawi --}}
                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#harga-{{ $index }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div id="harga-{{ $index }}" class="collapse">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Member</th>
                                                    <th style="text-align: center;">Non Member</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <form action="{{ route('hargajual.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <tr>
                                                        <td>
                                                            <table class="table table-bordered mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Harga</th>
                                                                        <th>Diskon(%)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="text-align: center;">
                                                                            <input type="text" style="width: 70px;" name="member_harga" id="member-harga-{{ $index }}" value="{{ $item->produk->harga }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" style="width: 70px;" name="diskon_member" id="diskon-member-{{ $index }}" value="0">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td>
                                                            <table class="table table-bordered mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Harga</th>
                                                                        <th>Diskon(%)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="text-align: center;">
                                                                            <input type="text" style="width: 70px;" name="non_member_harga" id="non-member-harga-{{ $index }}" value="{{ $item->produk->harga }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" style="width: 70px;" name="diskon_non_member" id="diskon-non-member-{{ $index }}" value="0">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td colspan="2">
                                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                        </td>
                                                    </tr>
                                                </form>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>

                                <td style="text-align: center;">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#harga1-{{ $index }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <div id="harga1-{{ $index }}" class="collapse">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center;">Member</th>
                                                    <th style="text-align: center;">Non Member</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <form action="{{ route('hargajual.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <tr>
                                                        <td>
                                                            <table class="table table-bordered mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Harga</th>
                                                                        <th>Diskon(%)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="text-align: center;">
                                                                            <input type="text" style="width: 70px;" name="member_harga" id="member-harga-{{ $index }}" value="{{ $item->produk->harga }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" style="width: 70px;" name="diskon_member" id="diskon-member-{{ $index }}" value="0">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td>
                                                            <table class="table table-bordered mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Harga</th>
                                                                        <th>Diskon(%)</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="text-align: center;">
                                                                            <input type="text" style="width: 70px;" name="non_member_harga" id="non-member-harga-{{ $index }}" value="{{ $item->produk->harga }}">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text" style="width: 70px;" name="diskon_non_member" id="diskon-non-member-{{ $index }}" value="0">
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td colspan="2">
                                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                        </td>
                                                    </tr>
                                                </form>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td id="harga-jual-member-{{ $index }}">{{ number_format($item->harga_jual_member, 0, ',', '.') }}</td>
                                <td id="harga-jual-non-member-{{ $index }}">{{ number_format($item->harga_jual_non_member, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ url('admin/hargajual/' . $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </section>
    <!-- /.card -->
@endsection

@section('scripts')
<script>
    // Function to remove formatting and convert to integer
    function parseHarga(value) {
        return parseInt(value.replace(/[^0-9]/g, ''), 10);
    }

    // Function to format number as currency
    function formatHarga(value) {
        return value.toLocaleString('id-ID');
    }

    // Function to update Harga Jual based on Member and Non Member prices
    function updateHargaJual(index) {
        // Get values from inputs
        var memberHarga = parseHarga(document.getElementById('member-harga-' + index).value);
        var memberDiskon = parseHarga(document.getElementById('diskon-member-' + index).value || '0') / 100;
        // var memberDiskon = parseHarga(document.getElementById('diskon-member-' + index).value) / 100;
        var nonMemberHarga = parseHarga(document.getElementById('non-member-harga-' + index).value);
        var nonMemberDiskon = parseHarga(document.getElementById('diskon-non-member-' + index).value || '0') / 100;

        // var nonMemberDiskon = parseHarga(document.getElementById('diskon-non-member-' + index).value) / 100;

        // Calculate harga jual after discount
        var memberHargaJual = memberHarga * (1 - memberDiskon);
        var nonMemberHargaJual = nonMemberHarga * (1 - nonMemberDiskon);

        // Update the displayed harga jual
        document.getElementById('harga-jual-member-' + index).innerText = formatHarga(memberHargaJual);
        document.getElementById('harga-jual-non-member-' + index).innerText = formatHarga(nonMemberHargaJual);
    }

    // Event listeners to update Harga Jual on input change
    @foreach ($harga as $index => $item)
    document.getElementById('member-harga-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    document.getElementById('diskon-member-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    document.getElementById('non-member-harga-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    document.getElementById('diskon-non-member-{{ $index }}').addEventListener('input', function() {
        updateHargaJual({{ $index }});
    });
    @endforeach
</script>
@endsection
