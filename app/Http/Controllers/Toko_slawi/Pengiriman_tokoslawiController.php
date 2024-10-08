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
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Stok_barangjadi;
use App\Models\Detail_stokbarangjadi;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use App\Models\Pengiriman_barangjadi;
use App\Models\Stok_tokoslawi;
use Maatwebsite\Excel\Facades\Excel;




class Pengiriman_tokoslawiController extends Controller{

    // public function index(Request $request)
    // {
    //     // Mengambil data stok_tokoslawi dengan relasi pengiriman_barangjadi dan produk
    //     $stokBarangJadi = Stok_tokoslawi::with(['pengiriman_barangjadi.produk'])
    //         ->orderBy('created_at', 'desc')
    //         ->get()
    //         ->groupBy(function ($item) {
    //             // Memeriksa apakah pengiriman_barangjadi ada sebelum mengakses kode_pengiriman
    //             return $item->pengiriman_barangjadi ? $item->pengiriman_barangjadi->kode_pengiriman : 'undefined';
    //         });
    
    //     return view('toko_slawi.pengiriman_tokoslawi.index', compact('stokBarangJadi'));
    // }
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_input = $request->tanggal_input;
        $tanggal_akhir = $request->tanggal_akhir;
    
        // Mengambil data stok_tokoslawi dengan relasi pengiriman_barangjadi dan produk
        $query = Stok_tokoslawi::with(['pengiriman_barangjadi.produk.klasifikasi']);
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal_input dan tanggal_akhir
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
    
        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_pengiriman
        $stokBarangJadi = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                // Memeriksa apakah pengiriman_barangjadi ada sebelum mengakses kode_pengiriman
                return $item->pengiriman_barangjadi ? $item->pengiriman_barangjadi->kode_pengiriman : 'undefined';
            });
    
        return view('toko_slawi.pengiriman_tokoslawi.index', compact('stokBarangJadi'));
    }
    
    
    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Stok_tokoslawi::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama
        $pengirimanBarangJadi = Stok_tokoslawi::with(['produk.subklasifikasi', 'toko'])->where('kode_pengiriman', $detailStokBarangJadi)->get();
        
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('toko_slawi.pengiriman_tokoslawi.show', compact('pengirimanBarangJadi', 'firstItem'));
    }


// public function posting_pengiriman($id)
// {
//     // Ambil data stok_tokoslawi berdasarkan ID
//     $stok = Stok_tokoslawi::where('id', $id)->first();

//     // Pastikan data ditemukan
//     if (!$stok) {
//         return response()->json(['error' => 'Data tidak ditemukan.'], 404);
//     }

//     // Ambil kode_pengiriman dari stok yang diambil
//     $kodePengiriman = $stok->kode_pengiriman;
    
//     // Ambil pengiriman_barangjadi_id dari stok yang diambil
//     $pengirimanId = $stok->pengiriman_barangjadi_id;

//     // Update status untuk semua stok_tokoslawi dengan kode_pengiriman yang sama
//     Stok_tokoslawi::where('kode_pengiriman', $kodePengiriman)->update([
//         'status' => 'posting',
//         'tanggal_terima' => Carbon::now('Asia/Jakarta'),
//     ]);

//     // Update status untuk pengiriman_barangjadi berdasarkan pengiriman_barangjadi_id
//     Pengiriman_barangjadi::where('id', $pengirimanId)->update([
//         'status' => 'posting',
//         'tanggal_terima' => Carbon::now('Asia/Jakarta'),

//     ]);

//     return response()->json(['success' => 'Berhasil mengubah status di stok_tokoslawi dan pengiriman_barangjadi.']);
// }
public function posting_pengiriman($id)
{
    // Ambil data stok_tokoslawi berdasarkan ID
    $stok = Stok_tokoslawi::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$stok) {
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // Ambil kode_pengiriman dan pengiriman_barangjadi_id dari stok yang diambil
    $kodePengiriman = $stok->kode_pengiriman;
    $pengirimanId = $stok->pengiriman_barangjadi_id;

    // Ambil pengiriman terkait dari tabel pengiriman_barangjadi
    $pengiriman = Pengiriman_barangjadi::find($pengirimanId);

    // Pastikan data pengiriman ditemukan
    if (!$pengiriman) {
        return response()->json(['error' => 'Data pengiriman tidak ditemukan.'], 404);
    }

    // Ambil detail stok barang jadi terkait produk
    $detailStoks = Detail_stokbarangjadi::where('produk_id', $pengiriman->produk_id)->get();
    $totalStok = $detailStoks->sum('stok');

    // Cek apakah stok cukup untuk jumlah pengiriman
    if ($totalStok < $pengiriman->jumlah) {
        return response()->json(['error' => 'Stok tidak cukup untuk melakukan posting.'], 400);
    }

    // Kurangi stok berdasarkan jumlah pengiriman
    $remaining = $pengiriman->jumlah;
    foreach ($detailStoks as $detailStok) {
        if ($detailStok->stok >= $remaining) {
            $detailStok->stok -= $remaining;
            $detailStok->save();
            break;
        } else {
            $remaining -= $detailStok->stok;
            $detailStok->stok = 0;
            $detailStok->save();
        }
    }

    // Update status untuk semua stok_tokoslawi dengan kode_pengiriman yang sama
    Stok_tokoslawi::where('kode_pengiriman', $kodePengiriman)->update([
        'status' => 'posting',
        'tanggal_terima' => Carbon::now('Asia/Jakarta'),
    ]);

    // Update status untuk pengiriman_barangjadi
    $pengiriman->update([
        'status' => 'posting',
        'tanggal_terima' => Carbon::now('Asia/Jakarta'),
    ]);

    return response()->json(['success' => 'Berhasil mengubah status di stok_tokoslawi dan pengurangan stok di detail_stokbarangjadi.']);
}


