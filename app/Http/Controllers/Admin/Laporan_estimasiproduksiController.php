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
use Dompdf\Options;




class Laporan_estimasiproduksiController extends Controller{


// public function index(Request $request)
// {
//     $status = $request->status;
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;
//     $tableType = $request->table_type; // Ambil nilai dari table_type

//     $permintaanProduks = [];
//     $pemesananProduk = [];

//     // Query Permintaanproduk jika memilih 'permintaan' atau 'all'
//     if ($tableType == 'permintaan' || $tableType == 'all') {
//         $inqueryPermintaan = Permintaanproduk::query();
        
//         if ($status) {
//             $inqueryPermintaan->where('status', $status);
//         }

//         if ($tanggalAwal && $tanggalAkhir) {
//             $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//             $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//             $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) use ($tanggalAwal, $tanggalAkhir) {
//                 $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
//             });
//         } else {
//             $inqueryPermintaan->whereHas('detailPermintaanProduks', function($query) {
//                 $query->whereDate('tanggal_permintaan', Carbon::today());
//             });
//         }

//         $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk'])
//             ->get()
//             ->flatMap(function ($permintaan) {
//                 return $permintaan->detailPermintaanProduks;
//             })
//             ->groupBy('produk_id')
//             ->map(function ($groupedDetails) {
//                 return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                     return [
//                         'jumlah' => $details->sum('jumlah'),
//                         'toko' => $details->first()->toko,
//                         'produk' => $details->first()->produk,
//                         'tanggal_permintaan' => $details->first()->tanggal_permintaan,
//                     ];
//                 });
//             });
//     }

//     // Query Pemesananproduk jika memilih 'pemesanan' atau 'all'
//     if ($tableType == 'pemesanan' || $tableType == 'all') {
//         $inqueryPemesanan = DetailPemesananProduk::query();
        
//         if ($status) {
//             $inqueryPemesanan->where('status', $status);
//         }

//         if ($tanggalAwal && $tanggalAkhir) {
//             $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//             $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//             $inqueryPemesanan->whereHas('pemesananProduk', function($query) use ($tanggalAwal, $tanggalAkhir) {
//                 $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
//             });
//         } else {
//             $inqueryPemesanan->whereHas('pemesananProduk', function($query) {
//                 $query->whereDate('tanggal_kirim', Carbon::today());
//             });
//         }

//         $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk'])
//             ->get()
//             ->groupBy('produk_id')
//             ->map(function ($groupedDetails) {
//                 return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                     return [
//                         'jumlah' => $details->sum('jumlah'),
//                         'toko' => $details->first()->pemesananProduk->toko,
//                         'produk' => $details->first()->produk,
//                         'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
//                         'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
//                         'detail' => $details
//                     ];
//                 });
//             });
//     }

//     return view('admin.laporan_estimasiproduksi.index', compact('permintaanProduks', 'pemesananProduk', 'tableType'));
// }
public function index(Request $request)
{
    $status = $request->status;
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;
    $tableType = $request->table_type; // Ambil nilai dari table_type

    $permintaanProduks = collect();
    $pemesananProduk = collect();

// Query Permintaanproduk
if ($tableType == 'permintaan' || $tableType == 'all') {
    $inqueryPermintaan = Permintaanproduk::query();
    if ($status) {
        $inqueryPermintaan->where('status', $status);
    }
    if ($tanggalAwal && $tanggalAkhir) {
        $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
        $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
        $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
        });
    } else {
        $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }
    $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk.klasifikasi'])
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
                    'klasifikasi' => $details->first()->produk->klasifikasi ?? 'N/A', // Tambahkan pengecekan untuk klasifikasi
                    'tanggal_permintaan' => $details->first()->tanggal_permintaan,
                ];
            });
        });
}

// Query Pemesananproduk
if ($tableType == 'pemesanan' || $tableType == 'all') {
    $inqueryPemesanan = DetailPemesananProduk::query();
    if ($status) {
        $inqueryPemesanan->where('status', $status);
    }
    if ($tanggalAwal && $tanggalAkhir) {
        $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
        $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
        $inqueryPemesanan->whereHas('pemesananProduk', function ($query) use ($tanggalAwal, $tanggalAkhir) {
            $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
        });
    } else {
        $inqueryPemesanan->whereHas('pemesananProduk', function ($query) {
            $query->whereDate('tanggal_kirim', Carbon::today());
        });
    }
    $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk.klasifikasi'])
        ->get()
        ->groupBy('produk_id')
        ->map(function ($groupedDetails) {
            return $groupedDetails->groupBy('toko_id')->map(function ($details) {
                return [
                    'jumlah' => $details->sum('jumlah'),
                    'toko' => $details->first()->pemesananProduk->toko,
                    'produk' => $details->first()->produk,
                    'klasifikasi' => $details->first()->produk->klasifikasi ?? 'N/A', // Tambahkan pengecekan untuk klasifikasi
                    'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
                    'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
                    'detail' => $details
                ];
            });
        });
}

