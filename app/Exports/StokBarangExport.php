<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use App\Models\Penjualanproduk;
use Carbon\Carbon;

class StokBarangExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    protected $counter;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->counter = 1; // Inisialisasi nomor urut

    }

    public function collection()
    {
        $status = $this->request->status;
        $tanggal_penjualan = $this->request->tanggal_penjualan;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $toko_id = $this->request->toko_id;
        $klasifikasi_id = $this->request->klasifikasi_id;

        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('detailPenjualanProduk.produk', function ($q) use ($klasifikasi_id) {
                    return $q->where('klasifikasi_id', $klasifikasi_id);
                });
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$start, $end]);
            })
            ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $start);
            })
            ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $end);
            })
            ->orderBy('tanggal_penjualan', 'desc');

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];

        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;

                if ($produk) {
                    $key = $produk->id;

                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                        ];
                    }

                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['total'] += $detail->total;

                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10;
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
                }
            }
        }

        // Konversi array finalResults ke collection untuk diekspor
        return collect($finalResults);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Penjualan',
            'Kode Produk',
            'Nama Produk',
            'Harga',
            'Jumlah',
            'Diskon',
            'Total'
        ];
    }

    public function map($row): array
    {
        return [

            $this->counter++, 
            $row['tanggal_penjualan'],
            $row['kode_lama'],
            $row['nama_produk'],
            $row['harga'],
            $row['jumlah'],
            $row['diskon'],
            $row['total'],
        ];
    }
}