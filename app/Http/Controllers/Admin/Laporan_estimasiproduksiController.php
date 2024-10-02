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


public function index(Request $request)
{
    $tanggal = $request->tanggal;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    // Query untuk pemesanan produk
    $queryPemesanan = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi']);

    // Filter pemesanan berdasarkan tanggal_kirim
    if ($tanggal && $tanggal_akhir) {
        $queryPemesanan->whereBetween('tanggal_kirim', [Carbon::parse($tanggal)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()]);
    } elseif ($tanggal) {
        $queryPemesanan->where('tanggal_kirim', '>=', Carbon::parse($tanggal)->startOfDay());
    } elseif ($tanggal_akhir) {
        $queryPemesanan->where('tanggal_kirim', '<=', Carbon::parse($tanggal_akhir)->endOfDay());
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $queryPemesanan->where('toko_id', $toko_id);
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $queryPemesanan->whereHas('detailpemesananproduk.produk', function($query) use ($klasifikasi_id) {
            $query->where('klasifikasi_id', $klasifikasi_id);
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $queryPemesanan->whereHas('detailpemesananproduk', function($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Query untuk permintaan produk
    $queryPermintaan = PermintaanProduk::with(['detailpermintaanproduks.produk.klasifikasi', 'detailpermintaanproduks.toko']);

    // Filter permintaan berdasarkan tanggal_permintaan
    if ($tanggal && $tanggal_akhir) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function ($query) use ($tanggal, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [Carbon::parse($tanggal)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()]);
        });
    } elseif ($tanggal) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function ($query) use ($tanggal) {
            $query->where('tanggal_permintaan', '>=', Carbon::parse($tanggal)->startOfDay());
        });
    } elseif ($tanggal_akhir) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', Carbon::parse($tanggal_akhir)->endOfDay());
        });
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $queryPermintaan->whereHas('detailpermintaanproduks.toko', function($query) use ($toko_id) {
            $query->where('id', $toko_id);
        });
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $queryPermintaan->whereHas('detailpermintaanproduks.produk', function($query) use ($klasifikasi_id) {
            $query->where('klasifikasi_id', $klasifikasi_id);
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Ambil hasil query
    $pemesanan = $queryPemesanan->get();
    $permintaan = $queryPermintaan->get();

    $produks = Produk::all();
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();

    return view('admin.laporan_estimasiproduksi.index', compact('pemesanan', 'permintaan', 'produks', 'tokos', 'klasifikasis'));
}



public function indexpemesanan(Request $request)
{
    $status = $request->status;
    $tanggal_kirim = $request->tanggal_kirim;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    // Query dasar untuk mengambil data pemesanan produk
    $query = Pemesananproduk::query();

    // Filter berdasarkan status
    if ($status) {
        $query->where('status', $status);
    }

    // Filter berdasarkan tanggal pemesanan
    if ($tanggal_kirim && $tanggal_akhir) {
        $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereBetween('tanggal_kirim', [$tanggal_kirim, $tanggal_akhir]);
    } elseif ($tanggal_kirim) {
        $tanggal_kirim = Carbon::parse($tanggal_kirim)->startOfDay();
        $query->where('tanggal_kirim', '>=', $tanggal_kirim);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->where('tanggal_kirim', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
        $query->whereDate('tanggal_kirim', Carbon::today());
    }

    // Filter berdasarkan produk
    if ($produk) {
        $query->whereHas('detailpemesananproduk', function ($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $query->where('toko_id', $toko_id);
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $query->whereHas('detailpemesananproduk.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    // Urutkan data berdasarkan ID secara descending 
    // $query->orderBy('id', 'DESC');

    // Ambil data pemesanan produk
    $inquery = $query->with(['toko', 'detailpemesananproduk.produk.klasifikasi'])->get();

    // Ambil semua data produk untuk dropdown
    $produks = Produk::all();

    // Ambil semua data toko untuk dropdown
    $tokos = Toko::all();

    // Ambil semua klasifikasi untuk dropdown
    $klasifikasis = Klasifikasi::all();

    // Kembalikan view dengan data pemesanan produk, produk, toko, dan klasifikasi
    return view('admin.laporan_estimasiproduksi.indexpemesanan', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
}

public function indexpermintaan(Request $request)
{
    $status = $request->status;
    $tanggal_permintaan = $request->tanggal_permintaan;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    $query = PermintaanProduk::with(['detailpermintaanproduks.produk.klasifikasi', 'detailpermintaanproduks.toko']);

    // Filter berdasarkan status
    if ($status) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($status) {
            $query->where('status', $status);
        });
    }

    // Filter berdasarkan tanggal permintaan
    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $query->orderBy('id', 'DESC');

    $inquery = $query->get();

    $produks = Produk::all();
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();

    return view('admin.laporan_estimasiproduksi.indexpermintaan', compact('inquery', 'produks', 'tokos', 'klasifikasis', 'klasifikasi_id'));
}

public function printReportPermintaan(Request $request)
{
    $klasifikasi_id = $request->get('klasifikasi_id');
    $toko_id = $request->get('toko_id');
    $tanggal_permintaan = $request->get('tanggal_permintaan');
    $tanggal_akhir = $request->get('tanggal_akhir');

    $query = PermintaanProduk::with([
        'detailpermintaanproduks.produk.klasifikasi.subklasifikasi',
        'detailpermintaanproduks.toko'
    ]);

    // Filter berdasarkan tanggal dan toko
    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $permintaanProduk = $query->get();
    $tokoData = $toko_id ? Toko::where('id', $toko_id)->get() : Toko::all();

    $filteredKlasifikasi = null;
    if ($klasifikasi_id) {
        $filteredKlasifikasi = Klasifikasi::find($klasifikasi_id);
    }

    $formattedStartDate = $tanggal_permintaan ? Carbon::parse($tanggal_permintaan)->format('d-m-Y') : 'N/A';
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

    // Menentukan nama toko
    $branchName = $toko_id ? Toko::find($toko_id)->nama_toko : 'Semua Toko';

    // Buat PDF menggunakan Facade Pdf
    $pdf = FacadePdf::loadView('admin.laporan_permintaanproduk.print', [
        'permintaanProduk' => $permintaanProduk,
        'tokoData' => $tokoData,
        'klasifikasi_id' => $klasifikasi_id,
        'filteredKlasifikasi' => $filteredKlasifikasi,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
        'branchName' => $branchName, // Sertakan variabel nama cabang toko
    ]);

    // Menambahkan nomor halaman di kanan bawah
    $pdf->output();
    $dompdf = $pdf->getDomPDF();
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 8;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
        $y = $canvas->get_height() - 15; // 15 pixel dari bawah

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $pdf->stream('laporan_permintaan_barangjadi.pdf');
}

public function printReportPermintaantoko(Request $request)
{
    $klasifikasi_id = $request->get('klasifikasi_id');
    $toko_id = $request->get('toko_id');
    $tanggal_permintaan = $request->get('tanggal_permintaan');
    $tanggal_akhir = $request->get('tanggal_akhir');

    $query = PermintaanProduk::with([
        'detailpermintaanproduks.produk.klasifikasi.subklasifikasi',
        'detailpermintaanproduks.toko'
    ]);

    // Filter berdasarkan tanggal dan toko
    if ($tanggal_permintaan && $tanggal_akhir) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [$tanggal_permintaan, $tanggal_akhir]);
        });
    } elseif ($tanggal_permintaan) {
        $tanggal_permintaan = Carbon::parse($tanggal_permintaan)->startOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_permintaan) {
            $query->where('tanggal_permintaan', '>=', $tanggal_permintaan);
        });
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $query->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', $tanggal_akhir);
        });
    } else {
        $query->whereHas('detailpermintaanproduks', function ($query) {
            $query->whereDate('tanggal_permintaan', Carbon::today());
        });
    }

    if ($toko_id) {
        $query->whereHas('detailpermintaanproduks', function ($query) use ($toko_id) {
            $query->where('toko_id', $toko_id);
        });
    }

    if ($klasifikasi_id) {
        $query->whereHas('detailpermintaanproduks.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
            $query->where('id', $klasifikasi_id);
        });
    }

    $permintaanProduk = $query->get();
    $tokoData = $toko_id ? Toko::where('id', $toko_id)->get() : Toko::all();

    $filteredKlasifikasi = null;
    if ($klasifikasi_id) {
        $filteredKlasifikasi = Klasifikasi::find($klasifikasi_id);
    }

    $formattedStartDate = $tanggal_permintaan ? Carbon::parse($tanggal_permintaan)->format('d-m-Y') : 'N/A';
    $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

    // Menentukan nama toko
    $branchName = $toko_id ? Toko::find($toko_id)->nama_toko : 'Semua Toko';

    // Buat PDF menggunakan Facade Pdf
    $pdf = FacadePdf::loadView('admin.laporan_estimasiproduksi.printpermintaantoko', [
        'permintaanProduk' => $permintaanProduk,
        'tokoData' => $tokoData,
        'klasifikasi_id' => $klasifikasi_id,
        'filteredKlasifikasi' => $filteredKlasifikasi,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
        'branchName' => $branchName, // Sertakan variabel nama cabang toko
    ]);

    // Menambahkan nomor halaman di kanan bawah
    $pdf->output();
    $dompdf = $pdf->getDomPDF();
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 8;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
        $y = $canvas->get_height() - 15; // 15 pixel dari bawah

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $pdf->stream('laporan_permintaan_barangjadi.pdf');
}

