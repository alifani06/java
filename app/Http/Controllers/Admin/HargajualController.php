<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Tokoslawi;
use App\Models\Tokobanjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Detailtoko;
use App\Models\Detailtokoslawi;
use App\Models\Detailtokobanjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;



class HargajualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tokoslawi = Tokoslawi::latest()->first();
        $tokobanjaran = Tokobanjaran::latest()->first();
        $tokotegal = Tokotegal::latest()->first();
        $tokopemalang = Tokopemalang::latest()->first();
        $tokobumiayu = Tokobumiayu::latest()->first();
        $tokocilacap = Tokocilacap::latest()->first();
        $produk = Produk::with(['tokoslawi', 'tokobanjaran','tokotegal', 'tokopemalang', 'tokobumiayu' , 'tokocilacap'])->get();
        return view('admin.hargajual.index', compact('produk', 'tokoslawi', 'tokobanjaran', 'tokotegal', 'tokopemalang', 'tokobumiayu', 'tokocilacap'));
    }


    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        //
    }


    public function all(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $query = Detailtokoslawi::with('produk');

        if ($start_date && $end_date) {
            $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
        }

        $detailtokoslawi = $query->orderBy('updated_at', 'desc')
                                ->get();

        return view('admin.hargajual.all', compact('detailtokoslawi'));
    }

    // public function show()
    // {
    //     $toko = request()->input('toko', 'tokoslawi'); // Ambil input toko dari request, default ke 'tokoslawi'
    //     $today = Carbon::today(); // Tanggal hari ini
        
    //     $produk = Produk::with(['tokoslawi', 'tokobanjaran' , 'tokotegal','tokopemalang', 'tokobumiayu', 'tokocilacap'])
    //         ->where(function ($query) use ($today) {
    //             $query->whereHas('tokoslawi', function ($query) use ($today) {
    //                 $query->whereDate('updated_at', $today)
    //                       ->where(function ($query) {
    //                           $query->whereRaw('tokoslawis.member_harga_slw != tokoslawis.harga_awal')
    //                                 ->orWhereRaw('tokoslawis.non_harga_slw != tokoslawis.harga_awal')
    //                                 ->orWhereRaw('tokoslawis.member_diskon_slw != 0')
    //                                 ->orWhereRaw('tokoslawis.non_diskon_slw != 0');
    //                       });
    //             });
    //         })
    //         ->orWhere(function ($query) use ($today) {
    //             $query->whereHas('tokobanjaran', function ($query) use ($today) {
    //                 $query->whereDate('updated_at', $today)
    //                       ->where(function ($query) {
    //                           $query->whereRaw('tokobanjarans.member_harga_bnjr != tokobanjarans.harga_awal')
    //                                 ->orWhereRaw('tokobanjarans.non_harga_bnjr != tokobanjarans.harga_awal')
    //                                 ->orWhereRaw('tokobanjarans.member_diskon_bnjr != 0')
    //                                 ->orWhereRaw('tokobanjarans.non_diskon_bnjr != 0');
    //                       });
    //             });
    //         })
    //         ->orWhere(function ($query) use ($today) {
    //             $query->whereHas('tokotegal', function ($query) use ($today) {
    //                 $query->whereDate('updated_at', $today)
    //                       ->where(function ($query) {
    //                           $query->whereRaw('tokotegals.member_harga_tgl != tokotegals.harga_awal')
    //                                 ->orWhereRaw('tokotegals.non_harga_tgl != tokotegals.harga_awal')
    //                                 ->orWhereRaw('tokotegals.member_diskon_tgl != 0')
    //                                 ->orWhereRaw('tokotegals.non_diskon_tgl != 0');
    //                       });
    //             });
    //         })
    //         ->orWhere(function ($query) use ($today) {
    //             $query->whereHas('tokopemalang', function ($query) use ($today) {
    //                 $query->whereDate('updated_at', $today)
    //                       ->where(function ($query) {
    //                           $query->whereRaw('tokopemalangs.member_harga_pml != tokopemalangs.harga_awal')
    //                                 ->orWhereRaw('tokopemalangs.non_harga_pml != tokopemalangs.harga_awal')
    //                                 ->orWhereRaw('tokopemalangs.member_diskon_pml != 0')
    //                                 ->orWhereRaw('tokopemalangs.non_diskon_pml != 0');
    //                       });
    //             });
    //         })
    //         ->orWhere(function ($query) use ($today) {
    //             $query->whereHas('tokobumiayu', function ($query) use ($today) {
    //                 $query->whereDate('updated_at', $today)
    //                       ->where(function ($query) {
    //                           $query->whereRaw('tokobumiayus.member_harga_bmy != tokobumiayus.harga_awal')
    //                                 ->orWhereRaw('tokobumiayus.non_harga_bmy != tokobumiayus.harga_awal')
    //                                 ->orWhereRaw('tokobumiayus.member_diskon_bmy != 0')
    //                                 ->orWhereRaw('tokobumiayus.non_diskon_bmy != 0');
    //                       });
    //             });
    //         })->orWhere(function ($query) use ($today) {
    //             $query->whereHas('tokocilacap', function ($query) use ($today) {
    //                 $query->whereDate('updated_at', $today)
    //                       ->where(function ($query) {
    //                           $query->whereRaw('tokocilacaps.member_harga_clc != tokocilacaps.harga_awal')
    //                                 ->orWhereRaw('tokocilacaps.non_harga_clc != tokocilacaps.harga_awal')
    //                                 ->orWhereRaw('tokocilacaps.member_diskon_clc != 0')
    //                                 ->orWhereRaw('tokocilacaps.non_diskon_clc != 0');
    //                       });
    //             });
    //         })
    //         ->get();
                
    //                 // Cek apakah ada data yang diperbarui hari ini
    //                 if ($produk->isEmpty()) {
    //                     // Jika tidak ada, redirect kembali dengan pesan
    //                     return redirect()->back()->with('info', 'Tidak ada data yang diperbarui hari ini.');
    //                 }
                    
    //                 return view('admin.hargajual.show', compact('produk'));
    // }

    public function edit($id)
    {
        //
    }

  
    public function update(Request $request, $id)
    {
        //
    }



    // public function updateHarga(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'id' => 'required|integer|exists:produks,id',
    //         'member_harga_bnjr' => 'nullable|numeric',
    //         'member_diskon_bnjr' => 'nullable|numeric',
    //         'non_harga_bnjr' => 'nullable|numeric',
    //         'non_diskon_bnjr' => 'nullable|numeric',
    //     ]);

    //     // Cari produk berdasarkan ID
    //     $produk = Produk::findOrFail($request->id);

    //     // Proses untuk Toko Banjaran
    //     $tokobanjaran = $produk->tokobanjaran->first();
    //     $hargaAwal = $tokobanjaran ? $tokobanjaran->member_harga_bnjr : null;
    //     $diskonAwal = $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : null;

    //     if ($tokobanjaran) {
    //         // Simpan harga awal dan diskon awal sebelum update
    //         $hargaAwal = $tokobanjaran->member_harga_bnjr;
    //         $diskonAwal = $tokobanjaran->member_diskon_bnjr;

    //         // Update harga dan diskon
    //         $tokobanjaran->member_harga_bnjr = $request->member_harga_bnjr ?? $tokobanjaran->member_harga_bnjr;
    //         $tokobanjaran->member_diskon_bnjr = $request->member_diskon_bnjr ?? $tokobanjaran->member_diskon_bnjr;
    //         $tokobanjaran->non_harga_bnjr = $request->non_harga_bnjr ?? $tokobanjaran->non_harga_bnjr;
    //         $tokobanjaran->non_diskon_bnjr = $request->non_diskon_bnjr ?? $tokobanjaran->non_diskon_bnjr;
    //         $tokobanjaran->save();
    //     } else {
    //         // Buat record baru jika belum ada
    //         $tokobanjaran = Tokobanjaran::create([
    //             'produk_id' => $produk->id,
    //             'member_harga_bnjr' => $request->member_harga_bnjr,
    //             'member_diskon_bnjr' => $request->member_diskon_bnjr,
    //             'non_harga_bnjr' => $request->non_harga_bnjr,
    //             'non_diskon_bnjr' => $request->non_diskon_bnjr,
    //         ]);
    //     }

    //     // Simpan ke tabel detailtokobanjaran
    //     DetailTokobanjaran::create([
    //         'produk_id' => $produk->id,
    //         'tokobanjaran_id' => $tokobanjaran->id,
    //         'harga_awal' => $hargaAwal,
    //         'diskon_awal' => $diskonAwal,
    //         'member_harga' => $request->member_harga_bnjr,
    //         'non_member_harga' => $request->non_harga_bnjr,
    //         'member_diskon' => $request->member_diskon_bnjr,
    //         'non_member_diskon' => $request->non_diskon_bnjr,
    //         'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
    //     ]);

    //     return response()->json(['success' => true]);
    // }
    public function updateHarga(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|integer|exists:produks,id',
            'member_harga_bnjr' => 'nullable|numeric',
            'member_diskon_bnjr' => 'nullable|numeric',
            'non_harga_bnjr' => 'nullable|numeric',
            'non_diskon_bnjr' => 'nullable|numeric',
        ]);

        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($request->id);

        // Proses untuk Toko Banjaran
        $tokobanjaran = $produk->tokobanjaran->first();

        // Inisialisasi variabel harga dan diskon awal sebelum update
        $memberHargaAwal = $tokobanjaran ? $tokobanjaran->member_harga_bnjr : null;
        $memberDiskonAwal = $tokobanjaran ? $tokobanjaran->member_diskon_bnjr : null;
        $nonMemberHargaAwal = $tokobanjaran ? $tokobanjaran->non_harga_bnjr : null;
        $nonMemberDiskonAwal = $tokobanjaran ? $tokobanjaran->non_diskon_bnjr : null;

        if ($tokobanjaran) {
            // Update harga dan diskon untuk Toko Banjaran
            $tokobanjaran->member_harga_bnjr = $request->member_harga_bnjr ?? $tokobanjaran->member_harga_bnjr;
            $tokobanjaran->member_diskon_bnjr = $request->member_diskon_bnjr ?? $tokobanjaran->member_diskon_bnjr;
            $tokobanjaran->non_harga_bnjr = $request->non_harga_bnjr ?? $tokobanjaran->non_harga_bnjr;
            $tokobanjaran->non_diskon_bnjr = $request->non_diskon_bnjr ?? $tokobanjaran->non_diskon_bnjr;
            $tokobanjaran->save();
        } else {
            // Buat record baru jika belum ada
            $tokobanjaran = Tokobanjaran::create([
                'produk_id' => $produk->id,
                'member_harga_bnjr' => $request->member_harga_bnjr,
                'member_diskon_bnjr' => $request->member_diskon_bnjr,
                'non_harga_bnjr' => $request->non_harga_bnjr,
                'non_diskon_bnjr' => $request->non_diskon_bnjr,
            ]);
        }

        // Simpan ke tabel detailtokobanjaran dengan data harga dan diskon awal serta yang baru
        DetailTokobanjaran::create([
            'produk_id' => $produk->id,
            'tokobanjaran_id' => $tokobanjaran->id,
            'member_hargaawal' => $memberHargaAwal, // Harga member sebelum update
            'non_member_hargaawal' => $nonMemberHargaAwal, // Harga non-member sebelum update
            'member_diskonawal' => $memberDiskonAwal, // Diskon member sebelum update
            'non_member_diskonawal' => $nonMemberDiskonAwal, // Diskon non-member sebelum update
            'member_harga' => $request->member_harga_bnjr,
            'non_member_harga' => $request->non_harga_bnjr,
            'member_diskon' => $request->member_diskon_bnjr,
            'non_member_diskon' => $request->non_diskon_bnjr,
            'tanggal_perubahan' => now(), // Set tanggal perubahan dengan timestamp saat ini
        ]);

        return response()->json(['success' => true]);
    }

    public function show()
    {
        // Mengambil data perubahan harga dan diskon dari tabel detailtokobanjaran
        $perubahanProduks = DetailTokobanjaran::with(['produk', 'tokobanjaran'])
            ->where(function($query) {
                $query->whereColumn('member_harga', '!=', 'member_hargaawal')
                    ->orWhereColumn('member_diskon', '!=', 'member_diskonawal')
                    ->orWhereColumn('non_member_harga', '!=', 'non_member_hargaawal')
                    ->orWhereColumn('non_member_diskon', '!=', 'non_member_diskonawal');
            })
            ->orderBy('tanggal_perubahan', 'desc')
            ->get();
        
        // Pastikan data dikirimkan ke view dengan nama 'perubahanProduks'
        return view('admin.hargajual.show', compact('perubahanProduks'));
    }
    
    public function print()
    {
        $perubahanProduks = DetailTokobanjaran::with(['produk', 'tokobanjaran'])
            ->orderBy('tanggal_perubahan', 'desc')
            ->get();

        $pdf = FacadePdf::loadView('admin.hargajual.print', compact('perubahanProduks'));
        return $pdf->stream('laporan_perubahan_harga.pdf');
    }
    



    public function cetakPdf(Request $request)
    {
        $toko = $request->input('toko', 'tokoslawi');
        $today = Carbon::today();

        // Query hanya data yang diubah hari ini
        $produk = Produk::whereHas($toko, function ($query) use ($today) {
            $query->whereDate('updated_at', $today);
        })->with([$toko])->get();

        // Periksa apakah view tersedia
        if (!view()->exists('admin.hargajual.cetak-pdf')) {
            return response()->json(['error' => 'View not found'], 404);
        }

        $pdf = FacadePdf::loadView('admin.hargajual.cetak-pdf', compact('produk', 'toko'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Harga Jual.pdf');
    }


    
    public function destroy($id)
    {
        //
    }
}
