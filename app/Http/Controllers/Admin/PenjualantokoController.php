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
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Klasifikasi;
use App\Models\Metodepembayaran;
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class PenjualantokoController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $inquery = Penjualanproduk::with('metodePembayaran')
            ->whereDate('created_at', $today)
            ->orWhere(function ($query) use ($today) {
                $query->where('status', 'unpost')
                    ->whereDate('created_at', '<', $today);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('admin.penjualan_toko.index', compact('inquery'));
    }
    



    // public function create(Request $request) {
    //     $tanggal_penjualan = $request->tanggal_penjualan;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $toko_id = $request->toko_id;
    //     $produk_id = $request->produk;
    //     $klasifikasi_id = $request->klasifikasi_id;
    
    //     // Query dasar untuk mengambil data penjualan produk sesuai toko yang dipilih
    //     $inquery = Penjualanproduk::with('detailPenjualanProduk.produk')
    //         ->when($toko_id, function ($query, $toko_id) {
    //             return $query->where('toko_id', $toko_id);
    //         })
    //         ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
    //             $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             return $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    //         })
    //         ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
    //             $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //             return $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    //         })
    //         ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             return $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    //         });
    
    //     // Ambil data penjualan
    //     $inquery = $inquery->get();
    
    //     // Gabungkan hasil berdasarkan produk_id
    //     $finalResults = [];
    
    //     foreach ($inquery as $penjualan) {
    //         foreach ($penjualan->detailPenjualanProduk as $detail) {
    //             $produk = $detail->produk;
    
    //             // Pastikan produk tidak null sebelum mengakses properti
    //             if ($produk) {
    //                 // Filter produk berdasarkan klasifikasi jika klasifikasi_id dipilih
    //                 if ($klasifikasi_id && $produk->klasifikasi_id != $klasifikasi_id) {
    //                     continue; // Lewati produk yang tidak sesuai dengan klasifikasi
    //                 }
    
    //                 // Filter ulang berdasarkan produk_id jika diperlukan
    //                 if ($produk_id && $produk->id != $produk_id) {
    //                     continue; // Lewati produk yang tidak sesuai dengan filter
    //                 }
    
    //                 $key = $produk->id; // Menggunakan ID produk sebagai key
    
    //                 if (!isset($finalResults[$key])) {
    //                     $finalResults[$key] = [
    //                         'tanggal_penjualan' => $penjualan->tanggal_penjualan,
    //                         'kode_lama' => $produk->kode_lama,
    //                         'nama_produk' => $produk->nama_produk,
    //                         'harga' => $produk->harga,
    //                         'jumlah' => 0,
    //                         'diskon' => 0,
    //                         'total' => 0,
    //                         'penjualan_kotor' => 0,
    //                         'penjualan_bersih' => 0,
    //                     ];
    //                 }
    
    //                 // Jumlahkan jumlah dan total
    //                 $finalResults[$key]['jumlah'] += $detail->jumlah;
    //                 $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
    //                 $finalResults[$key]['total'] += $detail->total;
    
    //                 // Hitung diskon 10% dari jumlah * harga
    //                 if ($detail->diskon > 0) {
    //                     $diskonPerItem = $produk->harga * 0.10;
    //                     $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
    //                 }
    
    //                 // Kalkulasi penjualan bersih (penjualan kotor - diskon)
    //                 $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
    //             }
    //         }
    //     }
    
    //     // Mengurutkan finalResults berdasarkan kode_lama
    //     uasort($finalResults, function ($a, $b) {
    //         return strcmp($a['kode_lama'], $b['kode_lama']);
    //     });
    
    //     // Ambil semua data toko, klasifikasi, dan produk untuk dropdown
    //     $tokos = Toko::all();
    //     $klasifikasis = Klasifikasi::all();
    //     $produks = Produk::all();
    
    //     return view('admin.penjualan_toko.create', [
    //         'finalResults' => $finalResults,
    //         'tokos' => $tokos,
    //         'produks' => $produks,
    //         'klasifikasis' => $klasifikasis,
    //     ]);
    // }

    public function create(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $kasir = $request->kasir;

        // Ambil semua data produk, toko, kasir, klasifikasi untuk dropdown
        $produks = Produk::all();
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $kasirs = Penjualanproduk::select('kasir')->distinct()->get();

        // Buat query dasar untuk menghitung total penjualan kotor
        $query = Penjualanproduk::query();

        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan tanggal penjualan
        if ($tanggal_penjualan && $tanggal_akhir) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
        } elseif ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
        }

        // Filter berdasarkan kasir
        if ($kasir) {
            $query->where('kasir', $kasir);
        }

        // Hitung total penjualan kotor
        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)'));

        // Hitung total diskon penjualan (nominal_diskon)
        $diskon_penjualan = $query->sum('nominal_diskon');

        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;

        // Query terpisah untuk menghitung total deposit masuk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggal_penjualan, $tanggal_akhir, $kasir) {
            if ($tanggal_penjualan && $tanggal_akhir) {
                $q->whereBetween('tanggal_pemesanan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $q->where('tanggal_pemesanan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $q->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            }
            if ($kasir) {
                $q->where('kasir', $kasir);
            }
        })->sum('dp_pemesanan');

        // Hitung total deposit keluar
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($kasir, $tanggal_penjualan, $tanggal_akhir) {
            if ($tanggal_penjualan && $tanggal_akhir) {
                $q->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
            } elseif ($tanggal_penjualan) {
                $q->where('tanggal_penjualan', '>=', $tanggal_penjualan);
            } elseif ($tanggal_akhir) {
                $q->where('tanggal_penjualan', '<=', $tanggal_akhir);
            }
            if ($kasir) {
                $q->where('kasir', $kasir);
            }
        })->sum('dp_pemesanan');

        // Hitung total dari berbagai metode pembayaran
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $qris = Penjualanproduk::where('metode_id', 17)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $transfer = Penjualanproduk::where('metode_id', 3)
            ->where('kasir', $kasir)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));

        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;

        return view('admin.penjualan_toko.create', compact(
            'produks',
            'tokos',
            'klasifikasis',
            'kasirs',
            'penjualan_kotor',
            'diskon_penjualan',
            'penjualan_bersih',
            'deposit_masuk',
            'total_penjualan',
            'mesin_edc',
            'qris',
            'gobiz',
            'transfer',
            'total_setoran',
            'deposit_keluar'
        ));
    }
    
    
    
    public function getdata(Request $request)
    {
        // Validasi input tanggal dan toko_id
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'toko_id' => 'required|exists:tokos,id',
        ]);
    
        // Ambil tanggal dan toko_id dari request
        $tanggalPenjualan = $request->input('tanggal_penjualan');
        $tokoId = $request->input('toko_id');
    
        // Query untuk menghitung penjualan kotor berdasarkan tanggal dan toko
        $penjualan_kotor = Penjualanproduk::whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->where('toko_id', $tokoId)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        // Query untuk menghitung diskon penjualan berdasarkan detailpenjualanproduk
        $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan)
              ->where('toko_id', $tokoId);
        })->get()->sum(function ($detail) {
            $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga); // Hapus "Rp." dan "."
            $jumlah = $detail->jumlah;
            $diskon = $detail->diskon / 100; // Ubah diskon persen menjadi desimal
    
            return $harga * $jumlah * $diskon; // Hitung diskon
        });
    
        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;
    
        // Deposit keluar dan masuk (sama seperti di fungsi sebelumnya)
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan)
              ->where('toko_id', $tokoId);
        })->sum('dp_pemesanan');
    
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_pemesanan', $tanggalPenjualan)
              ->where('toko_id', $tokoId);
        })->sum('dp_pemesanan');
    
        // Total dari berbagai metode pembayaran
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->where('toko_id', $tokoId)
            ->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_total, "Rp.", ""), ".", "") AS UNSIGNED)'));
    
        // Hitung total penjualan
        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        
        // Format hasil menjadi response JSON
        return response()->json([
            'penjualan_kotor' => number_format($penjualan_kotor, 0, ',', '.'),
            'diskon_penjualan' => number_format($diskon_penjualan, 0, ',', '.'),
            'penjualan_bersih' => number_format($penjualan_bersih, 0, ',', '.'),
            'deposit_keluar' => number_format($deposit_keluar, 0, ',', '.'),
            'deposit_masuk' => number_format($deposit_masuk, 0, ',', '.'),
            'mesin_edc' => number_format($mesin_edc, 0, ',', '.'),
            'qris' => number_format($qris, 0, ',', '.'),
            'gobiz' => number_format($gobiz, 0, ',', '.'),
            'transfer' => number_format($transfer, 0, ',', '.'),
            'total_penjualan' => number_format($total_penjualan, 0, ',', '.'),
            'total_setoran' => number_format($total_setoran, 0, ',', '.'),
        ]);
    }
    
    
    
    public function store(Request $request)
    {
        // Validasi input dengan custom error messages
        $validator = Validator::make($request->all(), [
            'tanggal_penjualan' => 'required|date',
            'total_setoran' => 'required',
            'tanggal_setoran' => 'required|date',
            'nominal_setoran' => 'required',

        ], [
            // Custom error messages
            'tanggal_penjualan.required' => 'Tanggal penjualan tidak boleh kosong.',
            
            'total_setoran.required' => 'Total setoran tidak boleh kosong.',
            'tanggal_setoran.required' => 'Tanggal setoran tidak boleh kosong.',
            'nominal_setoran.required' => 'Nominal setoran tidak boleh kosong.',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data ke database dan ambil ID dari data yang baru disimpan
        $setoranPenjualan = Setoran_penjualan::create([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'penjualan_kotor' => $request->penjualan_kotor,
            'diskon_penjualan' => $request->diskon_penjualan,
            'penjualan_bersih' => $request->penjualan_bersih,
            'deposit_keluar' => $request->deposit_keluar,
            'deposit_masuk' => $request->deposit_masuk,
            'total_penjualan' => $request->total_penjualan,
            'mesin_edc' => $request->mesin_edc,
            'qris' => $request->qris,
            'gobiz' => $request->gobiz,
            'transfer' => $request->transfer,
            'total_setoran' => $request->total_setoran,
            'tanggal_setoran' => $request->tanggal_setoran,
            'tanggal_setoran2' => $request->tanggal_setoran2,
            'nominal_setoran' => $request->nominal_setoran,
            'nominal_setoran2' => $request->nominal_setoran2,
            'plusminus' => $request->plusminus,
            'toko_id' => 1, // Menyimpan toko_id dengan nilai 1
            'status' => 'unpost',
        ]);

        return response()->json([
            'url' => route('inquery_setorantunai.print', $setoranPenjualan->id)
        ]);
    }
    



}