// public function printReportPemesanan(Request $request)
// {
//     $status = $request->status;
//     $tanggalAwal = $request->tanggal_awal;
//     $tanggalAkhir = $request->tanggal_akhir;

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

//     $pemesananProduk = $inqueryPemesanan->with(['pemesananProduk.toko', 'produk.klasifikasi'])
//         ->get()
//         ->groupBy('produk_id')
//         ->map(function ($groupedDetails) {
//             return $groupedDetails->groupBy('toko_id')->map(function ($details) {
//                 return [
//                     'jumlah' => $details->sum('jumlah'),
//                     'toko' => $details->first()->pemesananProduk->toko,
//                     'produk' => $details->first()->produk,
//                     'klasifikasi' => $details->first()->produk->klasifikasi,
//                 ];
//             });
//         });

//     // Generate PDF
//     return $this->generatePDF('admin.laporan_estimasiproduksi.printpemesanan', compact('pemesananProduk'), 'laporan_pemesanan.pdf');
// }

public function printReportPemesanan(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'status' => 'nullable|string',
        'tanggal_kirim' => 'nullable|date',
        'tanggal_akhir' => 'nullable|date',
        'toko_id' => 'nullable|integer',
    ]);

    // Tangkap data dari request
    $status = htmlspecialchars($validatedData['status'] ?? null);
    $tanggalPemesanan = $validatedData['tanggal_kirim'] ?? null;
    $tanggalAkhir = $validatedData['tanggal_akhir'] ?? null;
    $tokoId = htmlspecialchars($validatedData['toko_id'] ?? '0');

    // Definisikan daftar toko
    $tokoList = [
        1 => 'Banjaran',
        2 => 'Tegal',
        3 => 'Slawi',
        4 => 'Pemalang',
        5 => 'Bumiayu',
        6 => 'Cilacap',
    ];

    // Tentukan toko yang akan ditampilkan berdasarkan filter
    if ($tokoId && $tokoId != '0') {
        // Jika toko tertentu dipilih
        $tokoFieldMap = [
            $tokoId => strtolower($tokoList[$tokoId] ?? 'unknown'),
        ];
    } else {
        // Jika "Semua Toko" dipilih
        $tokoFieldMap = [];
        foreach ($tokoList as $id => $name) {
            $tokoFieldMap[$id] = strtolower($name);
        }
    }

    // Query untuk mendapatkan data pemesanan produk
    $query = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi'])
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalPemesanan, $tanggalAkhir) {
            $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            return $query->whereBetween('tanggal_kirim', [$tanggalPemesanan, $tanggalAkhir]);
        })
        ->when($tanggalPemesanan && !$tanggalAkhir, function ($query) use ($tanggalPemesanan) {
            $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
            return $query->where('tanggal_kirim', '>=', $tanggalPemesanan);
        })
        ->when(!$tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalAkhir) {
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            return $query->where('tanggal_kirim', '<=', $tanggalAkhir);
        })
        ->when(!$tanggalPemesanan && !$tanggalAkhir, function ($query) {
            return $query->whereDate('tanggal_kirim', Carbon::today());
        })
        ->when($tokoId && $tokoId != '0', function ($query) use ($tokoId) {
            return $query->where('toko_id', $tokoId);
        })
        ->orderBy('id', 'DESC')
        ->get();

    // Pengelompokan data berdasarkan klasifikasi
    $groupedData = [];
    foreach ($query as $item) {
        foreach ($item->detailpemesananproduk as $detail) {
            $klasifikasi = $detail->produk->klasifikasi->nama ?? 'Tidak ada';
            $key = $detail->kode_produk . '-' . ($detail->produk->klasifikasi->id ?? 'no-klasifikasi');

            if (!isset($groupedData[$klasifikasi])) {
                $groupedData[$klasifikasi] = [];
            }
            if (!isset($groupedData[$klasifikasi][$key])) {
                // Inisialisasi data produk
                $dataArray = [
                    'klasifikasi' => $klasifikasi,
                    'kode_produk' => $detail->kode_produk ?? 'Tidak ada',
                    'kode_lama' => $detail->kode_lama ?? 'Tidak ada',
                    'nama_produk' => $detail->nama_produk ?? 'Tidak ada',
                    'subtotal' => 0,
                ];
                // Inisialisasi field toko sesuai dengan toko yang dipilih
                foreach ($tokoFieldMap as $field) {
                    $dataArray[$field] = 0;
                }
                $groupedData[$klasifikasi][$key] = $dataArray;
            }
            // Map toko_id ke field toko yang sesuai
            $tokoField = $tokoFieldMap[$item->toko_id] ?? null;
            if ($tokoField) {
                $groupedData[$klasifikasi][$key][$tokoField] += (int) $detail->jumlah;
                $groupedData[$klasifikasi][$key]['subtotal'] += (int) $detail->jumlah;

            }
        }
    }

    foreach ($groupedData as $klasifikasi => &$items) {
        // Urutkan berdasarkan kode_lama (asumsikan kode_lama adalah string atau integer)
        uasort($items, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']); // Menggunakan strcmp untuk sorting string
        });
    }
    // Format tanggal untuk tampilan PDF
    $formattedStartDate = $tanggalPemesanan ? Carbon::parse($tanggalPemesanan)->format('d-m-Y') : null;
    $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : null;

    // Hitung total subtotal
    $totalSubtotal = 0;
    foreach ($groupedData as $klasifikasi => $items) {
        foreach ($items as $data) {
            $totalSubtotal += $data['subtotal'];
        }
    }

    // Buat PDF menggunakan Facade PDF
    $pdf = FacadePdf::loadView('admin.laporan_estimasiproduksi.printpemesanan', [
        'groupedData' => $groupedData,
        'totalSubtotal' => $totalSubtotal,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
        'toko_id' => $tokoId,
        'tokoFieldMap' => $tokoFieldMap,
        'tokoList' => $tokoList, 
        'selectedCabang' => $tokoList[$tokoId] ?? 'Semua Toko', // Tambahkan variabel ini
    ]);
    

    // Menambahkan nomor halaman di kanan bawah
    $pdf->output();
    $dompdf = $pdf->getDomPDF();
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 8;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10;
        $y = $canvas->get_height() - 15;

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $pdf->stream('Laporan_Pemesanan_Produk.pdf');
}

