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
use App\Models\Stok_tokoslawi;
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
use App\Models\Detail_stokbarangjadi;
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
use App\Models\Pengiriman_barangjadi;
use App\Models\Subklasifikasi;
use Maatwebsite\Excel\Facades\Excel;




class Inquery_pengirimanbarangjadiController extends Controller{


    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;  // Ambil toko_id dari request

        $query = Pengiriman_barangjadi::with(['produk.klasifikasi', 'toko']); // Pastikan toko diload

        if ($status) {
            $query->where('status', $status);
        }

        if ($toko_id) {
            $query->where('toko_id', $toko_id); // Tambahkan filter berdasarkan toko_id
        }

        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            $query->whereDate('tanggal_pengiriman', Carbon::today());
        }

        // Mengambil data yang telah difilter dan mengelompokkan berdasarkan kode_input
        $stokBarangJadi = $query
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('kode_pengiriman');

        // Ambil daftar toko untuk dropdown
        $tokos = Toko::all();

        return view('admin.inquery_pengirimanbarangjadi.index', compact('stokBarangJadi', 'tokos'));
    }
    
    public function show($id)
    {
        // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
        $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
        
        // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
        if (!$detailStokBarangJadi) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
        $pengirimanBarangJadi = Pengiriman_barangjadi::with([
            'produk.subklasifikasi.klasifikasi', 
            'toko'
        ])->where('kode_pengiriman', $detailStokBarangJadi)->get();
    
        // Kelompokkan data berdasarkan klasifikasi
        $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
            return $item->produk->subklasifikasi->klasifikasi->nama;
        });
    
        // Ambil item pertama untuk informasi toko
        $firstItem = $pengirimanBarangJadi->first();
        
        return view('admin.inquery_pengirimanbarangjadi.show', compact('groupedByKlasifikasi', 'firstItem'));
    }

    public function edit($id)
    {
        // Ambil data detail stok barang jadi yang terkait dengan ID
        $detailStokBarangjadi = Detail_stokbarangjadi::with('produk')
            ->where('id', $id)
            ->firstOrFail();
        
        // Ambil data produk yang terkait dengan ID
        $uniqueStokBarangjadi = collect([$detailStokBarangjadi]);
        
        // Ambil klasifikasi yang terkait dengan produk yang ada
        $produkIds = $uniqueStokBarangjadi->pluck('produk_id')->toArray();
        $klasifikasiIds = $uniqueStokBarangjadi->pluck('klasifikasi_id')->toArray();
        
        $klasifikasis = Klasifikasi::whereIn('id', $klasifikasiIds)
            ->with(['produks' => function ($query) use ($produkIds) {
                $query->whereIn('id', $produkIds);
            }])
            ->get();
        
        // Ambil semua toko
        $tokos = Toko::all();
        
        return view('admin.inquery_pengirimanbarangjadi.edit', compact('klasifikasis', 'tokos', 'uniqueStokBarangjadi'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'toko_id' => 'required|exists:tokos,id',
            'produk_id.*' => 'required|exists:produks,id',
            'jumlah.*' => 'required|numeric|min:0'
        ]);
        
        // Ambil data detail stok barang jadi yang terkait dengan ID
        $detailStokBarangjadi = Detail_stokbarangjadi::findOrFail($id);
        
        // Update data detail stok barang jadi
        $detailStokBarangjadi->toko_id = $request->toko_id;
        $detailStokBarangjadi->save();
        
        // Loop untuk update produk
        foreach ($request->produk_id as $index => $produk_id) {
            $detail = Detail_stokbarangjadi::where('id', $id)
                ->where('produk_id', $produk_id)
                ->first();
            
            if ($detail) {
                $detail->jumlah = $request->jumlah[$index];
                $detail->save();
            } else {
                // Jika data produk tidak ditemukan, tambahkan data baru
                Detail_stokbarangjadi::create([
                    'id' => $id,
                    'produk_id' => $produk_id,
                    'jumlah' => $request->jumlah[$index],
                    'toko_id' => $request->toko_id
                ]);
            }
        }
        
        return redirect()->route('admin.pengiriman_barangjadi.index')->with('success', 'Data berhasil diperbarui');
    }

    public function unpost_pengirimanbarangjadi($id)
    {
            // Ambil data stok barang berdasarkan ID
            $stok = Pengiriman_barangjadi::where('id', $id)->first();
        
            // Pastikan data ditemukan
            if (!$stok) {
                return back()->with('error', 'Data tidak ditemukan.');
            }
        
            // Ambil kode_input dari stok yang diambil
            $kodeInput = $stok->kode_pengiriman;
        
            // Update status untuk semua stok dengan kode_input yang sama di tabel stok_barangjadi
            Pengiriman_barangjadi::where('kode_pengiriman', $kodeInput)->update([
                'status' => 'unpost'
            ]);
            return back()->with('success', 'Berhasil mengubah status semua produk dan detail terkait dengan kode_input yang sama.');
    }
        
    public function posting_pengirimanbarangjadi($id)
    {
           // Ambil data pengiriman_barangjadi berdasarkan ID
            $pengiriman = Pengiriman_barangjadi::where('id', $id)->first();
        
            // Pastikan data ditemukan
            if (!$pengiriman) {
                return response()->json(['error' => 'Data tidak ditemukan.'], 404);
            }
        
            // Ambil kode_pengiriman dari pengiriman yang diambil
            $kodePengiriman = $pengiriman->kode_pengiriman;
        
            // Update status untuk semua pengiriman_barangjadi dengan kode_pengiriman yang sama
            Pengiriman_barangjadi::where('kode_pengiriman', $kodePengiriman)->update([
                'status' => 'posting'
            ]);
        
            // Update status untuk semua stok_tokoslawi terkait dengan pengiriman_barangjadi_id
            Stok_tokoslawi::where('pengiriman_barangjadi_id', $id)->update([
                'status' => 'posting'
            ]);
        
            return response()->json(['success' => 'Berhasil mengubah status semua produk dan detail terkait dengan kode_pengiriman yang sama.']);
    }

    public function print($id)
    {
            // Ambil kode_pengiriman dari pengiriman_barangjadi berdasarkan id
            $detailStokBarangJadi = Pengiriman_barangjadi::where('id', $id)->value('kode_pengiriman');
                
            // Jika kode_pengiriman tidak ditemukan, tampilkan pesan error
            if (!$detailStokBarangJadi) {
                return redirect()->back()->with('error', 'Data tidak ditemukan.');
            }
            
            // Ambil semua data dengan kode_pengiriman yang sama, termasuk relasi ke klasifikasi
            $pengirimanBarangJadi = Pengiriman_barangjadi::with([
                'produk.subklasifikasi.klasifikasi', 
                'toko'
            ])->where('kode_pengiriman', $detailStokBarangJadi)->get();
    
            // Kelompokkan data berdasarkan klasifikasi
            $groupedByKlasifikasi = $pengirimanBarangJadi->groupBy(function($item) {
                return $item->produk->subklasifikasi->klasifikasi->nama;
            });
    
            // Ambil item pertama untuk informasi toko
            $firstItem = $pengirimanBarangJadi->first();
            $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.print', compact('groupedByKlasifikasi', 'firstItem'));
    
            // Menambahkan nomor halaman di kanan bawah
            $pdf->output();
            $dompdf = $pdf->getDomPDF();
            $canvas = $dompdf->getCanvas();
            $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
                $text = "Page $pageNumber of $pageCount";
                $font = $fontMetrics->getFont('Arial', 'normal');
                $size = 8;
    
                // Menghitung lebar teks
                $width = $fontMetrics->getTextWidth($text, $font, $size);
    
                // Mengatur koordinat X dan Y
                $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
                $y = $canvas->get_height() - 15; // 15 pixel dari bawah
    
                // Menambahkan teks ke posisi yang ditentukan
                $canvas->text($x, $y, $text, $font, $size);
            });
    
            // Output PDF ke browser
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

    public function cetak_barcode($id)
    {
        $produk = Produk::findOrFail($id); 
    
        $klasifikasis = Klasifikasi::all();
        $subklasifikasis = Subklasifikasi::all();
    
        $pdf = FacadePdf::loadView('admin.inquery_pengirimanbarangjadi.cetak_barcode', compact('produk', 'klasifikasis', 'subklasifikasis'));
        
        $pdf->setPaper([0, 0, 612, 400], 'portrait'); 
        return $pdf->stream('penjualan.pdf');
    }

    
}