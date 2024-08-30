<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Support\Facades\DB;
use App\Models\Detailpemesananproduk;
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Stok_tokoslawi;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokotegal;
use App\Models\Stok_tokopemalang;
use App\Models\Stok_tokobumiayu;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Detail_stokbarangjadi;
use App\Models\Stok_barangjadi;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_tokobanjaran;
use App\Models\Pengirimanpemesanan_tokobanjaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;




class PengirimanbarangjadiController extends Controller{

    public function index()
    {
        $pengirimanBarangJadi = Pengiriman_barangjadi::with('produk.klasifikasi')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pengiriman');
    
        return view('admin.pengiriman_barangjadi.index', compact('pengirimanBarangJadi'));
    }


    
// create lama
// public function create()
// {
//     $detailStokBarangjadi = Detail_stokbarangjadi::select('produk_id', 'klasifikasi_id')
//         ->distinct()
//         ->get();

//     $produkIds = $detailStokBarangjadi->pluck('produk_id')->toArray();
//     $klasifikasiIds = $detailStokBarangjadi->pluck('klasifikasi_id')->toArray();

//     $klasifikasis = Klasifikasi::whereIn('id', $klasifikasiIds)
//         ->with(['produks' => function ($query) use ($produkIds) {
//             $query->whereIn('id', $produkIds);
//         }])
//         ->get();

//     $tokos = Toko::all();

//     return view('admin.pengiriman_barangjadi.create', compact('klasifikasis', 'tokos'));
// }

public function create()
{
    $detailStokBarangjadi = Detail_stokbarangjadi::with('produk')->distinct()->get();

    $produkIds = $detailStokBarangjadi->pluck('produk_id')->toArray();
    $klasifikasiIds = $detailStokBarangjadi->pluck('klasifikasi_id')->toArray();

    $klasifikasis = Klasifikasi::whereIn('id', $klasifikasiIds)
        ->with(['produks' => function ($query) use ($produkIds) {
            $query->whereIn('id', $produkIds);
        }])
        ->get();

    $tokos = Toko::all();

    return view('admin.pengiriman_barangjadi.create', compact('klasifikasis', 'tokos', 'detailStokBarangjadi'));
}

public function pengiriman_pemesanan()
{
    $detailStokBarangjadi = Detail_stokbarangjadi::with('produk')->distinct()->get();

    $produkIds = $detailStokBarangjadi->pluck('produk_id')->toArray();
    $klasifikasiIds = $detailStokBarangjadi->pluck('klasifikasi_id')->toArray();

    $klasifikasis = Klasifikasi::whereIn('id', $klasifikasiIds)
        ->with(['produks' => function ($query) use ($produkIds) {
            $query->whereIn('id', $produkIds);
        }])
        ->get();

    $tokos = Toko::all();

    return view('admin.pengiriman_barangjadi.pengiriman_pemesanan', compact('klasifikasis', 'tokos', 'detailStokBarangjadi'));
}



// public function store(Request $request)
// {
//     $kode = $this->kode();
//     $produkData = $request->input('produk_id', []);
//     $jumlahData = $request->input('jumlah', []);
//     $tokoId = $request->input('toko_id');

//     // Array untuk menyimpan ID pengiriman
//     $pengirimanIds = [];

//     foreach ($produkData as $key => $produkId) {
//         $jumlah = $jumlahData[$key] ?? null;

//         if (!is_null($jumlah) && $jumlah !== '') {
//             // Ambil kode produk untuk pesan error
//             $kodeProduk = Produk::where('id', $produkId)->value('kode_produk');

//             // Simpan pengiriman tanpa mengurangi stok
//             $pengiriman = Pengiriman_barangjadi::create([
//                 'kode_pengiriman' => $kode,
//                 'qrcode_pengiriman' => 'https://javabakery.id/pengiriman_produk/' . $kode,
//                 'produk_id' => $produkId,
//                 'toko_id' => $tokoId,
//                 'jumlah' => $jumlah,
//                 'status' => 'unpost',
//                 'tanggal_pengiriman' => Carbon::now('Asia/Jakarta'),
//             ]);

//             // Buat catatan stok di toko terkait
//             switch ($tokoId) {
//                 case 1:
//                     Pengiriman_tokobanjaran::create([
//                         'pengiriman_barangjadi_id' => $pengiriman->id,
//                         'kode_pengiriman' => $kode,
//                         'produk_id' => $produkId,
//                         'toko_id' => $tokoId,
//                         'jumlah' => $jumlah,
//                         'status' => 'unpost',
//                         'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        
//                     ]);
//                     break;
//                 case 2:
//                     Stok_tokotegal::create([
//                         'pengiriman_barangjadi_id' => $pengiriman->id,
//                         'kode_pengiriman' => $kode,
//                         'produk_id' => $produkId,
//                         'jumlah' => $jumlah,
//                         'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                     ]);
//                     break;
//                 case 3:
//                     Stok_tokoslawi::create([
//                         'pengiriman_barangjadi_id' => $pengiriman->id,
//                         'kode_pengiriman' => $kode,
//                         'produk_id' => $produkId,
//                         'jumlah' => $jumlah,
//                         'status' => 'unpost',
//                         'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                     ]);
//                     break;
//                 case 4:
//                     Stok_tokopemalang::create([
//                         'pengiriman_barangjadi_id' => $pengiriman->id,
//                         'kode_pengiriman' => $kode,
//                         'produk_id' => $produkId,
//                         'jumlah' => $jumlah,
//                         'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                     ]);
//                     break;
//                 case 5:
//                     Stok_tokobumiayu::create([
//                         'pengiriman_barangjadi_id' => $pengiriman->id,
//                         'kode_pengiriman' => $kode,
//                         'produk_id' => $produkId,
//                         'jumlah' => $jumlah,
//                         'tanggal_input' => Carbon::now('Asia/Jakarta'),
//                     ]);
//                     break;
//                 default:
//                     return redirect()->back()->with('error', 'Toko ID tidak valid');
//             }

//             // Simpan ID pengiriman yang baru dibuat
//             $pengirimanIds[] = $pengiriman->id;
//         }
//     }

//     // Jika ada ID pengiriman yang baru dibuat, arahkan ke halaman show
//     if (!empty($pengirimanIds)) {
//         $firstId = $pengirimanIds[0]; // Ambil ID pengiriman yang pertama
//         return redirect()->route('pengiriman_barangjadi.show', $firstId)
//             ->with('success', 'Berhasil menambahkan permintaan produk');
//     }

//     return redirect()->route('pengiriman_barangjadi.index')
//         ->with('success', 'Berhasil menambahkan permintaan produk');
// }

public function store(Request $request)
{
    $kode = $this->kode();
    $produkData = $request->input('produk_id', []);
    $jumlahData = $request->input('jumlah', []);
    $tokoId = $request->input('toko_id');

    // Array untuk menyimpan ID pengiriman
    $pengirimanIds = [];

    foreach ($produkData as $key => $produkId) {
        $jumlah = $jumlahData[$key] ?? null;

        if (!is_null($jumlah) && $jumlah !== '') {
            // Ambil stok produk dari detail_stokbarangjadi
            $stokProduk = Detail_stokbarangjadi::where('produk_id', $produkId)->value('stok');

            // Jika stok produk tidak ada atau kurang dari jumlah yang diminta, kembalikan pesan error
            if (is_null($stokProduk) || $stokProduk < $jumlah) {
                $kodeProduk = Produk::where('id', $produkId)->value('kode_produk');
                return redirect()->back()->with('error', 'Stok produk dengan kode ' . $kodeProduk . ' tidak cukup.');
            }

            // Simpan pengiriman tanpa mengurangi stok
            $pengiriman = Pengiriman_barangjadi::create([
                'kode_pengiriman' => $kode,
                'qrcode_pengiriman' => 'https://javabakery.id/pengiriman_produk/' . $kode,
                'produk_id' => $produkId,
                'toko_id' => $tokoId,
                'jumlah' => $jumlah,
                'status' => 'unpost',
                'tanggal_pengiriman' => Carbon::now('Asia/Jakarta'),
            ]);

            // Buat catatan stok di toko terkait
            switch ($tokoId) {
                case 1:
                    Pengiriman_tokobanjaran::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'toko_id' => $tokoId,
                        'jumlah' => $jumlah,
                        'status' => 'unpost',
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 2:
                    Stok_tokotegal::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 3:
                    Stok_tokoslawi::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'status' => 'unpost',
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 4:
                    Stok_tokopemalang::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 5:
                    Stok_tokobumiayu::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                default:
                    return redirect()->back()->with('error', 'Toko ID tidak valid');
            }

            // Simpan ID pengiriman yang baru dibuat
            $pengirimanIds[] = $pengiriman->id;
        }
    }

    // Jika ada ID pengiriman yang baru dibuat, arahkan ke halaman show
    if (!empty($pengirimanIds)) {
        $firstId = $pengirimanIds[0]; // Ambil ID pengiriman yang pertama
        return redirect()->route('pengiriman_barangjadi.show', $firstId)
            ->with('success', 'Berhasil menambahkan permintaan produk');
    }

    return redirect()->route('pengiriman_barangjadi.index')
        ->with('success', 'Berhasil menambahkan permintaan produk');
}


public function SimpanPengirimanpemesanan(Request $request)
{
    $kode = $this->kode();
    $produkData = $request->input('produk_id', []);
    $jumlahData = $request->input('jumlah', []);
    $tokoId = $request->input('toko_id');

    // Array untuk menyimpan ID pengiriman
    $pengirimanIds = [];

    foreach ($produkData as $key => $produkId) {
        $jumlah = $jumlahData[$key] ?? null;

        if (!is_null($jumlah) && $jumlah !== '') {
            // Ambil kode produk untuk pesan error
            $kodeProduk = Produk::where('id', $produkId)->value('kode_produk');

            // Simpan pengiriman tanpa mengurangi stok
            $pengiriman = Pengiriman_barangjadi::create([
                'kode_pengiriman' => $kode,
                'qrcode_pengiriman' => 'https://javabakery.id/pengiriman_produk/' . $kode,
                'produk_id' => $produkId,
                'toko_id' => $tokoId,
                'jumlah' => $jumlah,
                'status' => 'unpost',
                'tanggal_pengiriman' => Carbon::now('Asia/Jakarta'),
            ]);

            // Buat catatan stok di toko terkait
            switch ($tokoId) {
                case 1:
                    Pengirimanpemesanan_tokobanjaran::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'status' => 'unpost',
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        
                    ]);
                    break;
                case 2:
                    Stok_tokotegal::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 3:
                    Stok_tokoslawi::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'status' => 'unpost',
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 4:
                    Stok_tokopemalang::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                case 5:
                    Stok_tokobumiayu::create([
                        'pengiriman_barangjadi_id' => $pengiriman->id,
                        'kode_pengiriman' => $kode,
                        'produk_id' => $produkId,
                        'jumlah' => $jumlah,
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                default:
                    return redirect()->back()->with('error', 'Toko ID tidak valid');
            }

            // Simpan ID pengiriman yang baru dibuat
            $pengirimanIds[] = $pengiriman->id;
        }
    }

    // Jika ada ID pengiriman yang baru dibuat, arahkan ke halaman show
    if (!empty($pengirimanIds)) {
        $firstId = $pengirimanIds[0]; // Ambil ID pengiriman yang pertama
        return redirect()->route('pengiriman_barangjadi.show', $firstId)
            ->with('success', 'Berhasil menambahkan permintaan produk');
    }

    return redirect()->route('pengiriman_barangjadi.index')
        ->with('success', 'Berhasil menambahkan permintaan produk');
}




