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


// public function unpost_penjualanproduk($id)
// {
//     $item = Penjualanproduk::where('id', $id)->first();

    
//         // Update status deposit_driver menjadi 'posting'
//         $item->update([
//             'status' => 'unpost'
//         ]);
//     return back()->with('success', 'Berhasil');
// }

public function unpost_penjualanproduk($id)
{
    // Temukan data penjualan berdasarkan ID
    $item = Penjualanproduk::where('id', $id)->first();

    // Pastikan data penjualan ditemukan
    if ($item) {
        // Ambil detail penjualan terkait
        $details = Detailpenjualanproduk::where('penjualanproduk_id', $item->id)->get();
        
        // Loop melalui setiap detail penjualan untuk mengembalikan stok
        foreach ($details as $detail) {
            // Ambil produk_id dan jumlah dari detail penjualan
            $produkId = $detail->produk_id;
            $jumlah = $detail->jumlah;
            
            // Periksa toko_id dan kembalikan stok ke tabel yang sesuai
            switch ($item->toko_id) {
                case 1:
                    // Kembalikan stok ke stok_tokobanjaran
                    $stok = Stok_tokobanjaran::where('produk_id', $produkId)->first();
                    if ($stok) {
                        $stok->increment('jumlah', $jumlah);
                    }
                    break;
                case 2:
                    // Kembalikan stok ke stok_tokoslawi
                    $stok = Stok_tokoslawi::where('produk_id', $produkId)->first();
                    if ($stok) {
                        $stok->increment('jumlah', $jumlah);
                    }
                    break;

                // Tambahkan case lain untuk toko lain jika ada
                // case 3: Kembalikan stok ke stok_toko lainnya...

                default:
                    // Jika toko_id tidak diketahui, bisa ditangani di sini
                    break;
            }
        }

        // Hapus data dari tabel detailpenjualanproduk berdasarkan penjualanproduk_id
        Detailpenjualanproduk::where('penjualanproduk_id', $item->id)->delete();

        // Update status menjadi 'unpost'
        $item->update(['status' => 'unpost']);
        
        return back()->with('success', 'Berhasil di-unpost, stok dikembalikan, dan detail penjualan dihapus.');
    }

    return back()->with('error', 'Data tidak ditemukan.');
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
        $penjualan = Penjualanproduk::find($id);
        
        // Pastikan data penjualan ditemukan
        if ($penjualan) {
            return view('admin.inquery_penjualanproduk.update', compact('penjualan'));
        } else {
            return redirect()->back()->with('error', 'Data penjualan tidak ditemukan.');
        }
    }
    
    public function update(Request $request, $id)
    {
        $penjualan = Penjualanproduk::find($id);
        
        // Validasi data yang diinput
        $request->validate([
            'kode_penjualan' => 'required',
            'nama_pelanggan' => 'required',
            'kode_pelanggan' => 'required',
            'telp' => 'required',
            'alamat' => 'required',
            'kategori' => 'required',
            // Tambahkan validasi untuk field lainnya jika diperlukan
        ]);
    
        // Update data penjualan
        $penjualan->update([
            'kode_penjualan' => $request->kode_penjualan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'kode_pelanggan' => $request->kode_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            // Tambahkan update untuk field lainnya jika diperlukan
        ]);
    
        return redirect()->route('inquery_penjualanproduk.update', $id)->with('success', 'Data penjualan berhasil diperbarui.');
    }
    

    public function destroy($id)
    {
        //
    }

}