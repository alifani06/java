@extends('layouts.app')

@section('title', 'pemesanan produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">pemesanan produk</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/inquery_pembelianpart') }}">pemesanan produk</a></li>
                        <li class="breadcrumb-item active">pemesanan produk</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            @if (session('error_pelanggans') || session('error_pesanans'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5>
                        <i class="icon fas fa-ban"></i> Error!
                    </h5>
                    @if (session('error_pelanggans'))
                        @foreach (session('error_pelanggans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                    @if (session('error_pesanans'))
                        @foreach (session('error_pesanans') as $error)
                            - {{ $error }} <br>
                        @endforeach
                    @endif
                </div>
            @endif
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
                    @foreach (session('error') as $error)
                        - {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            <form action="{{ url('admin/inquery_pembelianpart/' . $inquery->id) }}" method="post" autocomplete="off">
                @csrf
                @method('put')
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Pelanggan</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-auto mt-2">
                                            <label class="form-label" for="kategori">Tipe Pelanggan</label>
                                            <select class="form-control" id="kategori" name="kategori">
                                                <option value="">- Pilih -</option>
                                                <option value="member" {{ (old('kategori') == 'member' || (isset($inquery) && $inquery->kategori == 'member')) ? 'selected' : '' }}>Member</option>
                                                <option value="nonmember" {{ (old('kategori') == 'nonmember' || (isset($inquery) && $inquery->kategori == 'nonmember')) ? 'selected' : '' }}>Non Member</option>
                                            </select>
                                        </div>
                                        <div class="col-auto mt-2">
                                            <label class="form-label" for="toko">Pilih Cabang</label>
                                            <select class="form-control" id="toko" name="toko">
                                                <option value="">- Pilih -</option>
                                                @foreach ($tokos as $toko)
                                                    <option value="{{ $toko->id }}" {{ $selectedTokoId == $toko->id ? 'selected' : '' }}>
                                                        {{ $toko->nama_toko }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-auto mt-2" id="kode_pemesanan">
                                            <label for="kode_pemesanan">Kode Pemesanan</label>
                                            <input type="text" class="form-control" id="kode_pemesanan" name="kode_pemesanan" readonly value="{{ $inquery->kode_pemesanan }}">
                                        </div>
                                        <div class="col-auto mt-2" id="kodePelangganRow" hidden>
                                            <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                            <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="scan kode Pelanggan" onchange="getData(this.value)">
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="namaPelangganRow">
                                        <div class="col-md-12 mb-3">
                                            <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ $inquery->nama_pelanggan }}" onclick="showCategoryModalpemesanan()">
                                        </div>
                                    </div>
                                    <div class="row align-items-center" id="telpRow">
                                        <div class="col-md-12 mb-3">
                                            <label for="telp">No. Telepon</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{ $inquery->telp }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="alamatRow">
                                        <div class="col-md-12 mb-3">
                                            <label for="catatan">Alamat</label>
                                            <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{ $inquery->alamat }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-stretch">
                            <div class="card flex-fill">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Pengiriman</h3>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12 mb-3">
                                        <label for="tanggal_kirim">Tanggal Pengiriman:</label>
                                        <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                            <input type="text" id="tanggal_kirim" name="tanggal_kirim"
                                                class="form-control datetimepicker-input"
                                                data-target="#reservationdatetime"
                                                value="{{ $inquery->tanggal_kirim }}"
                                                placeholder="DD/MM/YYYY HH:mm">
                                            <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-12">
                                            <label for="nama_penerima">Nama Penerima</label> <span style="font-size: 10px;">(kosongkan jika sama dengan nama pelanggan)</span>
                                            <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" placeholder="masukan nama Penerima" value="{{ $inquery->nama_penerima }}">
                                        </div>
                                    </div>
                                    <div class="row align-items-center" id="telp_penerima">
                                        <div class="col-md-12">
                                            <label for="telp_penerima">No. Telepon</label> <span style="font-size: 10px;">(kosongkan jika sama dengan Nomer telepon pelanggan)</span>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+62</span>
                                                </div>
                                                <input type="number" id="telp_penerima" name="telp_penerima" class="form-control" placeholder="Masukan nomor telepon" value="{{ $inquery->telp_penerima }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center" id="alamat_penerima">
                                        <div class="col-md-12 mb-3">
                                            <label for="alamat_penerima">Alamat Penerima</label><span style="font-size: 10px;"> (kosongkan jika sama dengan alamat pelanggan)</span>
                                            <textarea placeholder="Masukan alamat penerima" type="text" class="form-control" id="alamat_penerima" name="alamat_penerima">{{ $inquery->alamat_penerima }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian">
                                @foreach ($inquery->detailpemesananproduk as $detail)
                                    <tr id="pembelian-{{ $loop->index }}">
                                        <td class="text-center" id="urutan">{{ $loop->index + 1 }}</td>
                                    
                                        <td >
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control"
                                                    id="kode_produk-{{ $loop->index }}" name="kode_produk[]"
                                                    value="{{ $detail['kode_produk'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control"
                                                    id="nama_produk-{{ $loop->index }}" name="nama_produk[]"
                                                    value="{{ $detail['nama_produk'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="number"  class="form-control"
                                                    id="jumlah-{{ $loop->index }}" name="jumlah[]"
                                                    value="{{ $detail['jumlah'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control"
                                                    id="diskon-{{ $loop->index }}" name="diskon[]"
                                                    value="{{ $detail['diskon'] }}">
                                            </div>
                                        </td>
          
                                        <td>
                                            <div class="form-group">
                                                <input type="number" readonly class="form-control harga" id="harga-0"
                                                    name="harga[]" value="{{ $detail['harga'] }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" readonly class="form-control total" id="total-0"
                                                    name="total[]" data-row-id="0"
                                                    value="{{ $detail['total'] }}">
                                            </div>
                                        </td>
                                        <td style="width: 120px">
                                            <button type="button" class="btn btn-primary"
                                                onclick="barang({{ $loop->index }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button style="margin-left:5px" type="button" class="btn btn-danger"
                                                onclick="removeBan({{ $loop->index }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>

        <div class="modal fade" id="tableKategori" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Data Part</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="m-2">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                        </div>
                        <table id="tables" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Mmeber</th>
                                    <th>Diskon Mmeber</th>
                                    <th>Harga non Mmeber</th>
                                    <th>Diskon non Mmeber</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produks as $item)
                                    <tr data-kode_produk="{{ $item->kode_produk }}" 
                                        data-nama_produk="{{ $item->nama_produk }}"
                                        data-hargamember="{{ $item->tokoslawi->first()->member_harga_slw }}"
                                        data-diskonmember="{{ $item->tokoslawi->first()->member_diskon_slw }}"
                                        data-harganonmember="{{ $item->tokoslawi->first()->non_harga_slw }}"
                                        data-diskonnonmember="{{ $item->tokoslawi->first()->non_diskon_slw }}"
                                        data-param="{{ $loop->index }}">

                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode_produk }}</td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td>
                                            <span class="member_harga_slw">{{$item->tokoslawi->first()->member_harga_slw}}</span>
                                        </td>
                                        <td>
                                            <span class="member_diskon_slw">{{ $item->tokoslawi->first()->member_diskon_slw }}</span>
                                        </td>
                                        <td>
                                            <span class="non_harga_slw">{{$item->tokoslawi->first()->non_harga_slw}}</span>
                                        </td>
                                        <td>
                                            <span class="non_diskon_slw">{{ $item->tokoslawi->first()->non_diskon_slw }}</span>
                                        </td>

                                        <td class="text-center">
                                            <button type="button" id="btnTambah" class="btn btn-primary btn-sm"
                                                onclick="getBarang({{ $loop->index }})">
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
        </div>

 
    </section>

    
    <script>
        // memunculkan datatable pelaanggan dan produk
        $(document).ready(function() {
            // Inisialisasi datatables
            var pelangganTable = $('#datatables4').DataTable();
            var produkTable = $('#datatables5').DataTable();
    
            $('#tableMarketing').on('shown.bs.modal', function () {
                pelangganTable.columns.adjust().draw();
            });
    
            $('#tableProduk').on('shown.bs.modal', function () {
                produkTable.columns.adjust().draw();
            });
        });
    
        function showCategoryModalpemesanan() {
            $('#tableMarketing').modal('show');
        }
    
        function getSelectedDataPemesanan(nama_pelanggan, telp, alamat) {
            document.getElementById('nama_pelanggan').value = nama_pelanggan;
            document.getElementById('telp').value = telp;
            document.getElementById('alamat').value = alamat;
            $('#tableMarketing').modal('hide');
        }
    </script>


    <script>
        function getData(id) {
            var supplier_id = document.getElementById('supplier_id');
            $.ajax({
                url: "{{ url('admin/pembelian_ban/supplier') }}" + "/" + supplier_id.value,
                type: "GET",
                dataType: "json",
                success: function(supplier_id) {
                    var alamat = document.getElementById('alamat');
                    alamat.value = supplier_id.alamat;
                },
            });
        }

        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("tables");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                var displayRow = false;

                // Loop through columns (td 1, 2, and 3)
                for (j = 1; j <= 3; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            displayRow = true;
                            break; // Break the loop if a match is found in any column
                        }
                    }
                }

                // Set the display style based on whether a match is found in any column
                tr[i].style.display = displayRow ? "" : "none";
            }
        }
        document.getElementById("searchInput").addEventListener("input", filterTable);


        var activeSpecificationIndex = 0;

        function barang(param) {
            activeSpecificationIndex = param;
            // Show the modal and filter rows if necessary
            $('#tableKategori').modal('show');
        }

        function getBarang(rowIndex) {
            var selectedRow = $('#tables tbody tr:eq(' + rowIndex + ')');
            var kode_produk = selectedRow.data('kode_produk');
            var nama_produk = selectedRow.data('nama_produk');
            var hargamember = selectedRow.data('hargamember');
          

            // Update the form fields for the active specification
            $('#kode_produk-' + activeSpecificationIndex).val(kode_produk);
            $('#nama_produk-' + activeSpecificationIndex).val(nama_produk);
            $('#hargamember-' + activeSpecificationIndex).val(hargamember);
       

            $('#tableKategori').modal('hide');
        }


        $(document).on("input", ".total, .jumlah", function() {
            var currentRow = $(this).closest('tr');
            var total = parseFloat(currentRow.find(".total").val()) || 0;
            var jumlah = parseFloat(currentRow.find(".jumlah").val()) || 0;
            var harga = total * jumlah;
            currentRow.find(".harga").val(harga);
        });

        var data_pembelian = @json(session('data_pembelians'));
        var jumlah_ban = 1;

        if (data_pembelian != null) {
            jumlah_ban = data_pembelian.length;
            $('#tabel-pembelian').empty();
            var urutan = 0;
            $.each(data_pembelian, function(key, value) {
                urutan = urutan + 1;
                itemPembelian(urutan, key, value);
            });
        }

        function updateUrutan() {
            var urutan = document.querySelectorAll('#urutan');
            for (let i = 0; i < urutan.length; i++) {
                urutan[i].innerText = i + 1;
            }
        }

        var counter = 0;

        function addPesanan() {
            counter++;
            jumlah_ban = jumlah_ban + 1;

            if (jumlah_ban === 1) {
                $('#tabel-pembelian').empty();
            } else {
                // Find the last row and get its index to continue the numbering
                var lastRow = $('#tabel-pembelian tr:last');
                var lastRowIndex = lastRow.find('#urutan').text();
                jumlah_ban = parseInt(lastRowIndex) + 1;
            }

            console.log('Current jumlah_ban:', jumlah_ban);
            itemPembelian(jumlah_ban, jumlah_ban - 1);
            updateUrutan();
        }

        function removeBan(identifier) {
            var row = $('#pembelian-' + identifier);
            var detailId = row.find("input[name='detail_ids[]']").val();

            row.remove();

            if (detailId) {
                $.ajax({
                    url: "{{ url('admin/inquery_pembelianpart/deletepart/') }}/" + detailId,
                    type: "POST",
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Data deleted successfully');
                    },
                    error: function(error) {
                        console.error('Failed to delete data:', error);
                    }
                });
            }

            updateUrutan();
        }

        function itemPembelian(identifier, key, value = null) {
            var kategori = '';
            var kode_produk = '';
            var nama_produk = '';
            var jumlah = '';
            var diskon = '';
            var harga = '';
            var total = '';

            if (value !== null) {
                kategori = value.kategori;
                kode_produk = value.kode_produk;
                nama_produk = value.nama_produk;
                jumlah = value.jumlah;
                diskon = value.diskon;
                harga = value.harga;
                total = value.total;
            }

            console.log(kategori);
            // urutan 
            var item_pembelian = '<tr id="pembelian-' + key + '">';
            item_pembelian += '<td class="text-center" id="urutan">' + key + '</td>';

            // Kode produk 
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly  id="kode_produk-' + key +
                '" name="kode_produk[]" value="' +
                kode_produk +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            //jumlah
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control nama_produk" readonly id="nama_produk-' + key +
                '" name="nama_produk[]" value="' +
                nama_produk +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            //jumlah
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="number" class="form-control"  id="jumlah-' + key +
                '" name="jumlah[]" value="' +
                jumlah +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            //diskon
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly id="diskon-' + key +
                '" name="diskon[]" value="' +
                diskon +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            //harga
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" readonly id="harga-' + key +
                '" name="harga[]" value="' +
                harga +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';


            //total
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="number" class="form-control total" readonly id="total-' + key +
                '" name="total[]" value="' +
                total +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
            
            // delete
            item_pembelian += '<td style="width: 120px">';
            item_pembelian += '<button type="button" class="btn btn-primary" onclick="barang(' + key + ')">';
            item_pembelian += '<i class="fas fa-plus"></i>';
            item_pembelian += '</button>';
            item_pembelian += '<button style="margin-left:5px" type="button" class="btn btn-danger" onclick="removeBan(' +
                key + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelian').append(item_pembelian);
        }


        // Panggil fungsi refreshTable saat dokumen siap
        $(document).ready(function() {
            // Memproses pengiriman form
            $('#form-sparepart').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                // Mengirim permintaan Ajax
                $.ajax({
                    type: 'POST',
                    url: "{{ url('admin/tambah_sparepart') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert('Sparepart berhasil ditambahkan');
                            // Setelah berhasil menambahkan data, panggil refreshTable untuk memperbarui tabel
                            refreshTable();
                        } else {
                            alert('Gagal menambahkan sparepart. Silakan coba lagi.');
                        }
                    },
                    error: function(error) {
                        alert('Terjadi kesalahan saat mengirim permintaan. Silakan coba lagi.');
                    }
                });
            });
        });
    </script>
@endsection
