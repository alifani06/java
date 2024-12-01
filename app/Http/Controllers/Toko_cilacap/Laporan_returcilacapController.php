<?php

namespace App\Http\Controllers\Toko_cilacap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Klasifikasi;
use App\Models\Retur_barangjadi;
use Carbon\Carbon;
use App\Models\Toko;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class Laporan_returcilacapController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_retur = $request->tanggal_retur;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        // Query dasar
        $query = Retur_barangjadi::with('produk.klasifikasi');
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal
        if ($tanggal_retur && $tanggal_akhir) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
        } elseif ($tanggal_retur) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $query->where('tanggal_retur', '>=', $tanggal_retur);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_retur', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_retur', Carbon::today());
        }
    
        $query->where('toko_id', 6);

        if ($klasifikasi_id) {
            if (in_array($klasifikasi_id, ['gagal', 'sampel', 'retur_tukang_sapu', 'sortir'])) {
                // Konversi underscore ke spasi untuk mencocokkan dengan data di database
                $formattedKlasifikasi = str_replace('_', ' ', $klasifikasi_id);
        
                // Filter berdasarkan kolom keterangan
                $query->where('keterangan', $formattedKlasifikasi);
            } else {
                // Filter untuk klasifikasi dari tabel produk.klasifikasi
                $query->whereHas('produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                    $query->where('id', $klasifikasi_id);
                });
            }
        }
    
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');

        $totalJumlah = 0;
        $grandTotal = 0;

        foreach ($stokBarangJadi as $returGroup) {
            foreach ($returGroup as $retur) {
                $totalJumlah += $retur->jumlah;
                $grandTotal += $retur->jumlah * $retur->produk->harga;
            }
        }

        // Ambil semua data toko dan klasifikasi untuk dropdown
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();

        return view('toko_cilacap.laporan_returcilacap.index', compact('stokBarangJadi', 'tokos', 'klasifikasis', 'totalJumlah', 'grandTotal'));
    }
            

    public function printReportreturcilacap(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_retur = $request->tanggal_retur;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

        // Format tanggal untuk tampilan
        $formattedStartDate = $tanggal_retur ? Carbon::parse($tanggal_retur)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

        $toko = Toko::find(1); // Selalu ambil dari toko_id = 1
        $branchName = $toko ? $toko->nama_toko : 'Semua Toko';

        // Query dasar untuk mengambil data Retur_barangjadi
        $query = Retur_barangjadi::with('produk.klasifikasi')
            ->join('produks', 'produks.id', '=', 'retur_barangjadis.produk_id') // Join with 'produks'
            ->orderBy('produks.kode_lama', 'asc') // Order by 'kode_lama'
            ->orderBy('retur_barangjadis.tanggal_retur', 'desc'); // Then order by 'tanggal_retur'

        // Filter berdasarkan status
        if ($status) {
            $query->where('retur_barangjadis.status', $status);
        }

         $query->where('retur_barangjadis.toko_id', 6);

         if ($klasifikasi_id) {
            if (in_array($klasifikasi_id, ['gagal', 'sampel', 'retur_tukang_sapu', 'sortir'])) {
                // Konversi underscore ke spasi untuk mencocokkan dengan data di database
                $formattedKlasifikasi = str_replace('_', ' ', $klasifikasi_id);
        
                // Filter berdasarkan kolom keterangan
                $query->where('keterangan', $formattedKlasifikasi);
            } else {
                // Filter untuk klasifikasi dari tabel produk.klasifikasi
                $query->whereHas('produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                    $query->where('id', $klasifikasi_id);
                });
            }
        }

        // Filter berdasarkan tanggal retur
        if ($tanggal_retur && $tanggal_akhir) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('retur_barangjadis.tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
        } elseif ($tanggal_retur) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $query->where('retur_barangjadis.tanggal_retur', '>=', $tanggal_retur);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('retur_barangjadis.tanggal_retur', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('retur_barangjadis.tanggal_retur', Carbon::today());
        }

        // Eksekusi query dan dapatkan hasilnya
        $stokBarangJadi = $query->select('retur_barangjadis.*') // Select only 'retur_barangjadi' fields
            ->get()
            ->groupBy('kode_retur');

        // Hitung total jumlah dan grand total
        $totalJumlah = 0;
        $grandTotal = 0;

        foreach ($stokBarangJadi as $returGroup) {
            foreach ($returGroup as $retur) {
                $totalJumlah += $retur->jumlah;
                $grandTotal += $retur->jumlah * $retur->produk->harga;
            }
        }

        // Menggunakan FacadePdf untuk menghasilkan PDF
        $pdf = FacadePdf::loadView('admin.laporan_returbarangjadi.print', [
            'stokBarangJadi' => $stokBarangJadi,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'branchName' => $branchName,
            'totalJumlah' => $totalJumlah,
            'grandTotal' => $grandTotal,
        ]);

        // Menambahkan nomor halaman di kanan bawah
        $pdf->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->setOption('footer-right', 'Halaman [page] dari [topage]');

        // Output PDF ke browser
        return $pdf->stream('laporan_barangretur.pdf', ['Attachment' => false]);
    }

}