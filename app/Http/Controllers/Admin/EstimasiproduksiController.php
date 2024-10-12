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
    // Mengambil data dengan relasi menggunakan with
    $permintaanProduks = PermintaanProduk::with(['detailpermintaanproduks.toko']) // Memanggil relasi dari tabel terkait
        ->get();

    // Mengirim data ke view
    return view('admin.estimasi_produksi.index', compact('permintaanProduks'));
}

public function getDetailPermintaanProduk(Request $request)
{
    $kodePermintaan = $request->kode_permintaan;

    // Ambil data permintaan produk berdasarkan kode_permintaan
    $detailPermintaanProduks = DetailPermintaanProduk::whereHas('permintaanProduk', function ($query) use ($kodePermintaan) {
        $query->where('kode_permintaan', $kodePermintaan);
    })->with('produk')->get();

    // Kembalikan data dalam bentuk JSON
    return response()->json($detailPermintaanProduks);
}


// public function updateDetailPermintaanProduk(Request $request)
// {
//     // Ambil data yang dikirim dari AJAX
//     $updateData = $request->updateData;

//     // Loop melalui setiap produk dan update jumlah berdasarkan produk_id dan permintaanproduk_id
//     foreach ($updateData as $data) {
//         // Cari detail permintaan produk berdasarkan produk_id dan permintaanproduk_id
//         $detailPermintaanProduk = DetailPermintaanProduk::where('produk_id', $data['produk_id'])
//             ->where('permintaanproduk_id', $data['permintaanproduk_id']) // Tambahkan kriteria permintaanproduk_id
//             ->first();

//         if ($detailPermintaanProduk) {
//             // Update jumlah
//             $detailPermintaanProduk->jumlah = $data['jumlah'];
//             $detailPermintaanProduk->save();
//         }
//     }

//     // Kembalikan respon sukses
//     return response()->json(['success' => true]);
// }


// public function updateDetailPermintaanProduk(Request $request)
// {
//     // Ambil data yang dikirim dari form
//     $updateData = $request->updateData;

//     // Loop melalui setiap produk dan update jumlah berdasarkan produk_id dan permintaanproduk_id
//     foreach ($updateData as $data) {
//         // Cari detail permintaan produk berdasarkan produk_id dan permintaanproduk_id
//         $detailPermintaanProduk = DetailPermintaanProduk::where('produk_id', $data['produk_id'])
//             ->where('permintaanproduk_id', $data['permintaanproduk_id'])
//             ->first();

//         if ($detailPermintaanProduk) {
//             // Update jumlah
//             $detailPermintaanProduk->jumlah = $data['jumlah'];
//             $detailPermintaanProduk->save();
//         }
//     }

//     // Alihkan ke halaman show
//     return redirect()->route('estimasi_produksi.show', ['id' => $request->permintaanproduk_id])->with('success', 'Detail permintaan produk berhasil diperbarui.');
// }

public function updateDetailPermintaanProduk(Request $request)
{
    // Ambil data yang dikirim dari form
    $updateData = $request->updateData;

    // Loop melalui setiap produk dan update jumlah berdasarkan produk_id dan permintaanproduk_id
    foreach ($updateData as $data) {
        // Cari detail permintaan produk berdasarkan produk_id dan permintaanproduk_id
        $detailPermintaanProduk = DetailPermintaanProduk::where('produk_id', $data['produk_id'])
            ->where('permintaanproduk_id', $data['permintaanproduk_id'])
            ->first();

        if ($detailPermintaanProduk) {
            // Update jumlah
            $detailPermintaanProduk->jumlah = $data['jumlah'];
            $detailPermintaanProduk->save();
        }
    }

    // Alihkan ke halaman show dengan URL
    return response()->json([
        'success' => true,
        'redirectUrl' => route('estimasi_produksi.show', ['estimasi_produksi' => $request->permintaanproduk_id]) // Gunakan permintaanproduk_id yang diterima dari request
    ]);
}





public function show($id)
{
    $permintaanProduk = PermintaanProduk::find($id);
    $detailPermintaanProduks = DetailPermintaanProduk::with('toko')->where('permintaanproduk_id', $id)->get();

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Menghitung total jumlah per klasifikasi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data Subklasifikasi berdasarkan Klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    // Mengambil nama toko dari salah satu detail permintaan produk
    $toko = $detailPermintaanProduks->first()->toko;

    return view('admin.estimasi_produksi.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi', 'toko'));
}







}