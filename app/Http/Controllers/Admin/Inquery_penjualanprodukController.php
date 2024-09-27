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
use App\Models\Detailpenjualanproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Metodepembayaran;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use App\Models\Stok_tokobanjaran;
use App\Models\Stok_tokoslawi;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class Inquery_penjualanprodukController extends Controller
{

    public function index(Request $request)

{
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;

    $inquery = Penjualanproduk::query();

    if ($status) {
        $inquery->where('status', $status);
    }

    if ($tanggal_penjualan && $tanggal_akhir) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    } elseif ($tanggal_penjualan) {
        $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
        $inquery->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    } elseif ($tanggal_akhir) {
        $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
        $inquery->where('tanggal_penjualan', '<=', $tanggal_akhir);
    } else {
        // Jika tidak ada filter tanggal hari ini
        $inquery->whereDate('tanggal_penjualan', Carbon::today());
    }

    $inquery->orderBy('id', 'DESC');
    $inquery = $inquery->get();

    return view('admin.inquery_penjualanproduk.index', compact('inquery'));
}


public function posting_penjualanproduk($id)
{
    $item = Penjualanproduk::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'posting'
        ]);
    return back()->with('success', 'Berhasil');
}


public function unpost_penjualanproduk($id)
{
    $item = Penjualanproduk::where('id', $id)->first();

    
        // Update status deposit_driver menjadi 'posting'
        $item->update([
            'status' => 'unpost'
        ]);
    return back()->with('success', 'Berhasil');
}

// public function unpost_penjualanproduk($id)
// {
//     // Temukan data penjualan berdasarkan ID
//     $item = Penjualanproduk::where('id', $id)->first();

//     // Pastikan data penjualan ditemukan
//     if ($item) {
//         // Ambil detail penjualan terkait
//         $details = Detailpenjualanproduk::where('penjualanproduk_id', $item->id)->get();
        
//         // Loop melalui setiap detail penjualan untuk mengembalikan stok
//         foreach ($details as $detail) {
//             // Ambil produk_id dan jumlah dari detail penjualan
//             $produkId = $detail->produk_id;
//             $jumlah = $detail->jumlah;
            
//             // Periksa toko_id dan kembalikan stok ke tabel yang sesuai
//             switch ($item->toko_id) {
//                 case 1:
//                     // Kembalikan stok ke stok_tokobanjaran
//                     $stok = Stok_tokobanjaran::where('produk_id', $produkId)->first();
//                     if ($stok) {
//                         $stok->increment('jumlah', $jumlah);
//                     }
//                     break;
//                 case 2:
//                     // Kembalikan stok ke stok_tokoslawi
//                     $stok = Stok_tokoslawi::where('produk_id', $produkId)->first();
//                     if ($stok) {
//                         $stok->increment('jumlah', $jumlah);
//                     }
//                     break;

//                 // Tambahkan case lain untuk toko lain jika ada
//                 // case 3: Kembalikan stok ke stok_toko lainnya...

//                 default:
//                     // Jika toko_id tidak diketahui, bisa ditangani di sini
//                     break;
//             }
//         }

//         // Hapus data dari tabel detailpenjualanproduk berdasarkan penjualanproduk_id
//         Detailpenjualanproduk::where('penjualanproduk_id', $item->id)->delete();

//         // Update status menjadi 'unpost'
//         $item->update(['status' => 'unpost']);
        
//         return back()->with('success', 'Berhasil di-unpost, stok dikembalikan, dan detail penjualan dihapus.');
//     }