public function printReportPemesanantoko(Request $request)
{
    // Validasi input
    $validatedData = $request->validate([
        'status' => 'nullable|string',
        'tanggal_kirim' => 'nullable|date',
        'tanggal_akhir' => 'nullable|date',
        'toko_id' => 'nullable|integer',
    ]);

    // Tangkap data dari request
    $status = htmlspecialchars($validatedData['status'] ?? null);
    $tanggalPemesanan = $validatedData['tanggal_kirim'] ?? null;
    $tanggalAkhir = $validatedData['tanggal_akhir'] ?? null;
    $tokoId = htmlspecialchars($validatedData['toko_id'] ?? '0');

    // Definisikan daftar toko
    $tokoList = [
        1 => 'Banjaran',
        2 => 'Tegal',
        3 => 'Slawi',
        4 => 'Pemalang',
        5 => 'Bumiayu',
        6 => 'Cilacap',
    ];

    // Tentukan toko yang akan ditampilkan berdasarkan filter
    if ($tokoId && $tokoId != '0') {
        // Jika toko tertentu dipilih
        $tokoFieldMap = [
            $tokoId => strtolower($tokoList[$tokoId] ?? 'unknown'),
        ];
    } else {
        // Jika "Semua Toko" dipilih
        $tokoFieldMap = [];
        foreach ($tokoList as $id => $name) {
            $tokoFieldMap[$id] = strtolower($name);
        }
    }

    // Query untuk mendapatkan data pemesanan produk
    $query = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi'])
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalPemesanan, $tanggalAkhir) {
            $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            return $query->whereBetween('tanggal_kirim', [$tanggalPemesanan, $tanggalAkhir]);
        })
        ->when($tanggalPemesanan && !$tanggalAkhir, function ($query) use ($tanggalPemesanan) {
            $tanggalPemesanan = Carbon::parse($tanggalPemesanan)->startOfDay();
            return $query->where('tanggal_kirim', '>=', $tanggalPemesanan);
        })
        ->when(!$tanggalPemesanan && $tanggalAkhir, function ($query) use ($tanggalAkhir) {
            $tanggalAkhir = Carbon::parse($tanggalAkhir)->endOfDay();
            return $query->where('tanggal_kirim', '<=', $tanggalAkhir);
        })
        ->when(!$tanggalPemesanan && !$tanggalAkhir, function ($query) {
            return $query->whereDate('tanggal_kirim', Carbon::today());
        })
        ->when($tokoId && $tokoId != '0', function ($query) use ($tokoId) {
            return $query->where('toko_id', $tokoId);
        })
        ->orderBy('id', 'DESC')
        ->get();

    // Pengelompokan data berdasarkan klasifikasi
    $groupedData = [];
    foreach ($query as $item) {
        foreach ($item->detailpemesananproduk as $detail) {
            $klasifikasi = $detail->produk->klasifikasi->nama ?? 'Tidak ada';
            $key = $detail->kode_produk . '-' . ($detail->produk->klasifikasi->id ?? 'no-klasifikasi');

            if (!isset($groupedData[$klasifikasi])) {
                $groupedData[$klasifikasi] = [];
            }
            if (!isset($groupedData[$klasifikasi][$key])) {
                // Inisialisasi data produk
                $dataArray = [
                    'klasifikasi' => $klasifikasi,
                    'kode_produk' => $detail->kode_produk ?? 'Tidak ada',
                    'kode_lama' => $detail->kode_lama ?? 'Tidak ada',
                    'nama_produk' => $detail->nama_produk ?? 'Tidak ada',
                    'subtotal' => 0,
                ];
                // Inisialisasi field toko sesuai dengan toko yang dipilih
                foreach ($tokoFieldMap as $field) {
                    $dataArray[$field] = 0;
                }
                $groupedData[$klasifikasi][$key] = $dataArray;
            }
            // Map toko_id ke field toko yang sesuai
            $tokoField = $tokoFieldMap[$item->toko_id] ?? null;
            if ($tokoField) {
                $groupedData[$klasifikasi][$key][$tokoField] += (int) $detail->jumlah;
                $groupedData[$klasifikasi][$key]['subtotal'] += (int) $detail->jumlah;

            }
        }
    }

    foreach ($groupedData as $klasifikasi => &$items) {
        // Urutkan berdasarkan kode_lama (asumsikan kode_lama adalah string atau integer)
        uasort($items, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']); // Menggunakan strcmp untuk sorting string
        });
    }
    // Format tanggal untuk tampilan PDF
    $formattedStartDate = $tanggalPemesanan ? Carbon::parse($tanggalPemesanan)->format('d-m-Y') : null;
    $formattedEndDate = $tanggalAkhir ? Carbon::parse($tanggalAkhir)->format('d-m-Y') : null;

    // Hitung total subtotal
    $totalSubtotal = 0;
    foreach ($groupedData as $klasifikasi => $items) {
        foreach ($items as $data) {
            $totalSubtotal += $data['subtotal'];
        }
    }

    // Buat PDF menggunakan Facade PDF
    $pdf = FacadePdf::loadView('admin.laporan_estimasiproduksi.printpemesanantoko', [
        'groupedData' => $groupedData,
        'totalSubtotal' => $totalSubtotal,
        'startDate' => $formattedStartDate,
        'endDate' => $formattedEndDate,
        'toko_id' => $tokoId,
        'tokoFieldMap' => $tokoFieldMap,
        'tokoList' => $tokoList, 
        'selectedCabang' => $tokoList[$tokoId] ?? 'Semua Toko', // Tambahkan variabel ini
    ]);
    

    // Menambahkan nomor halaman di kanan bawah
    $pdf->output();
    $dompdf = $pdf->getDomPDF();
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 8;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10;
        $y = $canvas->get_height() - 15;

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $pdf->stream('Laporan_Pemesanan_Produk.pdf');
}

