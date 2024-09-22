<?php

namespace App\Http\Controllers\Toko_banjaran;

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

class Laporan_pemindahanbanjaranController extends Controller{

    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_input = $request->tanggal_input;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Pemindahan_tokoslawi::with('produk.klasifikasi');

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

            return view('toko_banjaran.laporan_pemindahanslawi.index', compact('stokBarangJadi'));
    }


public function posting_pemindahan($id)
{
    // Temukan data pemindahan berdasarkan ID
    $pemindahan = Pemindahan_tokoslawi::findOrFail($id);

    // Cek apakah status saat ini adalah 'unpost'
    if ($pemindahan->status == 'unpost') {
        // Ambil stok yang tersedia untuk produk yang sama
        $stok_items = Stok_tokoslawi::where('produk_id', $pemindahan->produk_id)
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

        return redirect()->route('pemindahan_tokoslawi.index')->with('success', 'Status berhasil diubah menjadi posting, stok telah diperbarui, dan tanggal terima telah disimpan.');
    }

    return redirect()->route('pemindahan_tokoslawi.index')->with('error', 'Status pemindahan tidak valid untuk diubah.');
}


public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Pemindahan_tokoslawi::where('id', $id)->value('kode_pemindahan');
    
    // Jika kode_pemindahan tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_pemindahan yang sama
    $pengirimanBarangJadi = Pemindahan_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_pemindahan', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('toko_banjaran.inquery_pemindahanslawi.show', compact('pengirimanBarangJadi', 'firstItem'));
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

//     return view('toko_banjaran.laporan_pemindahanslawi.print', compact('stokBarangJadi', 'status', 'tanggal_input', 'tanggal_akhir'));
// }

public function printReport(Request $request)
{
    $status = $request->status;
    $tanggal_input = $request->tanggal_pengiriman;
    $tanggal_akhir = $request->tanggal_akhir;

    $query = Pemindahan_tokoslawi::with('produk.klasifikasi');

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
    $pdf = FacadePdf::loadView('toko_banjaran.laporan_pemindahanslawi.print', compact('stokBarangJadi', 'status', 'tanggal_input', 'tanggal_akhir'));

    // Download PDF file
    return $pdf->stream('laporan_pemindahan.pdf');
}
}


 