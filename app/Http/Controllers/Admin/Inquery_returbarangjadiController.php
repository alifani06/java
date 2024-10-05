<?php

namespace App\Http\Controllers\Admin;

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
use App\Models\Retur_barangjadi;
use App\Models\Pemusnahan_barangjadi;
use App\Models\Toko;
use App\Models\Stok_retur;
use App\Models\Stok_tokobanjaran;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Retur_barnagjadi;
use App\Models\Retur_tokobanjaran;
use Maatwebsite\Excel\Facades\Excel;

class Inquery_returbarangjadiController extends Controller{


    public function index(Request $request)
    {
            $status = $request->status;
            $tanggal_retur = $request->tanggal_retur;
            $tanggal_akhir = $request->tanggal_akhir;

            $query = Retur_barangjadi::with('produk.klasifikasi');

            if ($status) {
                $query->where('status', $status);
            }

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

            // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
            $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');

            return view('admin.inquery_returbarangjadi.index', compact('stokBarangJadi'));
    }



public function kode()
{
    $prefix = 'PEM';
    $year = date('y'); // Dua digit terakhir dari tahun
    $date = date('md'); // Format bulan dan hari: MMDD

    // Mengambil kode retur terakhir yang dibuat pada hari yang sama
    $lastBarang = Pemusnahan_barangjadi::whereDate('tanggal_retur', Carbon::today())
                                  ->orderBy('kode_pemusnahan', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_pemusnahan;
        $lastNum = (int) substr($lastCode, strlen($prefix . $year . $date)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
    $newCode = $prefix . $year . $date . $formattedNum;
    return $newCode;
}


// public function unpost_retur($id)
// {
//     // Ambil data stok barang berdasarkan ID
//     $stok = Retur_barangjadi::where('id', $id)->first();

//     // Pastikan data ditemukan
//     if (!$stok) {
//         return back()->with('error', 'Data tidak ditemukan.');
//     }

//     // Ambil kode_input dari stok yang diambil
//     $kodeInput = $stok->kode_retur;

//     // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
//     Retur_barangjadi::where('kode_retur', $kodeInput)->update([
//         'status' => 'unpost',
//     ]);
    
//     // Update status untuk semua retur_tokoslawi dengan kode_retur yang sama
//     Retur_tokoslawi::where('kode_retur', $kodeInput)->update([
//         'status' => 'unpost'
//     ]);

//     return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
// }



    public function posting_retur($id)
    {
        $kode = $this->kode();

        // Ambil data Retur_barangjadi berdasarkan ID
        $pengiriman = Retur_barangjadi::where('id', $id)->first();

        // Pastikan data ditemukan
        if (!$pengiriman) {
            return response()->json(['error' => 'Data tidak ditemukan.'], 404);
        }

        // Ambil kode_retur dari pengiriman yang diambil
        $kodePengiriman = $pengiriman->kode_retur;

        // Ambil data produk terkait dengan kode_retur
        $returBarangjadiItems = Retur_barangjadi::where('kode_retur', $kodePengiriman)->get();

        // Update status untuk semua Retur_barangjadi dengan kode_retur yang sama
        Retur_barangjadi::where('kode_retur', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        // Update status untuk semua retur_tokoslawi dengan kode_retur yang sama
        Retur_tokobanjaran::where('kode_retur', $kodePengiriman)->update([
            'status' => 'posting',
            'tanggal_terima' => Carbon::now('Asia/Jakarta'),
        ]);

        // Simpan data ke tabel pemusnahan_barangjadis untuk setiap item
        foreach ($returBarangjadiItems as $item) {
            Pemusnahan_barangjadi::create([
                'kode_pemusnahan' => $kode,
                'kode_retur' => $item->kode_retur,
                'produk_id' => $item->produk_id,
                'toko_id' => $item->toko_id,
                'nama_produk' => $item->nama_produk,
                'status' => 'unpost',
                'jumlah' => $item->jumlah,
                'keterangan' => $item->keterangan,
                'tanggal_retur' => Carbon::now('Asia/Jakarta'),
            ]);
        }

        // Simpan data ke tabel pemusnahan_barangjadis untuk setiap item
        foreach ($returBarangjadiItems as $item) {
            Stok_retur::create([
                'kode_retur' => $item->kode_retur,
                'produk_id' => $item->produk_id,
                'toko_id' => $item->toko_id,
                'nama_produk' => $item->nama_produk,
                'status' => 'posting',
                'jumlah' => $item->jumlah,
                'keterangan' => $item->keterangan,
                'tanggal_retur' => Carbon::now('Asia/Jakarta'),
            ]);
        }

        return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_retur yang sama serta menyimpan data pemusnahan_barangjadis.']);
    }

//posting mengurangi stok
// public function posting_retur($id)
// {
//     $kode = $this->kode();

//     // Ambil data Retur_barangjadi berdasarkan ID
//     $pengiriman = Retur_barangjadi::where('id', $id)->first();

//     // Pastikan data ditemukan
//     if (!$pengiriman) {
//         return response()->json(['error' => 'Data tidak ditemukan.'], 404);
//     }

//     // Ambil kode_retur dari pengiriman yang diambil
//     $kodePengiriman = $pengiriman->kode_retur;

//     // Ambil data produk terkait dengan kode_retur
//     $returBarangjadiItems = Retur_barangjadi::where('kode_retur', $kodePengiriman)->get();

//     // Update status untuk semua Retur_barangjadi dan retur_tokoslawi dengan kode_retur yang sama
//     Retur_barangjadi::where('kode_retur', $kodePengiriman)->update([
//         'status' => 'posting',
//         'tanggal_terima' => Carbon::now('Asia/Jakarta'),
//     ]);

//     Retur_tokobanjaran::where('kode_retur', $kodePengiriman)->update([
//         'status' => 'posting',
//         'tanggal_terima' => Carbon::now('Asia/Jakarta'),
//     ]);

//     foreach ($returBarangjadiItems as $item) {
//         $stokItem = Stok_tokobanjaran::where('produk_id', $item->produk_id)
//             ->orderBy('jumlah', 'asc')
//             ->first();

//         if ($stokItem) {
//             // Pastikan stok mencukupi sebelum mengurangi
//             if ($stokItem->jumlah >= $item->jumlah) {
//                 $stokItem->jumlah -= $item->jumlah;
//                 $stokItem->save();
//             } else {
//                 return response()->json(['error' => 'Stok tidak mencukupi untuk produk ' . $item->nama_produk], 400);
//             }
//         }
//     }

//     // Simpan data ke tabel pemusnahan_barangjadis untuk setiap item
//     foreach ($returBarangjadiItems as $item) {
//         Pemusnahan_barangjadi::create([
//             'kode_pemusnahan' => $kode,
//             'kode_retur' => $item->kode_retur,
//             'produk_id' => $item->produk_id,
//             'toko_id' => $item->toko_id,
//             'nama_produk' => $item->nama_produk,
//             'status' => 'unpost',
//             'jumlah' => $item->jumlah,
//             'keterangan' => $item->keterangan,
//             'tanggal_retur' => Carbon::now('Asia/Jakarta'),
//         ]);
//     }

//     // Simpan data ke tabel stok_retur untuk setiap item
//     foreach ($returBarangjadiItems as $item) {
//         Stok_retur::create([
//             'kode_retur' => $item->kode_retur,
//             'produk_id' => $item->produk_id,
//             'nama_produk' => $item->nama_produk,
//             'toko_id' => $item->toko_id,
//             'status' => 'posting',
//             'jumlah' => $item->jumlah,
//             'keterangan' => $item->keterangan,
//             'tanggal_retur' => Carbon::now('Asia/Jakarta'),
//         ]);
//     }

//     return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_retur yang sama serta mengurangi stok di stok_tokoslawi dan menyimpan data pemusnahan_barangjadis.']);
// }





public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Retur_barangjadi::where('id', $id)->value('kode_retur');
    
    // Jika kode_retur tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_retur yang sama
    $pengirimanBarangJadi = Retur_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('admin.inquery_returbarangjadi.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function print($id)
    {
        $detailStokBarangJadi = Retur_barangjadi::where('id', $id)->value('kode_retur');
    
        // Jika kode_retur tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_retur yang sama
        $pengirimanBarangJadi = Retur_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        $pdf = FacadePdf::loadView('admin.inquery_returbarangjadi.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_slawi.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
        }

}


 