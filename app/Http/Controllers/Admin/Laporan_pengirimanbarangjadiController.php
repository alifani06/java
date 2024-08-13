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
use App\Models\Stok_barangjadi;
use App\Models\Permintaanproduk;
use App\Models\Detailpermintaanproduk;
use App\Models\Pengiriman_barangjadi;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class Laporan_pengirimanbarangjadiController extends Controller
{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_pengiriman = $request->tanggal_pengiriman;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pengiriman_barangjadi::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

            if ($tanggal_pengiriman && $tanggal_akhir) {
                $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
            } elseif ($tanggal_pengiriman) {
                $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
                $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
            } else {
                // Jika tidak ada filter tanggal, tampilkan data hari ini
                $query->whereDate('tanggal_pengiriman', Carbon::today());
            }

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->get()->groupBy('kode_pengiriman');

            return view('admin.laporan_pengirimanbarangjadi.index', compact('stokBarangJadi'));
    }

    

    // public function printReport(Request $request)
    // {
    //     // Ambil parameter dari request
    //     $tanggalPengiriman = $request->input('tanggal_pengiriman');
    //     $tanggalAkhir = $request->input('tanggal_akhir');
    //     $status = $request->input('status');
    
    //     // Buat query untuk ambil data berdasarkan filter
    //     $query = Pengiriman_barangjadi::query();
    
    //     if ($tanggalPengiriman) {
    //         $query->whereDate('tanggal_pengiriman', '>=', $tanggalPengiriman);
    //     }
    
    //     if ($tanggalAkhir) {
    //         $query->whereDate('tanggal_pengiriman', '<=', $tanggalAkhir);
    //     }
    
    //     if ($status) {
    //         $query->where('status', $status);
    //     }
    
    //     // Ambil data yang telah difilter
    //     $pengirimanBarangJadi = $query->with(['produk.subklasifikasi', 'toko'])->get();
    
    //     // Ambil item pertama untuk informasi toko
    //     $firstItem = $pengirimanBarangJadi->first();
    //     $pdf = FacadePdf::loadView('admin.laporan_pengirimanbarangjadi.print', compact('pengirimanBarangJadi', 'firstItem'));
    
    //     return $pdf->stream('laporan_pengiriman_barang_jadi.pdf');
    // }
    
    public function printReport(Request $request)
    {
        // Ambil parameter dari request
        $tanggalPengiriman = $request->input('tanggal_pengiriman');
        $tanggalAkhir = $request->input('tanggal_akhir');
        $status = $request->input('status');
    
        // Buat query untuk ambil data berdasarkan filter
        $query = Pengiriman_barangjadi::query();
    
        if ($tanggalPengiriman) {
            $query->whereDate('tanggal_pengiriman', '>=', $tanggalPengiriman);
        }
    
        if ($tanggalAkhir) {
            $query->whereDate('tanggal_pengiriman', '<=', $tanggalAkhir);
        }
    
        if ($status) {
            $query->where('status', $status);
        }
    
        // Ambil data yang telah difilter
        $pengirimanBarangJadi = $query->with(['produk.subklasifikasi', 'toko'])->get();
    
        // Kelompokkan data berdasarkan kode_pengiriman
        $groupedData = $pengirimanBarangJadi->groupBy('kode_pengiriman');
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
    
        // Kirim data ke tampilan PDF
        $pdf = FacadePdf::loadView('admin.laporan_pengirimanbarangjadi.print', compact('groupedData', 'firstItem'));
    
        return $pdf->stream('laporan_pengiriman_barang_jadi.pdf');
    }
    

    
}