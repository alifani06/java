<?php

namespace App\Http\Controllers\Toko_slawi;

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
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Laporan_stokbarangjadiController extends Controller
{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_input = $request->tanggal_input;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Stok_barangjadi::with('produk.klasifikasi');

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
            $stokBarangJadi = $query->get()->groupBy('kode_input');

            return view('admin.laporan_stokbarangjadi.index', compact('stokBarangJadi'));
    }

    // public function printReport(Request $request)
    // {
    //     $status = $request->get('status');
    //     $tanggal_input = $request->get('tanggal_input');
    //     $tanggal_akhir = $request->get('tanggal_akhir');
    
    //     $query = Stok_barangjadi::with('stok_barangjadi.produk.klasifikasi');
    
    //     if ($status) {
    //         $query->where('status', $status);
    //     }
    
    //     if ($tanggal_input) {
    //         $query->whereDate('tanggal_input', '>=', $tanggal_input);
    //     }
    
    //     if ($tanggal_akhir) {
    //         $query->whereDate('tanggal_input', '<=', $tanggal_akhir);
    //     }
    
    //     $stokBarangJadi = $query->get();
    
    //     $pdf = FacadePdf::loadView('admin.laporan_stokbarangjadi.print', compact('stokBarangJadi'));

    //     return $pdf->stream('surat_permintaan_produk.pdf');
    // }
    public function printReport(Request $request)
    {
        $status = $request->get('status');
        $tanggal_input = $request->get('tanggal_input');
        $tanggal_akhir = $request->get('tanggal_akhir');
    
        $query = Stok_barangjadi::with(['produk.klasifikasi']);
    
        if ($status) {
            $query->where('status', $status);
        }
    
        if ($tanggal_input) {
            $query->whereDate('tanggal_input', '>=', $tanggal_input);
        }
    
        if ($tanggal_akhir) {
            $query->whereDate('tanggal_input', '<=', $tanggal_akhir);
        }
    
        $stokBarangJadi = $query->get();
    
        $pdf = FacadePdf::loadView('admin.laporan_stokbarangjadi.print', compact('stokBarangJadi'));
    
        return $pdf->stream('laporan_stok_barang_jadi.pdf');
    }
    
}