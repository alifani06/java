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
        $stokBarangJadi = Stok_barangjadi::with(['produk.klasifikasi'])->get();
        
        return view('admin.stok_barangjadi.index', compact('stokBarangJadi'));
    }
    
    

    public function create()
    {
        
        $klasifikasis = Klasifikasi::with('produks')->get();
        
        return view('admin.stok_barangjadi.create', compact('klasifikasis'));    
    }




    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'klasifikasi_id' => 'required|exists:klasifikasis,id',
    //             'produk' => 'required|array',
    //             'produk.*.stok' => 'nullable|numeric|min:0',
    //         ],
    //         [
    //             'klasifikasi_id.required' => 'Pilih klasifikasi yang valid',
    //             'produk.required' => 'Pilih produk dan masukkan stok',
    //             'produk.*.stok.numeric' => 'Stok harus berupa angka',
    //             'produk.*.stok.min' => 'Stok tidak boleh kurang dari 0',
    //         ]
    //     );
    
    //     if ($validator->fails()) {
    //         // Mengambil kesalahan validasi
    //         $errors = $validator->errors()->all();
    //         return back()->withInput()->with('error', $errors);
    //     }
    
    //     // Simpan data stok
    //     foreach ($request->input('produk') as $produkId => $data) {
    //         // Pastikan $data['stok'] memiliki nilai sebelum mencoba menyimpannya
    //         if (isset($data['stok']) && $data['stok'] !== '') {
    //             Stok_barangjadi::updateOrCreate(
    //                 ['produk_id' => $produkId, 'klasifikasi_id' => $request->input('klasifikasi_id')],
    //                 ['stok' => $data['stok'], 'status' => 'unpost']
    //             );
    //         }
    //     }
    
    //     return redirect('admin/stok_barangjadi')->with('success', 'Berhasil menambahkan stok barang jadi');
    // }
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make(
            $request->all(),
            [
                'klasifikasi_id' => 'required|exists:klasifikasis,id',
                'produk' => 'required|array',
                'produk.*.stok' => 'nullable|numeric|min:0',
            ],
            [
                'klasifikasi_id.required' => 'Pilih klasifikasi yang valid',
                'produk.required' => 'Pilih produk dan masukkan stok',
                'produk.*.stok.numeric' => 'Stok harus berupa angka',
                'produk.*.stok.min' => 'Stok tidak boleh kurang dari 0',
            ]
        );
    
        if ($validator->fails()) {
            // Mengambil kesalahan validasi
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }
    
        // Simpan data stok
        foreach ($request->input('produk') as $produkId => $data) {
            // Pastikan $data['stok'] memiliki nilai sebelum mencoba menyimpannya
            if (isset($data['stok']) && $data['stok'] !== '') {
                $stokBarangJadi = Stok_barangjadi::where('produk_id', $produkId)
                    ->where('klasifikasi_id', $request->input('klasifikasi_id'))
                    ->first();
    
                if ($stokBarangJadi) {
                    // Tambahkan stok yang sudah ada dengan stok yang baru
                    $stokBarangJadi->stok += $data['stok'];
                    $stokBarangJadi->save();
                } else {
                    // Buat entri baru jika belum ada
                    Stok_barangjadi::create([
                        'produk_id' => $produkId,
                        'klasifikasi_id' => $request->input('klasifikasi_id'),
                        'stok' => $data['stok'],
                        'status' => 'unpost',
                    ]);
                }
            }
        }
    
        return redirect('admin/stok_barangjadi')->with('success', 'Berhasil menambahkan stok barang jadi');
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