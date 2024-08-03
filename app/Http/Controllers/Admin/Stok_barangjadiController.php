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
use App\Models\Detail_stokbarangjadi;
use App\Models\Stok_barangjadi;
use App\Models\Penjualanproduk;
use App\Models\Toko;
use App\Models\Dppemesanan;
use App\Models\Metodepembayaran;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use App\Imports\ProdukImport;
use Maatwebsite\Excel\Facades\Excel;




class Stok_barangjadiController extends Controller{

public function index()
{
    // Mengambil data stok_barangjadi beserta relasi detail_stokbarangjadi dan produk, lalu group by kode_input
    $stokBarangJadi = Stok_barangjadi::with('detail_stokbarangjadi.produk.klasifikasi')
        ->get()
        ->groupBy('kode_input');

    return view('admin.stok_barangjadi.index', compact('stokBarangJadi'));
}

    public function create()
    {
        
        $klasifikasis = Klasifikasi::with('produks')->get();
        
        return view('admin.stok_barangjadi.create', compact('klasifikasis'));    
    }

public function store(Request $request)
{
    $kode = $this->kode();

    $produkData = $request->input('produk', []);

    $detailData = [];

    foreach ($produkData as $produkId => $data) {
        $stok = $data['stok'] ?? null;

        if (!is_null($stok) && $stok !== '') {
            $stokBarangJadi = Stok_barangjadi::create([
                'produk_id' => $produkId,
                'klasifikasi_id' => $request->input('klasifikasi_id'),
                'stok' => $stok,
                'kode_input' => $kode,
                'tanggal_input' => Carbon::now('Asia/Jakarta'),
            ]);

            $detailData[] = [
                'stokbarangjadi_id' => $stokBarangJadi->id,
                'produk_id' => $produkId,
                'klasifikasi_id' => $request->input('klasifikasi_id'),
                'stok' => $stok,
                'status' => 'unpost',
            ];
        }
    }

    if (!empty($detailData)) {
        Detail_stokbarangjadi::insert($detailData);
    }

    return redirect('admin/stok_barangjadi')->with('success', 'Berhasil menambahkan stok barang jadi');
}



    public function kode()
    {
        $lastBarang = Stok_barangjadi::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_input;
            $num = (int) substr($lastCode, strlen('SB')) + 1; 
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'SB';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
    

    
//     public function show($id)
// {
//     $permintaanProduk = PermintaanProduk::find($id);
//     $detailPermintaanProduks = DetailPermintaanProduk::where('permintaanproduk_id', $id)->get();

//     // Mengelompokkan produk berdasarkan klasifikasi
//     $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
//         return $item->produk->klasifikasi->nama; // Ganti dengan nama klasifikasi jika diperlukan
//     });

//     // Menghitung total jumlah per klasifikasi
//     $totalPerDivisi = $produkByDivisi->map(function($produks) {
//         return $produks->sum('jumlah');
//     });

//     // Ambil data Subklasifikasi berdasarkan Klasifikasi
//     $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
//         return $produks->groupBy(function($item) {
//             return $item->produk->subklasifikasi->nama; // Ganti dengan nama subklasifikasi jika diperlukan
//         });
//     });

//     return view('admin.permintaan_produk.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi'));
// }
public function show($id)
{
    $permintaanProduk = PermintaanProduk::find($id);
    $detailPermintaanProduks = DetailPermintaanProduk::with('toko')->where('permintaanproduk_id', $id)->get();

    // Mengelompokkan produk berdasarkan klasifikasi
    $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
        return $item->produk->klasifikasi->nama;
    });

    // Menghitung total jumlah per klasifikasi
    $totalPerDivisi = $produkByDivisi->map(function($produks) {
        return $produks->sum('jumlah');
    });

    // Ambil data Subklasifikasi berdasarkan Klasifikasi
    $subklasifikasiByDivisi = $produkByDivisi->map(function($produks) {
        return $produks->groupBy(function($item) {
            return $item->produk->subklasifikasi->nama;
        });
    });

    // Mengambil nama toko dari salah satu detail permintaan produk
    $toko = $detailPermintaanProduks->first()->toko;

    return view('admin.permintaan_produk.show', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi', 'subklasifikasiByDivisi', 'toko'));
}


    public function print($id)
    {
        // $permintaanProduk = PermintaanProduk::where('id', $id)->firstOrFail();
        
        // $detailPermintaanProduks = $permintaanProduk->detailpermintaanproduks;
        $permintaanProduk = PermintaanProduk::find($id);
        $detailPermintaanProduks = DetailPermintaanProduk::where('permintaanproduk_id', $id)->get();
    
        // Mengelompokkan produk berdasarkan divisi
        $produkByDivisi = $detailPermintaanProduks->groupBy(function($item) {
            return $item->produk->klasifikasi->nama; // Ganti dengan nama divisi jika diperlukan
        });
    
        // Menghitung total jumlah per divisi
        $totalPerDivisi = $produkByDivisi->map(function($produks) {
            return $produks->sum('jumlah');
        });
        $toko = $detailPermintaanProduks->first()->toko;

        $pdf = FacadePdf::loadView('admin.permintaan_produk.print', compact('permintaanProduk', 'produkByDivisi', 'totalPerDivisi','toko'));

        return $pdf->stream('surat_permintaan_produk.pdf');
    }

    public function unpost(Request $request, $id)
    {
        $permintaan = Detailpermintaanproduk::find($id);
    
        if ($permintaan) {
            $permintaan->status = 'posting';
            $permintaan->save();
    
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false], 404);
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
        
        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
    
            Excel::import(new ProdukImport, $request->file('file'));
    
            // Redirect to the form with success message
            return redirect()->route('form.produk')->with('success', 'Data produk berhasil diimpor.');
        }
    
        public function formProduk()
        {
            $klasifikasis = Klasifikasi::with('produks')->get();
            $importedData = session('imported_data', []);
            return view('admin.permintaan_produk.form', compact('klasifikasis', 'importedData'));
        }
}