public function printReportAll(Request $request)
{
    $tanggal = $request->tanggal;
    $tanggal_akhir = $request->tanggal_akhir;
    $produk = $request->produk;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    // Query untuk pemesanan produk
    $queryPemesanan = Pemesananproduk::with(['toko', 'detailpemesananproduk.produk.klasifikasi']);

    // Filter pemesanan berdasarkan tanggal_kirim
    if ($tanggal && $tanggal_akhir) {
        $queryPemesanan->whereBetween('tanggal_kirim', [Carbon::parse($tanggal)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()]);
    } elseif ($tanggal) {
        $queryPemesanan->where('tanggal_kirim', '>=', Carbon::parse($tanggal)->startOfDay());
    } elseif ($tanggal_akhir) {
        $queryPemesanan->where('tanggal_kirim', '<=', Carbon::parse($tanggal_akhir)->endOfDay());
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $queryPemesanan->where('toko_id', $toko_id);
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $queryPemesanan->whereHas('detailpemesananproduk.produk', function($query) use ($klasifikasi_id) {
            $query->where('klasifikasi_id', $klasifikasi_id);
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $queryPemesanan->whereHas('detailpemesananproduk', function($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Query untuk permintaan produk
    $queryPermintaan = PermintaanProduk::with(['detailpermintaanproduks.produk.klasifikasi', 'detailpermintaanproduks.toko']);

    // Filter permintaan berdasarkan tanggal_permintaan
    if ($tanggal && $tanggal_akhir) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function ($query) use ($tanggal, $tanggal_akhir) {
            $query->whereBetween('tanggal_permintaan', [Carbon::parse($tanggal)->startOfDay(), Carbon::parse($tanggal_akhir)->endOfDay()]);
        });
    } elseif ($tanggal) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function ($query) use ($tanggal) {
            $query->where('tanggal_permintaan', '>=', Carbon::parse($tanggal)->startOfDay());
        });
    } elseif ($tanggal_akhir) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function ($query) use ($tanggal_akhir) {
            $query->where('tanggal_permintaan', '<=', Carbon::parse($tanggal_akhir)->endOfDay());
        });
    }

    // Filter berdasarkan toko
    if ($toko_id) {
        $queryPermintaan->whereHas('detailpermintaanproduks.toko', function($query) use ($toko_id) {
            $query->where('id', $toko_id);
        });
    }

    // Filter berdasarkan klasifikasi
    if ($klasifikasi_id) {
        $queryPermintaan->whereHas('detailpermintaanproduks.produk', function($query) use ($klasifikasi_id) {
            $query->where('klasifikasi_id', $klasifikasi_id);
        });
    }

    // Filter berdasarkan produk
    if ($produk) {
        $queryPermintaan->whereHas('detailpermintaanproduks', function($query) use ($produk) {
            $query->where('produk_id', $produk);
        });
    }

    // Ambil hasil query
    $pemesanan = $queryPemesanan->get();
    $permintaan = $queryPermintaan->get();
