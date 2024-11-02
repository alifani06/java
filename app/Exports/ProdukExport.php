<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProdukExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $produkWithStok;
    protected $namaKlasifikasi; // Tambahkan untuk menyimpan nama klasifikasi

    public function __construct($produkWithStok, $namaKlasifikasi = 'Semua Klasifikasi')
    {
        $this->produkWithStok = $produkWithStok;
        $this->namaKlasifikasi = $namaKlasifikasi;
    }

    public function collection()
    {
        return $this->produkWithStok->map(function ($produk, $index) {
            return [
                'no' => $index + 1, // Menambahkan nomor urut
                'kode_produk' => $produk->kode_lama,
                'nama_produk' => $produk->nama_produk,
                'stok' => $produk->jumlah ?? 0, // Tampilkan 0 jika stok kosong
                'harga_jual' => $produk->harga ?? 0,
                'subtotal' => $produk->subTotal ?? 0,
            ];
        })->push($this->totalRow()); // Menambahkan baris total
    }

    public function headings(): array
{
    return [
        ['PT JAVABAKERY FACTORY'],               // Baris pertama untuk judul
        ['Klasifikasi: ' . $this->namaKlasifikasi],  // Baris kedua untuk nama klasifikasi
        [], // Baris kosong untuk spasi
        ['No', 'Kode Produk', 'Nama Produk', 'Stok', 'Harga Jual', 'Sub Total'], // Header tabel
    ];
}

public function styles(Worksheet $sheet)
{
    // Mengatur gaya untuk judul dan klasifikasi
    $sheet->mergeCells('A1:F1'); // Merge untuk judul
    $sheet->mergeCells('A2:F2'); // Merge untuk klasifikasi

    $sheet->setCellValue('A1', 'PT JAVABAKERY FACTORY'); // Set judul
    $sheet->setCellValue('A2', 'Klasifikasi: ' . $this->namaKlasifikasi); // Set klasifikasi

    // Mengatur font dan alignment untuk judul dan klasifikasi
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A2')->getFont()->setBold(true);
    $sheet->getStyle('A1:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Rata tengah

    // Mengatur alignment dan gaya untuk header tabel
    $sheet->getStyle('A4:F4')->getFont()->setBold(true); // Header bold
    $sheet->getStyle('A:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT); // Semua kolom rata kanan

    // Mengatur alignment kiri untuk kolom kode produk dan nama produk
    $sheet->getStyle('B')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    $sheet->getStyle('C')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

    // Format angka dengan titik sebagai pemisah ribuan untuk harga jual dan subtotal
    $sheet->getStyle('D5:D' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getStyle('E5:E' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');
    $sheet->getStyle('F5:F' . $sheet->getHighestRow())->getNumberFormat()->setFormatCode('#,##0');

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
        $totalStok = $this->produkWithStok->sum('jumlah') ?? 0;
        $totalSubTotal = $this->produkWithStok->sum('subTotal') ?? 0;

        return [
            'no' => '', // Kosongkan kolom No
            'kode_produk' => 'Total', // Label untuk total
            'nama_produk' => '', // Kosongkan
            'stok' => $totalStok, // Total stok
            'harga_jual' => '', // Kosongkan
            'subtotal' => $totalSubTotal, // Total subtotal
        ];
    }

    public function title(): string
    {
        return 'Laporan Stok Produk';
    }
}