// Gabungkan data permintaan dan pemesanan
$combinedData = $pemesananProduk->map(function ($tokoDetails, $produkId) use ($permintaanProduks) {
    return $tokoDetails->map(function ($detail, $tokoId) use ($permintaanProduks, $produkId) {
        $permintaan = $permintaanProduks[$produkId][$tokoId] ?? ['jumlah' => 0];
        return [
            'pesanan' => $detail['jumlah'],
            'permintaan' => $permintaan['jumlah'],
            'total' => $detail['jumlah'] + $permintaan['jumlah'], // Menghitung total dari pesanan dan permintaan
            'toko' => $detail['toko'],
            'produk' => $detail['produk'],
            'klasifikasi' => $detail['klasifikasi'] ?? 'N/A', // Tambahkan pengecekan untuk klasifikasi
        ];
    });
})->union(
    $permintaanProduks->map(function ($tokoDetails, $produkId) use ($pemesananProduk) {
        return $tokoDetails->map(function ($detail, $tokoId) use ($pemesananProduk, $produkId) {
            if (!isset($pemesananProduk[$produkId][$tokoId])) {
                return [
                    'pesanan' => 0, // Jika tidak ada pesanan, set ke 0
                    'permintaan' => $detail['jumlah'],
                    'total' => $detail['jumlah'], // Total hanya dari permintaan
                    'toko' => $detail['toko'],
                    'produk' => $detail['produk'],
                    'klasifikasi' => $detail['klasifikasi'] ?? 'N/A', // Tambahkan pengecekan untuk klasifikasi
                ];
            }
            return null; // Sudah digabungkan di map pertama
        })->filter(); // Menghilangkan null value
    })
);

return view('admin.laporan_estimasiproduksi.index', compact('permintaanProduks', 'pemesananProduk', 'combinedData', 'tableType'));


    return view('admin.laporan_estimasiproduksi.index', compact('permintaanProduks', 'pemesananProduk', 'combinedData', 'tableType'));
}




// rinci
// public function printReport(Request $request)
// {
//     $status = $request->status;
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;

//     // Query Permintaanproduk
//     $inqueryPermintaan = Permintaanproduk::query();
//     if ($status) {
//         $inqueryPermintaan->where('status', $status);
//     }
//     if ($tanggalAwal && $tanggalAkhir) {
//         $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//         $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//         $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) use ($tanggalAwal, $tanggalAkhir) {
//             $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
//         });
//     } else {
//         $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) {
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

//     // Query Pemesananproduk
//     $inqueryPemesanan = DetailPemesananProduk::query();
//     if ($status) {
//         $inqueryPemesanan->where('status', $status);
//     }
//     if ($tanggalAwal && $tanggalAkhir) {
//         $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//         $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//         $inqueryPemesanan->whereHas('pemesananProduk', function ($query) use ($tanggalAwal, $tanggalAkhir) {
//             $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
//         });
//     } else {
//         $inqueryPemesanan->whereHas('pemesananProduk', function ($query) {
//             $query->whereDate('tanggal_kirim', Carbon::today());
//         });
//     }
//     $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk'])
//         ->get()
//         ->groupBy('produk_id')
//         ->map(function ($groupedDetails) {
//             return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $details->first()->pemesananProduk->toko,
//                     'produk' => $details->first()->produk,
//                     'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
//                     'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
//                     'detail' => $details,
//                 ];
//             });
//         });

//     // Generate PDF
//     $options = new Options();
//     $options->set('isHtml5ParserEnabled', true);
//     $options->set('isRemoteEnabled', true);

//     $dompdf = new Dompdf($options);

//     // Load view with data
//     $html = view('admin.laporan_estimasiproduksi.print', compact('permintaanProduks', 'pemesananProduk'))->render();
//     $dompdf->loadHtml($html);

//     // Set paper size and orientation
//     $dompdf->setPaper('A4', 'portrait');

//     // Render the PDF
//     $dompdf->render();

//     // Stream the PDF
//     return $dompdf->stream('laporan_estimasiproduksi.pdf', ['Attachment' => false]);
// }


// public function printReport(Request $request)
// {
//     $status = $request->status;
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;
//     $tableType = $request->table_type;

//     $permintaanProduks = collect();
//     $pemesananProduk = collect();

