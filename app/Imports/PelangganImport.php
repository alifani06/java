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

        // Simpan data ke tabel pelanggan
        $pelanggan = Pelanggan::create([
            'kode_lama' => $row['kode_lama'],
            'nama_pelanggan' => $row['nama_pelanggan'],
            'alamat' => $row['alamat'],
            'telp' => $row['telp'],
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

    public function chunkSize(): int
    {
        return 100; // Jumlah baris per chunk
    }
}

// class PelangganImport implements ToModel, WithHeadingRow
// {
//     use Importable;

//     public function model(array $row)
//     {
//         // Validasi data
//         $kode_lama = isset($row['kode_lama']) ? $row['kode_lama'] : null;
//         $nama_pelanggan = isset($row['nama_pelanggan']) ? $row['nama_pelanggan'] : null;
//         $alamat = isset($row['alamat']) ? $row['alamat'] : null;
//         $gender = isset($row['gender']) ? $row['gender'] : null;
//         $telp = isset($row['telp']) ? $row['telp'] : null;
//         $email = isset($row['email']) ? $row['email'] : null;
//         $pekerjaan = isset($row['pekerjaan']) ? $row['pekerjaan'] : null;
//         $tanggal_lahir = isset($row['tanggal_lahir']) ? $row['tanggal_lahir'] : null;
//         $tanggal_awal = isset($row['tanggal_awal']) ? $row['tanggal_awal'] : null;
//         $tanggal_akhir = isset($row['tanggal_akhir']) ? $row['tanggal_akhir'] : null;

//         // Simpan gambar jika ada
//         $gambar = isset($row['gambar']) ? $this->storeImage($row['gambar']) : null;

//         // Buat kode pelanggan
//         $kode_pelanggan = $this->generateKodePelanggan();

//         // Simpan data ke tabel Pelanggan
//         $pelanggan = Pelanggan::create([
//             'kode_lama' => $kode_lama,
//             'nama_pelanggan' => $nama_pelanggan,
//             'alamat' => $alamat,
//             'gender' => $gender,
//             'telp' => $telp,
//             'email' => $email,
//             'pekerjaan' => $pekerjaan,
//             'tanggal_lahir' => $tanggal_lahir,
//             'tanggal_awal' => $tanggal_awal,
//             'tanggal_akhir' => $tanggal_akhir,
//             'gambar' => $gambar,
//             'status' => 'null', // status default sesuai script 1
//             'kode_pelanggan' => $kode_pelanggan,
//             'qrcode_pelanggan' => 'https://javabakery.id/pelanggan/' . $kode_pelanggan,
//             'tanggal' => Carbon::now('Asia/Jakarta'),
//         ]);

//         // Tambahan tabel lain seperti TokoBanjaran, TokoTegal, dll., sesuai dengan data pelanggan

//         return $pelanggan;
//     }

//     private function generateKodePelanggan()
//     {
//         // Ambil pelanggan terakhir berdasarkan urutan kode_pelanggan
//         $lastPelanggan = Pelanggan::orderBy('kode_pelanggan', 'desc')->first();

//         if (!$lastPelanggan) {
//             $num = 1; // Jika tidak ada pelanggan sebelumnya, mulai dari 1
//         } else {
//             $lastCode = $lastPelanggan->kode_pelanggan;
//             $num = (int) substr($lastCode, strlen('JB')) + 1; // Ambil angka setelah prefix
//         }

//         // Format nomor pelanggan dengan 6 digit
//         $formattedNum = sprintf("%06d", $num);
//         $prefix = 'JB'; // Prefix kode pelanggan
//         $newCode = $prefix . $formattedNum; // Gabungkan prefix dan nomor

//         return $newCode;
//     }

//     private function storeImage($image)
//     {
//         // Proses penyimpanan gambar
//         $gambar = str_replace(' ', '', $image->getClientOriginalName());
//         $namaGambar = 'pelanggan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
//         $image->storeAs('public/uploads/', $namaGambar);

//         return $namaGambar;
//     }
// }


