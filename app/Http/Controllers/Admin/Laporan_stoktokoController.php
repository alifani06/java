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
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokotegal;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokopemalang;
use App\Models\Stok_tokocilacap;
use App\Models\Stok_tokobumiayu;
use App\Models\Permintaanproduk;
use App\Models\Detailpermintaanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokTokoExport;
use App\Exports\StokBarangExportBM;





class Laporan_stoktokoController extends Controller
{

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
    
        // Filter berdasarkan toko_id
        $toko_id = $request->get('toko_id');
        switch ($toko_id) {
            case '1':
                $stok = Stok_tokobanjaran::with('produk')->get();
                break;
            case '2':
                $stok = Stok_tokotegal::with('produk')->get();
                break;
            case '3':
                $stok = Stok_tokoslawi::with('produk')->get();
                break;
            case '4':
                $stok = Stok_tokopemalang::with('produk')->get();
                break;
            case '5':
                $stok = Stok_tokobumiayu::with('produk')->get();
                break;
            case '6':
                $stok = Stok_tokocilacap::with('produk')->get();
                break;
            default:
                $stok = collect();
                break;
        }
    
        $stokGrouped = $stok->groupBy('produk_id')->map(function ($group) {
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
    
        return view('admin.laporan_stoktoko.index', compact('produkWithStok', 'klasifikasis', 'subklasifikasis', 'totalHarga', 'totalStok', 'totalSubTotal'));
    }
    
    public function printReport(Request $request)
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
    
        // Filter berdasarkan toko_id
        $toko_id = $request->get('toko_id');
        if ($toko_id == '1') {
            $stok = Stok_tokobanjaran::with('produk')->get();
        }
        elseif ($toko_id == '2') {
            $stok = Stok_tokotegal::with('produk')->get();
        }
        elseif ($toko_id == '3') {
            $stok = Stok_tokoslawi::with('produk')->get();
        } 
        elseif ($toko_id == '4') {
            $stok = Stok_tokopemalang::with('produk')->get();
        }
        elseif ($toko_id == '5') {
            $stok = Stok_tokobumiayu::with('produk')->get();
        }
        elseif ($toko_id == '6') {
            $stok = Stok_tokocilacap::with('produk')->get();
        } else {
            $stok = collect();
        }
    
        $stokGrouped = $stok->groupBy('produk_id')->map(function ($group) {
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
    
        $subklasifikasis = $request->has('klasifikasi_id') 
            ? SubKlasifikasi::where('klasifikasi_id', $request->klasifikasi_id)->get() 
            : collect();
    
        // Inisialisasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        $dompdf = new Dompdf($options);
    
        // Memuat konten HTML dari view
        $html = view('admin.laporan_stoktoko.print', [
            'produkWithStok' => $produkWithStok,
            'klasifikasis' => $klasifikasis,
            'subklasifikasis' => $subklasifikasis,
            'totalHarga' => $totalHarga,
            'totalStok' => $totalStok,
            'totalSubTotal' => $totalSubTotal,
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
        return $dompdf->stream('laporan_stoktoko.pdf', ['Attachment' => false]);
    }

    public function exportExcelStok(Request $request)
    {
        return Excel::download(new StokTokoExport($request), 'BK.xlsx');
    }
    
}