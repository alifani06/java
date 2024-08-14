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


    public function index()
    {
        $today = \Carbon\Carbon::today();
        $tomorrow = $today->copy()->addDay();  // Mendapatkan tanggal besok
    
        $pemesananProduk = Detailpemesananproduk::with(['pemesananproduk.toko'])
            ->whereHas('pemesananproduk', function($query) use ($tomorrow) {
                $query->whereDate('tanggal_kirim', $tomorrow);
            })
            ->get();
    
        $permintaanProduks = PermintaanProduk::with(['detailpermintaanproduks.toko'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('admin.estimasi_produksi.index', compact('permintaanProduks', 'pemesananProduk'));
    }
    
}