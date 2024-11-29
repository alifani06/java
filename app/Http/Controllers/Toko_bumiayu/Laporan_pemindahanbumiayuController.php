<?php

namespace App\Http\Controllers\Toko_bumiayu;

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
use App\Models\Pemindahan_tokobumiayu;
use App\Models\Pemindahan_tokobumiayumasuk;
use App\Models\Retur_barnagjadi;
use App\Models\Stok_tokobumiayu;
use Maatwebsite\Excel\Facades\Excel;

class Laporan_pemindahanbumiayuController extends Controller{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        // Query untuk pemindahan_bumiayu
        $queryBumiayu = Pemindahan_tokobumiayu::with('produk.klasifikasi');
    
        // Query untuk pemindahan_bumiayumasuk
        $queryMasuk = Pemindahan_tokobumiayumasuk::with('produk.klasifikasi');
    
        if ($status) {
            $queryBumiayu->where('status', $status);
            $queryMasuk->where('status', $status);
        }
    
        if ($tanggal_input && $tanggal_akhir) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $queryBumiayu->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
            $queryMasuk->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        } elseif ($tanggal_input) {
            $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
            $queryBumiayu->where('tanggal_input', '>=', $tanggal_input);
            $queryMasuk->where('tanggal_input', '>=', $tanggal_input);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $queryBumiayu->where('tanggal_input', '<=', $tanggal_akhir);
            $queryMasuk->where('tanggal_input', '<=', $tanggal_akhir);
        } else {
            $queryBumiayu->whereDate('tanggal_input', Carbon::today());
            $queryMasuk->whereDate('tanggal_input', Carbon::today());
        }
    
        // Ambil data, urutkan, dan gabungkan
        $stokBumiayu = $queryBumiayu->orderBy('created_at', 'desc')->get();
        $stokMasuk = $queryMasuk->orderBy('created_at', 'desc')->get();
    
        // Gabungkan koleksi berdasarkan kode_pemindahan
        $stokBarangJadi = $stokBumiayu->merge($stokMasuk)->groupBy('kode_pemindahan');
    
        return view('toko_bumiayu.laporan_pemindahanbumiayu.index', compact('stokBarangJadi'));
    }
    


public function posting_pemindahan($id)
{
    // Temukan data pemindahan berdasarkan ID
    $pemindahan = Pemindahan_tokobumiayu::findOrFail($id);

    // Cek apakah status saat ini adalah 'unpost'
    if ($pemindahan->status == 'unpost') {
        // Ambil stok yang tersedia untuk produk yang sama
        $stok_items = Stok_tokobumiayu::where('produk_id', $pemindahan->produk_id)
            ->where('jumlah', '>', 0)
            ->orderBy('jumlah', 'asc')
            ->get();

        $jumlah_yang_dibutuhkan = $pemindahan->jumlah;

        foreach ($stok_items as $stok) {
            if ($jumlah_yang_dibutuhkan <= 0) {
                break;
            }

            if ($stok->jumlah >= $jumlah_yang_dibutuhkan) {
                $stok->jumlah -= $jumlah_yang_dibutuhkan;
                $stok->save();
                $jumlah_yang_dibutuhkan = 0;
            } else {
                $jumlah_yang_dibutuhkan -= $stok->jumlah;
                $stok->jumlah = 0;
                $stok->save();
            }
        }

        // Cek jika jumlah yang dibutuhkan masih lebih dari 0 setelah mengupdate stok
        if ($jumlah_yang_dibutuhkan > 0) {
            return redirect()->back()->with('error', 'Jumlah stok untuk produk ' . $pemindahan->produk->nama_produk . ' tidak mencukupi.');
        }

        // Update status dan tanggal terima pada tabel pemindahan_tokoslawi
        $pemindahan->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        // Update status dan tanggal terima pada tabel pemindahan_barangjadis
        Pemindahan_barangjadi::where('kode_pemindahan', $pemindahan->kode_pemindahan)
            ->update([
                'status' => 'posting',
                'tanggal_terima' => Carbon::now('Asia/Jakarta'),
            ]);

        return redirect()->route('pemindahan_tokobumiayu.index')->with('success', 'Status berhasil diubah menjadi posting, stok telah diperbarui, dan tanggal terima telah disimpan.');
    }

    return redirect()->route('pemindahan_tokobumiayu.index')->with('error', 'Status pemindahan tidak valid untuk diubah.');
}


public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Pemindahan_tokobumiayu::where('id', $id)->value('kode_pemindahan');
    
    // Jika kode_pemindahan tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_pemindahan yang sama
    $pengirimanBarangJadi = Pemindahan_tokobumiayu::with(['produk.subklasifikasi', 'toko'])->where('kode_pemindahan', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('toko_bumiayu.inquery_pemindahanbumiayu.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function printReportpemindahanBmy(Request $request)
{
    $status = $request->input('status');
    $tanggal_input = $request->input('tanggal_input');
    $tanggal_akhir = $request->input('tanggal_akhir');

    // Query untuk pemindahan_bumiayu
    $queryBumiayu = Pemindahan_tokobumiayu::with('produk.klasifikasi');

    // Query untuk pemindahan_bumiayumasuk
    $queryMasuk = Pemindahan_tokobumiayumasuk::with('produk.klasifikasi');

    if ($status) {
        $queryBumiayu->where('status', $status);
        $queryMasuk->where('status', $status);
    }

    if ($tanggal_input && $tanggal_akhir) {
        $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $queryBumiayu->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
        $queryMasuk->whereBetween('tanggal_input', [$tanggal_input, $tanggal_akhir]);
    } elseif ($tanggal_input) {
        $tanggal_input = Carbon::parse($tanggal_input)->startOfDay();
        $queryBumiayu->where('tanggal_input', '>=', $tanggal_input);
        $queryMasuk->where('tanggal_input', '>=', $tanggal_input);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $queryBumiayu->where('tanggal_input', '<=', $tanggal_akhir);
        $queryMasuk->where('tanggal_input', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal, tampilkan data hari ini
        $queryBumiayu->whereDate('tanggal_input', Carbon::today());
        $queryMasuk->whereDate('tanggal_input', Carbon::today());
    }

    // Ambil data dari kedua query
    $stokBumiayu = $queryBumiayu->orderBy('created_at', 'desc')->get();
    $stokMasuk = $queryMasuk->orderBy('created_at', 'desc')->get();

    // Gabungkan data
    $stokBarangJadi = $stokBumiayu->merge($stokMasuk)->groupBy('kode_pemindahan');

    // Format tanggal untuk header laporan
    $formattedStartDate = $tanggal_input ? Carbon::parse($tanggal_input)->format('d-m-Y') : 'N/A';
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

    // Inisialisasi DOMPDF
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new \Dompdf\Dompdf($options);

    // Muat konten HTML dari view
    $html = view('toko_bumiayu.laporan_pemindahanbumiayu.print', [
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


 