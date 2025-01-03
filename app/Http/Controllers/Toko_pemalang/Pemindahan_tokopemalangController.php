<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Models\Pemindahan_tokoslawi;
use App\Models\Pemindahan_tokobanjaranmasuk;
use App\Models\Pemindahan_tokotegalmasuk;
use App\Models\Pemindahan_tokoslawimasuk;
use App\Models\Pemindahan_tokotegal;
use App\Models\Pemindahan_barangjadi;
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
use App\Models\Pemindahan_tokobanjaran;
use App\Models\Pemindahan_tokobumiayumasuk;
use App\Models\Pemindahan_tokocilacapmasuk;
use App\Models\Pemindahan_tokopemalang;
use App\Models\Pemindahan_tokopemalangmasuk;
use App\Models\Retur_barnagjadi;
use Maatwebsite\Excel\Facades\Excel;

class Pemindahan_tokopemalangController extends Controller{

    public function index()
    {
        // Ambil data retur_tokoslawi beserta relasi produk dan urutkan berdasarkan created_at terbaru
        $pemindahan_tokopemalang = Pemindahan_tokopemalang::with('produk')
                            ->where('status', 'posting')
                            ->orderBy('created_at', 'desc')
                            ->get();
    
        return view('toko_pemalang.pemindahan_tokopemalang.index', compact('pemindahan_tokopemalang'));
    }
      

public function create()
{
    // Fetch all products
    $produks = Produk::all();
    $tokos = Toko::all();

    return view('toko_pemalang.pemindahan_tokopemalang.create', compact('produks', 'tokos'));
}


public function store(Request $request)
{
    $request->validate([
        'produk_id' => 'required|array',
        'produk_id.*' => 'exists:produks,id',
        'jumlah' => 'required|array',
        'jumlah.*' => 'integer|min:1',
        'keterangan' => 'required|array',
        'keterangan.*' => 'string',
    ]);

    $kode = $this->kode();
    $produk_ids = $request->input('produk_id');
    $jumlahs = $request->input('jumlah');
    $keterangans = $request->input('keterangan');

    foreach ($produk_ids as $index => $produk_id) {
        // Simpan ke tabel pemindahan_tokoslawi
        Pemindahan_tokopemalang::create([
            'kode_pemindahan' => $kode,
            'produk_id' => $produk_id,
            'toko_id' => '4',  // Ganti sesuai dengan toko tujuan
            'status' => 'unpost',
            'jumlah' => $jumlahs[$index],
            'keterangan' => $keterangans[$index],
            'tanggal_input' => Carbon::now('Asia/Jakarta'),
        ]);

        // Simpan ke tabel pemindahan_barangjadis
        Pemindahan_barangjadi::create([
            'kode_pemindahan' => $kode,
            'produk_id' => $produk_id,
            'toko_id' => '4',  // Ganti sesuai dengan toko tujuan
            'status' => 'unpost',
            'jumlah' => $jumlahs[$index],
            'keterangan' => $keterangans[$index],
            'tanggal_input' => Carbon::now('Asia/Jakarta'),
        ]);

        // Simpan ke tabel berdasarkan keterangan
        switch ($keterangans[$index]) {
            case 'BANJARAN':
                Pemindahan_tokobanjaranmasuk::create([
                    'kode_pemindahan' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '4',  // Ganti sesuai dengan ID toko BANJARAN
                    'status' => 'unpost',
                    'jumlah' => $jumlahs[$index],
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
                break;
            case 'TEGAL':
                Pemindahan_tokotegalmasuk::create([
                    'kode_pemindahan' => $kode,
                    'produk_id' => $produk_id,
                    'toko_id' => '4',  // Ganti sesuai dengan ID toko TEGAL
                    'status' => 'unpost',
                    'jumlah' => $jumlahs[$index],
                    'keterangan' => $keterangans[$index],
                    'tanggal_input' => Carbon::now('Asia/Jakarta'),
                ]);
                break;
                case 'SLAWI':
                    Pemindahan_tokoslawimasuk::create([
                        'kode_pemindahan' => $kode,
                        'produk_id' => $produk_id,
                        'toko_id' => '4',  // Ganti sesuai dengan ID toko TEGAL
                        'status' => 'unpost',
                        'jumlah' => $jumlahs[$index],
                        'keterangan' => $keterangans[$index],
                        'tanggal_input' => Carbon::now('Asia/Jakarta'),
                    ]);
                    break;
                    case 'PEMALANG':
                        Pemindahan_tokopemalangmasuk::create([
                            'kode_pemindahan' => $kode,
                            'produk_id' => $produk_id,
                            'toko_id' => '4',  // Ganti sesuai dengan ID toko TEGAL
                            'status' => 'unpost',
                            'jumlah' => $jumlahs[$index],
                            'keterangan' => $keterangans[$index],
                            'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        ]);
                    break;
                    case 'BUMIAYU':
                        Pemindahan_tokobumiayumasuk::create([
                            'kode_pemindahan' => $kode,
                            'produk_id' => $produk_id,
                            'toko_id' => '4',  // Ganti sesuai dengan ID toko TEGAL
                            'status' => 'unpost',
                            'jumlah' => $jumlahs[$index],
                            'keterangan' => $keterangans[$index],
                            'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        ]);
                    break;
                    case 'CILACAP':
                        Pemindahan_tokocilacapmasuk::create([
                            'kode_pemindahan' => $kode,
                            'produk_id' => $produk_id,
                            'toko_id' => '4',  // Ganti sesuai dengan ID toko TEGAL
                            'status' => 'unpost',
                            'jumlah' => $jumlahs[$index],
                            'keterangan' => $keterangans[$index],
                            'tanggal_input' => Carbon::now('Asia/Jakarta'),
                        ]);
                    break;            
                        
                        default:
                // Tidak melakukan apa-apa jika keterangan tidak cocok
                break;
        }
    }

    return redirect()->route('pemindahan_tokopemalang.index')->with('success', 'Data pemindahan barang berhasil disimpan.');
}

public function kode()
{
    $prefix = 'FOE';
    $year = date('y'); // Dua digit terakhir dari tahun
    $date = date('dm'); // Format bulan dan hari: MMDD

    // Mengambil kode retur terakhir yang dibuat pada hari yang sama
    $lastBarang = Pemindahan_tokopemalang::whereDate('tanggal_input', Carbon::today())
                                  ->orderBy('kode_pemindahan', 'desc')
                                  ->first();

    if (!$lastBarang) {
        $num = 1;
    } else {
        $lastCode = $lastBarang->kode_pemindahan;
        $lastNum = (int) substr($lastCode, strlen($prefix  . $date . $year)); // Mengambil urutan terakhir
        $num = $lastNum + 1;
    }

    $formattedNum = sprintf("%04d", $num); // Urutan dengan 4 digit
    $newCode = $prefix  . $date . $year . $formattedNum;
    return $newCode;
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
    
    return view('toko_pemalang.inquery_returbanjaran.show', compact('pengirimanBarangJadi', 'firstItem'));
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
        
        $pdf = FacadePdf::loadView('toko_pemalang.inquery_returbanjaran.print', compact('pengirimanBarangJadi', 'firstItem'));

        return $pdf->stream('surat_permintaan_produk.pdf');
        
        // return view('toko_pemalang.retur_tokoslawi.print', compact('pengirimanBarangJadi', 'firstItem'));
        }

}


 