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
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;






class Laporan_hasilpenjualanController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $produk = $request->produk;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

        // Query dasar untuk mengambil data penjualan produk
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
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_penjualan', Carbon::today());
        }

        // Filter berdasarkan produk
        if ($produk) {
            $query->whereHas('detailpenjualanproduk', function ($query) use ($produk) {
                $query->where('produk_id', $produk);
            });
        }

        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }

        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('detailpenjualanproduk.produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }

        // Urutkan data berdasarkan ID secara descending
        $query->orderBy('id', 'DESC');

        // Ambil data penjualan produk
        $inquery = $query->with(['toko', 'detailpenjualanproduk.produk.klasifikasi'])->get();

        // Ambil semua data produk untuk dropdown
        $produks = Produk::all();

        // Ambil semua data toko untuk dropdown
        $tokos = Toko::all();

        // Ambil semua klasifikasi untuk dropdown
        $klasifikasis = Klasifikasi::all();

        // Kembalikan view dengan data penjualan produk, produk, toko, dan klasifikasi
        return view('admin.laporan_penjualanproduk.index', compact('inquery', 'produks', 'tokos', 'klasifikasis'));
    }


   
}