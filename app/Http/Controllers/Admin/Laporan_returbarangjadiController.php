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
use App\Models\Retur_barangjadi;
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


class Laporan_returbarangjadiController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_retur = $request->tanggal_retur;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        // Query dasar
        $query = Retur_barangjadi::with('produk.klasifikasi');
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal
        if ($tanggal_retur && $tanggal_akhir) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
        } elseif ($tanggal_retur) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $query->where('tanggal_retur', '>=', $tanggal_retur);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_retur', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_retur', Carbon::today());
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_retur
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');
    
        // Ambil semua data toko untuk dropdown
        $tokos = Toko::all();
        
        // Ambil semua data klasifikasi untuk dropdown
        $klasifikasis = Klasifikasi::all();
    
        return view('admin.laporan_returbarangjadi.index', compact('stokBarangJadi', 'tokos', 'klasifikasis'));
    }
    
            
    public function printReportretur(Request $request)
    {
        $status = $request->status;
        $tanggal_retur = $request->tanggal_retur;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        // Query dasar
        $query = Retur_barangjadi::with('produk.klasifikasi');
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal
        if ($tanggal_retur && $tanggal_akhir) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
        } elseif ($tanggal_retur) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $query->where('tanggal_retur', '>=', $tanggal_retur);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_retur', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_retur', Carbon::today());
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_retur
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');
    
        // Load view untuk PDF
        $pdf = FacadePdf::loadView('admin.laporan_returbarangjadi.print', compact('stokBarangJadi', 'tanggal_retur', 'tanggal_akhir'));
    
        // Set properti tambahan PDF
        $pdf->setPaper('A4', 'potrait');
    
        // Return hasil cetak PDF
        return $pdf->stream('Laporan_Retur_BarangJadi.pdf');
    }
    
    
}