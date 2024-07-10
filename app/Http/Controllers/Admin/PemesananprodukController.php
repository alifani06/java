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
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class PemesananprodukController extends Controller
{
 
    public function index()
    {
        $produks = Produk::all();
        return view('admin.pemesanan_produk.index', compact('produks'));

    }

    public function create()
    {

        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
    
        $produks = Produk::with('tokoslawi')->get();

        $kategoriPelanggan = 'member';
    
        return view('admin.pemesanan_produk.create', compact('barangs', 'produks', 'details', 'tokoslawis', 'pelanggans', 'kategoriPelanggan'));
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