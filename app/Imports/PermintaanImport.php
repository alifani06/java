<?php

namespace App\Imports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Detailpermintaanproduk;
use App\Models\Permintaanproduk;
use Carbon\Carbon;


class PermintaanImport implements ToModel, WithHeadingRow
{
    use Importable;
    private $lastPermintaanProdukId;
    private $kodePermintaan;public function model(array $row)
    {
        // Jika kode_permintaan belum diinisialisasi, buat permintaan produk baru
        if (!$this->kodePermintaan) {
            $this->kodePermintaan = $this->generateKodePermintaan();
    
            // Buat entri baru di tabel permintaanProduks
            $permintaanProduk = PermintaanProduk::create([
                'kode_permintaan' => $this->kodePermintaan,
                'status' => 'unpost',
                'qrcode_permintaan' => 'https://javabakery.id/permintaan_produk/' . $this->kodePermintaan,
            ]);
    
            // Simpan ID permintaan produk yang terakhir
            $this->lastPermintaanProdukId = $permintaanProduk->id;
        }
    
        // Temukan produk berdasarkan kode_lama
        $produk = Produk::where('kode_lama', $row['kode_lama'])->first();
        if ($produk) {
            $produkId = $produk->id;
        } else {
            // Jika produk tidak ditemukan, skip baris atau lakukan penanganan kesalahan
            return null;
        }
    
        // Pastikan nilai 'jumlah' adalah angka dan bukan referensi sel atau formula
        $jumlah = is_numeric($row['jumlah']) ? $row['jumlah'] : 0; // Default ke 0 jika tidak valid
    
        // Simpan detail permintaan untuk setiap produk
        Detailpermintaanproduk::create([
            'permintaanproduk_id' => $this->lastPermintaanProdukId,
            'produk_id' => $produkId,
            'toko_id' => '1',
            'jumlah' => $jumlah, // Simpan jumlah yang sudah diverifikasi
            'status' => 'unpost',
            'tanggal_permintaan' => Carbon::now('Asia/Jakarta'),
        ]);
    
        // Kembalikan entri permintaan produk (opsional)
        return null;
    }
    

    public function getLastPermintaanProdukId()
    {
        return $this->lastPermintaanProdukId;
    }

    private function generateKodePermintaan()
    {
        // Ambil permintaan terakhir berdasarkan urutan kode_permintaan
        $lastBarang = PermintaanProduk::orderBy('kode_permintaan', 'desc')->first();

        if (!$lastBarang) {
            $num = 1; // Jika tidak ada permintaan sebelumnya, mulai dari 1
        } else {
            $lastCode = $lastBarang->kode_permintaan;
            $num = (int) substr($lastCode, strlen('JLC')) + 1; // Ambil angka setelah prefix
        }

        // Format nomor permintaan dengan 6 digit
        $formattedNum = sprintf("%06d", $num);
        $prefix = 'JLC'; // Prefix kode permintaan
        $newCode = $prefix . $formattedNum; // Gabungkan prefix dan nomor

        return $newCode;
    }
    

}