//     // Query Pemesananproduk
//     if ($tableType == 'pemesanan' || $tableType == 'all') {
//         $inqueryPemesanan = DetailPemesananProduk::query();
//         if ($status) {
//             $inqueryPemesanan->where('status', $status);
//         }
//         if ($tanggalAwal && $tanggalAkhir) {
//             $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//             $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//             $inqueryPemesanan->whereHas('pemesananProduk', function ($query) use ($tanggalAwal, $tanggalAkhir) {
//                 $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
//             });
//         } else {
//             $inqueryPemesanan->whereHas('pemesananProduk', function ($query) {
//                 $query->whereDate('tanggal_kirim', Carbon::today());
//             });
//         }
//         $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk.klasifikasi'])
//             ->get()
//             ->groupBy('produk_id')
//             ->map(function ($groupedDetails) {
//                 return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                     return [
//                         'jumlah' => $details->sum('jumlah'),
//                         'toko' => $details->first()->pemesananProduk->toko,
//                         'produk' => $details->first()->produk,
//                         'klasifikasi' => $details->first()->produk->klasifikasi,
//                         'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
//                         'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
//                         'detail' => $details,
//                     ];
//                 });
//             });
//     }

//     // Query Permintaanproduk
//     if ($tableType == 'permintaan' || $tableType == 'all') {
//         $inqueryPermintaan = Permintaanproduk::query();
//         if ($status) {
//             $inqueryPermintaan->where('status', $status);
//         }
//         if ($tanggalAwal && $tanggalAkhir) {
//             $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
//             $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
//             $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) use ($tanggalAwal, $tanggalAkhir) {
//                 $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
//             });
//         } else {
//             $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) {
//                 $query->whereDate('tanggal_permintaan', Carbon::today());
//             });
//         }
//         $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk.klasifikasi'])
//             ->get()
//             ->flatMap(function ($permintaan) {
//                 return $permintaan->detailPermintaanProduks;
//             })
//             ->groupBy('produk_id')
//             ->map(function ($groupedDetails) {
//                 return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                     return [
//                         'jumlah' => $details->sum('jumlah'),
//                         'toko' => $details->first()->toko,
//                         'produk' => $details->first()->produk,
//                         'klasifikasi' => $details->first()->produk->klasifikasi,
//                         'tanggal_permintaan' => $details->first()->tanggal_permintaan,
//                     ];
//                 });
//             });
//     }

//     // Gabungkan data permintaan dan pemesanan jika memilih 'all'
//     $combinedData = collect();
//     if ($tableType == 'all') {
//         $combinedData = $permintaanProduks->merge($pemesananProduk);
//     } else {
//         $combinedData = ($tableType == 'permintaan') ? $permintaanProduks : $pemesananProduk;
//     }

//     // Generate PDF
//     $options = new Options();
//     $options->set('isHtml5ParserEnabled', true);
//     $options->set('isRemoteEnabled', true);

//     $dompdf = new Dompdf($options);

//     // Load view with data
//     $html = view('admin.laporan_estimasiproduksi.print', compact('permintaanProduks', 'pemesananProduk', 'combinedData', 'tableType', 'tanggalAwal', 'tanggalAkhir'))->render();
//     $dompdf->loadHtml($html);

//     // Set paper size and orientation
//     $dompdf->setPaper('A4', 'portrait');

//     // Render the PDF
//     $dompdf->render();

//     // Stream the PDF
//     return $dompdf->stream('laporan_estimasiproduksi.pdf', ['Attachment' => false]);
// }
public function printReport(Request $request)
{
    $status = $request->status;
    $tanggalAwal = $request->tanggal_awal;
    $tanggalAkhir = $request->tanggal_akhir;
    $tableType = $request->table_type;

    $permintaanProduks = collect();
    $pemesananProduk = collect();

    // Query Pemesananproduk
    if ($tableType == 'pemesanan' || $tableType == 'all') {
        $inqueryPemesanan = DetailPemesananProduk::query();
        if ($status) {
            $inqueryPemesanan->where('status', $status);
        }
        if ($tanggalAwal && $tanggalAkhir) {
            $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $inqueryPemesanan->whereHas('pemesananProduk', function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('tanggal_kirim', [$tanggalAwal, $tanggalAkhir]);
            });
        } else {
            $inqueryPemesanan->whereHas('pemesananProduk', function ($query) {
                $query->whereDate('tanggal_kirim', Carbon::today());
            });
        }
        $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk.klasifikasi'])
            ->get()
            ->groupBy('produk_id')
            ->map(function ($groupedDetails) {
                return $groupedDetails->groupBy('toko_id')->map(function ($details) {
                    return [
                        'jumlah' => $details->sum('jumlah'),
                        'toko' => $details->first()->pemesananProduk->toko,
                        'produk' => $details->first()->produk,
                        'klasifikasi' => $details->first()->produk->klasifikasi,
                        'kode_pemesanan' => $details->pluck('pemesananProduk.kode_pemesanan')->unique()->values(),
                        'tanggal_kirim' => $details->first()->pemesananProduk->tanggal_kirim,
                        'detail' => $details,
                    ];
                });
            });
    }

    // Query Permintaanproduk
    if ($tableType == 'permintaan' || $tableType == 'all') {
        $inqueryPermintaan = Permintaanproduk::query();
        if ($status) {
            $inqueryPermintaan->where('status', $status);
        }
        if ($tanggalAwal && $tanggalAkhir) {
            $tanggalAwal = Carbon::parse($tanggalAwal)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) use ($tanggalAwal, $tanggalAkhir) {
                $query->whereBetween('tanggal_permintaan', [$tanggalAwal, $tanggalAkhir]);
            });
        } else {
            $inqueryPermintaan->whereHas('detailPermintaanProduks', function ($query) {
                $query->whereDate('tanggal_permintaan', Carbon::today());
            });
        }
        $permintaanProduks = $inqueryPermintaan->with(['detailPermintaanProduks.toko', 'detailPermintaanProduks.produk.klasifikasi'])
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
                        'klasifikasi' => $details->first()->produk->klasifikasi,
                        'tanggal_permintaan' => $details->first()->tanggal_permintaan,
                    ];
                });
            });
    }

