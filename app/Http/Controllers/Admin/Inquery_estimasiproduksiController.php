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




class Inquery_estimasiproduksiController extends Controller{


    public function index(Request $request)
{
    $status = $request->status;
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;

    // Query untuk Permintaanproduk
    $inqueryPermintaan = Permintaanproduk::query();
    
    if ($status) {
        $inqueryPermintaan->where('status', $status);
    }

    if ($tanggalAwal && $tanggalAkhir) {
        $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
        $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
        $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
        });
    } else {
        $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk'])
        ->get()
        ->flatMap(function ($permintaan) {
            return $permintaan->detailPermintaanProduks;
        })
        ->groupBy('produk_id')
        ->map(function ($groupedDetails) {
            return $groupedDetails->groupBy('toko_id')->map(function ($details) {
                return [
                    'jumlah' => $details->sum('jumlah'),
                    'toko' => $details->first()->toko,
                    'produk' => $details->first()->produk,
                    'tanggal_permintaan' => $details->first()->tanggal_permintaan,
                ];
            });
        });

   // Query untuk Pemesananproduk
   $inqueryPemesanan = DetailPemesananProduk::query();
    
   if ($status) {
       $inqueryPemesanan->where('status', $status);
   }

   if ($tanggalAwal && $tanggalAkhir) {
       $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
       $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
       $inqueryPemesanan->whereHas('pemesananProduk', function($query) use ($tanggalAwal, $tanggalAkhir) {
           $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
       });
   } else {
       $inqueryPemesanan->whereHas('pemesananProduk', function($query) {
           $query->whereDate('tanggal_kirim', Carbon::today());
       });
   }

   $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk'])
       ->get()
       ->groupBy('produk_id')
       ->map(function ($groupedDetails) {
           return $groupedDetails->groupBy('toko_id')->map(function ($details) {
               return [
                   'jumlah' => $details->sum('jumlah'),
                   'toko' => $details->first()->pemesananProduk->toko,
                   'produk' => $details->first()->produk,
                   'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
                   'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
                   'detail' => $details
               ];
           });
       });
    return view('admin.inquery_estimasiproduksi.index', compact('permintaanProduks', 'pemesananProduk'));
}

// public function index(Request $request)
// {
//     $status = $request->status;
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;

//     // Query untuk Permintaanproduk
//     $inqueryPermintaan = Permintaanproduk::query();
    
//     if ($status) {
//         $inqueryPermintaan->where('status', $status);
//     }

//     if ($tanggalAwal && $tanggalAkhir) {
//         $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//         $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//         $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) use ($tanggalAwal, $tanggalAkhir) {
//             $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
//         });
//     } else {
//         $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) {
//             $query->whereDate('tanggal_permintaan', Carbon::today());
//         });
//     }

//     $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk'])
//         ->get()
//         ->flatMap(function ($permintaan) {
//             return $permintaan->detailPermintaanProduks;
//         })
//         ->groupBy('produk_id')
//         ->map(function ($groupedDetails) {
//             return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $details->first()->toko,
//                     'produk' => $details->first()->produk,
//                     'tanggal_permintaan' => $details->first()->tanggal_permintaan,
//                 ];
//             });
//         });

//    // Query untuk Pemesananproduk
//    $inqueryPemesanan = DetailPemesananProduk::query();
    
//    if ($status) {
//        $inqueryPemesanan->where('status', $status);
//    }

//    // Filter untuk hanya menampilkan tanggal_kirim besok
//    $besok = Carbon::tomorrow();

//    $inqueryPemesanan->whereHas('pemesananProduk', function($query) use ($besok) {
//        $query->whereDate('tanggal_kirim', $besok);
//    });

//    $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk'])
//        ->get()
//        ->groupBy('produk_id')
//        ->map(function ($groupedDetails) {
//            return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                return [
//                    'jumlah' => $details->sum('jumlah'),
//                    'toko' => $details->first()->pemesananProduk->toko,
//                    'produk' => $details->first()->produk,
//                    'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
//                    'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
//                    'detail' => $details
//                ];
//            });
//        });

//     return view('admin.inquery_estimasiproduksi.index', compact('permintaanProduks', 'pemesananProduk'));
// }

// public function index(Request $request)
// {
//     $status = $request->status;
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;

//     // Query untuk Permintaanproduk
//     $inqueryPermintaan = Permintaanproduk::query();
    
//     if ($status) {
//         $inqueryPermintaan->where('status', $status);
//     }

//     if ($tanggalAwal && $tanggalAkhir) {
//         $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//         $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//         $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) use ($tanggalAwal, $tanggalAkhir) {
//             $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
//         });
//     } else {
//         $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) {
//             $query->whereDate('tanggal_permintaan', Carbon::today());
//         });
//     }

//     $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk'])
//         ->get()
//         ->flatMap(function ($permintaan) {
//             return $permintaan->detailPermintaanProduks;
//         })
//         ->groupBy('produk_id')
//         ->map(function ($groupedDetails) {
//             return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                 $firstDetail = $details->first();
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $firstDetail ? $firstDetail->toko : null,
//                     'produk' => $firstDetail ? $firstDetail->produk : null,
//                     'tanggal_permintaan' => $firstDetail ? $firstDetail->tanggal_permintaan : null,
//                 ];
//             });
//         });

//     // Query untuk Pemesananproduk
//     $inqueryPemesanan = DetailPemesananProduk::query();
    
//     if ($status) {
//         $inqueryPemesanan->where('status', $status);
//     }

//     if ($tanggalAwal && $tanggalAkhir) {
//         $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//         $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//         $inqueryPemesanan->whereHas('pemesananProduk', function($query) use ($tanggalAwal, $tanggalAkhir) {
//             $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
//         });
//     } else {
//         $inqueryPemesanan->whereHas('pemesananProduk', function($query) {
//             $query->whereDate('tanggal_kirim', Carbon::today());
//         });
//     }

//     $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk'])
//         ->get()
//         ->groupBy('produk_id')
//         ->map(function ($groupedDetails) {
//             return $groupedDetails->groupBy('pemesananProduk.kode_pemesanan')->map(function ($details) {
//                 $firstDetail = $details->first();
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $firstDetail ? $firstDetail->pemesananProduk->toko : null,
//                     'produk' => $firstDetail ? $firstDetail->produk : null,
//                     'tanggal_kirim' => $firstDetail ? $firstDetail->pemesananProduk->tanggal_kirim : null,
//                     'kode_pemesanan' => $firstDetail ? $firstDetail->pemesananProduk->kode_pemesanan : null,
//                 ];
//             });
//         });

//     return view('admin.inquery_estimasiproduksi.index', compact('permintaanProduks', 'pemesananProduk'));
// }





}