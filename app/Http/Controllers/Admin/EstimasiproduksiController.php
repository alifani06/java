<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
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
use App\Models\Detailpenjualanproduk;
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;




class EstimasiproduksiController extends Controller{

// public function index()
// {
//     $today = \Carbon\Carbon::today();
//     $tomorrow = $today->copy()->addDay();  // Mendapatkan tanggal besok

//     // Query pemesanan produk dengan relasi ke toko dan produk
//     $pemesananProduk = DetailPemesananProduk::with(['pemesananProduk.toko', 'produk'])
//         ->whereHas('pemesananProduk', function($query) use ($tomorrow) {
//             $query->whereDate('tanggal_kirim', $tomorrow);
//         })
//         ->get()
//         ->groupBy('produk_id') // Kelompokkan berdasarkan produk_id
//         ->map(function ($groupedDetails) {
//             // Kelompokkan berdasarkan toko_id di dalam produk_id
//             return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $details->first()->pemesananProduk->toko,
//                     'produk' => $details->first()->produk,
//                     'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
//                     'detail' => $details
//                 ];
//             });
//         });
   

//     // Query permintaan produk dengan relasi ke toko, produk, dan detail permintaan produk
//     $permintaanProduks = PermintaanProduk::with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk'])
//         ->where('status', 'posting')
//         ->whereDate('created_at', $today)
//         ->orderBy('created_at', 'desc')
//         ->get()
//         ->flatMap(function ($permintaan) {
//             return $permintaan->detailPermintaanProduks;
//         })
//         ->groupBy('produk_id')
//         ->map(function ($groupedDetails) {
//             return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $details->first()->toko,
//                     'produk' => $details->first()->produk,
//                     'tanggal_permintaan' => $details->first()->tanggal_permintaan,  // Ambil tanggal_permintaan
//                 ];
//             });
//         });

//     return view('admin.estimasi_produksi.index', compact('permintaanProduks', 'pemesananProduk'));
// }


public function index()
{
    $today = \Carbon\Carbon::today();
    $tomorrow = $today->copy()->addDay();  // Mendapatkan tanggal besok

    // Query pemesanan produk dengan relasi ke toko dan produk
    $pemesananProduk = DetailPemesananProduk::with(['pemesananProduk.toko', 'produk'])
        ->whereHas('pemesananProduk', function($query) use ($tomorrow) {
            $query->whereDate('tanggal_kirim', $tomorrow);
        })
        ->get()
        ->groupBy('produk_id') // Kelompokkan berdasarkan produk_id
        ->map(function ($groupedDetails) {
            // Kelompokkan berdasarkan toko_id di dalam produk_id
            return $groupedDetails->groupBy('toko_id')->map(function ($details) {
                return [
                    'jumlah' => $details->sum('jumlah'),
                    'toko' => $details->first()->pemesananProduk->toko,
                    'produk' => $details->first()->produk,
                    'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
                    'detail' => $details
                ];
            });
        });
   
    // Query permintaan produk dengan relasi ke toko, produk, dan detail permintaan produk
    $permintaanProduks = PermintaanProduk::with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk'])
        ->where('status', 'posting')
        ->whereHas('detailPermintaanProduks', function($query) use ($today) {
            $query->whereDate('tanggal_permintaan', $today);
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->flatMap(function ($permintaan) {
            return $permintaan->detailPermintaanProduks;
        })
        ->groupBy('produk_id')
        ->map(function ($groupedDetails) {
            return $groupedDetails->groupBy('toko_id')->map(function ($details) {
                return [
                    'jumlah' => $details->sum('jumlah'),
                    'toko' => $details->first()->toko,
                    'produk' => $details->first()->produk,
                    'tanggal_permintaan' => $details->first()->tanggal_permintaan,  // Ambil tanggal_permintaan
                ];
            });
        });

    return view('admin.estimasi_produksi.index', compact('permintaanProduks', 'pemesananProduk'));
}



}