    public function kode()
    {
        $lastBarang = Pengiriman_barangjadi::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_pengiriman;
            $num = (int) substr($lastCode, strlen('SB')) + 1; 
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'JX';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
   
    // public function show($id)
    // {
    //     // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
    //     $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
    //     // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
    //     if (!$detailStokBarangJadi) {
    //         return redirect()->back()->with('error', 'Data tidak ditemukan.');
    //     }
        
    //     // Ambil semua data dengan kode_pengiriman yang sama
    //     $pengirimanBarangJadi = Pengiriman_barangjadi::with(['produk.subklasifikasi', 'toko'])->where('kode_pengiriman', $detailStokBarangJadi)->get();
        
    //     // Ambil item pertama untuk informasi toko
    //     $firstItem = $pengirimanBarangJadi->first();
        
    //     return view('admin.pengiriman_barangjadi.show', compact('pengirimanBarangJadi', 'firstItem'));
    // }

    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengiriman', $detailStokBarangJadi)->get();
    
        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.pengiriman_barangjadi.show', compact('groupedByKlasifikasi', 'firstItem'));
    }
    


    
    public function print($id)
    {
  // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
  $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
  // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
  if (!$detailStokBarangJadi) {
      return redirect()->back()->with('error', 'Data tidak ditemukan.');
  }
  
  // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
  $pengirimanBarangJadi = Pengiriman_barangjadi::with([
      'produk.subklasifikasi.klasifikasi', 
      'toko'
  ])->where('kode_pengiriman', $detailStokBarangJadi)->get();

  // Kelompokkan data berdasarkan klasifikasi
  $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
      return $item->produk->subklasifikasi->klasifikasi->nama;
  });
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        $pdf = FacadePdf::loadView('admin.pengiriman_barangjadi.print', compact('groupedByKlasifikasi', 'firstItem'));

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
           
    }
        
    

    public function update(Request $request, $id)
    {
           
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
        
            return redirect('admin/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
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
            return view('admin.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}