// Gabungkan data permintaan dan pemesanan
// $combinedData = $pemesananProduk->map(function ($tokoDetails, $produkId) use ($permintaanProduks) {
//     return $tokoDetails->map(function ($detail, $tokoId) use ($permintaanProduks, $produkId) {
//         $permintaan = $permintaanProduks[$produkId][$tokoId] ?? ['jumlah' => 0];
//         return [
//             'pesanan' => $detail['jumlah'],
//             'permintaan' => $permintaan['jumlah'],
//             'total' => $detail['jumlah'] + $permintaan['jumlah'], // Menghitung total dari pesanan dan permintaan
//             'toko' => $detail['toko'],
//             'produk' => $detail['produk'],
//             'klasifikasi' => $detail['klasifikasi'],
//         ];
//     });
// })->union(
//     $permintaanProduks->map(function ($tokoDetails, $produkId) use ($pemesananProduk) {
//         return $tokoDetails->map(function ($detail, $tokoId) use ($pemesananProduk, $produkId) {
//             if (!isset($pemesananProduk[$produkId][$tokoId])) {
//                 return [
//                     'pesanan' => 0, // Jika tidak ada pesanan, set ke 0
//                     'permintaan' => $detail['jumlah'],
//                     'total' => $detail['jumlah'], // Total hanya dari permintaan
//                     'toko' => $detail['toko'],
//                     'produk' => $detail['produk'],
//                     'klasifikasi' => $detail['klasifikasi'],
//                 ];
//             }
//             return null; // Sudah digabungkan di map pertama
//         })->filter(); // Menghilangkan null value
//     })
// );

// Gabungkan data permintaan dan pemesanan
$combinedData = $pemesananProduk->map(function ($tokoDetails, $produkId) use ($permintaanProduks) {
    return $tokoDetails->map(function ($detail, $tokoId) use ($permintaanProduks, $produkId) {
        $permintaan = $permintaanProduks[$produkId][$tokoId] ?? ['jumlah' => 0];
        return [
            'pesanan' => $detail['jumlah'],
            'permintaan' => $permintaan['jumlah'],
            'total' => $detail['jumlah'] + $permintaan['jumlah'], // Menghitung total dari pesanan dan permintaan
            'toko' => $detail['toko'],
            'produk' => $detail['produk'],
            'klasifikasi' => $detail['klasifikasi'],
        ];
    });
})->union(
    $permintaanProduks->map(function ($tokoDetails, $produkId) use ($pemesananProduk) {
        return $tokoDetails->map(function ($detail, $tokoId) use ($pemesananProduk, $produkId) {
            if (!isset($pemesananProduk[$produkId][$tokoId])) {
                return [
                    'pesanan' => 0, // Jika tidak ada pesanan, set ke 0
                    'permintaan' => $detail['jumlah'],
                    'total' => $detail['jumlah'], // Total hanya dari permintaan
                    'toko' => $detail['toko'],
                    'produk' => $detail['produk'],
                    'klasifikasi' => $detail['klasifikasi'],
                ];
            }
            return null; // Sudah digabungkan di map pertama
        })->filter(); // Menghilangkan null value
    })
);


    


    // Generate PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    // Load view with data
    $html = view('admin.laporan_estimasiproduksi.print', compact('permintaanProduks', 'pemesananProduk', 'combinedData', 'tableType', 'tanggalAwal', 'tanggalAkhir'))->render();
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'potrait');

    // Render the PDF
    $dompdf->render();

    // Stream the PDF
    return $dompdf->stream('laporan_estimasiproduksi.pdf', ['Attachment' => false]);
}


}