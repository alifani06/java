<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>PERUBAHAN HARGA PRODUK - {{ ucfirst($toko) }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode produk</th>
                <th>Nama produk</th>
                <th>Harga produk awal</th>
                <th>Harga Member</th>
                <th>Diskon Member (%)</th>
                <th>Harga Non Member</th>
                <th>Diskon Non Member (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produk as $index => $item)
                @if($item->$toko->isNotEmpty())
                    @php
                        $hargaAwal = $item->harga;
                        $diskonAwal = 0; // asumsi diskon awal adalah 0
                        $memberHarga = null;
                        $nonMemberHarga = null;
                        $memberDiskon = null;
                        $nonMemberDiskon = null;
                        $isChanged = false;
    
                        if ($toko == 'tokoslawi') {
                            $memberHarga = $item->tokoslawi->first()->member_harga_slw;
                            $nonMemberHarga = $item->tokoslawi->first()->non_harga_slw;
                            $memberDiskon = $item->tokoslawi->first()->member_diskon_slw;
                            $nonMemberDiskon = $item->tokoslawi->first()->non_diskon_slw;
                        } elseif ($toko == 'tokobenjaran') {
                            $memberHarga = $item->tokobenjaran->first()->member_harga_bnjr;
                            $nonMemberHarga = $item->tokobenjaran->first()->non_harga_bnjr;
                            $memberDiskon = $item->tokobenjaran->first()->member_diskon_bnjr;
                            $nonMemberDiskon = $item->tokobenjaran->first()->non_diskon_bnjr;
                        }
    
                        if ($memberHarga != $hargaAwal || $nonMemberHarga != $hargaAwal || $memberDiskon != $diskonAwal || $nonMemberDiskon != $diskonAwal) {
                            $isChanged = true;
                        }
                    @endphp
    
                    @if($isChanged)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kode_produk }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ 'Rp. ' . number_format($hargaAwal, 0, ',', '.') }}</td>
                            <td>
                                @if($memberHarga != $hargaAwal || $memberDiskon != $diskonAwal)
                                    {{ 'Rp. ' . number_format($memberHarga - ($memberHarga * $memberDiskon / 100), 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($memberHarga != $hargaAwal || $memberDiskon != $diskonAwal)
                                    {{ $memberDiskon }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($nonMemberHarga != $hargaAwal || $nonMemberDiskon != $diskonAwal)
                                    {{ 'Rp. ' . number_format($nonMemberHarga - ($nonMemberHarga * $nonMemberDiskon / 100), 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($nonMemberHarga != $hargaAwal || $nonMemberDiskon != $diskonAwal)
                                    {{ $nonMemberDiskon }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
    
    
    
</body>
</html>