//     return back()->with('error', 'Data tidak ditemukan.');
// }






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


        
    // public function edit($id)
    // {
    //     $penjualan = Penjualanproduk::find($id);
        
    //     // Pastikan data penjualan ditemukan
    //     if ($penjualan) {
    //         return view('admin.inquery_penjualanproduk.update', compact('penjualan'));
    //     } else {
    //         return redirect()->back()->with('error', 'Data penjualan tidak ditemukan.');
    //     }
    // }

    public function edit($id)
    {
        $produks = Produk::with(['tokobanjaran', 'stok_tokobanjaran'])->get();
        $metodes = Metodepembayaran::all();

        $penjualan = PenjualanProduk::with('detailPenjualanProduk')->findOrFail($id);
        
        return view('admin.inquery_penjualanproduk.update', compact('penjualan','produks','metodes'));
    }
    
    


    public function update(Request $request, $id)
    {
        // Validasi data input jika diperlukan
        $request->validate([
            'nama_pelanggan' => 'nullable|string',
            'kategori' => 'nullable|string',
            'telp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'jumlah' => 'nullable|array', // Pastikan jumlah produk dikirim sebagai array
            'harga' => 'nullable|array',
            'diskon' => 'nullable|array',
            'kode_produk' => 'nullable|array', // Tambahkan validasi untuk kode_produk
            'kode_lama' => 'nullable|array',   // Tambahkan validasi untuk kode_lama
            'produk_id' => 'nullable|array',   // Tambahkan validasi untuk produk_id
            'nama_produk' => 'nullable|array',  // Tambahkan validasi untuk nama_produk
        ]);
    
        // Cari data penjualan berdasarkan ID
        $penjualan = PenjualanProduk::findOrFail($id);
    
        // Update data pelanggan
        $penjualan->nama_pelanggan = $request->input('nama_pelanggan');
        $penjualan->kategori = $request->input('kategori');
        $penjualan->telp = $request->input('telp');
        $penjualan->alamat = $request->input('alamat');
        $penjualan->sub_total = $request->input('sub_total');
        $penjualan->sub_totalasli = $request->input('sub_totalasli');
        $penjualan->bayar = $request->input('bayar');
        $penjualan->kembali = $request->input('kembali');
        $penjualan->save();
    
        // Menghitung jumlah detail yang ada
        $existingDetailCount = count($penjualan->detailPenjualanProduk);
    
        // Loop untuk update atau tambah detail penjualan
        foreach ($request->input('jumlah') as $key => $jumlah) {
            if ($key < $existingDetailCount) {
                // Update detail yang ada
                $detailPenjualan = $penjualan->detailPenjualanProduk[$key]; // Ambil item detail yang relevan
                $detailPenjualan->jumlah = $jumlah;
                $detailPenjualan->harga = $request->input('harga')[$key] ?? $detailPenjualan->harga;
                $detailPenjualan->diskon = $request->input('diskon')[$key] ?? $detailPenjualan->diskon;
                $detailPenjualan->kode_produk = $request->input('kode_produk')[$key] ?? $detailPenjualan->kode_produk; // Menyimpan kode_produk
                $detailPenjualan->kode_lama = $request->input('kode_lama')[$key] ?? $detailPenjualan->kode_lama;    
                $detailPenjualan->produk_id = $request->input('produk_id')[$key] ?? $detailPenjualan->produk_id;     
                $detailPenjualan->nama_produk = $request->input('nama_produk')[$key] ?? $detailPenjualan->nama_produk;   
    
                // Perhitungan total menggunakan rumus dari Script 2
                $nominalDiskon = ($detailPenjualan->harga * ($detailPenjualan->diskon / 100)) * $jumlah; // Hitung nominal diskon
                $hargaSetelahDiskon = $detailPenjualan->harga - ($detailPenjualan->harga * ($detailPenjualan->diskon / 100));
                $total = $hargaSetelahDiskon * $jumlah;
                $totalasli = $detailPenjualan->harga * $jumlah;
    
                // Simpan total
                $detailPenjualan->total = $total;
                $detailPenjualan->totalasli = $totalasli; // Tambahkan kolom total asli jika diperlukan
                $detailPenjualan->save(); 
            } else {
                // Tambah detail baru jika key lebih besar dari jumlah detail yang ada
                $detailPenjualan = new DetailPenjualanProduk();
                $detailPenjualan->penjualanproduk_id = $penjualan->id; // Asosiasi dengan penjualan yang benar
                $detailPenjualan->jumlah = $jumlah;
                $detailPenjualan->harga = $request->input('harga')[$key] ?? 0; // Pastikan harga tidak null
                $detailPenjualan->diskon = $request->input('diskon')[$key] ?? 0; // Pastikan diskon tidak null
                $detailPenjualan->kode_produk = $request->input('kode_produk')[$key] ?? ''; // Menyimpan kode_produk, kosong jika null
                $detailPenjualan->kode_lama = $request->input('kode_lama')[$key] ?? '';     // Menyimpan kode_lama, kosong jika null
                $detailPenjualan->produk_id = $request->input('produk_id')[$key] ?? null;   // Menyimpan produk_id
                $detailPenjualan->nama_produk = $request->input('nama_produk')[$key] ?? ''; // Menyimpan nama_produk, kosong jika null
    
                // Perhitungan total menggunakan rumus dari Script 2
                $nominalDiskon = ($detailPenjualan->harga * ($detailPenjualan->diskon / 100)) * $jumlah; // Hitung nominal diskon
                $hargaSetelahDiskon = $detailPenjualan->harga - ($detailPenjualan->harga * ($detailPenjualan->diskon / 100));
                $total = $hargaSetelahDiskon * $jumlah;
                $totalasli = $detailPenjualan->harga * $jumlah;
    
                // Simpan total
                $detailPenjualan->total = $total; // Simpan total yang sudah dihitung
                $detailPenjualan->totalasli = $totalasli; // Tambahkan kolom total asli jika diperlukan
                $detailPenjualan->save(); // Simpan detail baru
            }
        }
    
        // Redirect kembali dengan pesan sukses
        return redirect()->route('inquery_penjualanproduk.index')->with('success', 'Data penjualan berhasil diperbarui.');
    }
    


    public function hapusProduk(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:detailpenjualanproduk,id', // Validasi id
        ]);
    
        $detail = DetailPenjualanProduk::find($request->id); // Cari berdasarkan id
        if ($detail) {
            $detail->delete(); // Hapus data
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false]);
    }
    
    
        
    
    

    public function destroy($id)
    {
        //
    }

}