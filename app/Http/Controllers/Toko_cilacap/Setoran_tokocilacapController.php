<?php

namespace App\Http\Controllers\Toko_cilacap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use Illuminate\Support\Facades\DB;
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
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Setoran_penjualan;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;





class Setoran_tokocilacapController extends Controller
{

    public function index(Request $request)
    {
        $setoranPenjualans = Setoran_penjualan::where('toko_id', 6)
            ->orderBy('id', 'DESC')
            ->get();
    
        // Kirim data ke view
        return view('toko_cilacap.setoran_tokocilacap.index', compact('setoranPenjualans'));
    }
    
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

        return view('toko_cilacap.setoran_tokocilacap.create', compact(
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
        // Validasi input tanggal
        $request->validate([
            'tanggal_penjualan' => 'required|date',
        ]);
    
        // Ambil tanggal dan toko_id dari request
        $tanggalPenjualan = $request->input('tanggal_penjualan');
        $tokoId = $request->input('toko_id');
    
        // Query untuk menghitung penjualan kotor dengan filter toko
        $query = Penjualanproduk::whereDate('tanggal_penjualan', $tanggalPenjualan);
        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }
    
        $penjualan_kotor = $query->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_totalasli, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));
    
        // Menghitung diskon penjualan
        $diskon_penjualan = Detailpenjualanproduk::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
            if ($tokoId) {
                $q->where('toko_id', $tokoId);
            }
        })->get()->sum(function ($detail) {
            $harga = (float)str_replace(['Rp.', '.'], '', $detail->harga);
            $jumlah = $detail->jumlah;
            $diskon = $detail->diskon / 100;
    
            return $harga * $jumlah * $diskon;
        });
    
        // Hitung penjualan bersih
        $penjualan_bersih = $penjualan_kotor - $diskon_penjualan;
    
        // Hitung total deposit keluar
        $deposit_keluar = Dppemesanan::whereHas('penjualanproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_penjualan', $tanggalPenjualan);
            if ($tokoId) {
                $q->where('toko_id', $tokoId);
            }
        })->sum('dp_pemesanan');
    
        // Hitung total deposit masuk
        $deposit_masuk = Dppemesanan::whereHas('pemesananproduk', function ($q) use ($tanggalPenjualan, $tokoId) {
            $q->whereDate('tanggal_pemesanan', $tanggalPenjualan);
            if ($tokoId) {
                $q->where('toko_id', $tokoId);
            }
        })->sum('dp_pemesanan');
    
        // Hitung total metode pembayaran dengan filter toko
        $mesin_edc = Penjualanproduk::where('metode_id', 1)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->when($tokoId, function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId);
            })
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));
    
        $qris = Penjualanproduk::where('metode_id', 17)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->when($tokoId, function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId);
            })
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));
    
        $gobiz = Penjualanproduk::where('metode_id', 2)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->when($tokoId, function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId);
            })
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));
    
        $transfer = Penjualanproduk::where('metode_id', 3)
            ->whereDate('tanggal_penjualan', $tanggalPenjualan)
            ->when($tokoId, function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId);
            })
            ->sum(Penjualanproduk::raw('CAST(REGEXP_REPLACE(REPLACE(sub_total, "Rp", ""), "[^0-9]", "") AS UNSIGNED)'));
    
        // Hitung total penjualan
        $total_penjualan = $penjualan_bersih - ($deposit_keluar - $deposit_masuk);
        $total_metode = $mesin_edc + $qris + $gobiz + $transfer;
        $total_setoran = $total_penjualan - $total_metode;
    
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
            'total_metode' => number_format($total_metode, 0, ',', '.'),
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
            'toko_id' => 'required|exists:tokos,id', // Validasi bahwa toko_id harus ada di tabel tokos
        ], [
            // Custom error messages
            'tanggal_penjualan.required' => 'Tanggal penjualan tidak boleh kosong.',
            'total_setoran.required' => 'Total setoran tidak boleh kosong.',
            'tanggal_setoran.required' => 'Tanggal setoran tidak boleh kosong.',
            'nominal_setoran.required' => 'Nominal setoran tidak boleh kosong.',
            'toko_id.required' => 'Toko harus dipilih.',
            'toko_id.exists' => 'Toko yang dipilih tidak valid.',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Fungsi untuk menghilangkan format angka
        $removeFormat = function ($value) {
            return (int)str_replace(['.', ','], '', $value); // Hilangkan titik dan koma
        };
    
        // Simpan data ke database
        Setoran_penjualan::create([
            'tanggal_penjualan' => $request->tanggal_penjualan,
            'penjualan_kotor' => $removeFormat($request->penjualan_kotor),
            'diskon_penjualan' => $removeFormat($request->diskon_penjualan),
            'penjualan_bersih' => $removeFormat($request->penjualan_bersih),
            'deposit_keluar' => $removeFormat($request->deposit_keluar),
            'deposit_masuk' => $removeFormat($request->deposit_masuk),
            'total_penjualan' => $removeFormat($request->total_penjualan),
            'mesin_edc' => $removeFormat($request->mesin_edc),
            'qris' => $removeFormat($request->qris),
            'gobiz' => $removeFormat($request->gobiz),
            'transfer' => $removeFormat($request->transfer),
            'total_setoran' => $removeFormat($request->total_setoran),
            'tanggal_setoran' => $request->tanggal_setoran,
            'tanggal_setoran2' => $request->tanggal_setoran2,
            'nominal_setoran' => $removeFormat($request->nominal_setoran),
            'nominal_setoran2' => $removeFormat($request->nominal_setoran2),
            'plusminus' => $removeFormat($request->plusminus),
            'toko_id' => 6, // Ambil nilai toko_id dari request
            'status' => 'unpost',
        ]);
    
        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('setoran_tokocilacap.index')->with('success', 'Data berhasil disimpan!');
    }
    
    

    

    public function print($id)
    {
        // Ambil data setoran penjualan berdasarkan id yang dipilih
        $setoranPenjualans = Setoran_penjualan::findOrFail($id);
    
        // Load view untuk PDF dan kirimkan data
        $pdf = FacadePdf::loadView('toko_cilacap.setoran_tokocilacap.printtunai', compact('setoranPenjualans'));
    
        // Return PDF stream agar langsung bisa ditampilkan
        return $pdf->stream('setoran_penjualan.pdf');
    }
    

    }

    


