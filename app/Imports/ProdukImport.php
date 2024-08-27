<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
use App\Models\Stok_tokobanjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;

class ProdukImport implements ToModel, WithHeadingRow
{
    use Importable;

    // public function model(array $row)
    // {
    //     return new Produk([
    //         'nama_produk' => $row['nama_produk'],
    //         'klasifikasi_id' => $row['klasifikasi_id'],
    //         'subklasifikasi_id' => $row['subklasifikasi_id'],
    //         'satuan' => $row['satuan'],
    //         'harga' => $row['harga'],
    //         'gambar' => isset($row['gambar']) ? $row['gambar'] : null,
    //         'diskon' => 0,
    //         'kode_produk' => $this->generateKodeProduk(),
    //         'qrcode_produk' => 'https://javabakery.id/produk/' . $this->generateKodeProduk(),
    //         'tanggal' => now(),
    //     ]);
    // }

    // private function generateKodeProduk()
    // {
    //     $lastBarang = Produk::latest()->first();
    //     if (!$lastBarang) {
    //         $num = 1;
    //     } else {
    //         $lastCode = $lastBarang->kode_produk;
    //         $num = (int) substr($lastCode, strlen('FE')) + 1;
    //     }
    //     $formattedNum = sprintf("%06s", $num);
    //     $prefix = 'PR';
    //     $newCode = $prefix . $formattedNum;
    //     return $newCode;
    // }
    public function model(array $row)
    {
        // Pastikan harga tidak null
        $harga = isset($row['harga']) ? $row['harga'] : 0;
    
        $kode_produk = $this->generateKodeProduk();
    
        // Simpan data ke tabel produk
        $produk = Produk::create([
            'nama_produk' => $row['nama_produk'],
            'klasifikasi_id' => $row['klasifikasi_id'],
            'subklasifikasi_id' => $row['subklasifikasi_id'],
            'satuan' => $row['satuan'],
            'harga' => $harga,
            'gambar' => isset($row['gambar']) ? $row['gambar'] : null,
            'diskon' => 0,
            'kode_produk' => $kode_produk,
            'qrcode_produk' => 'https://javabakery.id/produk/' . $kode_produk,
            'tanggal' => now(),
        ]);
    
        // Simpan data ke tabel toko
        TokoSlawi::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $harga,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_diskon_slw' => 0,
            'non_harga_slw' => $harga,
            'non_diskon_slw' => 0,
        ]);
    
        Tokobanjaran::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_bnjr' => $harga,
            'member_diskon_bnjr' => 0,
            'non_harga_bnjr' => $harga,
            'non_diskon_bnjr' => 0,
        ]);
    
        Tokotegal::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_tgl' => $harga,
            'member_diskon_tgl' => 0,
            'non_harga_tgl' => $harga,
            'non_diskon_tgl' => 0,
        ]);
    
        Tokopemalang::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_pml' => $harga,
            'member_diskon_pml' => 0,
            'non_harga_pml' => $harga,
            'non_diskon_pml' => 0,
        ]);
    
        Tokobumiayu::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_bmy' => $harga,
            'member_diskon_bmy' => 0,
            'non_harga_bmy' => $harga,
            'non_diskon_bmy' => 0,
        ]);
    
        Tokocilacap::create([
            'produk_id' => $produk->id,
            'harga_awal' => $harga,
            'diskon_awal' => 0,
            'member_harga_clc' => $harga,
            'member_diskon_clc' => 0,
            'non_harga_clc' => $harga,
            'non_diskon_clc' => 0,
        ]);
    
        // Simpan data ke tabel stok_tokobanjaran dengan jumlah 0
        Stok_tokobanjaran::create([
            'produk_id' => $produk->id,
            'jumlah' => 0,
        ]);
    
        return $produk;
    }
    
    private function generateKodeProduk()
    {
        $lastBarang = Produk::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_produk;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'PR';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
    

}
