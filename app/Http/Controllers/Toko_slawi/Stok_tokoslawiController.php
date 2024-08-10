<?php

namespace App\Http\Controllers\Toko_slawi;

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
use Maatwebsite\Excel\Facades\Excel;

class Stok_tokoslawiController extends Controller{

// public function index()
// {
//     // Ambil data stok_tokoslawi beserta relasi produk
//     $stok_tokoslawi = Stok_tokoslawi::with('produk')->get();

//     // Loop untuk memeriksa status dan mengatur jumlah yang ditampilkan
//     foreach ($stok_tokoslawi as $stok) {
//         if ($stok->status == 'unpost') {
//             $stok->jumlah = 0;
//         }
//     }

//     return view('admin.stok_tokoslawi.index', compact('stok_tokoslawi'));
// }

public function index()
{
    // Ambil data stok_tokoslawi beserta relasi produk
    $stok_tokoslawi = Stok_tokoslawi::with('produk')->where('status', 'posting')->get();

    // Kelompokkan stok berdasarkan produk_id dan jumlahkan jumlahnya
    $stokGrouped = $stok_tokoslawi->groupBy('produk_id')->map(function ($group) {
        // Ambil data produk dari kelompok
        $firstItem = $group->first();
        // Jumlahkan jumlah stok
        $totalJumlah = $group->sum('jumlah');
        // Atur jumlah total dan ambil produk dari item pertama
        $firstItem->jumlah = $totalJumlah;
        return $firstItem;
    });

    // Kumpulkan data hasil pengelompokan ke dalam array
    $stokGrouped = $stokGrouped->values();

    return view('admin.stok_tokoslawi.index', compact('stokGrouped'));
}

public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('admin.stok_tokoslawi.create', compact('produks', 'tokos'));
}


public function store(Request $request)
{
    $request->validate([
        // 'toko_id' => 'required|exists:tokos,id',
        'produk_id' => 'required|array',
        'produk_id.*' => 'exists:produks,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1'
    ]);

    // $toko_id = $request->input('toko_id');
    $produk_ids = $request->input('produk_id');
    $jumlahs = $request->input('jumlah');

    foreach ($produk_ids as $index => $produk_id) {
        Stok_tokoslawi::create([
            // 'toko_id' => $toko_id,
            'produk_id' => $produk_id,
            'status' => 'posting',
            'jumlah' => $jumlahs[$index],
            'tanggal_input' => Carbon::now('Asia/Jakarta'),
        ]);
    }

    return redirect()->route('stok_tokoslawi.index')->with('success', 'Data stok barang berhasil disimpan.');
}



}


 