@extends('layouts.app')

@section('title', 'Data produk')

<style>
    .btn-xs {
        padding: 2px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
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
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Harga Jual</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Data Harga Jual</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
      
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Harga Jual</h3>
                <div class="float-right">
                    <a href="{{ url('admin/hargajual/show') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> 
                    </a>
                </div>
            </div>
            
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
                            <th colspan="4" style="text-align: center;">Toko Tegal</th>
                            <th colspan="4" style="text-align: center;">Toko Pemalang</th>
                            <th colspan="4" style="text-align: center;">Toko Bumiayu</th>
                            <th colspan="4" style="text-align: center;">Toko Cilacap</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                     
                            <th style="text-align: center;">Member</th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;">Non Member</th>

                            <th style="text-align: center;">Member</th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;">Non Member</th>

                            <th style="text-align: center;">Member</th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;">Non Member</th>

                            <th style="text-align: center;">Member</th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;">Non Member</th>

                            <th style="text-align: center;">Member</th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;">Non Member</th>

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
                       
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>

                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>

                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>

                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>

                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>

                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                            <th style="text-align: center;">Harga</th>
                            <th style="text-align: center;">Diskon (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $index => $item)
                        <tr id="row-{{ $loop->index }}">
                            <form id="update-harga-form-{{ $index }}" method="POST" action="{{ route('update.harga') }}">
                                @csrf
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_produk }}</td>
                                <td>{{ $item->nama_produk }}</td>
                                <td hidden>{{ 'Rp. ' . number_format($item->tokoslawi->first()->member_harga_slw - ($item->tokoslawi->first()->member_harga_slw * $item->tokoslawi->first()->member_diskon_slw / 100), 0, ',', '.') }}</td> <!-- Display total after discount with format -->
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs" id="update-button-{{ $loop->index }}" onclick="updateHarga({{ $loop->index }}, {{ $item->id }})">
                                        <i class="fa fa-edit" id="icon-{{ $loop->index }}"></i>
                                    </button>
                                </td>

                                {{-- slawi --}}
                                <td style="text-align: center;">
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_harga_slw" id="member-harga-{{ $loop->index }}" value="{{ $item->tokoslawi->first()->member_harga_slw ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_slw" id="diskon-member-{{ $loop->index }}" value="{{ $item->tokoslawi->first()->member_diskon_slw ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>
                                <td style="text-align: center;">
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_harga_slw" id="non-member-harga-{{ $loop->index }}" value="{{ $item->tokoslawi->first()->non_harga_slw ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_slw" id="diskon-non-member-{{ $loop->index }}" value="{{ $item->tokoslawi->first()->non_diskon_slw ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>

                                {{-- Benjaran --}}
                                <td style="text-align: center;">
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_harga_bnjr" id="member-harga-bnjr-{{ $loop->index }}" value="{{ $item->tokobenjaran->first()->member_harga_bnjr ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_bnjr" id="diskon-member-bnjr-{{ $loop->index }}" value="{{ $item->tokobenjaran->first()->member_diskon_bnjr ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>
                                <td style="text-align: center;">
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_harga_bnjr" id="non-member-harga-bnjr-{{ $loop->index }}" value="{{ $item->tokobenjaran->first()->non_harga_bnjr ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_bnjr" id="diskon-non-member-bnjr-{{ $loop->index }}" value="{{ $item->tokobenjaran->first()->non_diskon_bnjr ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                </td>

                            
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection


<script>
    function updateHarga(index, id) {
        const form = document.getElementById(`update-harga-form-${index}`);
        const formData = new FormData(form);
        formData.append('id', id);

        fetch('{{ route('update.harga') }}', {
            method: 'POST',
            body: formData,
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Harga berhasil diperbarui.',
                    timer: 1000,
                    showConfirmButton: false
                });
                
                // Ubah kembali button dan icon setelah sukses update
                const updateButton = document.getElementById(`update-button-${index}`);
                const icon = document.getElementById(`icon-${index}`);
                updateButton.classList.remove('btn-success');
                updateButton.classList.add('btn-danger');
                icon.classList.remove('fa-check');
                icon.classList.add('fa-edit');
            }
        }).catch(error => {
            console.error('Kesalahan:', error);
        });
    }

    function markAsChanged(index) {
        // Fungsi ini tetap kosong agar tidak ada perubahan saat input diubah
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateButtons = document.querySelectorAll('button[id^="update-button-"]');
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const index = this.id.replace('update-button-', '');
                const icon = document.getElementById(`icon-${index}`);
                
                // Ubah button dan icon saat diklik
                this.classList.remove('btn-danger');
                this.classList.add('btn-success');
                icon.classList.remove('fa-edit');
                icon.classList.add('fa-check');
                
                // Panggil fungsi updateHarga dengan parameter yang sesuai
                updateHarga(index, formData.get('id'));
            });
        });
    });
</script>
