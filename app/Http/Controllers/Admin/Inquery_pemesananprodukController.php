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
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class Inquery_pemesananprodukController extends Controller
{
 
    // public function index(Request $request)

    // {
    //     // Membuat kueri Pemesananproduk
    //     $query = Pemesananproduk::query();
    
    //     // Jika terdapat filter tanggal di request
    //     if ($request->has('start_date') && $request->has('end_date')) {
    //         $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
    //         $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
    
    //         // Menambahkan kondisi whereBetween untuk filter tanggal yang diinputkan
    //         $query->whereBetween('tanggal_pemesanan', [$startDate, $endDate]);
    //     } else {
    //         // Jika tidak ada filter tanggal, menampilkan data hari ini saja
    //         $todayStart = Carbon::now()->startOfDay();
    //         $todayEnd = Carbon::now()->endOfDay();
    //         $query->whereBetween('tanggal_pemesanan', [$todayStart, $todayEnd]);
    //     }
    
    //     // Mengambil hasilnya
    //     $pemesanans = $query->get();
    
    //     // Mengembalikan tampilan dengan hasil
    //     return view('admin.inquery_pemesananproduk.index', compact('pemesanans', 'query'));
    // }
    public function index(Request $request)
    {
   

        $kategori = $request->kategori;
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        
        // Mulai dengan query builder pada model Pemesananproduk
        $inquery = Pemesananproduk::with('detailpemesananproduk');
        
        if ($kategori) {
            $inquery->where('kategori', $kategori);
        }
        
        if ($tanggal_awal && $tanggal_akhir) {
            $inquery->whereBetween('tanggal_pemesanan', [$tanggal_awal, $tanggal_akhir]);
        } elseif ($tanggal_awal) {
            $inquery->where('tanggal_pemesanan', '>=', $tanggal_awal);
        } elseif ($tanggal_akhir) {
            $inquery->where('tanggal_pemesanan', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal hari ini
            $inquery->whereDate('tanggal_pemesanan', Carbon::today());
        }
        
        // Tambahkan orderBy dan dapatkan hasilnya
        $inquery = $inquery->orderBy('id', 'DESC')->get();
        

        return view('admin.inquery_pemesananproduk.index', compact('inquery'));
    }
    
    public function create()
    {

       
    }
    
 
    
    public function store(Request $request)
{

}



    public function show($id)
    {
        //
    }

  
    public function edit($id)
    {
        $inquery = Pemesananproduk::with('detailpemesananproduk')->findOrFail($id);
        $pemesanans = $inquery->get();
    
        // Mengembalikan tampilan dengan hasil
        return view('admin.inquery_pemesananproduk.update', compact('pemesanans', 'inquery'));
    }

 
    public function update(Request $request, $id)
    {
       
    }


    public function destroy($id)
    {
        //
    }

}