// Gabungkan hasil permintaan ke dalam pemesanan berdasarkan produk_id
$pemesanan = $pemesanan->map(function($pesanan) use ($permintaan) {
    $produk_id = $pesanan->detailpemesananproduk->first()->produk_id;

    // Inisialisasi nilai stok dan pesanan dari tiap kota
    $pesanan->pesanan_banjaran = $pesanan->detailpemesananproduk->where('toko.nama', 'Banjaran')->sum('jumlah');
    $pesanan->stok_banjaran = $permintaan->where('detailpermintaanproduks.produk_id', $produk_id)
        ->where('detailpermintaanproduks.toko.nama', 'Banjaran')
        ->sum('detailpermintaanproduks.jumlah');
    
    $pesanan->stok_tegal = $pesanan->detailpemesananproduk->where('toko.nama', 'Tegal')->sum('jumlah');
    $pesanan->pesanan_tegal = $permintaan->where('detailpermintaanproduks.produk_id', $produk_id)
        ->where('detailpermintaanproduks.toko.nama', 'Tegal')
        ->sum('detailpermintaanproduks.jumlah');

    // Lanjutkan untuk kota lainnya...
    // Slawi, Bumiayu, Pemalang, Cilacap

    // Hitung total pesanan dan total permintaan
    $pesanan->jumlah_pesanan = $pesanan->detailpemesananproduk->sum('jumlah');
    $pesanan->jumlah_permintaan = $permintaan->where('detailpermintaanproduks.produk_id', $produk_id)->sum('detailpermintaanproduks.jumlah');
    
    return $pesanan;
});


    // Generate PDF
    $pdf = FacadePdf::loadView('admin.laporan_estimasiproduksi.print', compact('pemesanan', 'tanggal', 'tanggal_akhir'));
    $pdf->output();
    $dompdf = $pdf->getDomPDF();
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 8;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10;
        $y = $canvas->get_height() - 15;

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    return $pdf->stream('report.pdf');
}






}