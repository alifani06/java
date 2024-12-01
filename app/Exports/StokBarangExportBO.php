<?php

namespace App\Exports;

use App\Models\Pemindahan_tokoslawi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class StokBarangExportBO implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $request;
    protected $counter;
    protected $totalJumlah = 0;
    protected $grandTotal = 0;
    protected $finalResults = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->counter = 1; // Inisialisasi nomor urut
    }

    public function collection()
    {
        $status = $this->request->status;
        $tanggal_input = $this->request->tanggal_input;
        $tanggal_akhir = $this->request->tanggal_akhir;
        $klasifikasi_id = $this->request->klasifikasi_id;

        $toko_id = 3; // Tetapkan toko_id menjadi 3

        $query = Pemindahan_tokoslawi::with('produk.klasifikasi')
            ->orderBy('tanggal_input', 'desc')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->where('toko_id', $toko_id)
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                    return $q->where('klasifikasi_id', $klasifikasi_id);
                });
            })
            ->when($tanggal_input && $tanggal_akhir, function ($query) use ($tanggal_input, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_input)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_input', [$start, $end]);
            })
            ->when($tanggal_input && !$tanggal_akhir, function ($query) use ($tanggal_input) {
                $start = Carbon::parse($tanggal_input)->startOfDay();
                return $query->where('tanggal_input', '>=', $start);
            })
            ->when(!$tanggal_input && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_input', '<=', $end);
            })
            ->when(!$tanggal_input && !$tanggal_akhir, function ($query) {
                return $query->whereDate('tanggal_input', Carbon::today());
            });

        $inquery = $query->get();

        // Gabungkan hasil berdasarkan produk_id
        foreach ($inquery as $pemindahan) {
            $produk = $pemindahan->produk;

            if ($produk) {
                $key = $produk->id;

                if (!isset($this->finalResults[$key])) {
                    $totalHarga = $pemindahan->jumlah * $produk->harga;

                    $this->finalResults[$key] = [
                        'no' => $this->counter++, // Tambahkan nomor urut
                        'tanggal_input' => $pemindahan->tanggal_input,
                        'kode_lama' => $produk->kode_lama,
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $produk->harga,
                        'jumlah' => $pemindahan->jumlah,
                        'total' => $totalHarga,
                    ];
                } else {
                    $this->finalResults[$key]['jumlah'] += $pemindahan->jumlah;
                    $this->finalResults[$key]['total'] += $pemindahan->jumlah * $produk->harga;
                }

                $this->totalJumlah += $pemindahan->jumlah;
                $this->grandTotal += $pemindahan->jumlah * $produk->harga;
            }
        }

        // Konversi array finalResults ke collection untuk diekspor
        $resultsCollection = collect($this->finalResults);

        // Menambahkan baris total
        $resultsCollection->push([
            'no' => '', // Kosongkan nomor urut
            'tanggal_input' => 'Total',
            'kode_lama' => '',
            'nama_produk' => '',
            'harga' => '',
            'jumlah' => $this->totalJumlah,
            'total' => $this->grandTotal,
        ]);

        return $resultsCollection;
    }

    public function headings(): array
    {
        return [
            ['PT JAVABAKERY FACTORY'], // Judul laporan
            [], // Baris kosong
            ['No', 'Tanggal Input', 'Kode Produk', 'Nama Produk', 'Harga', 'Jumlah', 'Total'], // Header tabel
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1'); // Merge untuk baris judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14); // Gaya judul
        $sheet->getStyle('A3:G3')->getFont()->setBold(true); // Gaya header tabel
        $sheet->getStyle('A1:G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Rata tengah judul

        // Set lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5); // Kolom No
        $sheet->getColumnDimension('B')->setWidth(15); // Tanggal Input
        $sheet->getColumnDimension('C')->setWidth(15); // Kode Produk
        $sheet->getColumnDimension('D')->setWidth(30); // Nama Produk
        $sheet->getColumnDimension('E')->setWidth(15); // Harga
        $sheet->getColumnDimension('F')->setWidth(10); // Jumlah
        $sheet->getColumnDimension('G')->setWidth(15); // Total

        // Format angka
        $sheet->getStyle('E4:G' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');
    }

    public function title(): string
    {
        return 'Laporan Pemindahan Barang';
    }
}


