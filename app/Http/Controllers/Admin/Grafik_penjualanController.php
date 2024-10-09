<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use App\Models\Pelanggan;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Barang;
use App\Models\Detailbarangjadi;
use App\Models\Detailpemesananproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;






class Grafik_penjualanController extends Controller
{


    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
        $produk_id = $request->produk; // Tambahkan filter produk
    
        // Query dasar untuk mengambil data Penjualanproduk
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$start, $end]);
            })
            ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
                $start = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $start);
            })
            ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                $end = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $end);
            });
    
        // Eksekusi query dan dapatkan hasilnya
        $inquery = $query->get();
    
        // Gabungkan hasil berdasarkan tanggal penjualan
        $finalResults = [];
    
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;
    
                if ($produk) {
                    // Cek filter klasifikasi dan produk
                    if ($klasifikasi_id && $produk->klasifikasi_id != $klasifikasi_id) {
                        continue; // Lewati produk yang tidak sesuai klasifikasi
                    }
    
                    if ($produk_id && $produk->id != $produk_id) {
                        continue; // Lewati produk yang tidak sesuai filter produk
                    }
    
                    // Pastikan tanggal_penjualan diubah menjadi objek Carbon sebelum diformat
                    $tanggal = Carbon::parse($penjualan->tanggal_penjualan)->format('Y-m-d'); // Mengubah string menjadi Carbon dan memformat
    
                    // Jika tanggal belum ada di $finalResults, inisialisasi
                    if (!isset($finalResults[$tanggal])) {
                        $finalResults[$tanggal] = [
                            'tanggal_penjualan' => $tanggal,
                            'penjualan_bersih' => 0, // Inisialisasi untuk penjualan bersih
                        ];
                    }
    
                    // Hitung penjualan kotor dan diskon
                    $penjualan_kotor = $detail->jumlah * $produk->harga;
                    $diskon = ($detail->diskon > 0) ? $detail->jumlah * $produk->harga * 0.10 : 0;
                    $penjualan_bersih = $penjualan_kotor - $diskon;
    
                    // Tambahkan penjualan bersih ke finalResults (diakumulasi)
                    $finalResults[$tanggal]['penjualan_bersih'] += $penjualan_bersih;
                }
            }
        }
    
        // Mengurutkan finalResults berdasarkan tanggal penjualan
        ksort($finalResults);
    
        // Ambil data untuk filter
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $produks = Produk::all();
    
        // Pass data ke view
        return view('admin.grafik_penjualan.index', [
            'finalResults' => $finalResults,
            'tokos' => $tokos,
            'produks' => $produks,
            'klasifikasis' => $klasifikasis,
            'startDate' => $tanggal_penjualan,
            'endDate' => $tanggal_akhir,
        ]);
    }
    
    
    


  
}