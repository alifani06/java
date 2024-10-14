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
use App\Models\Estimasiproduksi;
use Maatwebsite\Excel\Facades\Excel;




class SuratperintahproduksiController extends Controller{

   
    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_estimasi = $request->tanggal_estimasi;
        $tanggal_akhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

        $query = Estimasiproduksi::with(['detailestimasiproduksi.produk.klasifikasi', 'detailestimasiproduksi.toko']);

        // Filter berdasarkan status
        if ($status) {
            $query->whereHas('detailestimasiproduksi', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Filter berdasarkan tanggal permintaan
        if ($tanggal_estimasi && $tanggal_akhir) {
            $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_estimasi, $tanggal_akhir) {
                $query->whereBetween('tanggal_estimasi', [$tanggal_estimasi, $tanggal_akhir]);
            });
        } elseif ($tanggal_estimasi) {
            $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
            $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_estimasi) {
                $query->where('tanggal_estimasi', '>=', $tanggal_estimasi);
            });
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_akhir) {
                $query->where('tanggal_estimasi', '<=', $tanggal_akhir);
            });
        } else {
            $query->whereHas('detailestimasiproduksi', function ($query) {
                $query->whereDate('tanggal_estimasi', Carbon::today());
            });
        }

        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailestimasiproduksi', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }

        // Filter berdasarkan toko
        if ($toko_id) {
            $query->whereHas('detailestimasiproduksi', function ($query) use ($toko_id) {
                $query->where('toko_id', $toko_id);
            });
        }

        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('detailestimasiproduksi.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }

        $query->orderBy('id', 'DESC');

        $inquery = $query->get();

        // Mengelompokkan detail berdasarkan produk_id dan menjumlahkan jumlahnya
        $groupedInquery = $inquery->flatMap(function ($estimasiproduksi) {
            return $estimasiproduksi->detailestimasiproduksi;
        })->groupBy('produk_id')->map(function ($details) {
            $firstDetail = $details->first(); 
            $firstDetail->jumlah = $details->sum('jumlah'); 
            return $firstDetail;
        });
        

        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();

        return view('admin.suratperintahproduksi.index', compact('inquery','groupedInquery', 'produks', 'tokos', 'klasifikasis', 'klasifikasi_id'));
    }

//     public function index(Request $request)
// {
//     $status = $request->status;
//     $tanggal_estimasi = $request->tanggal_estimasi;
//     $tanggal_akhir = $request->tanggal_akhir;
//     $produk = $request->produk;
//     $toko_id = $request->toko_id;
//     $klasifikasi_id = $request->klasifikasi_id;

//     // Query utama
//     $query = Estimasiproduksi::with(['detailestimasiproduksi.produk.klasifikasi', 'detailestimasiproduksi.toko']);

//     // Filter berdasarkan status
//     if ($status) {
//         $query->whereHas('detailestimasiproduksi', function ($query) use ($status) {
//             $query->where('status', $status);
//         });
//     }

//     // Filter berdasarkan tanggal permintaan
//     if ($tanggal_estimasi && $tanggal_akhir) {
//         $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
//         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
//         $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_estimasi, $tanggal_akhir) {
//             $query->whereBetween('tanggal_estimasi', [$tanggal_estimasi, $tanggal_akhir]);
//         });
//     } elseif ($tanggal_estimasi) {
//         $tanggal_estimasi = Carbon::parse($tanggal_estimasi)->startOfDay();
//         $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_estimasi) {
//             $query->where('tanggal_estimasi', '>=', $tanggal_estimasi);
//         });
//     } elseif ($tanggal_akhir) {
//         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
//         $query->whereHas('detailestimasiproduksi', function ($query) use ($tanggal_akhir) {
//             $query->where('tanggal_estimasi', '<=', $tanggal_akhir);
//         });
//     } else {
//         $query->whereHas('detailestimasiproduksi', function ($query) {
//             $query->whereDate('tanggal_estimasi', Carbon::today());
//         });
//     }

//     // Filter berdasarkan produk
//     if ($produk) {
//         $query->whereHas('detailestimasiproduksi', function ($query) use ($produk) {
//             $query->where('produk_id', $produk);
//         });
//     }

