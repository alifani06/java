@extends('layouts.app')

@section('title', 'Perbarui Pemesanan Produk')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Perbarui pemesanan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item"><a href="{{ url('admin/pembelian_ban') }}">Transaksi</a></li> --}}
                        <li class="breadcrumb-item active">Perbarui Pemesanan</li>
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
            <form action="{{ url('admin/pemesanan_produk/' . $inquery->id) }}" method="post" autocomplete="off">
                @csrf
                @method('put')
                <div class="card">
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
                                        <option value="{{ $toko->id }}" {{ old('toko') == $toko->id || (isset($toko) && $toko->toko_id == $toko->id) ? 'selected' : '' }}>
                                            {{ $toko->nama_toko }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            
                            <div class="col-auto mt-2" id="kode_pemesanan" >
                                <label for="kode_pemesanan">KOde Pemesanan</label>
                                <input type="text" class="form-control" id="kode_pemesanan" name="kode_pemesanan" readonly value="{{$inquery->kode_pemesanan}}">
                            </div>
                            <div class="col-auto mt-2" id="kodePelangganRow" hidden>
                                <label for="qrcode_pelanggan">Scan Kode Pelanggan</label>
                                <input type="text" class="form-control" id="qrcode_pelanggan" name="qrcode_pelanggan" placeholder="scan kode Pelanggan" onchange="getData(this.value)">
                            </div>
                        </div>
                    
                        <div class="row mb-3 align-items-center" id="namaPelangganRow" >
   
                            <div class="col-md-4 mb-3 "> 
                                <label for="telp">Nama Pelanggan</label>
                                <input readonly placeholder="Masukan Nama Pelanggan" type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" value="{{ $inquery->nama_pelanggan }}" onclick="showCategoryModalpemesanan()">
                            </div>     
                        </div>

                        <div class="row  align-items-center" id="telpRow" >
                            <div class="col-md-4 mb-3">
                                <label for="telp">No. Telepon</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">+62</span>
                                    </div>
                                    <input type="number" id="telp" name="telp" class="form-control" placeholder="Masukan nomor telepon" value="{{$inquery->telp}}">
                                </div>
                            </div>
                        </div>
                    
                        <div class="row mb-3 align-items-center" id="alamatRow" >
                            <div class="col-md-4 mb-3">
                                <label for="catatan">Alamat</label>
                                <textarea placeholder="" type="text" class="form-control" id="alamat" name="alamat">{{$inquery->alamat}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Detail Pengiriman</h3>
                        </div>
                        <div class="card-body">
                            <div class="col-md-4 mb-3">
                                <label for="tanggal_kirim">Tanggal Pengiriman:</label>
                                <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                                    <input type="text" id="tanggal_kirim" name="tanggal_kirim"
                                           class="form-control datetimepicker-input"
                                           data-target="#reservationdatetime"
                                           value="{{$inquery->tanggal_kirim}}"
                                           placeholder="DD/MM/YYYY HH:mm">
                                    <div class="input-group-append" data-target="#reservationdatetime" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3 align-items-center">
                                <div class="col-md-6 -auto" id="" >
                                    <label for="nama_penerima">Nama Penerima </label> <span style="font-size: 10px;">(kosongkan jika sama dengan nama pelanggan)</span>
                                    <input type="text" class="form-control" id="nama_penerima" name="nama_penerima"  value="{{$inquery->nama_penerima}}">
                                </div>
                            </div>
                            <div class="row  align-items-center" id="telp_penerima" >
                                <div class="col-md-6">
                                    <label for="telp_penerima">No. Telepon</label> <span style="font-size: 10px;">(kosongkan jika sama dengan Nomer telepon pelanggan)</span>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+62</span>
                                        </div>
                                        <input type="number" id="telp_penerima" name="telp_penerima" class="form-control" placeholder="Masukan nomor telepon" value="{{ old('telp_penerima') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 align-items-center" id="alamat_penerima" >
                                <div class="col-md-6 mb-3">
                                    <label for="alamat_penerima">Alamat Penerima</label><span style="font-size: 10px;"> (kosongkan jika sama dengan alamat pelanggan)</span>
                                    <textarea placeholder="Masukan alamat penerima" type="text" class="form-control" id="alamat_penerima" name="alamat_penerima">{{ old('alamat_penerima') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
    
                 <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambah Pemesanan</h3>
                        <div class="float-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addPesanan()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                 
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
                                    <th>Toatal</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-pembelian">
                                @foreach ($inquery->detailpemesananproduk as $detail)
                                    <tr id="pembelian-{{ $loop->index }}">
                                        <td class="text-center" id="urutan">{{ $loop->index + 1 }}</td>
                                        <td style="width: 140px">
                                            {{-- <div class="form-group" hidden>
                                                <input type="text" class="form-control" id="nomor_seri-0"
                                                    name="detail_ids[]" value="{{ $detail['id'] }}">
                                            </div> --}}
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="kode_produk-0"
                                                    name="kode_produk[]" value="{{ $detail->kode_produk }}">
                                            </div>
                                        </td>
                                        <td style="width: 190px">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="nama_produk-0"
                                                    name="nama_produk[]" value="{{ $detail->nama_produk }}">
                                            </div></td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="jumlah-0"
                                                    name="jumlah[]" value="{{ $detail->jumlah }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="diskon-0"
                                                    name="diskon[]" value="{{ $detail->diskon }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="harga-0"
                                                    name="harga[]" value="{{ $detail->harga }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="total-0"
                                                    name="total[]" value="{{ $detail->total }}">
                                            </div>
                                        </td>
                                        {{-- </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" id="ukuran_id-{{ $loop->index }}"
                                                    name="ukuran_id[]">
                                                    <option value="">- Pilih Ukuran -</option>
                                                    @foreach ($ukurans as $ukuran)
                                                        <option value="{{ $ukuran->id }}"
                                                            {{ old('ukuran_id.' . $loop->parent->index, $detail['ukuran_id']) == $ukuran->id ? 'selected' : '' }}>
                                                            {{ $ukuran->ukuran }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control" id="kondisi_ban-0" name="kondisi_ban[]">
                                                    <option value="">- Pilih Kondisi -</option>
                                                    <option value="BARU"    
                                                        {{ old('kondisi_ban', $detail['kondisi_ban']) == 'BARU' ? 'selected' : null }}>
                                                        BARU</option>
                                                    <option value="BEKAS"
                                                        {{ old('kondisi_ban', $detail['kondisi_ban']) == 'BEKAS' ? 'selected' : null }}>
                                                        BEKAS</option>
                                                    <option value="KANISIR"
                                                        {{ old('kondisi_ban', $detail['kondisi_ban']) == 'KANISIR' ? 'selected' : null }}>
                                                        KANISIR</option>
                                                    <option value="AFKIR"
                                                        {{ old('kondisi_ban', $detail['kondisi_ban']) == 'AFKIR' ? 'selected' : null }}>
                                                        AFKIR</option>
                                                    <option value="PROFIT"
                                                        {{ old('kondisi_ban', $detail['kondisi_ban']) == 'PROFIT' ? 'selected' : null }}>
                                                        PROFIT</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <select class="form-control" id="merek_id-{{ $loop->index }}"
                                                        name="merek_id[]">
                                                        <option value="">- Pilih Merek -</option>
                                                        @foreach ($mereks as $merek)
                                                            <option value="{{ $merek->id }}"
                                                                {{ old('merek_id.' . $loop->parent->index, $detail['merek_id']) == $merek->id ? 'selected' : '' }}>
                                                                {{ $merek->nama_merek }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <select class="form-control" id="typeban_id-{{ $loop->index }}"
                                                        name="typeban_id[]">
                                                        <option value="">- Pilih Type -</option>
                                                        @foreach ($typebans as $typeban_id)
                                                            <option value="{{ $typeban_id->id }}"
                                                                {{ old('typeban_id.' . $loop->parent->index, $detail['typeban_id']) == $typeban_id->id ? 'selected' : '' }}>
                                                                {{ $typeban_id->nama_type }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="harga-0" name="harga[]"
                                                    value="{{ $detail['harga'] }}"
                                                    onkeypress="return /[0-9,]/.test(event.key)">
                                            </div>
                                        </td> --}}
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeBan({{ $loop->index }}, {{ $detail['id'] }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> 
                    <div style="margin-right: 20px; margin-left:20px" class="form-group">
                        <label style="font-size:14px" class="mt-3" for="nopol">Grand Total</label>
                        <input style="font-size:14px" type="text" class="form-control text-right" id="grand_total"
                            name="grand_total" readonly placeholder=""
                            value="{{ old('grand_total', $inquery->grand_total) }}">
                    </div>
                    <div class="card-footer text-right">
                        <button type="reset" class="btn btn-secondary">Reset</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>

        </div>
  
    </section>
    {{-- <script>
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
            }

            itemPembelian(jumlah_ban, jumlah_ban - 1);

            updateUrutan();
        }


        function removeBan(identifier, detailId) {
            var row = document.getElementById('pembelian-' + identifier);
            row.remove();

            $.ajax({
                url: "{{ url('admin/ban/') }}/" + detailId,
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
            updateGrandTotal()
            updateUrutan();
        }

        function itemPembelian(identifier, key, value = null) {
            var no_seri = '';
            var ukuran_id = '';
            var merek_id = '';
            var typeban_id = '';
            var harga = '';
            var kondisi_ban = '';

            if (value !== null) {
                no_seri = value.no_seri;
                ukuran_id = value.ukuran_id;
                merek_id = value.merek_id;
                typeban_id = value.typeban_id;
                harga = value.harga;
                kondisi_ban = value.kondisi_ban;
            }

            console.log(no_seri);
            // urutan 
            var item_pembelian = '<tr id="pembelian-' + urutan + '">';
            item_pembelian += '<td class="text-center" id="urutan">' + urutan + '</td>';
            item_pembelian += '<td style="width: 240px">';

            // no_seri 
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" id="nomor_seri-' + key +
                '" name="no_seri[]" value="' +
                no_seri +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
            item_pembelian += '<td>';

            // ukuran 
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="ukuran_id-' + key +
                '" name="ukuran_id[]" onchange="getHarga(' + key + ')">';
            item_pembelian += '<option value="">- Pilih Ukuran -</option>';
            item_pembelian += '@foreach ($ukurans as $ukuran_id)';
            item_pembelian +=
                '<option value="{{ $ukuran_id->id }}" {{ $ukuran_id->id == ' + ukuran_id + ' ? 'selected' : '' }}>{{ $ukuran_id->ukuran }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // kondisi_ban
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control" id="kondisi_ban-' + key + '" name="kondisi_ban[]">';
            item_pembelian += '<option value="">- Pilih Kondisi -</option>';
            item_pembelian += '<option value="BARU"' + (kondisi_ban === 'BARU' ? ' selected' : '') + '>BARU</option>';
            item_pembelian += '<option value="BEKAS"' + (kondisi_ban === 'BEKAS' ? ' selected' : '') +
                '>BEKAS</option>';
            item_pembelian += '<option value="KANISIR"' + (kondisi_ban === 'KANISIR' ? ' selected' : '') +
                '>KANISIR</option>';
            item_pembelian += '<option value="AFKIR"' + (kondisi_ban === 'AFKIR' ? ' selected' : '') +
                '>AFKIR</option>';
            item_pembelian += '<option value="PROFIT"' + (kondisi_ban === 'PROFIT' ? ' selected' : '') +
                '>PROFIT</option>';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // merek
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="merek_id-' + key +
                '" name="merek_id[]">';
            item_pembelian += '<option value="">- Pilih Merek -</option>';
            item_pembelian += '@foreach ($mereks as $merek_id)';
            item_pembelian +=
                '<option value="{{ $merek_id->id }}" {{ $merek_id->id == ' + merek_id + ' ? 'selected' : '' }}>{{ $merek_id->nama_merek }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
            item_pembelian += '</td>'

            // type
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">';
            item_pembelian += '<select class="form-control select2bs4" id="typeban_id-' + key +
                '" name="typeban_id[]">';
            item_pembelian += '<option value="">- Pilih Type -</option>';
            item_pembelian += '@foreach ($typebans as $typeban_id)';
            item_pembelian +=
                '<option value="{{ $typeban_id->id }}" {{ $typeban_id->id == ' + typeban_id + ' ? 'selected' : '' }}>{{ $typeban_id->nama_type }}</option>';
            item_pembelian += '@endforeach';
            item_pembelian += '</select>';
            item_pembelian += '</div>';
            item_pembelian += '</td>';
            item_pembelian += '</td>'

            // harga
            item_pembelian += '<td>';
            item_pembelian += '<div class="form-group">'
            item_pembelian += '<input type="text" class="form-control" onkeypress="return /[0-9,]/.test(event.key)" id="harga-' + key + '" name="harga[]" value="' +
                harga +
                '" ';
            item_pembelian += '</div>';
            item_pembelian += '</td>';

            // delete
            item_pembelian += '<td>';
            item_pembelian += '<button type="button" class="btn btn-danger" onclick="removeBan(' + urutan + ')">';
            item_pembelian += '<i class="fas fa-trash"></i>';
            item_pembelian += '</button>';
            item_pembelian += '</td>';
            item_pembelian += '</tr>';

            $('#tabel-pembelian').append(item_pembelian);


            if (value !== null) {
                $('#nomor_seri-' + key).val(value.no_seri);
                $('#ukuran_id-' + key).val(value.ukuran_id);
                $('#kondisi_ban-' + key).val(value.kondisi_ban);
                $('#merek_id-' + key).val(value.merek_id);
                $('#typeban_id-' + key).val(value.typeban_id);
                $('#harga-' + key).val(value.harga);
            }
        }
    </script>

    <script>
        function updateGrandTotal() {
            var grandTotal = 0;

            // Loop through all elements with name "nominal_tambahan[]"
            $('input[name^="harga"]').each(function() {
                var nominalValue = parseFloat($(this).val().replace(/\./g, '').replace(',', '.')) || 0;
                grandTotal += nominalValue;
            });
            // $('#sub_total').val(grandTotal.toLocaleString('id-ID'));
            // $('#pph2').val(pph2Value.toLocaleString('id-ID'));
            $('#grand_total').val(formatRupiah(grandTotal));
            console.log(grandTotal);
        }

        $('body').on('input', 'input[name^="harga"]', function() {
            updateGrandTotal();
        });

        // Panggil fungsi saat halaman dimuat untuk menginisialisasi grand total
        $(document).ready(function() {
            updateGrandTotal();
        });

        function formatRupiah(value) {
            return value.toLocaleString('id-ID');
        }

        // function formatRupiahsss(number) {
        //     var formatted = new Intl.NumberFormat('id-ID', {
        //         minimumFractionDigits: 1,
        //         maximumFractionDigits: 1
        //     }).format(number);
        //     return '' + formatted;
        // }
    </script> --}}
@endsection
