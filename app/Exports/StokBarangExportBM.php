<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use App\Models\Pengiriman_barangjadi;
use Carbon\Carbon;

class StokBarangExportBM implements FromCollection, WithHeadings, WithMapping
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
        $tanggal_pengiriman = $this->request->tanggal_pengiriman;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $toko_id = $this->request->toko_id;
        $klasifikasi_id = $this->request->klasifikasi_id;

        $query = Pengiriman_barangjadi::with('produk.klasifikasi')
            ->orderBy('tanggal_pengiriman', 'desc')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                    return $q->where('klasifikasi_id', $klasifikasi_id);
                });
            })
            ->when($tanggal_pengiriman && $tanggal_akhir, function ($query) use ($tanggal_pengiriman, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_pengiriman)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_pengiriman', [$start, $end]);
            })
            ->when($tanggal_pengiriman && !$tanggal_akhir, function ($query) use ($tanggal_pengiriman) {
                $start = Carbon::parse($tanggal_pengiriman)->startOfDay();
                return $query->where('tanggal_pengiriman', '>=', $start);
            })
            ->when(!$tanggal_pengiriman && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_pengiriman', '<=', $end);
            });

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];

        foreach ($inquery as $pengiriman) {
            $produk = $pengiriman->produk;

            if ($produk) {
                $key = $produk->id;

                if (!isset($finalResults[$key])) {
                    $totalHarga = $pengiriman->jumlah * $produk->harga;
                    $diskon = $pengiriman->diskon ? ($totalHarga * 0.10) : 0;  // Diskon 10% jika ada

                    $finalResults[$key] = [
                        'tanggal_pengiriman' => $pengiriman->tanggal_pengiriman,
                        'kode_lama' => $produk->kode_lama,
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $produk->harga,
                        'jumlah' => $pengiriman->jumlah,
                        'diskon' => $diskon,
                        'total' => $totalHarga - $diskon,
                    ];
                } else {
                    $finalResults[$key]['jumlah'] += $pengiriman->jumlah;
                    $totalHarga = $pengiriman->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $totalHarga;

                    if ($pengiriman->diskon) {
                        $diskonPerItem = $produk->harga * 0.10;
                        $finalResults[$key]['diskon'] += $pengiriman->jumlah * $diskonPerItem;
                        $finalResults[$key]['total'] -= $pengiriman->jumlah * $diskonPerItem;
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
            'Tanggal Pengiriman',
            'Kode Produk',
            'Nama Produk',
            'Harga',
            'Jumlah',
            'Total'
        ];
    }

    public function map($row): array
    {
        return [
            $this->counter++, 
            $row['tanggal_pengiriman'],
            $row['kode_lama'],
            $row['nama_produk'],
            $row['harga'],
            $row['jumlah'],
            $row['total'],
        ];
    }
}
