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
        // Ambil filter dari request
        $tanggalPenjualan = $request->get('tanggal_penjualan');
        $tokoId = $request->get('toko_id');
    
        // Ambil data penjualan berdasarkan tanggal dan toko
        $query = PenjualanProduk::query();
    
        if ($tanggalPenjualan) {
            $query->whereDate('tanggal_penjualan', $tanggalPenjualan); // pastikan kolom tanggal_penjualan ada
        }
    
        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }
    
        // Jumlahkan semua sub_totalasli berdasarkan filter
        $penjualanKotor = $query->sum('sub_totalasli'); // pastikan kolom sub_totalasli ada di tabel
    
        // Formatkan nilai penjualan_kotor dalam format Rupiah
        $penjualanKotorFormatted = 'Rp ' . number_format($penjualanKotor, 0, ',', '.');
    
        // Ambil data toko untuk dropdown
        $tokos = Toko::all();
    
        // Kembalikan view dengan data yang dibutuhkan
        return view('admin.penjualan_toko.create', compact('penjualanKotorFormatted', 'tokos'));
    }
    

    public function getdata(Request $request)
{
    // Ambil parameter dari request (tanggal dan toko)
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id;

    // Buat query dasar untuk menghitung total penjualan kotor
    $query = Penjualanproduk::query();

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

    // Filter berdasarkan toko
    if ($toko_id) {
        $query->where('toko_id', $toko_id);
    }

    // Hitung total penjualan kotor
    $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp ", ""), ".", "") AS UNSIGNED)'));

    // Mengembalikan hasil sebagai response JSON
    return response()->json(['penjualan_kotor' => $penjualan_kotor]);
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
    

    public function getPenjualanKotor(Request $request)
    {

    
        // Ambil data dari request
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $toko_id = $request->toko_id;
    
        // Buat query dasar untuk menghitung total penjualan kotor
        $query = Penjualanproduk::query();
        
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal penjualan (hanya tanggal_penjualan, tanpa tanggal_akhir)
        if ($tanggal_penjualan) {
            $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
            $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Hitung total penjualan kotor
        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REPLACE(REPLACE(sub_totalasli, "Rp ", ""), ".", "") AS UNSIGNED)'));
    
        // Pastikan data dikembalikan dalam format JSON
        return response()->json([
            'penjualan_kotor' => $penjualan_kotor
        ]);
    }
    



}