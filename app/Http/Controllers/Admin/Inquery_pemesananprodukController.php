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
    $status = $request->status;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    $inquery = Pemesananproduk::query();

    if ($status) {
        $inquery->where('status', $status);
    }

    if ($tanggal_awal && $tanggal_akhir) {
        $inquery->whereBetween('tanggal_pemesanan', [$tanggal_awal, $tanggal_akhir]);
    } elseif ($tanggal_awal) {
        $inquery->where('tanggal_pemesanan', '>=', $tanggal_awal);
    } elseif ($tanggal_akhir) {
        $inquery->where('tanggal_pemesanan', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal, gunakan tanggal hari ini
        $inquery->whereDate('tanggal_pemesanan', Carbon::today());
    }

    $inquery->orderBy('id', 'DESC');
    $inquery = $inquery->get();

    return view('admin.inquery_pemesananproduk.index', compact('inquery'));
}

public function unpost_pemesananproduk($id)
{
    $item = Pemesananproduk::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'unpost'
        ]);
    return back()->with('success', 'Berhasil');
}

public function posting_pemesananproduk($id)
{
    $item = Pemesananproduk::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
    return back()->with('success', 'Berhasil');
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