//     // Filter berdasarkan toko
//     if ($toko_id) {
//         $query->whereHas('detailestimasiproduksi', function ($query) use ($toko_id) {
//             $query->where('toko_id', $toko_id);
//         });
//     }

//     // Filter berdasarkan klasifikasi
//     if ($klasifikasi_id) {
//         $query->whereHas('detailestimasiproduksi.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
//             $query->where('id', $klasifikasi_id);
//         });
//     }

//     // Ambil data dan urutkan berdasarkan ID secara descending
//     $query->orderBy('id', 'DESC');
//     $inquery = $query->get();

//     // Menggabungkan data dengan produk_id yang sama dan menjumlahkan jumlahnya
//     $groupedData = collect();
//     foreach ($inquery as $estimasiproduksi) {
//         foreach ($estimasiproduksi->detailestimasiproduksi as $detail) {
//             $produkId = $detail->produk_id;
//             $existing = $groupedData->firstWhere('produk_id', $produkId);
            
//             if ($existing) {
//                 // Jika produk sudah ada, tambahkan jumlahnya
//                 $existing['jumlah'] += $detail->jumlah;
//             } else {
//                 // Jika produk belum ada, tambahkan sebagai item baru
//                 $groupedData->push([
//                     'produk_id' => $produkId,
//                     'produk_nama' => $detail->produk->nama_produk,
//                     'kode_lama' => $detail->produk->kode_lama,
//                     'klasifikasi' => $detail->produk->klasifikasi->nama ?? 'N/A',
//                     'jumlah' => $detail->jumlah,
//                 ]);
//             }
//         }
//     }

//     // Ambil semua data untuk dropdown filter
//     $produks = Produk::all();
//     $tokos = Toko::all();
//     $klasifikasis = Klasifikasi::all();

//     return view('admin.suratperintahproduksi.index', compact('groupedData', 'produks', 'tokos', 'klasifikasis', 'klasifikasi_id'));
// }




// public function printReportestimasi(Request $request)
// {
//     // Ambil filter dari request
//     $klasifikasi_id = $request->get('klasifikasi_id');

//     // Buat query untuk mengambil data estimasi produksi
//     $inquery = EstimasiProduksi::with(['detailestimasiproduksi.produk.klasifikasi'])
//         ->when($klasifikasi_id, function ($query) use ($klasifikasi_id) {
//             // Filter berdasarkan klasifikasi_id jika ada
//             $query->whereHas('detailestimasiproduksi.produk', function ($query) use ($klasifikasi_id) {
//                 $query->where('klasifikasi_id', $klasifikasi_id);
//             });
//         })
//         ->get();

//     // Persiapkan data untuk ditampilkan
//     $groupedData = [];
//     foreach ($inquery as $estimasiproduksi) {
//         foreach ($estimasiproduksi->detailestimasiproduksi as $detail) {
//             // Tentukan kategori berdasarkan 'kategori'
//             $kategori = $detail->kategori; // 'permintaan' atau 'pemesanan'
//             $produkId = $detail->produk_id;
//             $klasifikasi = $detail->produk->klasifikasi->nama ?? 'N/A';

//             if (!isset($groupedData[$klasifikasi])) {
//                 $groupedData[$klasifikasi] = [];
//             }

//             if (!isset($groupedData[$klasifikasi][$produkId])) {
//                 $groupedData[$klasifikasi][$produkId] = [
//                     'klasifikasi' => $klasifikasi,
//                     'kode_lama' => $detail->kode_lama,
//                     'nama_produk' => $detail->nama_produk,
//                     'stok' => [1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-', 6 => '-'], 
//                     'pes' => [1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-', 6 => '-'], 
//                 ];
//             }

//             // Isi jumlah berdasarkan kategori
//             if ($kategori === 'permintaan') {
//                 $tokoId = 1; // Misalkan toko_id 1 untuk 'permintaan'
//                 if ($groupedData[$klasifikasi][$produkId]['stok'][$tokoId] === '-') {
//                     $groupedData[$klasifikasi][$produkId]['stok'][$tokoId] = $detail->jumlah; 
//                 } else {
//                     $groupedData[$klasifikasi][$produkId]['stok'][$tokoId] += $detail->jumlah; 
//                 }
//             } elseif ($kategori === 'pesanan') {
//                 $tokoId = 2; // Misalkan toko_id 2 untuk 'pemesanan'
//                 if ($groupedData[$klasifikasi][$produkId]['pes'][$tokoId] === '-') {
//                     $groupedData[$klasifikasi][$produkId]['pes'][$tokoId] = $detail->jumlah; 
//                 } else {
//                     $groupedData[$klasifikasi][$produkId]['pes'][$tokoId] += $detail->jumlah;
//                 }
//             }
//         }
//     }

