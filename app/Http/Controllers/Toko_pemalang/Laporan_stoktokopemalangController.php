<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Models\Stokpesanan_tokobanjaran;
use App\Models\Stokpesanan_tokobumiayu;
use App\Models\Stokpesanan_tokocilacap;
use App\Models\Stokpesanan_tokopemalang;
use App\Models\Stokpesanan_tokoslawi;
use App\Models\Stokpesanan_tokotegal;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;





class Laporan_stoktokopemalangController extends Controller
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
    
        $stok = Stok_tokopemalang::with('produk')->get();
    
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
    
        return view('toko_pemalang.laporan_stoktokopemalang.index', compact('produkWithStok', 'klasifikasis', 'subklasifikasis', 'totalHarga', 'totalStok', 'totalSubTotal'));
    }

    public function stoktokopesananpemalang(Request $request)
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
    
        $stok = Stokpesanan_tokopemalang::with('produk')->get();
    
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
    
        return view('toko_pemalang.laporan_stoktokopemalang.indexpesanan', compact('produkWithStok', 'klasifikasis', 'subklasifikasis', 'totalHarga', 'totalStok', 'totalSubTotal'));
    }
    
    public function semuaStokTokoPemalang(Request $request)
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
    
        // Retrieve stok from both Stok_tokotegal and Stokpesanan_tokotegal
        $stokTokoTegal = Stok_tokopemalang::with('produk')->get();
        $stokPesananTokoTegal = Stokpesanan_tokopemalang::with('produk')->get();
    
        // Combine stok and group by produk_id
        // Gabungkan stok dari dua tabel
        $stokCombined = $stokTokoTegal->concat($stokPesananTokoTegal)
        ->groupBy('produk_id')
        ->map(function ($group) {
            // Jumlahkan stok dari kedua tabel (stok toko dan stok pesanan)
            $totalJumlah = $group->sum('jumlah');
            
            // Buat item baru untuk menyimpan hasil gabungan stok
            $resultItem = $group->first();  // Mengambil item pertama sebagai dasar
            
            // Perbarui jumlah stok dengan total hasil penjumlahan
            $resultItem->jumlah = $totalJumlah;
            
            return $resultItem;
        })->values();

    
    
        // Initialize totals
        $totalHarga = 0;
        $totalStok = 0;
        $totalSubTotal = 0;
    
        // Map stok to produk and calculate totals
        $produkWithStok = $produk->map(function ($item) use ($stokCombined, &$totalHarga, &$totalStok, &$totalSubTotal) {
            $stokItem = $stokCombined->firstWhere('produk_id', $item->id);
            $item->jumlah = $stokItem ? $stokItem->jumlah : 0; // Use the combined jumlah
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
    
        return view('toko_pemalang.laporan_stoktokopemalang.indexall', compact('produkWithStok', 'klasifikasis', 'subklasifikasis', 'totalHarga', 'totalStok', 'totalSubTotal'));
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
            $tokoCabang = 'BANJARAN';
        }
        elseif ($toko_id == '2') {
            $stok = Stok_tokotegal::with('produk')->get();
            $tokoCabang = 'TEGAL';

        }
        elseif ($toko_id == '3') {
            $stok = Stok_tokoslawi::with('produk')->get();
            $tokoCabang = 'SLAWI';

        } 
        elseif ($toko_id == '4') {
            $stok = Stok_tokopemalang::with('produk')->get();
            $tokoCabang = 'PEMALANG';

        }
        elseif ($toko_id == '5') {
            $stok = Stok_tokobumiayu::with('produk')->get();
            $tokoCabang = 'BUMIAYU';

        }
        elseif ($toko_id == '6') {
            $stok = Stok_tokocilacap::with('produk')->get();
            $tokoCabang = 'CILACAP';

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
        $html = view('toko_pemalang.laporan_stoktokopemalang.print', [
            'produkWithStok' => $produkWithStok,
            'klasifikasis' => $klasifikasis,
            'subklasifikasis' => $subklasifikasis,
            'totalHarga' => $totalHarga,
            'totalStok' => $totalStok,
            'totalSubTotal' => $totalSubTotal,
            'tokoCabang' => $tokoCabang, // Pass the cabang name

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

    public function printReportstokpesananpemalang(Request $request)
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
            $stok = Stokpesanan_tokobanjaran::with('produk')->get();
            $tokoCabang = 'BANJARAN';
        }
        elseif ($toko_id == '2') {
            $stok = Stokpesanan_tokotegal::with('produk')->get();
            $tokoCabang = 'TEGAL';
        }
        elseif ($toko_id == '3') {
            $stok = Stokpesanan_tokoslawi::with('produk')->get();
            $tokoCabang = 'SLAWI';
        } 
        elseif ($toko_id == '4') {
            $stok = Stokpesanan_tokopemalang::with('produk')->get();
            $tokoCabang = 'PEMALANG';
        }
        elseif ($toko_id == '5') {
            $stok = Stokpesanan_tokobumiayu::with('produk')->get();
            $tokoCabang = 'BUMIAYU';
        }
        elseif ($toko_id == '6') {
            $stok = Stokpesanan_tokocilacap::with('produk')->get();
            $tokoCabang = 'CILACAP';
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
        $html = view('toko_pemalang.laporan_stoktokopemalang.printpesanan', [
            'produkWithStok' => $produkWithStok,
            'klasifikasis' => $klasifikasis,
            'subklasifikasis' => $subklasifikasis,
            'totalHarga' => $totalHarga,
            'totalStok' => $totalStok,
            'totalSubTotal' => $totalSubTotal,
            'tokoCabang' => $tokoCabang,
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
    
    public function printReportsemuastokpemalang(Request $request)
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
    
        // Retrieve stok from both Stok_tokotegal and Stokpesanan_tokotegal
        $stokTokoTegal = Stok_tokopemalang::with('produk')->get();
        $stokPesananTokoTegal = Stokpesanan_tokopemalang::with('produk')->get();
    
        // Combine stok and group by produk_id
        $stokCombined = $stokTokoTegal->concat($stokPesananTokoTegal)
            ->groupBy('produk_id')
            ->map(function ($group) {
                $totalJumlah = $group->sum('jumlah');
                $resultItem = $group->first();  // Mengambil item pertama sebagai dasar
                $resultItem->jumlah = $totalJumlah;  // Menyimpan hasil gabungan stok
                return $resultItem;
            })->values();
    
        // Initialize totals
        $totalHarga = 0;
        $totalStok = 0;
        $totalSubTotal = 0;
    
        // Map stok to produk and calculate totals
        $produkWithStok = $produk->map(function ($item) use ($stokCombined, &$totalHarga, &$totalStok, &$totalSubTotal) {
            $stokItem = $stokCombined->firstWhere('produk_id', $item->id);
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
    
        // Inisialisasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        $dompdf = new Dompdf($options);
    
        // Memuat konten HTML dari view
        $html = view('toko_pemalang.laporan_stoktokopemalang.printsemuastok', [
            'produkWithStok' => $produkWithStok,
            'klasifikasis' => $klasifikasis,
            'subklasifikasis' => $subklasifikasis,
            'totalHarga' => $totalHarga,
            'totalStok' => $totalStok,
            'totalSubTotal' => $totalSubTotal,
            'tokoCabang' => 'BANJARAN', // Ini harus ada untuk menyertakan variabel

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
        return $dompdf->stream('laporan_semuastoktoko.pdf', ['Attachment' => false]);
    }
}