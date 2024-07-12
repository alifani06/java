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
 
    public function index()
    {
        // $detailpemesananproduk = Detailpemesananproduk::latest()->first();

        $pemesanans = Detailpemesananproduk::with('pemesananproduk')->get();
        return view('admin.inquery_pemesananproduk.index', compact('pemesanans'));

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