<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang Jadi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            padding-bottom: 100px; /* Increased padding-bottom for signatures */
        }
        .logo img {
            width: 150px;
            height: 77px;
        }
        .header {
            text-align: center;
            margin-top: 20px;
        }
        .header .title {
            font-weight: bold;
            font-size: 28px;
        }
        .header .address, .header .contact {
            font-size: 12px;
        }
        .divider {
            border: 0.5px solid #000;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .section-title {
            margin-top: 30px;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .signature-container {
            margin-top: 60px;
        }
        .signature-row {
            display: flex;
            justify-content: space-between;
            margin: 0 20px;
        }
        .signaturea {
            flex: 1;
            text-align: left;
            margin: 0 10px; /* Space between signatures */
        }
        .signatureb {
            flex: 1;
            text-align: center;
            margin: 0 10px;
            margin-top: -200px; /* Space between signatures */
        }
        .signaturec {
            flex: 1;
            text-align: right;
            margin: 0 10px; 
            margin-top: -200px; /* Space between signatures */
        }
        .signature p {
            margin: 0;
            margin-top: 10px;
        }
        .total-row {
            font-weight: bold;
        }
        .notes {
            margin-top: 30px;
            font-size: 12px;
        }
        .notes p {
            margin: 0;
        }

        @media print {
            .header {
                position: fixed;
                top: 0;
                width: 100%;
            }
            .table {
                margin-top: 150px;
            }
            .page-break {
                page-break-before: always;
                clear: both; /* Ensures that the page-break is clear of other elements */
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY">
        </div>
        <div>
            <span class="title">PT JAVA BAKERY FACTORY</span><br>
            <span class="address">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span><br>
            <span class="contact">Telp / Fax, Email :</span>
        </div>
        <br>
        <hr class="divider">
    </div>

    <div class="change-header">LAPORAN STOK BARANG JADI</div>
    <div class="text" style="margin-bottom: 1px;">
        @php
            \Carbon\Carbon::setLocale('id'); // Set locale ke bahasa Indonesia
    
            $formattedStartDate = \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y');
            $formattedEndDate = \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y');
            $currentDateTime = \Carbon\Carbon::now()->translatedFormat('d F Y H:i');
        @endphp
    
        @if ($startDate && $endDate)
            <p>
                Periode: {{ $formattedStartDate }} s/d {{ $formattedEndDate }} &nbsp;&nbsp;&nbsp;
                <span style="float: right; font-style: italic">{{ $currentDateTime }}</span>
            </p>
        @else
            <p>
                Periode: Tidak ada tanggal awal dan akhir yang diteruskan. &nbsp;&nbsp;&nbsp;
                <span style="float: right;">{{ $currentDateTime }}</span>
            </p>
        @endif
    </div>
    @php
        use Carbon\Carbon;
        $currentKodeInput = null;
        $totalStok = 0; // Initialize total stock counter
    @endphp

    @foreach ($stokBarangJadi as $index => $item)
        @if ($currentKodeInput != $item->kode_input)
            @if ($index != 0)
                <tr class="total-row">
                    <td colspan="6" class="text-end">Total:</td>
                    <td>{{ $totalStok }}</td>
                </tr>
                </tbody></table>
                <div class="page-break"></div> <!-- Page break added here -->
            @endif
            @php
                $totalStok = 0; // Reset total stock counter
                $currentKodeInput = $item->kode_input;
            @endphp
            <div class="section-title" style="margin-top: 2px;">Kode Input: {{ $currentKodeInput }}</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Inputan</th>
                        <th>Tanggal Inputan</th>
                        <th>Divisi</th>
                        <th>Kode Produk</th>
                        <th>Produk</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
        @endif
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->kode_input }}</td>
            <td>{{ Carbon::parse($item->tanggal_input)->translatedFormat('d F Y') }}</td>
            <td>{{ $item->produk->klasifikasi->nama }}</td>
            <td>{{ $item->produk->kode_produk }}</td>
            <td>{{ $item->produk->nama_produk }}</td>
            <td>{{ $item->stok }}</td>
        </tr>
        @php
            $totalStok += $item->stok; // Accumulate stock total
        @endphp
    @endforeach
    <!-- Display total stock for the last set of data -->
    <tr class="total-row">
        <td colspan="6" class="text-end">Total:</td>
        <td>{{ $totalStok }}</td>
    </tr>
                </tbody>
            </table>

            <div class="signature-container">
                <div class="signature-row">
                    <div class="signaturea">
                        <p style="margin-left: 30px;"><strong>Gudang</strong></p><br><br>
                        <p>____________________</p>
                    </div>
                    <div class="signatureb">
                        <p><strong>Accounting</strong></p><br><br>
                        <p>____________________</p>
                    </div>
                    <div class="signaturec">
                        <p style="margin-right: 60px;"><strong>Baker</strong></p><br><br>
                        <p>____________________</p>
                    </div>
                </div>
            </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</html>
