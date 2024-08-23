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
use App\Models\Retur_barnagjadi;
use Maatwebsite\Excel\Facades\Excel;

class Laporan_pemindahanbarangjadiController extends Controller{

    public function index(Request $request)
    {
        $status = $request->input('status');
        $tanggal_input = $request->input('tanggal_input');
        $tanggal_akhir = $request->input('tanggal_akhir');
    
        $query = Pemindahan_barangjadi::with('produk.klasifikasi');
    
        if ($status) {
            $query->where('status', $status);
        }
    
        // Validasi dan konversi tanggal input
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
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pemindahan
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');
    
        return view('admin.laporan_pemindahanbarangjadi.index', compact('stokBarangJadi'));
    }
    
    // public function printReportpemindahan(Request $request, $id)
    // {
    //     // Ambil filter status dan tanggal dari request
    //     $status = $request->status;
    //     $tanggal_input = $request->tanggal_input;
    //     $tanggal_akhir = $request->tanggal_akhir;
    
    //     // Ambil kode_pemindahan berdasarkan id
    //     $detailStokBarangJadi = Pemindahan_barangjadi::where('id', $id)->value('kode_pemindahan');
    
    //     if (!$detailStokBarangJadi) {
    //         return redirect()->back()->with('error', 'Data tidak ditemukan.');
    //     }
    
    //     // Query untuk mendapatkan data yang sesuai dengan kode_pemindahan dan filter yang diterapkan
    //     $query = Pemindahan_barangjadi::with(['produk.subklasifikasi', 'toko'])
    //                                   ->where('kode_pemindahan', $detailStokBarangJadi);
    
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
    
    //     // Ambil data sesuai filter yang diterapkan
    //     $pengirimanBarangJadi = $query->get();
    
    //     // Ambil item pertama untuk informasi toko
    //     $firstItem = $pengirimanBarangJadi->first();
    
    //     $pdf = FacadePdf::loadView('admin.laporan_pemindahanbarangjadi.print', compact('pengirimanBarangJadi', 'firstItem'));
    
    //     return $pdf->stream('surat_pemindahan_produk.pdf');
    // }
    

    public function printReportpemindahan(Request $request)
    {
        $status = $request->input('status');
        $tanggal_input = $request->input('tanggal_input');
        $tanggal_akhir = $request->input('tanggal_akhir');
    
        $query = Pemindahan_barangjadi::with('produk.klasifikasi');
    
        if ($status) {
            $query->where('status', $status);
        }
    
        // Validasi dan konversi tanggal input
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
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pemindahan
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_pemindahan');
    
        // Mengirim data ke view untuk menghasilkan PDF
        $pdf = FacadePdf::loadView('admin.laporan_pemindahanbarangjadi.print', compact('stokBarangJadi', 'tanggal_input', 'tanggal_akhir'));
    
        // Menentukan nama file dan mengirimkan PDF sebagai response
        return $pdf->stream('laporan_pemindahan_barangjadi.pdf');
    }
    

}


 