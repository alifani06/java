<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Report Pemindahan Barang</h1>
    <p>Status: {{ $status ? $status : 'Semua' }}</p>
    <p>Dari Tanggal: {{ $tanggal_input ? \Carbon\Carbon::parse($tanggal_input)->format('d/m/Y') : 'Semua' }}</p>
    <p>Sampai Tanggal: {{ $tanggal_akhir ? \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') : 'Semua' }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pemindahan</th>
                <th>Tanggal Pengiriman</th>
                <th>Tanggal Terima</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stokBarangJadi as $kodeInput => $stokBarangJadiItems)
            @php
                $firstItem = $stokBarangJadiItems->first();
            @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $firstItem->kode_pemindahan }}</td>
                    <td>{{ \Carbon\Carbon::parse($firstItem->tanggal_input)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($firstItem->tanggal_terima)
                            {{ \Carbon\Carbon::parse($firstItem->tanggal_terima)->format('d/m/Y H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $firstItem->keterangan }}</td>
                </tr>

            @endforeach
        </tbody>
    </table>
</body>
</html>
