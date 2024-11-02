<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukExport implements FromCollection, WithHeadings, WithStyles
{
    protected $produkWithStok;

    public function __construct($produkWithStok)
    {
        $this->produkWithStok = $produkWithStok;
    }

    public function collection()
    {
        return $this->produkWithStok->map(function ($produk, $index) {
            return [
                'no' => $index + 1, // Menambahkan nomor urut
                'kode_produk' => $produk->kode_lama,
                'nama_produk' => $produk->nama_produk,
                'stok' => $produk->jumlah ?? 0, // Tampilkan 0 jika stok kosong
                'harga_jual' => $produk->harga,
                'subtotal' => $produk->subTotal,
            ];
        })->push($this->totalRow()); // Menambahkan baris total
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Produk',
            'Nama Produk',
            'Stok',
            'Harga Jual',
            'Sub Total',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Mengatur alignment untuk header dan kolom stok, harga jual, dan subtotal
        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // Header bold
        $sheet->getStyle('A:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Semua kolom rata kanan

        // Mengatur alignment kiri untuk kolom kode produk dan nama produk
        $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Format angka tanpa tanda mata uang untuk harga jual dan subtotal
        $sheet->getStyle('D2:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0'); // Kolom stok
        $sheet->getStyle('E2:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0'); // Harga jual dan subtotal

        // Mengatur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
    }

    protected function totalRow()
    {
        // Menghitung total stok dan subtotal
        $totalStok = $this->produkWithStok->sum('jumlah');
        $totalSubTotal = $this->produkWithStok->sum('subTotal');

        return [
            'no' => '', // Kosongkan kolom No
            'kode_produk' => 'Total', // Label untuk total
            'nama_produk' => '', // Kosongkan
            'stok' => $totalStok, // Total stok
            'harga_jual' => '', // Kosongkan
            'subtotal' => $totalSubTotal, // Total subtotal
        ];
    }
}
