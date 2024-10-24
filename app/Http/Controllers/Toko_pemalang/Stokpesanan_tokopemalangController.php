<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Models\Detail_stokbarangjadi;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Stok_tokoslawi;
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
use App\Models\Stokpesanan_tokobanjaran;
use App\Models\Stokpesanan_tokopemalang;
use App\Models\Subklasifikasi;
use Maatwebsite\Excel\Facades\Excel;

class Stokpesanan_tokopemalangController extends Controller{


public function index(Request $request)
{
    $klasifikasis = Klasifikasi::all();
    $produkQuery = Produk::with(['klasifikasi', 'subklasifikasi']);

    // Filter berdasarkan klasifikasi_id
    if ($request->has('klasifikasi_id') && $request->klasifikasi_id) {
        $produkQuery->where('klasifikasi_id', $request->klasifikasi_id);
    }

    // Filter berdasarkan subklasifikasi_id
    if ($request->has('subklasifikasi_id') && $request->subklasifikasi_id) {
        $produkQuery->where('subklasifikasi_id', $request->subklasifikasi_id);
    }

    $produk = $produkQuery->get();

    $stok_tokobanjaran = Stokpesanan_tokopemalang::with('produk')->get();
    $stokGrouped = $stok_tokobanjaran->groupBy('produk_id')->map(function ($group) {
        $firstItem = $group->first();
        $totalJumlah = $group->sum('jumlah');
        $firstItem->jumlah = $totalJumlah;
        return $firstItem;
    })->values();

    $totalHarga = 0;
    $totalStok = 0;
    $totalSubTotal = 0;

    $produkWithStok = $produk->map(function ($item) use ($stokGrouped, &$totalHarga, &$totalStok, &$totalSubTotal) {
        $stokItem = $stokGrouped->firstWhere('produk_id', $item->id);
        $item->jumlah = $stokItem ? $stokItem->jumlah : 0;
        $subTotal = $item->jumlah * $item->harga;
        $item->subTotal = $subTotal;
        $totalHarga += $item->harga * $item->jumlah;
        $totalStok += $item->jumlah;
        $totalSubTotal += $subTotal;
        return $item;
    });

    // Kirim data subklasifikasi jika ada klasifikasi_id
    $subklasifikasis = $request->has('klasifikasi_id') 
        ? SubKlasifikasi::where('klasifikasi_id', $request->klasifikasi_id)->get() 
        : collect();

    return view('toko_pemalang.stokpesanan_tokopemalang.index', compact('produkWithStok', 'klasifikasis', 'subklasifikasis', 'totalHarga', 'totalStok', 'totalSubTotal'));
}



}


 