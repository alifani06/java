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
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
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
use Maatwebsite\Excel\Facades\Excel;




class EstimasiproduksiController extends Controller{

// public function index()
// {
//     $produk = Produk::all();
//     // Mengambil data dengan relasi menggunakan with
//     $permintaanProduks = PermintaanProduk::with(['detailpermintaanproduks.toko']) // Memanggil relasi dari tabel terkait
//         ->get();

//     // Mengirim data ke view
//     return view('admin.estimasi_produksi.index', compact('permintaanProduks', 'produk'));
// }
public function index(Request $request)
{
    $status = $request->status;
    $tanggal_permintaan = $request->tanggal_permintaan;
    $tanggal_akhir = $request->tanggal_akhir;

    $inquery = Permintaanproduk::query();

    if ($status) {
        $inquery->where('status', $status);
    }

    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->whereHas('detailpermintaanproduks', function($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $inquery->whereHas('detailpermintaanproduks', function($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->whereHas('detailpermintaanproduks', function($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        // Jika tidak ada filter tanggal, ambil data hari ini
        $inquery->whereHas('detailpermintaanproduks', function($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    $inquery->orderBy('id', 'DESC');

    // Menggunakan with untuk eager loading relasi detailpermintaanproduks dan toko
    $permintaanProduks = $inquery->with(['detailpermintaanproduks', 'toko'])->get();

   return view('admin.estimasi_produksi.index', compact('permintaanProduks'));
}

public function edit($id)
{
    $produks = Produk::all();
    $permintaanProduks = Permintaanproduk::with(['detailpermintaanproduks', 'toko'])->findOrFail($id);

    return view('admin.estimasi_produksi.update', compact('permintaanProduks', 'produks'));
}

// public function update(Request $request, $id)
// {
   

//     $error_pesanans = array();
//     $data_pembelians = collect();

//     if ($request->has('produk_id')) {
//         for ($i = 0; $i < count($request->produk_id); $i++) {
//             $validasi_produk = Validator::make($request->all(), [
//                 'produk_id.' . $i => 'required',
//                 'jumlah.' . $i => 'required',
//             ]);

//             if ($validasi_produk->fails()) {
//                 array_push($error_pesanans, "Barang nomor " . ($i + 1) . " belum dilengkapi!"); // Corrected the syntax for concatenation and indexing
//             }

//             $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];

//             $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
//             $data_pembelians->push([
//                 'permintaanproduk_id' => $request->detail_ids[$i] ?? null,
//                 'produk_id' => $produk_id,
//                 'jumlah' => $jumlah,
//             ]);
//         }
//     }


//     $cetakpdf = Permintaanproduk::findOrFail($id);

//     // Update the main transaction
//     $cetakpdf->update([

//         'kode_permintaan' => $request->kode_permintaan,
//         // 'status' => 'unpost',
//     ]);

//     $transaksi_id = $cetakpdf->id;
//     $detailIds = $request->input('detail_ids');

//     foreach ($data_pembelians as $data_pesanan) {
//         $detailId = $data_pesanan['permintaanproduk_id'];

//         if ($detailId) {
//             Detailpermintaanproduk::where('id', $detailId)->update([
//                 'permintaanproduk_id' => $cetakpdf->id,
//                 'produk_id' => $data_pesanan['produk_id'],
//                 'jumlah' => $data_pesanan['jumlah'],
//             ]);
//         } else {
//             $existingDetail = Detailpermintaanproduk::where([
//                 'permintaanproduk_id' => $cetakpdf->id,
//                 'produk_id' => $data_pesanan['produk_id'],
//                 'jumlah' => $data_pesanan['jumlah'],
//             ])->first();

//             if (!$existingDetail) {
//                 Detailpermintaanproduk::create([
//                     'permintaanproduk_id' => $cetakpdf->id,
//                     'produk_id' => $data_pesanan['produk_id'],
//                     'jumlah' => $data_pesanan['jumlah'],
//                     'toko_id' => 1,  
//                     'tanggal_permintaan' => Carbon::now('Asia/Jakarta'),  
//                     'status' => 'unpost', 

//                 ]);
//             }
//         }
//     }
//     $details = Detailpermintaanproduk::where('permintaanproduk_id', $cetakpdf->id)->get();

//     return view('admin.estimasi_produksi.show', compact('cetakpdf', 'details'));
// }

public function update(Request $request, $id)
{
    $error_pesanans = array();
    $data_pembelians = collect();

    if ($request->has('produk_id')) {
        for ($i = 0; $i < count($request->produk_id); $i++) {
            $validasi_produk = Validator::make($request->all(), [
                'produk_id.' . $i => 'required',
                'jumlah.' . $i => 'required',
            ]);

            if ($validasi_produk->fails()) {
                array_push($error_pesanans, "Barang nomor " . ($i + 1) . " belum dilengkapi!");
            }

            $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
            $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];

            $data_pembelians->push([
                'permintaanproduk_id' => $request->detail_ids[$i] ?? null,
                'produk_id' => $produk_id,
                'jumlah' => $jumlah,
            ]);
        }
    }

    // Ambil data permintaan produk utama
    $permintaanProduk = Permintaanproduk::findOrFail($id);

    // Update transaksi utama
    $permintaanProduk->update([
        'kode_permintaan' => $request->kode_permintaan,
    ]);

    foreach ($data_pembelians as $data_pesanan) {
        $detailId = $data_pesanan['permintaanproduk_id'];

        if ($detailId) {
            Detailpermintaanproduk::where('id', $detailId)->update([
                'permintaanproduk_id' => $permintaanProduk->id,
                'produk_id' => $data_pesanan['produk_id'],
                'jumlah' => $data_pesanan['jumlah'],
            ]);
        } else {
            $existingDetail = Detailpermintaanproduk::where([
                'permintaanproduk_id' => $permintaanProduk->id,
                'produk_id' => $data_pesanan['produk_id'],
                'jumlah' => $data_pesanan['jumlah'],
            ])->first();

            if (!$existingDetail) {
                Detailpermintaanproduk::create([
                    'permintaanproduk_id' => $permintaanProduk->id,
                    'produk_id' => $data_pesanan['produk_id'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'toko_id' => 1,
                    'tanggal_permintaan' => Carbon::now('Asia/Jakarta'),
                    'status' => 'unpost',
                ]);
            }
        }
    }

    // Ambil detail permintaan produk termasuk toko
    $details = Detailpermintaanproduk::with('toko', 'produk.klasifikasi')->where('permintaanproduk_id', $permintaanProduk->id)->get();

    // Pastikan variabel $toko diambil dari salah satu detail yang ada
    $toko = $details->isNotEmpty() ? $details->first()->toko : null;

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $details->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Hitung total jumlah per divisi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data subklasifikasi berdasarkan klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    // Arahkan ke view show dengan semua variabel yang diperlukan
    return view('admin.estimasi_produksi.show', compact('permintaanProduk', 'details', 'toko', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi'));
}


public function show($id)
{
    $permintaanProduk = PermintaanProduk::find($id);
    $detailPermintaanProduks = DetailPermintaanProduk::with('toko')->where('permintaanproduk_id', $id)->get();

    // Cek apakah ada data pada $detailPermintaanProduks sebelum mengambil toko
    $toko = $detailPermintaanProduks->isNotEmpty() ? $detailPermintaanProduks->first()->toko : null;

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Menghitung total jumlah per klasifikasi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data Subklasifikasi berdasarkan Klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    return view('admin.estimasi_produksi.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi', 'toko'));
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

    $pdf = FacadePdf::loadView('admin.estimasi_produksi.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi','toko'));

    return $pdf->stream('surat_permintaan_produk.pdf');
}



public function deletedetailpermintaan($id)
{
    $item = Detailpermintaanproduk::find($id);
    $item->delete();
    return response()->json(['message' => 'Detail Permintaan not found'], 404);
}



}