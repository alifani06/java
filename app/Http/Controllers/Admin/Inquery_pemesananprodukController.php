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
 
    public function index(Request $request)

    {
        // Membuat kueri Pemesananproduk
        $query = Pemesananproduk::query();
    
        // Jika terdapat filter tanggal di request
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
    
            // Menambahkan kondisi whereBetween untuk filter tanggal yang diinputkan
            $query->whereBetween('tanggal_pemesanan', [$startDate, $endDate]);
        } else {
            // Jika tidak ada filter tanggal, menampilkan data hari ini saja
            $todayStart = Carbon::now()->startOfDay();
            $todayEnd = Carbon::now()->endOfDay();
            $query->whereBetween('tanggal_pemesanan', [$todayStart, $todayEnd]);
        }
    
        // Mengambil hasilnya
        $pemesanans = $query->get();
    
        // Mengembalikan tampilan dengan hasil
        return view('admin.inquery_pemesananproduk.index', compact('pemesanans', 'query'));
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


    }

 
    public function update(Request $request, $id)
    {
       
    }


    public function destroy($id)
    {
        //
    }

}