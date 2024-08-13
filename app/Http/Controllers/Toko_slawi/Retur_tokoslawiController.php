<?php

namespace App\Http\Controllers\Toko_slawi;

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

class Retur_tokoslawiController extends Controller{

    // public function index()
    // {
    //     // Ambil data retur_tokoslawi beserta relasi produk
    //     $retur_tokoslawi = Retur_tokoslawi::with('produk')->where('status', 'posting')->get();
    
    //     return view('toko_slawi.retur_tokoslawi.index', compact('retur_tokoslawi'));
    // }
    public function index()
    {
        // Ambil data retur_tokoslawi beserta relasi produk
        $retur_tokoslawi = Retur_tokoslawi::with('produk')->where('status', 'posting')->get();
    
        return view('toko_slawi.retur_tokoslawi.index', compact('retur_tokoslawi'));
    }
    

    

public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('toko_slawi.retur_tokoslawi.create', compact('produks', 'tokos'));
}


// public function store(Request $request)
// {
//     $request->validate([
//         'produk_id' => 'required|array',
//         'produk_id.*' => 'exists:produks,id',
//         'jumlah' => 'required|array',
//         'jumlah.*' => 'integer|min:1',
//         'keterangan' => 'required|array',
//         'keterangan.*' => 'in:produk gagal,oper,sampel'
//     ]);

//     $kode = $this->kode();
//     $produk_ids = $request->input('produk_id');
//     $jumlahs = $request->input('jumlah');
//     $keterangans = $request->input('keterangan');

//     foreach ($produk_ids as $index => $produk_id) {
//         $jumlah_yang_dibutuhkan = $jumlahs[$index];
        
//         // Ambil semua stok yang sesuai dengan produk_id dan urutkan berdasarkan jumlah stok
//         $stok_items = Stok_tokoslawi::where('produk_id', $produk_id)
//             ->where('jumlah', '>', 0)
//             ->orderBy('jumlah', 'asc')
//             ->get();

//         if ($stok_items->isEmpty()) {
//             return redirect()->back()->with('error', 'Stok untuk produk dengan ID ' . $produk_id . ' tidak ditemukan.');
//         }

//         foreach ($stok_items as $stok) {
//             if ($jumlah_yang_dibutuhkan <= 0) {
//                 break;
//             }

//             if ($stok->jumlah >= $jumlah_yang_dibutuhkan) {
//                 // Jika stok saat ini cukup untuk memenuhi kebutuhan
//                 $stok->jumlah -= $jumlah_yang_dibutuhkan;
//                 $stok->save();

//                 // Simpan data retur_tokoslawi
//                 Retur_tokoslawi::create([
//                     'kode_retur' => $kode,
//                     'produk_id' => $produk_id,
//                     'toko_id' => '3',
//                     'status' => 'unpost',
//                     'jumlah' => $jumlah_yang_dibutuhkan,
//                     'keterangan' => $keterangans[$index],
//                     'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                 ]);

//                 // Simpan data ke tabel retur_barangjadis
//                 Retur_barangjadi::create([
//                     'kode_retur' => $kode,
//                     'produk_id' => $produk_id,
//                     'toko_id' => '3',
//                     'status' => 'unpost',
//                     'jumlah' => $jumlah_yang_dibutuhkan,
//                     'keterangan' => $keterangans[$index],
//                     'tanggal_retur' => Carbon::now('Asia/Jakarta'),
//                 ]);

//                 $jumlah_yang_dibutuhkan = 0;
//             } else {
//                 // Jika stok saat ini tidak cukup, kurangi jumlah stok ini dan lanjutkan ke stok berikutnya
//                 $jumlah_yang_dibutuhkan -= $stok->jumlah;

//                 // Simpan data retur_tokoslawi dengan stok yang tersedia
//                 Retur_tokoslawi::create([
//                     'kode_retur' => $kode,
//                     'produk_id' => $produk_id,
//                     'toko_id' => '3',
//                     'status' => 'unpost',
//                     'jumlah' => $stok->jumlah,
//                     'keterangan' => $keterangans[$index],
//                     'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                 ]);

//                 // Simpan data ke tabel retur_barangjadis dengan stok yang tersedia
//                 Retur_barangjadi::create([
//                     'kode_retur' => $kode,
//                     'produk_id' => $produk_id,
//                     'toko_id' => '3',
//                     'status' => 'unpost',
//                     'jumlah' => $stok->jumlah,
//                     'keterangan' => $keterangans[$index],
//                     'tanggal_retur' => Carbon::now('Asia/Jakarta'),
//                 ]);

//                 $stok->jumlah = 0;
//                 $stok->save();
//             }
//         }

//         if ($jumlah_yang_dibutuhkan > 0) {
//             return redirect()->back()->with('error', 'Jumlah stok untuk produk ' . $stok->produk->nama_produk . ' tidak mencukupi.');
//         }
//     }

