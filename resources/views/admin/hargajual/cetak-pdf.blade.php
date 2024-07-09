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
            margin: 0;
            padding: 0;
            padding-bottom: 40px;
            position: relative; /* Tambahkan posisi relatif untuk body */
        }
        .logo {
            position: absolute;
            top: -20px;
            left: -30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            position: absolute;
            top: -20px;
            left: 90px;
        }
        .welcome-text {
            position: fixed;
            top: -50px;
            left: 9px;
            right: 9px;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            color: #000;
            z-index: 999;
        }
        .t1 {
            width: 100%;
            border-collapse: collapse;
        }
        .section-title {
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
        }
        .section-title.tokoslawi {
            margin-top: 30px;
        }
        .section-title.tokobenjaran,
        .section-title.tokotegal,
        .section-title.tokopemalang,
        .section-title.tokobumiayu,
        .section-title.tokocilacap {
            margin-top: 30px;
        }
        .signature-container {
            position: absolute;
            bottom: 50px; /* Atur jarak signature dari bottom */
            left: 0;
            right: 0;
            text-align: center; /* Pusatkan signature */
        }
        .signature {
            display: inline-block;
            margin-right: 100px; /* Jarak antara signature */
        }

        /* Gaya khusus untuk header "SURAT PERUBAHAN HARGA" */
        .change-header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-top: 100px;
        }
        .tanggal{
            text-align: right;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="welcome-text">
        <div class="header">
            <table width="100%">
                <tr>
                    <td style="width: 20%;">
                        <div style="text-align: left;">
                            <img src="{{ asset('storage/uploads/icon/bakery.png') }}" alt="JAVA BAKERY" width="150" height="70">
                        </div>
                    </td>
                    <td style="width: 70%; text-align: left;">
                        <div style="text-align: center;">
                            <span style="font-weight: bold; font-size: 28px;">JAVA BAKERY</span>
                            <br>
                            <span style="font-size: 12px;">JL. HOS COKRO AMINOTO NO 5 SLAWI TEGAL</span>
                            <br>
                            <span style="font-size: 12px;">Telp / Fax,</span>
                            <br>
                            <span style="font-size: 12px;">Email : </span>
                        </div>
                    </td>
                </tr>
            </table>
            <hr style="border: 0.5px solid; margin-top: 3px; margin-bottom: 1px; padding: 0;">
            <hr style="border: 0.5px solid; margin-top: 1px; margin-bottom: 1px; padding: 0;">
        </div>
    </div>

    <!-- Elemen untuk header "SURAT PERUBAHAN HARGA" -->
    <div class="change-header">SURAT PERUBAHAN HARGA</div>

<!-- Tanggal dan waktu cetak -->
<div class="tanggal" >
    Tanggal: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
</div>
    @php
        $tokoNames = [
            'tokoslawi' => 'Toko Slawi',
            'tokobenjaran' => 'Toko Benjaran',
            'tokotegal' => 'Toko Tegal',
            'tokopemalang' => 'Toko Pemalang',
            'tokobumiayu' => 'Toko Bumiayu',
            'tokocilacap' => 'Toko Cilacap'
        ];
    @endphp

    @foreach ($tokoNames as $toko => $tokoName)
        @php
            $filteredData = $produk->filter(function ($item) use ($toko) {
                return $item->$toko->isNotEmpty();
            });
            $isTokoChanged = false;
        @endphp

        @if ($filteredData->isNotEmpty())
            @foreach ($filteredData as $index => $item)
                @php
                    $hargaAwal = $item->harga;
                    $diskonAwal = 0; // asumsi diskon awal adalah 0
                    $memberHarga = null;
                    $nonMemberHarga = null;
                    $memberDiskon = null;
                    $nonMemberDiskon = null;

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
                    } elseif ($toko == 'tokotegal') {
                        $memberHarga = $item->tokotegal->first()->member_harga_tgl;
                        $nonMemberHarga = $item->tokotegal->first()->non_harga_tgl;
                        $memberDiskon = $item->tokotegal->first()->member_diskon_tgl;
                        $nonMemberDiskon = $item->tokotegal->first()->non_diskon_tgl;
                    } elseif ($toko == 'tokopemalang') {
                        $memberHarga = $item->tokopemalang->first()->member_harga_pml;
                        $nonMemberHarga = $item->tokopemalang->first()->non_harga_pml;
                        $memberDiskon = $item->tokopemalang->first()->member_diskon_pml;
                        $nonMemberDiskon = $item->tokopemalang->first()->non_diskon_pml;
                    } elseif ($toko == 'tokobumiayu') {
                        $memberHarga = $item->tokobumiayu->first()->member_harga_bmy;
                        $nonMemberHarga = $item->tokobumiayu->first()->non_harga_bmy;
                        $memberDiskon = $item->tokobumiayu->first()->member_diskon_bmy;
                        $nonMemberDiskon = $item->tokobumiayu->first()->non_diskon_bmy;
                    } elseif ($toko == 'tokocilacap') {
                        $memberHarga = $item->tokocilacap->first()->member_harga_clc;
                        $nonMemberHarga = $item->tokocilacap->first()->non_harga_clc;
                        $memberDiskon = $item->tokocilacap->first()->member_diskon_clc;
                        $nonMemberDiskon = $item->tokocilacap->first()->non_diskon_clc;
                    }

                    if ($memberHarga != $hargaAwal || $nonMemberHarga != $hargaAwal || $memberDiskon != $diskonAwal || $nonMemberDiskon != $diskonAwal) {
                        $isTokoChanged = true;
                        break;
                    }
                @endphp
            @endforeach

            @if($isTokoChanged)
                <div class="section-title {{ $toko }}">{{ $tokoName }}</div>
                <table class="t1">
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
                        @foreach ($filteredData as $index => $item)
                            @php
                                $hargaAwal = $item->harga;
                                $diskonAwal = 0; // asumsi diskon awal adalah 0
                                $memberHarga = null;
                                $nonMemberHarga = null;
                                $memberDiskon = null;
                                $nonMemberDiskon = null;

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
                                } elseif ($toko == 'tokotegal') {
                                    $memberHarga = $item->tokotegal->first()->member_harga_tgl;
                                    $nonMemberHarga = $item->tokotegal->first()->non_harga_tgl;
                                    $memberDiskon = $item->tokotegal->first()->member_diskon_tgl;
                                    $nonMemberDiskon = $item->tokotegal->first()->non_diskon_tgl;
                                } elseif ($toko == 'tokopemalang') {
                                    $memberHarga = $item->tokopemalang->first()->member_harga_pml;
                                    $nonMemberHarga = $item->tokopemalang->first()->non_harga_pml;
                                    $memberDiskon = $item->tokopemalang->first()->member_diskon_pml;
                                    $nonMemberDiskon = $item->tokopemalang->first()->non_diskon_pml;
                                } elseif ($toko == 'tokobumiayu') {
                                    $memberHarga = $item->tokobumiayu->first()->member_harga_bmy;
                                    $nonMemberHarga = $item->tokobumiayu->first()->non_harga_bmy;
                                    $memberDiskon = $item->tokobumiayu->first()->member_diskon_bmy;
                                    $nonMemberDiskon = $item->tokobumiayu->first()->non_diskon_bmy;
                                } elseif ($toko == 'tokocilacap') {
                                    $memberHarga = $item->tokocilacap->first()->member_harga_clc;
                                    $nonMemberHarga = $item->tokocilacap->first()->non_harga_clc;
                                    $memberDiskon = $item->tokocilacap->first()->member_diskon_clc;
                                    $nonMemberDiskon = $item->tokocilacap->first()->non_diskon_clc;
                                }

                                $isChanged = false;

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
                        @endforeach
                    </tbody>
                </table>
            @endif
        @endif
    @endforeach

    <div class="signature-container">
        <div class="signature">
            <p>Finance</p>
            <br><br>
            <p>________________</p>
        </div>
        <div class="signature">
            <p>Admin</p>
            <br><br>
            <p>________________</p>
        </div>
        <div class="signature">
            <p>Owner</p>
            <br><br>
            <p>________________</p>
        </div>
    </div>
</body>
</html>
