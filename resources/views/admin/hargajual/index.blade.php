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
                <!-- Dropdown untuk memilih toko -->
                <div class="form-group">
                    <label for="tokoSelect">Pilih Toko:</label>
                    <select id="tokoSelect" class="form-control" onchange="showTable(this.value)">
                        <option value="banjaran">Toko Banjaran</option>
                        <option value="slawi">Toko Slawi</option>
                        <option value="tegal">Toko Tegal</option>
                        <option value="pemalang">Toko Pemalang</option>
                        <option value="bumiayu">Toko Bumiayu</option>
                        <option value="cilacap">Toko Cilacap</option>
                        <!-- Tambahkan opsi lain di sini jika ada toko tambahan -->
                    </select>
                </div>
   
                <!-- Tabel Toko Banjaran -->
                <div id="tabelBanjaran">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th rowspan="3" style="text-align: center;">No</th>
                                <th rowspan="3" style="text-align: center;">Kode Produk</th>
                                <th rowspan="3" style="text-align: center;">Nama Produk</th>
                                <th rowspan="3" style="text-align: center;">Harga Awal</th>
                                <th rowspan="3" style="text-align: center;">+</th>
                                <th colspan="4" style="text-align: center;">Toko Banjaran</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">Member</th>
                                <th colspan="2" style="text-align: center;">Non Member</th>
                            </tr>
                            <tr>
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
                                    <td>{{ $item->kode_lama }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" id="update-button-{{ $loop->index }}" onclick="updateHarga({{ $loop->index }}, {{ $item->id }})">
                                            <i class="fa fa-save" id="icon-{{ $loop->index }}"></i>
                                        </button>
                                    </td>
   
                                     {{-- Banjaran --}}
                                     <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_harga_bnjr" id="member-harga-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->member_harga_bnjr ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_bnjr" id="diskon-member-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->member_diskon_bnjr ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_harga_bnjr" id="non-member-harga-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->non_harga_bnjr ?? $item->harga }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_bnjr" id="diskon-non-member-bnjr-{{ $loop->index }}" value="{{ $item->tokobanjaran->first()->non_diskon_bnjr ?? $item->diskon }}" onchange="markAsChanged({{ $loop->index }})">
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
   
                {{-- tegal --}}
                <div id="tabelTegal">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th rowspan="3" style="text-align: center;">No</th>
                                <th rowspan="3" style="text-align: center;">Kode Produk</th>
                                <th rowspan="3" style="text-align: center;">Nama Produk</th>
                                <th rowspan="3" style="text-align: center;">Harga Awal</th>
                                <th rowspan="3" style="text-align: center;">+</th>
                                <th colspan="4" style="text-align: center;">Toko Tegal</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">Member</th>
                                <th colspan="2" style="text-align: center;">Non Member</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($produk as $index => $item)
                            <tr id="row-{{ $loop->index }}">
                                <form id="update-hargaTgl-form-{{ $index }}" method="POST" action="{{ route('update.hargaTgl') }}">
                                    @csrf
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_lama }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" id="updateTgl-button-{{ $loop->index }}" onclick="updateHargaTgl({{ $loop->index }}, {{ $item->id }})">
                                            <i class="fa fa-save" id="icon-{{ $loop->index }}"></i>
                                        </button>
                                    </td>
   
                                     {{-- Tegal --}}
                                     <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_harga_tgl" id="member-harga-tgl-{{ $loop->index }}" value="{{ $item->tokotegal->first()->member_harga_tgl ?? $item->harga }}" onchange="markAsChangedTgl({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_tgl" id="diskon-member-tgl-{{ $loop->index }}" value="{{ $item->tokotegal->first()->member_diskon_tgl ?? $item->diskon }}" onchange="markAsChangedTgl({{ $loop->index }})">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_harga_tgl" id="non-member-harga-tgl-{{ $loop->index }}" value="{{ $item->tokotegal->first()->non_harga_tgl ?? $item->harga }}" onchange="markAsChangedTgl({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_tgl" id="diskon-non-member-tgl-{{ $loop->index }}" value="{{ $item->tokotegal->first()->non_diskon_tgl ?? $item->diskon }}" onchange="markAsChangedTgl({{ $loop->index }})">
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

{{-- pemalang --}}
                <div id="tabelPemalang">
                    <table id="datatables1" class="table table-sm table-bordered table-striped table-hover" style="font-size: 12px;">
                        <thead>
                            <tr>
                                <th rowspan="3" style="text-align: center;">No</th>
                                <th rowspan="3" style="text-align: center;">Kode Produk</th>
                                <th rowspan="3" style="text-align: center;">Nama Produk</th>
                                <th rowspan="3" style="text-align: center;">Harga Awal</th>
                                <th rowspan="3" style="text-align: center;">+</th>
                                <th colspan="4" style="text-align: center;">Toko Pemalang</th>
                            </tr>
                            <tr>
                                <th colspan="2" style="text-align: center;">Member</th>
                                <th colspan="2" style="text-align: center;">Non Member</th>
                            </tr>
                            <tr>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                                <th style="text-align: center;">Harga</th>
                                <th style="text-align: center;">Diskon (%)</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                            @foreach ($produk as $index => $item)
                            <tr id="row-{{ $loop->index }}">
                                <form id="update-hargaPml-form-{{ $index }}" method="POST" action="{{ route('update.hargaPml') }}">
                                    @csrf
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode_lama }}</td>
                                    <td>{{ $item->nama_produk }}</td>
                                    <td>{{ $item->harga }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-xs" id="update-button-{{ $loop->index }}" onclick="updateHargaPml({{ $loop->index }}, {{ $item->id }})">
                                            <i class="fa fa-save" id="icon-{{ $loop->index }}"></i>
                                        </button>
                                    </td>
   
                                     {{-- Banjaran --}}
                                     <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_harga_pml" id="member-harga-pml-{{ $loop->index }}" value="{{ $item->tokopemalang->first()->member_harga_pml ?? $item->harga }}" onchange="markAsChangedPml({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="member_diskon_pml" id="diskon-member-pml-{{ $loop->index }}" value="{{ $item->tokopemalang->first()->member_diskon_pml ?? $item->diskon }}" onchange="markAsChangedPml({{ $loop->index }})">
                                    </td>
                                    <td style="text-align: center;">
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_harga_pml" id="non-member-harga-pml-{{ $loop->index }}" value="{{ $item->tokopemalang->first()->non_harga_pml ?? $item->harga }}" onchange="markAsChangedPml({{ $loop->index }})">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" style="width: 70px;" name="non_diskon_pml" id="diskon-non-member-pml-{{ $loop->index }}" value="{{ $item->tokopemalang->first()->non_diskon_pml ?? $item->diskon }}" onchange="markAsChangedPml({{ $loop->index }})">
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
   
</section>
@endsection

<script>
    // Variabel untuk menyimpan DataTable instance
let tableBanjaran;
let tableTegal;
let tablePemalang;

// Fungsi untuk menampilkan tabel dan menginisialisasi DataTable
function showTable(toko) {
    // Sembunyikan semua tabel toko terlebih dahulu
    let tables = document.querySelectorAll('[id^="tabel"]');
    tables.forEach(table => table.style.display = 'none');

    // Hapus DataTable jika sebelumnya sudah diinisialisasi
    if ($.fn.DataTable.isDataTable('#datatables1')) {
        $('#datatables1').DataTable().destroy();
    }

    // Tampilkan dan inisialisasi DataTable sesuai toko yang dipilih
    if (toko === 'banjaran') {
        document.getElementById('tabelBanjaran').style.display = 'block';

        // Inisialisasi DataTable dengan fitur pencarian aktif
        tableBanjaran = $('#datatables1').DataTable({
            searching: true,
            paging: true,
            info: true,
        });

    } else if (toko === 'tegal') {
        document.getElementById('tabelTegal').style.display = 'block';

        // Inisialisasi DataTable dengan fitur pencarian non-aktif
        tableTegal = $('#datatables1').DataTable({
            searching: false,
            paging: true,
            info: true,
        });
    }else if (toko === 'pemalang') {
        document.getElementById('tabelPemalang').style.display = 'block';

        // Inisialisasi DataTable dengan fitur pencarian non-aktif
        tableTegal = $('#datatables1').DataTable({
            searching: false,
            paging: true,
            info: true,
        });
    }
}

// Inisialisasi tampilan tabel berdasarkan pilihan default
document.addEventListener('DOMContentLoaded', () => {
    showTable(document.getElementById('tokoSelect').value);
});

</script>


<script>
        function updateHarga(index, id) {
        const form = document.getElementById(`update-harga-form-${index}`);
        const formData = new FormData(form);
        formData.append('id', id);

        fetch('{{ route('update.harga') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Jika dibutuhkan untuk keamanan
            }
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

                // Ubah tombol menjadi success dan ubah ikon menjadi check
                updateButton.classList.remove('btn-danger');
                updateButton.classList.add('btn-success');
                icon.classList.remove('fa-edit');
                icon.classList.add('fa-check');
            }
        }).catch(error => {
            console.error('Kesalahan:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Terjadi kesalahan saat mengupdate harga.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    function markAsChanged(index) {
        const updateButton = document.getElementById(`update-button-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        // Ubah tombol dan icon menjadi tampilan "perubahan" (edit) saat ada input yang diubah
        updateButton.classList.remove('btn-success');
        updateButton.classList.add('btn-danger');
        icon.classList.remove('fa-check');
        icon.classList.add('fa-edit');
    }

</script>

<script>
    function updateHargaTgl(index, id) {
        const form = document.getElementById(`update-hargaTgl-form-${index}`);
        const formData = new FormData(form);
        formData.append('id', id);

        fetch('{{ route('update.hargaTgl') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Jika dibutuhkan untuk keamanan
            }
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

                // Ubah tombol menjadi success dan ubah ikon menjadi check
                updateButton.classList.remove('btn-danger');
                updateButton.classList.add('btn-success');
                icon.classList.remove('fa-edit');
                icon.classList.add('fa-check');
            }
        }).catch(error => {
            console.error('Kesalahan:', error);
            // Menghapus atau mengomentari alert gagal saat terjadi error
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Gagal!',
            //     text: 'Terjadi kesalahan saat mengupdate harga.',
            //     timer: 2000,
            //     showConfirmButton: false
            // });
        });
    }

    function markAsChangedTgl(index) {
        const updateButton = document.getElementById(`update-button-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        // Ubah tombol dan icon menjadi tampilan "perubahan" (edit) saat ada input yang diubah
        updateButton.classList.remove('btn-success');
        updateButton.classList.add('btn-danger');
        icon.classList.remove('fa-check');
        icon.classList.add('fa-edit');
    }
</script>

<script>
    function updateHargaPml(index, id) {
        const form = document.getElementById(`update-hargaPml-form-${index}`);
        const formData = new FormData(form);
        formData.append('id', id);

        fetch('{{ route('update.hargaPml') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'  // Jika dibutuhkan untuk keamanan
            }
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

                // Ubah tombol menjadi success dan ubah ikon menjadi check
                updateButton.classList.remove('btn-danger');
                updateButton.classList.add('btn-success');
                icon.classList.remove('fa-edit');
                icon.classList.add('fa-check');
            }
        }).catch(error => {
            console.error('Kesalahan:', error);
            // Menghapus atau mengomentari alert gagal saat terjadi error
            // Swal.fire({
            //     icon: 'error',
            //     title: 'Gagal!',
            //     text: 'Terjadi kesalahan saat mengupdate harga.',
            //     timer: 2000,
            //     showConfirmButton: false
            // });
        });
    }

    function markAsChangedTgl(index) {
        const updateButton = document.getElementById(`update-button-${index}`);
        const icon = document.getElementById(`icon-${index}`);

        // Ubah tombol dan icon menjadi tampilan "perubahan" (edit) saat ada input yang diubah
        updateButton.classList.remove('btn-success');
        updateButton.classList.add('btn-danger');
        icon.classList.remove('fa-check');
        icon.classList.add('fa-edit');
    }
</script>


