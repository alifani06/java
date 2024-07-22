<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pemsanan Produk</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: 'DOSVGA', monospace;
            color: black;
        }

        .container {
            text-align: center;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
            /* Atur ukuran font tabel sesuai kebutuhan */
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
        }

        .signature {
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;

            /* Menghilangkan garis tepi tabel */
        }

        td {
            padding: 5px 10px;

            /* Menghilangkan garis tepi sel */

        }

        .label {
            text-align: left;
            width: 50%;
            border: none;
            /* Mengatur lebar kolom teks */
        }

        .value {
            text-align: right;
            width: 50%;
            border: none;
            /* Mengatur lebar kolom hasil */
        }

        .separator {
            text-align: center;
            font-weight: bold;
            border: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>LAPORAN PEMESANAN PRODUK</h1>
    </div>
    <div class="text">
        @if ($startDate && $endDate)
            <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>
        @else
            <p>Periode: Tidak ada tanggal awal dan akhir yang diteruskan.</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Divisi</th>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Benjaran</th>
                <th>Tegal</th>
                <th>Slawi</th>
                <th>Pemalang</th>
                <th>Bumiayu</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $currentKodeProduk = null;
                $currentTotal = 0;
            @endphp
            @foreach ($groupedData as $detail)
                @if ($currentKodeProduk && $currentKodeProduk != $detail['kode_produk'])
                    <tr>
                        <td colspan="9" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                        <td>{{ number_format($currentTotal, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $currentTotal = 0;
                    @endphp
                @endif

                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $detail['klasifikasi'] }}</td>
                    <td>{{ $detail['kode_produk'] }}</td>
                    <td>{{ $detail['nama_produk'] }}</td>
                    <td>{{ $detail['benjaran'] }}</td>
                    <td>{{ $detail['tegal'] }}</td>
                    <td>{{ $detail['slawi'] }}</td>
                    <td>{{ $detail['pemalang'] }}</td>
                    <td>{{ $detail['bumiayu'] }}</td>
                    <td>{{ number_format($detail['subtotal'], 0, ',', '.') }}</td>
                </tr>

                @php
                    $currentKodeProduk = $detail['kode_produk'];
                    $currentTotal += $detail['subtotal'];
                @endphp
            @endforeach

            @if ($currentKodeProduk)
                <tr>
                    <td colspan="9" class="text-right"><strong>Total untuk Produk: {{ $currentKodeProduk }}</strong></td>
                    <td>{{ number_format($currentTotal, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-right"><strong>Total Keseluruhan</strong></td>
                <td>{{ number_format($totalSubtotal, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <table>
            {{-- <tr>
                <td class="label">Total :</td>
                <td class="value">Rp. {{ number_format($total, 2) }}</td>
            </tr> --}}
            <!-- Tambahkan baris-baris lainnya jika diperlukan -->
            <tr>
                <td class="separator" colspan="2">______________________________</td>
            </tr>
            {{-- <tr>
                <td class="label">Sub Total :</td>
                <td class="value">Rp. {{ number_format($total, 2) }}</td>
            </tr> --}}
        </table>
    </div>


    {{-- <div class="signature">
        <p>_________________________</p>
        <p>Tanda Tangan</p>
    </div> --}}

    <script>
        function printReport() {
            var startDate = tanggalAwal.value;
            var endDate = tanggalAkhir.value;

            if (startDate && endDate) {
                form.action = "{{ url('admin/print_pemesanan') }}" + "?start_date=" + startDate + "&end_date=" + endDate;
                form.submit();

                // Update the date range in the HTML
                document.getElementById("dateRange").textContent = "Tanggal awal: " + startDate + " - Tanggal akhir: " +
                    endDate;
            } else {
                alert("Silakan isi kedua tanggal sebelum mencetak.");
            }
        }
    </script>
</body>


</html>