//     // Generate PDF atau lakukan tindakan lain sesuai kebutuhan
//     $pdf = FacadePdf::loadView('admin.suratperintahproduksi.print', compact('groupedData', 'klasifikasi_id'));
//     return $pdf->stream('laporan_estimasi.pdf');
// }

public function printReportestimasi(Request $request)
{
    // Ambil filter dari request
    $klasifikasi_id = $request->get('klasifikasi_id');
    $tanggal = $request->get('tanggal_estimasi');
    $tanggal_akhir = $request->get('tanggal_akhir');

    // Buat query untuk mengambil data estimasi produksi
    $inquery = EstimasiProduksi::with(['detailestimasiproduksi.produk.klasifikasi'])
        ->when($klasifikasi_id, function ($query) use ($klasifikasi_id) {
            // Filter berdasarkan klasifikasi_id jika ada
            $query->whereHas('detailestimasiproduksi.produk', function ($query) use ($klasifikasi_id) {
                $query->where('klasifikasi_id', $klasifikasi_id);
            });
        })
        ->get();

    // Persiapkan data untuk ditampilkan
    $groupedData = [];
    foreach ($inquery as $estimasiproduksi) {
        foreach ($estimasiproduksi->detailestimasiproduksi as $detail) {
            // Tentukan kategori berdasarkan 'kategori'
            $kategori = $detail->kategori; // 'permintaan' atau 'pemesanan'
            $produkId = $detail->produk_id;
            $klasifikasi = $detail->produk->klasifikasi->nama ?? 'N/A';

            if (!isset($groupedData[$klasifikasi])) {
                $groupedData[$klasifikasi] = [];
            }

            if (!isset($groupedData[$klasifikasi][$produkId])) {
                $groupedData[$klasifikasi][$produkId] = [
                    'klasifikasi' => $klasifikasi,
                    'kode_lama' => $detail->kode_lama,
                    'nama_produk' => $detail->nama_produk,
                    'stok' => [1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-', 6 => '-'], 
                    'pes' => [1 => '-', 2 => '-', 3 => '-', 4 => '-', 5 => '-', 6 => '-'], 
                ];
            }

            // Isi jumlah berdasarkan kategori
            if ($kategori === 'permintaan') {
                $tokoId = 1; // Misalkan toko_id 1 untuk 'permintaan'
                if ($groupedData[$klasifikasi][$produkId]['stok'][$tokoId] === '-') {
                    $groupedData[$klasifikasi][$produkId]['stok'][$tokoId] = $detail->jumlah; 
                } else {
                    $groupedData[$klasifikasi][$produkId]['stok'][$tokoId] += $detail->jumlah; 
                }
            } elseif ($kategori === 'pesanan') {
                $tokoId = 2; // Misalkan toko_id 2 untuk 'pemesanan'
                if ($groupedData[$klasifikasi][$produkId]['pes'][$tokoId] === '-') {
                    $groupedData[$klasifikasi][$produkId]['pes'][$tokoId] = $detail->jumlah; 
                } else {
                    $groupedData[$klasifikasi][$produkId]['pes'][$tokoId] += $detail->jumlah;
                }
            }
        }
    }

    // Format tanggal untuk tampilan PDF
    $formattedStartDate = $tanggal ? Carbon::parse($tanggal)->translatedFormat('d F Y') : 'Tidak ada';
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') : 'Tidak ada';
    $currentDateTime = Carbon::now()->translatedFormat('d F Y H:i');

    // Generate PDF
    $pdf = FacadePdf::loadView('admin.suratperintahproduksi.print', [
        'groupedData' => $groupedData,
        'klasifikasi_id' => $klasifikasi_id,
        'formattedStartDate' => $formattedStartDate,
        'formattedEndDate' => $formattedEndDate,
        'currentDateTime' => $currentDateTime,
    ]);

    return $pdf->stream('laporan_estimasi.pdf');
}






}