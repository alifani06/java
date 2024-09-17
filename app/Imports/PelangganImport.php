<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


// class PelangganImport implements ToModel, WithHeadingRow, WithChunkReading {
    
//     use Importable;

//     public function model(array $row)
//     {
//         // Periksa apakah baris kosong (misalnya, kolom 'kode_lama' tidak ada)
//         if (empty($row['kode_lama']) || empty($row['nama_pelanggan']) || empty($row['alamat'])) {
//             return null; // Abaikan baris ini
//         }

//         // Generate kode pelanggan seperti di script 1
//         $kode = $this->generateKodePelanggan();

//         // Convert Excel serial date to Carbon date
//         $tanggalAkhir = $this->convertExcelDateToCarbon($row['tanggal_akhir']);

//         // Simpan data ke tabel pelanggan
//         $pelanggan = Pelanggan::create([
//             'kode_lama' => $row['kode_lama'],
//             'nama_pelanggan' => $row['nama_pelanggan'],
//             'alamat' => $row['alamat'],
//             'telp' => $row['telp'],
//             'tanggal_akhir' => $tanggalAkhir,
//             'kode_pelanggan' => $kode,
//             'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $kode,
//             'status' => 'null',
//             'tanggal' => Carbon::now('Asia/Jakarta'),
//         ]);

//         return $pelanggan;
//     }

//     private function generateKodePelanggan()
//     {
//         // Ambil pelanggan terakhir berdasarkan urutan id
//         $lastPelanggan = Pelanggan::orderBy('id', 'desc')->first();

//         if (!$lastPelanggan) {
//             $num = 1; // Jika tidak ada pelanggan sebelumnya, mulai dari 1
//         } else {
//             $lastCode = $lastPelanggan->id;
//             $num = $lastCode + 1; // Increment ID
//         }

//         // Format nomor pelanggan dengan 6 digit
//         $formattedNum = sprintf("%06d", $num);
//         $prefix = 'JB'; // Prefix kode pelanggan
//         $newCode = $prefix . $formattedNum; // Gabungkan prefix dan nomor

//         return $newCode;
//     }

//     private function convertExcelDateToCarbon($excelDate)
//     {
//         if (is_numeric($excelDate)) {
//             // Excel serial date format to Carbon date
//             return Carbon::createFromFormat('Y-m-d', Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2)->format('Y-m-d'));
//         }

//         return null; // Handle cases where the date is not numeric
//     }

//     public function chunkSize(): int
//     {
//         return 100; // Jumlah baris per chunk
//     }
// }

class PelangganImport implements ToModel, WithHeadingRow, WithChunkReading {
    
    use Importable;

    public function model(array $row)
    {
        // Periksa apakah baris kosong (misalnya, kolom 'kode_lama' tidak ada)
        if (empty($row['kode_lama']) || empty($row['nama_pelanggan']) || empty($row['alamat'])) {
            return null; // Abaikan baris ini
        }

        // Generate kode pelanggan seperti di script 1
        $kode = $this->generateKodePelanggan();

        // Convert Excel serial date to Carbon date
        $tanggalAkhir = $this->convertExcelDateToCarbon($row['tanggal_akhir']);

        // Bersihkan kolom telp untuk hanya menyimpan angka
        $telp = $this->cleanPhoneNumber($row['telp']);

        // Simpan data ke tabel pelanggan
        $pelanggan = Pelanggan::create([
            'kode_lama' => $row['kode_lama'],
            'nama_pelanggan' => $row['nama_pelanggan'],
            'alamat' => $row['alamat'],
            'telp' => $telp,
            'tanggal_akhir' => $tanggalAkhir,
            'kode_pelanggan' => $kode,
            'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $kode,
            'status' => 'null',
            'tanggal' => Carbon::now('Asia/Jakarta'),
        ]);

        return $pelanggan;
    }

    private function generateKodePelanggan()
    {
        // Ambil pelanggan terakhir berdasarkan urutan id
        $lastPelanggan = Pelanggan::orderBy('id', 'desc')->first();

        if (!$lastPelanggan) {
            $num = 1; // Jika tidak ada pelanggan sebelumnya, mulai dari 1
        } else {
            $lastCode = $lastPelanggan->id;
            $num = $lastCode + 1; // Increment ID
        }

        // Format nomor pelanggan dengan 6 digit
        $formattedNum = sprintf("%06d", $num);
        $prefix = 'JB'; // Prefix kode pelanggan
        $newCode = $prefix . $formattedNum; // Gabungkan prefix dan nomor

        return $newCode;
    }

    private function convertExcelDateToCarbon($excelDate)
    {
        if (is_numeric($excelDate)) {
            // Excel serial date format to Carbon date
            return Carbon::createFromFormat('Y-m-d', Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2)->format('Y-m-d'));
        }

        return null; // Handle cases where the date is not numeric
    }

    private function cleanPhoneNumber($phoneNumber)
    {
        // Hapus semua karakter non-numerik
        return preg_replace('/\D/', '', $phoneNumber);
    }

    public function chunkSize(): int
    {
        return 100; // Jumlah baris per chunk
    }
}





