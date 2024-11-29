<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Models\Pemindahan_tokopemalang;
use App\Models\Pemindahan_tokopemalangmasuk;
use App\Models\Retur_barnagjadi;
use Maatwebsite\Excel\Facades\Excel;

class Laporan_pemindahanpemalangController extends Controller{


    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        // Query untuk pemindahan_bumiayu
        $queryPemalang = Pemindahan_tokopemalang::with('produk.klasifikasi');
    
        // Query untuk pemindahan_bumiayumasuk
        $queryMasuk = Pemindahan_tokopemalangmasuk::with('produk.klasifikasi');
    
        if ($status) {
            $queryPemalang->where('status', $status);
            $queryMasuk->where('status', $status);
        }
    
        if ($tanggal_input && $tanggal_akhir) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $queryPemalang->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
            $queryMasuk->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        } elseif ($tanggal_input) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $queryPemalang->where('tanggal_input', '>=', $tanggal_input);
            $queryMasuk->where('tanggal_input', '>=', $tanggal_input);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $queryPemalang->where('tanggal_input', '<=', $tanggal_akhir);
            $queryMasuk->where('tanggal_input', '<=', $tanggal_akhir);
        } else {
            $queryPemalang->whereDate('tanggal_input', Carbon::today());
            $queryMasuk->whereDate('tanggal_input', Carbon::today());
        }
    
        // Ambil data, urutkan, dan gabungkan
        $stokPemalang = $queryPemalang->orderBy('created_at', 'desc')->get();
        $stokMasuk = $queryMasuk->orderBy('created_at', 'desc')->get();
    
        // Gabungkan koleksi berdasarkan kode_pemindahan
        $stokBarangJadi = $stokPemalang->merge($stokMasuk)->groupBy('kode_pemindahan');
    
        return view('toko_pemalang.laporan_pemindahanpemalang.index', compact('stokBarangJadi'));
    }
    


public function printReportpemindahanPml(Request $request)
{
    $status = $request->input('status');
    $tanggal_input = $request->input('tanggal_input');
    $tanggal_akhir = $request->input('tanggal_akhir');

    // Query untuk pemindahan_bumiayu
    $queryPemalang = Pemindahan_tokopemalang::with('produk.klasifikasi');

    // Query untuk pemindahan_bumiayumasuk
    $queryMasuk = Pemindahan_tokopemalangmasuk::with('produk.klasifikasi');

    if ($status) {
        $queryPemalang->where('status', $status);
        $queryMasuk->where('status', $status);
    }

    if ($tanggal_input && $tanggal_akhir) {
        $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $queryPemalang->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        $queryMasuk->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
    } elseif ($tanggal_input) {
        $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
        $queryPemalang->where('tanggal_input', '>=', $tanggal_input);
        $queryMasuk->where('tanggal_input', '>=', $tanggal_input);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $queryPemalang->where('tanggal_input', '<=', $tanggal_akhir);
        $queryMasuk->where('tanggal_input', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal, tampilkan data hari ini
        $queryPemalang->whereDate('tanggal_input', Carbon::today());
        $queryMasuk->whereDate('tanggal_input', Carbon::today());
    }

    // Ambil data dari kedua query
    $stokPemalang = $queryPemalang->orderBy('created_at', 'desc')->get();
    $stokMasuk = $queryMasuk->orderBy('created_at', 'desc')->get();

    // Gabungkan data
    $stokBarangJadi = $stokPemalang->merge($stokMasuk)->groupBy('kode_pemindahan');

    // Format tanggal untuk header laporan
    $formattedStartDate = $tanggal_input ? Carbon::parse($tanggal_input)->format('d-m-Y') : 'N/A';
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

    // Inisialisasi DOMPDF
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new \Dompdf\Dompdf($options);

    // Muat konten HTML dari view
    $html = view('toko_pemalang.laporan_pemindahanpemalang.print', [
        'stokBarangJadi' => $stokBarangJadi,
        'tanggal_input' => $tanggal_input,
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

        // Hitung posisi teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);
        $x = $canvas->get_width() - $width - 10;
        $y = $canvas->get_height() - 15;

        // Tambahkan teks
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $dompdf->stream('laporan_pemindahan_barangjadi.pdf', ['Attachment' => false]);
}
}


 