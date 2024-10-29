<?php

namespace App\Http\Controllers\Toko_cilacap;

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
use App\Models\Retur_tokoslawi;
use App\Models\Pemindahan_tokoslawi;
use App\Models\Pemindahan_barangjadi;
use App\Models\Retur_barangjadi;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Pemindahan_tokobanjaran;
use App\Models\Pemindahan_tokocilacap;
use App\Models\Pemindahan_tokotegal;
use App\Models\Retur_barnagjadi;
use Maatwebsite\Excel\Facades\Excel;

class Laporan_pemindahancilacapController extends Controller{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_input = $request->tanggal_input;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pemindahan_tokocilacap::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

            if ($tanggal_input && $tanggal_akhir) {
                $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
            } elseif ($tanggal_input) {
                $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
                $query->where('tanggal_input', '>=', $tanggal_input);
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->where('tanggal_input', '<=', $tanggal_akhir);
            } else {
                // Jika tidak ada filter tanggal, tampilkan data hari ini
                $query->whereDate('tanggal_input', Carbon::today());
            }

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');

            return view('toko_cilacap.laporan_pemindahancilacap.index', compact('stokBarangJadi'));
    }





public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Pemindahan_tokocilacap::where('id', $id)->value('kode_pemindahan');
    
    // Jika kode_pemindahan tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_pemindahan yang sama
    $pengirimanBarangJadi = Pemindahan_tokocilacap::with(['produk.subklasifikasi', 'toko'])->where('kode_pemindahan', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('toko_cilacap.inquery_pemindahancilacap.show', compact('pengirimanBarangJadi', 'firstItem'));
}

// public function printReport(Request $request)
// {
//     $status = $request->status;
//     $tanggal_input = $request->tanggal_pengiriman;
//     $tanggal_akhir = $request->tanggal_akhir;

//     $query = Pemindahan_tokoslawi::with('produk.klasifikasi');

//     if ($status) {
//         $query->where('status', $status);
//     }

//     if ($tanggal_input && $tanggal_akhir) {
//         $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
//         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
//         $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
//     } elseif ($tanggal_input) {
//         $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
//         $query->where('tanggal_input', '>=', $tanggal_input);
//     } elseif ($tanggal_akhir) {
//         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
//         $query->where('tanggal_input', '<=', $tanggal_akhir);
//     } else {
//         // Jika tidak ada filter tanggal, tampilkan data hari ini
//         $query->whereDate('tanggal_input', Carbon::today());
//     }

//     // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
//     $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');

//     return view('toko_cilacap.laporan_pemindahancilacap.print', compact('stokBarangJadi', 'status', 'tanggal_input', 'tanggal_akhir'));
// }

public function printReport(Request $request)
{
    $status = $request->status;
    $tanggal_input = $request->tanggal_pengiriman;
    $tanggal_akhir = $request->tanggal_akhir;

    $query = Pemindahan_tokocilacap::with('produk.klasifikasi');

    if ($status) {
        $query->where('status', $status);
    }

    if ($tanggal_input && $tanggal_akhir) {
        $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
    } elseif ($tanggal_input) {
        $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
        $query->where('tanggal_input', '>=', $tanggal_input);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->where('tanggal_input', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal, tampilkan data hari ini
        $query->whereDate('tanggal_input', Carbon::today());
    }

    // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
    $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');

    // Generate PDF
    $pdf = FacadePdf::loadView('toko_cilacap.laporan_pemindahancilacap.print', compact('stokBarangJadi', 'status', 'tanggal_input', 'tanggal_akhir'));

    // Download PDF file
    return $pdf->stream('laporan_pemindahan.pdf');
}
}


 