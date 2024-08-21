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

class Inquery_pemindahanbanjaranController extends Controller{

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

            return view('toko_banjaran.inquery_pemindahanbanjaran.index', compact('stokBarangJadi'));
    }



// public function unpost_retur($id)
// {
//     // Ambil data stok barang berdasarkan ID
//     $stok = Retur_tokoslawi::where('id', $id)->first();

//     // Pastikan data ditemukan
//     if (!$stok) {
//         return back()->with('error', 'Data tidak ditemukan.');
//     }

//     // Ambil kode_input dari stok yang diambil
//     $kodeInput = $stok->kode_retur;

//     // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
//     Retur_tokoslawi::where('kode_retur', $kodeInput)->update([
//         'status' => 'unpost'
//     ]);
//     return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
// }


// public function posting_retur($id)
// {
//    // Ambil data Retur_tokoslawi berdasarkan ID
//     $pengiriman = Retur_tokoslawi::where('id', $id)->first();

//     // Pastikan data ditemukan
//     if (!$pengiriman) {
//         return response()->json(['error' => 'Data tidak ditemukan.'], 404);
//     }

//     // Ambil kode_retur dari pengiriman yang diambil
//     $kodePengiriman = $pengiriman->kode_retur;

//     // Update status untuk semua Retur_tokoslawi dengan kode_retur yang sama
//     Retur_tokoslawi::where('kode_retur', $kodePengiriman)->update([
//         'status' => 'posting'
//     ]);

//     // Update status untuk semua stok_tokoslawi terkait dengan Retur_tokoslawi_id
//     Stok_tokoslawi::where('pengiriman_barangjadi_id', $id)->update([
//         'status' => 'posting'
//     ]);

//     return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_retur yang sama.']);
// }

// public function posting_pemindahan($id)
// {
//     $pemindahan = Pemindahan_tokoslawi::findOrFail($id);

//     if ($pemindahan->status == 'unpost') {
//         $stok_items = Stok_tokoslawi::where('produk_id', $pemindahan->produk_id)
//             ->where('jumlah', '>', 0)
//             ->orderBy('jumlah', 'asc')
//             ->get();

//         $jumlah_yang_dibutuhkan = $pemindahan->jumlah;

//         foreach ($stok_items as $stok) {
//             if ($jumlah_yang_dibutuhkan <= 0) {
//                 break;
//             }

//             if ($stok->jumlah >= $jumlah_yang_dibutuhkan) {
//                 $stok->jumlah -= $jumlah_yang_dibutuhkan;
//                 $stok->save();
//                 $jumlah_yang_dibutuhkan = 0;
//             } else {
//                 $jumlah_yang_dibutuhkan -= $stok->jumlah;
//                 $stok->jumlah = 0;
//                 $stok->save();
//             }
//         }

//         if ($jumlah_yang_dibutuhkan > 0) {
//             return redirect()->back()->with('error', 'Jumlah stok untuk produk ' . $pemindahan->produk->nama_produk . ' tidak mencukupi.');
//         }

//         $pemindahan->update([
//             'status' => 'posting',
//             'tanggal_terima' => Carbon::now('Asia/Jakarta'),
//         ]);

//         return redirect()->route('pemindahan_tokoslawi.index')->with('success', 'Status berhasil diubah menjadi posting, stok telah diperbarui, dan tanggal terima telah disimpan.');
//     }

//     return redirect()->route('pemindahan_tokoslawi.index')->with('error', 'Status pemindahan tidak valid untuk diubah.');
// }

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
    
    return view('toko_banjaran.inquery_pemindahanbanjaran.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function print($id)
    {
        $detailStokBarangJadi = Pemindahan_tokoslawi::where('id', $id)->value('kode_pemindahan');
    
        // Jika kode_pemindahan tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pemindahan yang sama
        $pengirimanBarangJadi = Pemindahan_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_pemindahan', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        $pdf = FacadePdf::loadView('toko_banjaran.inquery_pemindahanbanjaran.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_banjaran.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
    }

}


 