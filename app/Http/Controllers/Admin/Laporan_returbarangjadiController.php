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
use Dompdf\Options;

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
    
        // Format tanggal
        $formattedStartDate = $tanggal_retur ? Carbon::parse($tanggal_retur)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';
    
        // Inisialisasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Jika menggunakan URL eksternal untuk gambar atau CSS
    
        $dompdf = new Dompdf($options);
    
        // Memuat konten HTML dari view
        $html = view('admin.laporan_returbarangjadi.print', [
            'stokBarangJadi' => $stokBarangJadi,
            'tanggal_retur' => $tanggal_retur,
            'tanggal_akhir' => $tanggal_akhir,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
        ])->render();
    
        $dompdf->loadHtml($html);
    
        // Set ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');
    
        // Render PDF
        $dompdf->render();
    
        // Menambahkan nomor halaman di kanan bawah
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Page $pageNumber of $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 10;
    
            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);
    
            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah
    
            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });
    
        // Output PDF ke browser
        return $dompdf->stream('Laporan_Retur_BarangJadi.pdf', ['Attachment' => false]);
    }
    
}