<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
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
use App\Models\Detailpermintaanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Permintaanproduk;
use App\Models\Permintaanprodukdetail;
use App\Models\Klasifikasi;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class PermintaanprodukController extends Controller{

    public function index(Request $request)
    {
      
        return view('admin.permintaan_produk.index');
    }
    

    public function create()
    {
        $klasifikasis = Klasifikasi::with('produks')->get();
        
        return view('admin.permintaan_produk.create', compact('klasifikasis'));
    }
    

    // public function store(Request $request)
    // {
    
    //     $kode = $this->kode();
    //     $produkData = $request->input('produk', []);
    //     // $klasifikasiId = $request->input('klasifikasi_id'); // Ambil klasifikasi_id dari input

    //     foreach ($produkData as $produkId => $data) {
    //         $jumlah = $data['jumlah'] ?? null; 

    //         if (!is_null($jumlah) && $jumlah !== '') {
    //             PermintaanProduk::create([
    //                 'produk_id' => $produkId,
    //                 // 'klasifikasi_id' => $klasifikasiId, // Tambahkan klasifikasi_id
    //                 'jumlah' => $jumlah,
    //                 'kode_permintaan' => $this->kode(),
    //                 'qrcode_permintaan' => 'https://javabakery.id/permintaan_produk/' . $kode,
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Data berhasil disimpan.');
    // }

    // public function kode()
    // {
    //     $lastBarang = Permintaanproduk::latest()->first();
    //     if (!$lastBarang) {
    //         $num = 1;
    //     } else {
    //         $lastCode = $lastBarang->kode_permintaan;
    //         $num = (int) substr($lastCode, strlen('FE')) + 1;
    //     }
    //     $formattedNum = sprintf("%06s", $num);
    //     $prefix = 'PB';
    //     $newCode = $prefix . $formattedNum;
    //     return $newCode;
    // }
    public function store(Request $request)
    {
        // Generate a new kode_permintaan
        $kode = $this->kode();
    
        // Create the main PermintaanProduk entry
        $permintaanProduk = PermintaanProduk::create([
            'kode_permintaan' => $kode,
            'qrcode_permintaan' => 'https://javabakery.id/permintaan_produk/' . $kode,
            // Include other necessary fields if needed
        ]);
    
        // Get produk data from the request
        $produkData = $request->input('produk', []);
    
        // Save each product with the same kode_permintaan
        foreach ($produkData as $produkId => $data) {
            $jumlah = $data['jumlah'] ?? null;
    
            if (!is_null($jumlah) && $jumlah !== '') {
                // Create the detail entry with the same kode_permintaan
                Detailpermintaanproduk::create([
                    'permintaanproduk_id' => $permintaanProduk->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                ]);
            }
        }
    
        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
    
    public function kode()
    {
        $lastBarang = PermintaanProduk::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_permintaan;
            $num = (int) substr($lastCode, strlen('PB')) + 1; // Updated the prefix
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'PB';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
    
    public function show($id)
    {   
     
    }
    

    public function edit($id)
    {
           
    }
        

    public function update(Request $request, $id)
    {
           
    }


        public function destroy($id)
        {
            DB::transaction(function () use ($id) {
                $pemesanan = Pemesananproduk::findOrFail($id);
        
                // Menghapus (soft delete) detail pemesanan terkait
                DetailPemesananProduk::where('pemesananproduk_id', $id)->delete();
        
                // Menghapus (soft delete) data pemesanan
                $pemesanan->delete();
            });
        
            return redirect('admin/pemesanan_produk')->with('success', 'Berhasil menghapus data pesanan');
        }
        

}