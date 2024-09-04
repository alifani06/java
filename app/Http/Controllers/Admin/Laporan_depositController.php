<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use App\Models\Toko;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class Laporan_depositController extends Controller
{
 
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_pemesanan = $request->tanggal_pemesanan;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_pelunasan = $request->status_pelunasan;
        $toko_id = $request->toko_id; // Ambil filter toko_id dari request
        
        // Ambil daftar toko untuk filter
        $tokos = Toko::all();
    
        // Query dasar untuk mengambil data Dppemesanan
        $inquery = Dppemesanan::with(['pemesananproduk.toko']) // Memuat relasi toko melalui pemesananproduk
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan status
        if ($status) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }
        
        // Filter berdasarkan toko_id
        if ($toko_id) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
                $query->where('toko_id', $toko_id);
            });
        }
        
        // Filter berdasarkan tanggal pemesanan
        if ($tanggal_pemesanan && $tanggal_akhir) {
            $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_pemesanan, $tanggal_akhir) {
                $query->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
            });
        } elseif ($tanggal_pemesanan) {
            $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
            $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_pemesanan) {
                $query->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
            });
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            });
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $inquery->whereHas('pemesananproduk', function ($query) {
                $query->whereDate('tanggal_pemesanan', Carbon::today());
            });
        }
        
        // Filter berdasarkan status pelunasan
        if ($status_pelunasan == 'diambil') {
            $inquery->whereNotNull('pelunasan');
        } elseif ($status_pelunasan == 'belum_diambil') {
            $inquery->whereNull('pelunasan');
        }
        
        // Eksekusi query dan dapatkan hasilnya
        $inquery = $inquery->get();
        
        // Kirim data ke view
        return view('admin.laporan_deposit.index', compact('inquery', 'tokos'));
    }



    public function printReportdeposit(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_pemesanan = $request->tanggal_pemesanan;
        $tanggal_akhir = $request->tanggal_akhir;
        $status_pelunasan = $request->status_pelunasan;
        $toko_id = $request->toko_id; // Ambil filter toko_id dari request

        // Ambil daftar toko untuk filter
        $tokos = Toko::all();

        // Query dasar untuk mengambil data Dppemesanan
        $inquery = Dppemesanan::with(['pemesananproduk.toko']) // Memuat relasi toko melalui pemesananproduk
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($status) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($status) {
                $query->where('status', $status);
            });
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $inquery->whereHas('pemesananproduk', function ($query) use ($toko_id) {
                $query->where('toko_id', $toko_id);
            });
        }

        // Filter berdasarkan tanggal pemesanan
        if ($tanggal_pemesanan && $tanggal_akhir) {
            $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_pemesanan, $tanggal_akhir) {
                $query->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
            });
        } elseif ($tanggal_pemesanan) {
            $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
            $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_pemesanan) {
                $query->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
            });
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $inquery->whereHas('pemesananproduk', function ($query) use ($tanggal_akhir) {
                $query->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            });
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $inquery->whereHas('pemesananproduk', function ($query) {
                $query->whereDate('tanggal_pemesanan', Carbon::today());
            });
        }

        // Filter berdasarkan status pelunasan
        if ($status_pelunasan == 'diambil') {
            $inquery->whereNotNull('pelunasan');
        } elseif ($status_pelunasan == 'belum_diambil') {
            $inquery->whereNull('pelunasan');
        }

        // Eksekusi query dan dapatkan hasilnya
        $inquery = $inquery->get();

        // Kirim data ke view cetak
        $pdf = FacadePdf::loadView('admin.laporan_deposit.print', compact('inquery', 'tokos', 'status', 'tanggal_pemesanan', 'tanggal_akhir', 'status_pelunasan', 'toko_id'));
    
        // Return PDF
        return $pdf->stream('laporan_deposit.pdf');
    }


}