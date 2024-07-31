<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;


class ProdukImport implements ToModel
{
    private $importedData = [];

    public function model(array $row)
    {
        $this->importedData[] = [
            'kode_produk' => $row['kode_produk'],
            'nama_produk' => $row['nama_produk'],
            'jumlah' => $row['jumlah'],
        ];

        return new Produk([
            'kode_produk' => $row['kode_produk'],
            'nama_produk' => $row['nama_produk'],
            'jumlah' => $row['jumlah'],
        ]);
    }

    public function getImportedData()
    {
        return $this->importedData;
    }
}