<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
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
use App\Models\Dppemesanan;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Setoran_penjualan;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Setoran_pelunasanController extends Controller
{
    
    public function index(Request $request)
    {
        // Ambil semua data setoran penjualan
        $setoranPenjualans = Setoran_penjualan::orderBy('id', 'DESC')->get();
    
        // Kirim data ke view
        return view('admin.setoran_pelunasan.index', compact('setoranPenjualans'));
    }

    public function create(Request $request)
    {
        $tokos = Toko::all();

        // Ambil satu record terbaru dari Setoran_penjualan
        $setoranPenjualans = Setoran_penjualan::orderBy('id', 'DESC')->first();
    
        // Kirim data ke view
        return view('admin.setoran_pelunasan.create', compact('setoranPenjualans','tokos'));
    }
    
    
    


   
    public function getdata1(Request $request)
    {
        // Validasi input
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'toko_id' => 'nullable|exists:tokos,id' // Validasi toko_id
        ]);
    
        // Ambil parameter dari request
        $tanggalPenjualan = $request->input('tanggal_penjualan');
        $tokoId = $request->input('toko_id');
    
        // Query untuk mengambil data dari tabel setoran_penjualan
        $query = Setoran_penjualan::whereDate('tanggal_penjualan', $tanggalPenjualan);
    
        // Filter berdasarkan toko_id jika diberikan
        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }
    
        // Ambil data pertama yang ditemukan
        $setoranPenjualan = $query->first();
    
        // Jika tidak ada data yang ditemukan, kembalikan respons default
        if (!$setoranPenjualan) {
            return response()->json([
                'id' => null,
                'penjualan_kotor' => 0,
                'diskon_penjualan' => 0,
                'penjualan_bersih' => 0,
                'deposit_keluar' => 0,
                'deposit_masuk' => 0,
                'mesin_edc' => 0,
                'qris' => 0,
                'gobiz' => 0,
                'transfer' => 0,
                'total_penjualan' => 0,
                'total_setoran' => 0,
                'nominal_setoran' => 0,
                'nominal_setoran2' => 0,
                'plusminus' => 0,
            ]);
        }
    
        // Kembalikan hasil dari setoran_penjualan dalam format JSON
        return response()->json([
            'id' => $setoranPenjualan->id,
            'tanggal_penjualan' => $setoranPenjualan->tanggal_penjualan,
            'penjualan_kotor' => number_format($setoranPenjualan->penjualan_kotor, 0, ',', '.'),
            'diskon_penjualan' => number_format($setoranPenjualan->diskon_penjualan, 0, ',', '.'),
            'penjualan_bersih' => number_format($setoranPenjualan->penjualan_bersih, 0, ',', '.'),
            'deposit_keluar' => number_format($setoranPenjualan->deposit_keluar, 0, ',', '.'),
            'deposit_masuk' => number_format($setoranPenjualan->deposit_masuk, 0, ',', '.'),
            'mesin_edc' => number_format($setoranPenjualan->mesin_edc, 0, ',', '.'),
            'qris' => number_format($setoranPenjualan->qris, 0, ',', '.'),
            'gobiz' => number_format($setoranPenjualan->gobiz, 0, ',', '.'),
            'transfer' => number_format($setoranPenjualan->transfer, 0, ',', '.'),
            'total_penjualan' => number_format($setoranPenjualan->total_penjualan, 0, ',', '.'),
            'total_setoran' => number_format($setoranPenjualan->total_setoran, 0, ',', '.'),
            'nominal_setoran' => number_format($setoranPenjualan->nominal_setoran, 0, ',', '.'),
            'nominal_setoran2' => number_format($setoranPenjualan->nominal_setoran2, 0, ',', '.'),
            'plusminus' => number_format($setoranPenjualan->plusminus, 0, ',', '.'),
        ]);
    }
    


    

    // public function store(Request $request)
    // {
    //     // Validasi data
    //     $request->validate([
    //         'penjualan_kotor' => 'required|numeric',
    //         'diskon_penjualan' => 'required|numeric',
    //         'penjualan_bersih' => 'required|numeric',
    //         'deposit_keluar' => 'required|numeric',
    //         'deposit_masuk' => 'required|numeric',
    //         'total_penjualan' => 'required|numeric',
    //         'mesin_edc' => 'required|numeric',
    //         'qris' => 'required|numeric',
    //         'gobiz' => 'required|numeric',
    //         'transfer' => 'required|numeric',
    //         'tanggal_penjualan' => 'required|date',
    //     ]);

    //     // Simpan data ke database
    //     $setoranPelunasan = new Setoran_penjualan();
    //     $setoranPelunasan->penjualan_kotor = $request->penjualan_kotor;
    //     $setoranPelunasan->diskon_penjualan = $request->diskon_penjualan;
    //     $setoranPelunasan->penjualan_bersih = $request->penjualan_bersih;
    //     $setoranPelunasan->deposit_keluar = $request->deposit_keluar;
    //     $setoranPelunasan->deposit_masuk = $request->deposit_masuk;
    //     $setoranPelunasan->total_penjualan = $request->total_penjualan;
    //     $setoranPelunasan->mesin_edc = $request->mesin_edc;
    //     $setoranPelunasan->qris = $request->qris;
    //     $setoranPelunasan->gobiz = $request->gobiz;
    //     $setoranPelunasan->transfer = $request->transfer;
    //     $setoranPelunasan->tanggal_penjualan = $request->tanggal_penjualan;
    //     $setoranPelunasan->total_setoran = $request->total_setoran;
    //     $setoranPelunasan->nominal_setoran = $request->nominal_setoran;
    //     $setoranPelunasan->plusminus = $request->plusminus;
    //     $setoranPelunasan->status = 'posting';
    //     $setoranPelunasan->save();

    //     // Redirect ke halaman lain atau berikan pesan sukses
    //     return redirect()->back()->with('success', 'Data berhasil disimpan!');
    // }

 

    public function updateStatus(Request $request)
    {
        // Ambil id setoran dari request
        $setoran_id = $request->input('id');
    
        // Cari setoran_penjualan berdasarkan id
        $setoran = Setoran_penjualan::find($setoran_id);
    
        if ($setoran) {
            // Hapus format number sebelum menyimpan
            $penjualan_kotor = str_replace('.', '', $request->input('penjualan_kotor'));
            $diskon_penjualan = str_replace('.', '', $request->input('diskon_penjualan'));
            $penjualan_bersih = str_replace('.', '', $request->input('penjualan_bersih'));
            $total_penjualan = str_replace('.', '', $request->input('total_penjualan'));
            $deposit_keluar = str_replace('.', '', $request->input('deposit_keluar'));
            $deposit_masuk = str_replace('.', '', $request->input('deposit_masuk'));
    
            // Update field berdasarkan input yang sudah dihapus format number-nya
            $setoran->penjualan_kotor = $penjualan_kotor;
            $setoran->diskon_penjualan = $diskon_penjualan;
            $setoran->penjualan_bersih = $penjualan_bersih;
            $setoran->total_penjualan = $total_penjualan;
            $setoran->deposit_keluar = $deposit_keluar;
            $setoran->deposit_masuk = $deposit_masuk;
            $setoran->status = 'posting'; // Contoh pengubahan status
            $setoran->save();
    
            // Redirect dengan pesan sukses
            return redirect()->route('setoran_pelunasan.index')->with('success', 'Data berhasil disimpan');
        }
    
        // Jika setoran tidak ditemukan
        return redirect()->back()->with('error', 'Setoran tidak ditemukan');
    }
    
    
    
    

    public function printPenjualanKotor(Request $request) 
    {
        // Ambil parameter tanggal_penjualan dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
    
        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }
    
        // Ambil filter lain seperti status, toko_id, dll.
        $status = $request->get('status');
        $toko_id = $request->get('toko_id');
        $produk_id = $request->get('produk');
    
        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Pastikan menggunakan whereDate
            ->orderBy('tanggal_penjualan', 'desc');
    
        $inquery = $query->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;
    
                // Pastikan produk tidak null sebelum mengakses properti dan cocok dengan filter produk_id
                if ($produk && (!$produk_id || $produk->id == $produk_id)) {
                    $key = $produk->id; // Menggunakan ID produk sebagai key
    
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0,
                            'penjualan_bersih' => 0,
                        ];
                    }
    
                    // Jumlahkan jumlah dan total
                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;
    
                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
    
                    // Kalkulasi penjualan bersih (penjualan kotor - diskon)
                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }
    
        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });
    
        // Ambil data untuk filter
        $tokos = Toko::all(); // Model untuk tabel toko
        $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi
        $produks = Produk::all(); // Model untuk tabel produk
    
        // Dapatkan nama toko berdasarkan toko_id
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Pass raw dates ke view
        $startDate = $tanggal_penjualan;
    
        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
        $pdf = FacadePdf::loadView('admin.setoran_pelunasan.printBk', [
            'finalResults' => $finalResults,
            'startDate' => $startDate,
            'branchName' => $branchName,
            'tokos' => $tokos,
            'produks' => $produks, // Tambahkan data produk
            'klasifikasis' => $klasifikasis,
        ]);
    
        // Output PDF ke browser
        return $pdf->stream('laporan_penjualan_produk.pdf');
    }

    public function printDiskonPenjualan(Request $request) 
    {
        // Ambil parameter tanggal_penjualan dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
    
        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }
    
        // Ambil filter lain seperti status, toko_id, dll.
        $status = $request->get('status');
        $toko_id = $request->get('toko_id');
        $produk_id = $request->get('produk');
    
        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Pastikan menggunakan whereDate
            ->orderBy('tanggal_penjualan', 'desc');
    
        $inquery = $query->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;
    
                // Pastikan produk tidak null sebelum mengakses properti dan cocok dengan filter produk_id
                if ($produk && (!$produk_id || $produk->id == $produk_id)) {
                    $key = $produk->id; // Menggunakan ID produk sebagai key
    
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0,
                            'penjualan_bersih' => 0,
                        ];
                    }
    
                    // Jumlahkan jumlah dan total
                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;
    
                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
    
                    // Kalkulasi penjualan bersih (penjualan kotor - diskon)
                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }
    
        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });
    
        // Ambil data untuk filter
        $tokos = Toko::all(); // Model untuk tabel toko
        $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi
        $produks = Produk::all(); // Model untuk tabel produk
    
        // Dapatkan nama toko berdasarkan toko_id
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Pass raw dates ke view
        $startDate = $tanggal_penjualan;
    
        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
        $pdf = FacadePdf::loadView('admin.setoran_pelunasan.printDiskon', [
            'finalResults' => $finalResults,
            'startDate' => $startDate,
            'branchName' => $branchName,
            'tokos' => $tokos,
            'produks' => $produks, // Tambahkan data produk
            'klasifikasis' => $klasifikasis,
        ]);
    
        // Output PDF ke browser
        return $pdf->stream('laporan_penjualan_produk.pdf');
    }


    public function printDepositKeluar(Request $request) 
    {
        // Ambil parameter tanggal_penjualan dari request
        $tanggal_penjualan = $request->get('tanggal_penjualan'); // Menggunakan query string
    
        // Pastikan tanggal_penjualan tidak null
        if (!$tanggal_penjualan) {
            return redirect()->back()->with('error', 'Tanggal penjualan tidak boleh kosong.');
        }
    
        // Ambil filter lain seperti status, toko_id, dll.
        $status = $request->get('status');
        $toko_id = $request->get('toko_id');
        $produk_id = $request->get('produk');
    
        // Query data penjualan
        $query = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->whereDate('tanggal_penjualan', Carbon::parse($tanggal_penjualan)->startOfDay()) // Pastikan menggunakan whereDate
            ->orderBy('tanggal_penjualan', 'desc');
    
        $inquery = $query->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
        
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                $produk = $detail->produk;
    
                // Pastikan produk tidak null sebelum mengakses properti dan cocok dengan filter produk_id
                if ($produk && (!$produk_id || $produk->id == $produk_id)) {
                    $key = $produk->id; // Menggunakan ID produk sebagai key
    
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $produk->kode_lama,
                            'nama_produk' => $produk->nama_produk,
                            'harga' => $produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                            'penjualan_kotor' => 0,
                            'penjualan_bersih' => 0,
                        ];
                    }
    
                    // Jumlahkan jumlah dan total
                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                    $finalResults[$key]['total'] += $detail->total;
    
                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
    
                    // Kalkulasi penjualan bersih (penjualan kotor - diskon)
                    $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
                }
            }
        }
    
        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });
    
        // Ambil data untuk filter
        $tokos = Toko::all(); // Model untuk tabel toko
        $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi
        $produks = Produk::all(); // Model untuk tabel produk
    
        // Dapatkan nama toko berdasarkan toko_id
        $branchName = 'Semua Toko';
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Pass raw dates ke view
        $startDate = $tanggal_penjualan;
    
        // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
        $pdf = FacadePdf::loadView('admin.setoran_pelunasan.printDiskon', [
            'finalResults' => $finalResults,
            'startDate' => $startDate,
            'branchName' => $branchName,
            'tokos' => $tokos,
            'produks' => $produks, // Tambahkan data produk
            'klasifikasis' => $klasifikasis,
        ]);
    
        // Output PDF ke browser
        return $pdf->stream('laporan_penjualan_produk.pdf');
    }
    


}   