//     return redirect()->route('retur_tokoslawi.index')->with('success', 'Data retur barang berhasil disimpan.');
// }
public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required|array',
        'produk_id.*' => 'exists:produks,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
        'keterangan' => 'required|array',
        'keterangan.*' => 'in:produk gagal,oper,sampel'
    ]);

    $kode = $this->kode();
    $produk_ids = $request->input('produk_id');
    $jumlahs = $request->input('jumlah');
    $keterangans = $request->input('keterangan');

    foreach ($produk_ids as $index => $produk_id) {
        $jumlah_yang_dibutuhkan = $jumlahs[$index];
        
        // Ambil produk berdasarkan ID untuk mendapatkan nama produk
        $produk = Produk::find($produk_id);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk dengan ID ' . $produk_id . ' tidak ditemukan.');
        }

        // Tambahkan kata "Retur" ke nama produk
        $nama_produk_retur = $produk->nama_produk . ' RETUR';

        // Ambil semua stok yang sesuai dengan produk_id dan urutkan berdasarkan jumlah stok
        $stok_items = Stok_tokoslawi::where('produk_id', $produk_id)
            ->where('jumlah', '>', 0)
            ->orderBy('jumlah', 'asc')
            ->get();

        if ($stok_items->isEmpty()) {
            return redirect()->back()->with('error', 'Stok untuk produk dengan ID ' . $produk_id . ' tidak ditemukan.');
        }

        foreach ($stok_items as $stok) {
            if ($jumlah_yang_dibutuhkan <= 0) {
                break;
            }

            if ($stok->jumlah >= $jumlah_yang_dibutuhkan) {
                // Jika stok saat ini cukup untuk memenuhi kebutuhan
                $stok->jumlah -= $jumlah_yang_dibutuhkan;
                $stok->save();

                // Simpan data retur_tokoslawi
                Retur_tokoslawi::create([
                    'kode_retur' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '3',
                    'status' => 'unpost',
                    'jumlah' => $jumlah_yang_dibutuhkan,
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);

                // Simpan data ke tabel retur_barangjadis dengan nama produk yang telah dimodifikasi
                Retur_barangjadi::create([
                    'kode_retur' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '3',
                    'nama_produk' => $nama_produk_retur,
                    'status' => 'unpost',
                    'jumlah' => $jumlah_yang_dibutuhkan,
                    'keterangan' => $keterangans[$index],
                    'tanggal_retur' => Carbon::now('Asia/Jakarta'),
                ]);

                $jumlah_yang_dibutuhkan = 0;
            } else {
                // Jika stok saat ini tidak cukup, kurangi jumlah stok ini dan lanjutkan ke stok berikutnya
                $jumlah_yang_dibutuhkan -= $stok->jumlah;

                // Simpan data retur_tokoslawi dengan stok yang tersedia
                Retur_tokoslawi::create([
                    'kode_retur' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '3',
                    'status' => 'unpost',
                    'jumlah' => $stok->jumlah,
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);

                // Simpan data ke tabel retur_barangjadis dengan nama produk yang telah dimodifikasi
                Retur_barangjadi::create([
                    'produk_id' => $produk_id,
                    'toko_id' => '3',
                    'nama_produk' => $nama_produk_retur,
                    'status' => 'unpost',
                    'jumlah' => $stok->jumlah,
                    'keterangan' => $keterangans[$index],
                    'tanggal_retur' => Carbon::now('Asia/Jakarta'),
                ]);

                $stok->jumlah = 0;
                $stok->save();
            }
        }

        if ($jumlah_yang_dibutuhkan > 0) {
            return redirect()->back()->with('error', 'Jumlah stok untuk produk ' . $stok->produk->nama_produk . ' tidak mencukupi.');
        }
    }

    return redirect()->route('retur_tokoslawi.index')->with('success', 'Data retur barang berhasil disimpan.');
}


public function kode()
{
    $prefix = 'RSLW';
    $year = date('y'); // Dua digit terakhir dari tahun
    $date = date('md'); // Format bulan dan hari: MMDD

    // Mengambil kode retur terakhir yang dibuat pada hari yang sama
    $lastBarang = Retur_tokoslawi::whereDate('tanggal_input', Carbon::today())
                                  ->orderBy('kode_retur', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_retur;
        $lastNum = (int) substr($lastCode, strlen($prefix . $year . $date)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
    $newCode = $prefix . $year . $date . $formattedNum;
    return $newCode;
}


public function unpost_retur($id)
{
    // Ambil data stok barang berdasarkan ID
    $stok = Retur_tokoslawi::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$stok) {
        return back()->with('error', 'Data tidak ditemukan.');
    }

    // Ambil kode_input dari stok yang diambil
    $kodeInput = $stok->kode_retur;

    // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
    Retur_tokoslawi::where('kode_retur', $kodeInput)->update([
        'status' => 'unpost'
    ]);
    return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
}


public function posting_retur($id)
{
   // Ambil data Retur_tokoslawi berdasarkan ID
    $pengiriman = Retur_tokoslawi::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$pengiriman) {
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // Ambil kode_retur dari pengiriman yang diambil
    $kodePengiriman = $pengiriman->kode_retur;

    // Update status untuk semua Retur_tokoslawi dengan kode_retur yang sama
    Retur_tokoslawi::where('kode_retur', $kodePengiriman)->update([
        'status' => 'posting'
    ]);

    // Update status untuk semua stok_tokoslawi terkait dengan Retur_tokoslawi_id
    Stok_tokoslawi::where('pengiriman_barangjadi_id', $id)->update([
        'status' => 'posting'
    ]);

    return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_retur yang sama.']);
}

public function show($id)
{
    // Ambil kode_retur dari pengiriman_barangjadi berdasarkan id
    $detailStokBarangJadi = Retur_tokoslawi::where('id', $id)->value('kode_retur');
    
    // Jika kode_retur tidak ditemukan, tampilkan pesan error
    if (!$detailStokBarangJadi) {
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    
    // Ambil semua data dengan kode_retur yang sama
    $pengirimanBarangJadi = Retur_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
    
    // Ambil item pertama untuk informasi toko
    $firstItem = $pengirimanBarangJadi->first();
    
    return view('toko_slawi.inquery_returslawi.show', compact('pengirimanBarangJadi', 'firstItem'));
}

public function print($id)
    {
        $detailStokBarangJadi = Retur_tokoslawi::where('id', $id)->value('kode_retur');
    
        // Jika kode_retur tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_retur yang sama
        $pengirimanBarangJadi = Retur_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_retur', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        $pdf = FacadePdf::loadView('toko_slawi.inquery_returslawi.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_slawi.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
        }

}


 