public function unpost_pengiriman($id)
        {
            // Ambil data stok_tokoslawi berdasarkan ID
    $stok = Stok_tokoslawi::where('id', $id)->first();

    // Pastikan data ditemukan
    if (!$stok) {
        return response()->json(['error' => 'Data tidak ditemukan.'], 404);
    }

    // Ambil kode_pengiriman dari stok yang diambil
    $kodePengiriman = $stok->kode_pengiriman;
    
    // Ambil pengiriman_barangjadi_id dari stok yang diambil
    $pengirimanId = $stok->pengiriman_barangjadi_id;

    // Update status untuk semua stok_tokoslawi dengan kode_pengiriman yang sama
    Stok_tokoslawi::where('kode_pengiriman', $kodePengiriman)->update([
        'status' => 'unpost'
    ]);

    // Update status untuk pengiriman_barangjadi berdasarkan pengiriman_barangjadi_id
    Pengiriman_barangjadi::where('id', $pengirimanId)->update([
        'status' => 'unpost'
    ]);

    return response()->json(['success' => 'Berhasil mengubah status di stok_tokoslawi dan pengiriman_barangjadi.']);
        }

    public function print($id)
    {
        // $permintaanProduk = PermintaanProduk::where('id', $id)->firstOrFail();
        
        // $detailPermintaanProduks = $permintaanProduk->detailpermintaanproduks;
        $permintaanProduk = PermintaanProduk::find($id);
        $detailPermintaanProduks = DetailPermintaanProduk::where('permintaanproduk_id', $id)->get();
    
        // Mengelompokkan produk berdasarkan divisi
        $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
            return $item->produk->klasifikasi->nama; // Ganti dengan nama divisi jika diperlukan
        });
    
        // Menghitung total jumlah per divisi
        $totalPerDivisi = $produkByDivisi->map(function($produks) {
            return $produks->sum('jumlah');
        });
        $toko = $detailPermintaanProduks->first()->toko;

        $pdf = FacadePdf::loadView('toko_slawi.permintaan_produk.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi','toko'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }

    public function unpost(Request $request, $id)
    {
        $permintaan = Detailpermintaanproduk::find($id);
    
        if ($permintaan) {
            $permintaan->status = 'posting';
            $permintaan->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false], 404);
    }
    
    

    public function edit($id)
    {
        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $klasifikasis = Klasifikasi::all(); // Menyediakan daftar klasifikasi

        return view('toko_slawi.stok_barangjadi.edit', compact('stok_barangjadi', 'klasifikasis'));
    }

    // Method untuk memproses update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'klasifikasi_id' => 'required|exists:klasifikasis,id',
            'produk' => 'required|array',
            'produk.*.stok' => 'required|integer|min:0',
        ]);

        $stok_barangjadi = Stok_Barangjadi::findOrFail($id);
        $stok_barangjadi->klasifikasi_id = $request->klasifikasi_id;
        $stok_barangjadi->save();

        // Update stok produk
        foreach ($request->produk as $produkId => $data) {
            // Lakukan update stok produk sesuai kebutuhan
            // Misalnya, update stok produk dalam pivot table jika ada
            $stok_barangjadi->produks()->updateExistingPivot($produkId, ['stok' => $data['stok']]);
        }

        return redirect()->route('stokbarangjadi.edit', $id)->with('success', 'Data berhasil diperbarui!');
    }

        public function destroy($id)
        {
            DB::transaction(function () use ($id) {
                $pemesanan = Pemesananproduk::findOrFail($id);
        
                // Menghapus (soft delete) detail pemesanan terkait
                DetailPemesananProduk::where('pemesananproduk_id', $id)->delete();
        
                // Menghapus (soft delete) data pemesanan
                $pemesanan->delete();
            });
        
            return redirect('toko_slawi/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
        }
        
        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
    
            Excel::import(new ProdukImport, $request->file('file'));
    
            // Redirect to the form with success message
            return redirect()->route('form.produk')->with('success', 'Data produk berhasil diimpor.');
        }
    
        public function formProduk()
        {
            $klasifikasis = Klasifikasi::with('produks')->get();
            $importedData = session('imported_data', []);
            return view('toko_slawi.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}