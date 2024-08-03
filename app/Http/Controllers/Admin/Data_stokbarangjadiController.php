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
use App\Models\Stok_barangjadi;
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

class Data_stokbarangjadiController extends Controller{

    public function index()
    {
        // Mengambil data produk beserta stok_barangjadi dan detail_stokbarangjadi
        $produks = Produk::with(['stok_barangjadii.detail_stokbarangjadi'])->get();
        
        return view('admin.data_stokbarangjadi.index', compact('produks'));
    }
    
    // public function index()
    // {
    //     // Mengambil data produk beserta stok_barangjadi dan detail_stokbarangjadi
    //     $produks = Produk::with(['stok_barangjadii.detail_stokbarangjadi'])
    //         ->get()
    //         ->groupBy('kode_produk') // Mengelompokkan berdasarkan kode produk
    //         ->map(function ($group) {
    //             // Untuk setiap grup produk dengan kode yang sama
    //             $firstProduct = $group->first(); // Ambil data produk dari item pertama di grup
    
    //             // Menghitung stok yang ditampilkan berdasarkan status
    //             $totalStock = $group->flatMap(function ($produk) {
    //                 return $produk->stok_barangjadii;
    //             })->flatMap(function ($stok) {
    //                 return $stok->detail_stokbarangjadi->filter(function ($detailStok) {
    //                     return $detailStok->status == 'posting';
    //                 });
    //             })->sum('stok');
    
    //             // Menambahkan atribut sementara untuk stok
    //             $firstProduct->displayed_stock = $totalStock;
    //             return $firstProduct;
    //         })
    //         ->sortByDesc('displayed_stock'); // Mengurutkan berdasarkan stok yang ditampilkan secara descending
    
    //     return view('admin.data_stokbarangjadi.index', compact('produks'));
    // }
    
    

    
 
    }



 