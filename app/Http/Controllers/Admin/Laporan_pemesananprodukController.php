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
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;



class Laporan_pemesananprodukController extends Controller
{
 
    public function index(Request $request)
{
    $status = $request->status;
    $tanggal_pemesanan = $request->tanggal_pemesanan;
    $tanggal_akhir = $request->tanggal_akhir;

    $inquery = Pemesananproduk::query();

    if ($status) {
        $inquery->where('status', $status);
    }

    if ($tanggal_pemesanan && $tanggal_akhir) {
        $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
    } elseif ($tanggal_pemesanan) {
        $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
        $inquery->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->where('tanggal_pemesanan', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal hari ini
        $inquery->whereDate('tanggal_pemesanan', Carbon::today());
    }

    $inquery->orderBy('id', 'DESC');
    $inquery = $inquery->get();

    return view('admin.laporan_pemesananproduk.index', compact('inquery'));
            
}
    public function print_pemesanan(Request $request)
        {
    
            $status = $request->status;
            $tanggal_pemesanan = $request->tanggal_pemesanan;
            $tanggal_akhir = $request->tanggal_akhir;
        
            $inquery = Pemesananproduk::query();
        
            if ($status) {
                $inquery->where('status', $status);
            }
        
            if ($tanggal_pemesanan && $tanggal_akhir) {
                $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->whereBetween('tanggal_pemesanan', [$tanggal_pemesanan, $tanggal_akhir]);
            } elseif ($tanggal_pemesanan) {
                $tanggal_pemesanan = Carbon::parse($tanggal_pemesanan)->startOfDay();
                $inquery->where('tanggal_pemesanan', '>=', $tanggal_pemesanan);
            } elseif ($tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                $inquery->where('tanggal_pemesanan', '<=', $tanggal_akhir);
            } else {
                // Jika tidak ada filter tanggal hari ini
                $inquery->whereDate('tanggal_pemesanan', Carbon::today());
            }
        
            $inquery->orderBy('id', 'DESC');
            $inquery = $inquery->get();
        
    
                $pdf = FacadePdf::loadView('admin.laporan_pemesananproduk.print', compact('inquery'));
                return $pdf->stream('Laporan_Pembelian_Ban.pdf');
      
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