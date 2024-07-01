@extends('layouts.app')

@section('title', 'Data produk')


<style>
    .btn-xs {
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
</style>
<style>
    .btn {
        padding: 10px 20px;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-danger {
        color: white;
        background-color: red;
    }
    .btn-success {
        color: white;
        background-color: green;
    }
    .icon-separator {
        margin: 0 10px;
    }
</style>
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Harga Jual</h3>
                    <div class="float-right">
                        <a href="{{ url('admin/hargajual/updated-items') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-print"></i> 
                        </a>
                    </div>
                </div>
                
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode produk</th>
                                <th>Nama produk</th>
                                <th>+</th>
                                <th colspan="4" style="text-align: center;">Toko Slawi</th>
                                <th colspan="4" style="text-align: center;">Toko Benjaran</th>
                      
                             
                           
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                              {{-- slalwi --}}
                                <th style="text-align: center;">Member</th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;">Non Member</th>
                                {{-- benjaran --}}
                                <th style="text-align: center;">Member</th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;"></th>
                                <th style="text-align: center;">Non Member</th>
                              
                          
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                {{-- slawi --}}
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                {{-- benjaran --}}
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                              
                          
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($harga as $index => $item)
                            <tr>
                                <form id="update-harga-form" method="POST" action="{{ route('update.harga') }}">
                                    @csrf
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->produk->kode_produk }}</td>
                                    <td>{{ $item->produk->nama_produk }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" id="update-button-{{ $loop->index }}" onclick="updateHarga({{ $loop->index }}, {{ $item->id }})">
                                            <i class="fa fa-edit" id="icon-{{ $loop->index }}"></i>
                                        </button>
                                    </td>

                                    {{-- slawi --}}
                                    <td style="text-align: center;">
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="member_harga_slw" id="member-harga-{{ $loop->index }}" value="{{ $item->member_harga_slw ?? $item->produk->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_slw" id="diskon-member-{{ $loop->index }}" value="{{ $item->member_diskon_slw ?? $item->produk->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="non_harga_slw" id="non-member-harga-{{ $loop->index }}" value="{{ $item->non_harga_slw ?? $item->produk->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    
                                    <td>
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_slw" id="diskon-non-member-{{ $loop->index }}" value="{{ $item->non_diskon_slw ?? $item->produk->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                
                                    {{-- Benjaran --}}
                                    <td style="text-align: center;">
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="member_harga" id="member-harga-{{ $loop->index }}" value="{{ old('member_harga', $item->produk->harga) }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="diskon_member" id="diskon-member-{{ $loop->index }}" value="0" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="non_member_harga" id="non-member-harga-{{ $loop->index }}" value="{{ $item->non_member_harga ?? $item->produk->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    
                                    <td>
                                        <input type="text" class="form-control form-control-sm" style="width: 70px;" name="diskon_non_member" id="diskon-non-member-{{ $loop->index }}" value="0" onchange="markAsChanged({{ $loop->index }})">
                                    </td>

                                    
                                </form>


                            

                               
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

{{-- 
<script>
    function updateHarga(index, id) {
        const formData = new FormData();

        const memberHargaInput = document.getElementById(`member-harga-${index}`);
        const diskonMemberInput = document.getElementById(`diskon-member-${index}`);
        const nonMemberHargaInput = document.getElementById(`non-member-harga-${index}`);
        const diskonNonMemberInput = document.getElementById(`diskon-non-member-${index}`);

        if (memberHargaInput.value != memberHargaInput.defaultValue) {
            formData.append('member_harga_slw', memberHargaInput.value);
        }
        if (diskonMemberInput.value != diskonMemberInput.defaultValue) {
            formData.append('member_diskon_slw', diskonMemberInput.value);
        }
        if (nonMemberHargaInput.value != nonMemberHargaInput.defaultValue) {
            formData.append('non_harga_slw', nonMemberHargaInput.value);
        }
        if (diskonNonMemberInput.value != diskonNonMemberInput.defaultValue) {
            formData.append('non_diskon_slw', diskonNonMemberInput.value);
        }

        formData.append('id', id);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('update.harga') }}', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`row-${index}`).style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Kesalahan:', error);
        });
    }

    function markAsChanged(index) {
        const updateButton = document.getElementById(`update-button-${index}`);
        const icon = document.getElementById(`icon-${index}`);
        updateButton.classList.remove('btn-danger');
        updateButton.classList.add('btn-success');
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-check');
    }
</script> --}}
<script>
    function updateHarga(index, id) {
        const formData = new FormData();

        const memberHargaInput = document.getElementById(`member-harga-${index}`);
        const diskonMemberInput = document.getElementById(`diskon-member-${index}`);
        const nonMemberHargaInput = document.getElementById(`non-member-harga-${index}`);
        const diskonNonMemberInput = document.getElementById(`diskon-non-member-${index}`);

        const memberHargaBnjrInput = document.getElementById(`member-harga-bnjr-${index}`);
        const diskonMemberBnjrInput = document.getElementById(`diskon-member-bnjr-${index}`);
        const nonMemberHargaBnjrInput = document.getElementById(`non-member-harga-bnjr-${index}`);
        const diskonNonMemberBnjrInput = document.getElementById(`diskon-non-member-bnjr-${index}`);

        if (memberHargaInput.value != memberHargaInput.defaultValue) {
            formData.append('member_harga_slw', memberHargaInput.value);
        }
        if (diskonMemberInput.value != diskonMemberInput.defaultValue) {
            formData.append('member_diskon_slw', diskonMemberInput.value);
        }
        if (nonMemberHargaInput.value != nonMemberHargaInput.defaultValue) {
            formData.append('non_harga_slw', nonMemberHargaInput.value);
        }
        if (diskonNonMemberInput.value != diskonNonMemberInput.defaultValue) {
            formData.append('non_diskon_slw', diskonNonMemberInput.value);
        }

        // if (memberHargaBnjrInput.value != memberHargaBnjrInput.defaultValue) {
        //     formData.append('member_harga_bnjr', memberHargaBnjrInput.value);
        // }
        // if (diskonMemberBnjrInput.value != diskonMemberBnjrInput.defaultValue) {
        //     formData.append('member_diskon_bnjr', diskonMemberBnjrInput.value);
        // }
        // if (nonMemberHargaBnjrInput.value != nonMemberHargaBnjrInput.defaultValue) {
        //     formData.append('non_harga_bnjr', nonMemberHargaBnjrInput.value);
        // }
        // if (diskonNonMemberBnjrInput.value != diskonNonMemberBnjrInput.defaultValue) {
        //     formData.append('non_diskon_bnjr', diskonNonMemberBnjrInput.value);
        // }
        formData.append('id', id);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('update.harga') }}', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`row-${index}`).style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Kesalahan:', error);
        });
    }

    function markAsChanged(index) {
        const updateButton = document.getElementById(`update-button-${index}`);
        const icon = document.getElementById(`icon-${index}`);
        updateButton.classList.remove('btn-danger');
        updateButton.classList.add('btn-success');
        icon.classList.remove('fa-edit');
        icon.classList.add('fa-check');
    }
</script>
