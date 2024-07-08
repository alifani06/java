<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use App\Models\Produk;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Detailtoko;
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
        $tokobenjaran = Tokobenjaran::latest()->first();
        $tokotegal = Tokotegal::latest()->first();
        $tokopemalang = Tokopemalang::latest()->first();
        $tokobumiayu = Tokobumiayu::latest()->first();
        $produk = Produk::with(['tokoslawi', 'tokobenjaran','tokotegal', 'tokopemalang', 'tokobumiayu'])->get();
        return view('admin.hargajual.index', compact('produk', 'tokoslawi', 'tokobenjaran', 'tokotegal', 'tokopemalang', 'tokobumiayu'));
    }


    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        //
    }

    
    public function show()
    {
        $toko = request()->input('toko', 'tokoslawi'); // Ambil input toko dari request, default ke 'tokoslawi'
        $today = Carbon::today(); // Tanggal hari ini
        
        $produk = Produk::with(['tokoslawi', 'tokobenjaran' , 'tokotegal'])
            ->where(function ($query) use ($today) {
                $query->whereHas('tokoslawi', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokoslawis.member_harga_slw != tokoslawis.harga_awal')
                                    ->orWhereRaw('tokoslawis.non_harga_slw != tokoslawis.harga_awal')
                                    ->orWhereRaw('tokoslawis.member_diskon_slw != 0')
                                    ->orWhereRaw('tokoslawis.non_diskon_slw != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokobenjaran', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokobenjarans.member_harga_bnjr != tokobenjarans.harga_awal')
                                    ->orWhereRaw('tokobenjarans.non_harga_bnjr != tokobenjarans.harga_awal')
                                    ->orWhereRaw('tokobenjarans.member_diskon_bnjr != 0')
                                    ->orWhereRaw('tokobenjarans.non_diskon_bnjr != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokotegal', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokotegals.member_harga_tgl != tokotegals.harga_awal')
                                    ->orWhereRaw('tokotegals.non_harga_tgl != tokotegals.harga_awal')
                                    ->orWhereRaw('tokotegals.member_diskon_tgl != 0')
                                    ->orWhereRaw('tokotegals.non_diskon_tgl != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokopemalang', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokopemalangs.member_harga_pml != tokopemalangs.harga_awal')
                                    ->orWhereRaw('tokopemalangs.non_harga_pml != tokopemalangs.harga_awal')
                                    ->orWhereRaw('tokopemalangs.member_diskon_pml != 0')
                                    ->orWhereRaw('tokopemalangs.non_diskon_pml != 0');
                          });
                });
            })
            ->orWhere(function ($query) use ($today) {
                $query->whereHas('tokobumiayu', function ($query) use ($today) {
                    $query->whereDate('updated_at', $today)
                          ->where(function ($query) {
                              $query->whereRaw('tokobumiayus.member_harga_bmy != tokobumiayus.harga_awal')
                                    ->orWhereRaw('tokobumiayus.non_harga_bmy != tokobumiayus.harga_awal')
                                    ->orWhereRaw('tokobumiayus.member_diskon_bmy != 0')
                                    ->orWhereRaw('tokobumiayus.non_diskon_bmy != 0');
                          });
                });
            })
            ->get();
                
                    // Cek apakah ada data yang diperbarui hari ini
                    if ($produk->isEmpty()) {
                        // Jika tidak ada, redirect kembali dengan pesan
                        return redirect()->back()->with('info', 'Tidak ada data yang diperbarui hari ini.');
                    }
                    
                    return view('admin.hargajual.show', compact('produk'));
    }


 
 
    public function edit($id)
    {
        //
    }

  
    public function update(Request $request, $id)
    {
        //
    }


public function updateHarga(Request $request)
{
    // Validasi input
    $request->validate([
        'id' => 'required|integer|exists:produks,id',
        'member_harga_slw' => 'nullable|numeric',
        'member_diskon_slw' => 'nullable|numeric',
        'non_harga_slw' => 'nullable|numeric',
        'non_diskon_slw' => 'nullable|numeric',
        
        'member_harga_bnjr' => 'nullable|numeric',
        'member_diskon_bnjr' => 'nullable|numeric',
        'non_harga_bnjr' => 'nullable|numeric',
        'non_diskon_bnjr' => 'nullable|numeric',

        'member_harga_tgl' => 'nullable|numeric',
        'member_diskon_tgl' => 'nullable|numeric',
        'non_harga_tgl' => 'nullable|numeric',
        'non_diskon_tgl' => 'nullable|numeric',

        'member_harga_pml' => 'nullable|numeric',
        'member_diskon_pml' => 'nullable|numeric',
        'non_harga_pml' => 'nullable|numeric',
        'non_diskon_pml' => 'nullable|numeric',

        'member_harga_bmy' => 'nullable|numeric',
        'member_diskon_bmy' => 'nullable|numeric',
        'non_harga_bmy' => 'nullable|numeric',
        'non_diskon_bmy' => 'nullable|numeric',
    ]);

    // Cari produk berdasarkan ID
    $produk = Produk::findOrFail($request->id);

    // Update atau buat entri baru untuk toko Slawi
    $tokoslawi = $produk->tokoslawi->first();
    if ($tokoslawi) {
        $tokoslawi->member_harga_slw = $request->member_harga_slw ?? $tokoslawi->member_harga_slw;
        $tokoslawi->member_diskon_slw = $request->member_diskon_slw ?? $tokoslawi->member_diskon_slw;
        $tokoslawi->non_harga_slw = $request->non_harga_slw ?? $tokoslawi->non_harga_slw;
        $tokoslawi->non_diskon_slw = $request->non_diskon_slw ?? $tokoslawi->non_diskon_slw;
        $tokoslawi->save();
    } else {
        $tokoslawi = Tokoslawi::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $request->member_harga_slw,
            'member_diskon_slw' => $request->member_diskon_slw,
            'non_harga_slw' => $request->non_harga_slw,
            'non_diskon_slw' => $request->non_diskon_slw,
        ]);
    }

    $harga_diskon_member = $request->member_harga_slw * (1 - ($request->member_diskon_slw / 100));
    $harga_diskon_non_member = $request->non_harga_slw * (1 - ($request->non_diskon_slw / 100));
    // Simpan ID toko Slawi di detailtoko
    Detailtoko::create([
        'tokoslawi_id' => $tokoslawi->id,
        'member_harga' => $request->member_harga_slw,
        'member_diskon' => $request->member_diskon_slw,
        'non_member_harga' => $request->non_harga_slw,
        'non_member_diskon' => $request->non_diskon_slw,
        'harga_diskon_member' => $harga_diskon_member,
        'harga_diskon_non' => $harga_diskon_non_member,
        'harga_awal' => $request->member_harga_slw,
        'diskon_awal' =>  0,
        'created_at' => Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon::now('Asia/Jakarta'),
    ]);

    // Update atau buat entri baru untuk toko Benjaran
    $tokobenjaran = $produk->tokobenjaran->first();
    if ($tokobenjaran) {
        $tokobenjaran->member_harga_bnjr = $request->member_harga_bnjr ?? $tokobenjaran->member_harga_bnjr;
        $tokobenjaran->member_diskon_bnjr = $request->member_diskon_bnjr ?? $tokobenjaran->member_diskon_bnjr;
        $tokobenjaran->non_harga_bnjr = $request->non_harga_bnjr ?? $tokobenjaran->non_harga_bnjr;
        $tokobenjaran->non_diskon_bnjr = $request->non_diskon_bnjr ?? $tokobenjaran->non_diskon_bnjr;
        $tokobenjaran->save();
    } else {
        $tokobenjaran = Tokobenjaran::create([
            'produk_id' => $produk->id,
            'member_harga_bnjr' => $request->member_harga_bnjr,
            'member_diskon_bnjr' => $request->member_diskon_bnjr,
            'non_harga_bnjr' => $request->non_harga_bnjr,
            'non_diskon_bnjr' => $request->non_diskon_bnjr,
        ]);
    }

    $harga_diskon_member = $request->member_harga_bnjr * (1 - ($request->member_diskon_bnjr / 100));
    $harga_diskon_non_member = $request->non_harga_bnjr * (1 - ($request->non_diskon_bnjr / 100));
    // Simpan ID toko Benjaran di detailtoko
    Detailtoko::create([
        'tokobenjaran_id' => $tokobenjaran->id,
        'member_harga' => $request->member_harga_bnjr,
        'member_diskon' => $request->member_diskon_bnjr,
        'non_member_harga' => $request->non_harga_bnjr,
        'non_member_diskon' => $request->non_diskon_bnjr,
        'harga_diskon_member' => $harga_diskon_member,
        'harga_diskon_non' => $harga_diskon_non_member,
        'created_at' => Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon::now('Asia/Jakarta'),
    ]);

    // Update atau buat entri baru untuk toko Tegal
    $tokotegal = $produk->tokotegal->first();
    if ($tokotegal) {
        $tokotegal->member_harga_tgl = $request->member_harga_tgl ?? $tokotegal->member_harga_tgl;
        $tokotegal->member_diskon_tgl = $request->member_diskon_tgl ?? $tokotegal->member_diskon_tgl;
        $tokotegal->non_harga_tgl = $request->non_harga_tgl ?? $tokotegal->non_harga_tgl;
        $tokotegal->non_diskon_tgl = $request->non_diskon_tgl ?? $tokotegal->non_diskon_tgl;
        $tokotegal->save();
    } else {
        $tokotegal = Tokotegal::create([
            'produk_id' => $produk->id,
            'member_harga_tgl' => $request->member_harga_tgl,
            'member_diskon_tgl' => $request->member_diskon_tgl,
            'non_harga_tgl' => $request->non_harga_tgl,
            'non_diskon_tgl' => $request->non_diskon_tgl,
        ]);
    }

    $harga_diskon_member = $request->member_harga_tgl * (1 - ($request->member_diskon_tgl / 100));
    $harga_diskon_non_member = $request->non_harga_tgl * (1 - ($request->non_diskon_tgl / 100));
    // Simpan ID toko Tegal di detailtoko
    Detailtoko::create([
        'tokotegal_id' => $tokotegal->id,
        'member_harga' => $request->member_harga_tgl,
        'member_diskon' => $request->member_diskon_tgl,
        'non_member_harga' => $request->non_harga_tgl,
        'non_member_diskon' => $request->non_diskon_tgl,
        'harga_diskon_member' => $harga_diskon_member,
        'harga_diskon_non' => $harga_diskon_non_member,
        'created_at' => Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon::now('Asia/Jakarta'),
 
    ]);


    // Update atau buat entri baru untuk toko pemalang
    $tokopemalang = $produk->tokopemalang->first();
    if ($tokopemalang) {
        $tokopemalang->member_harga_pml = $request->member_harga_pml ?? $tokopemalang->member_harga_pml;
        $tokopemalang->member_diskon_pml = $request->member_diskon_pml ?? $tokopemalang->member_diskon_pml;
        $tokopemalang->non_harga_pml = $request->non_harga_pml ?? $tokopemalang->non_harga_pml;
        $tokopemalang->non_diskon_pml = $request->non_diskon_pml ?? $tokopemalang->non_diskon_pml;
        $tokopemalang->save();
    } else {
        $tokopemalang = Tokopemalang::create([
            'produk_id' => $produk->id,
            'member_harga_pml' => $request->member_harga_pml,
            'member_diskon_pml' => $request->member_diskon_pml,
            'non_harga_pml' => $request->non_harga_pml,
            'non_diskon_pml' => $request->non_diskon_pml,
        ]);
    }

    $harga_diskon_member = $request->member_harga_pml * (1 - ($request->member_diskon_pml / 100));
    $harga_diskon_non_member = $request->non_harga_pml * (1 - ($request->non_diskon_pml / 100));
    // Simpan ID toko Pemalang di detailtoko
    Detailtoko::create([
        'tokopemalang_id' => $tokopemalang->id,
        'member_harga' => $request->member_harga_pml,
        'member_diskon' => $request->member_diskon_pml,
        'non_member_harga' => $request->non_harga_pml,
        'non_member_diskon' => $request->non_diskon_pml,
        'harga_diskon_member' => $harga_diskon_member,
        'harga_diskon_non' => $harga_diskon_non_member,
        'created_at' => Carbon::now('Asia/Jakarta'),
        'updated_at' => Carbon::now('Asia/Jakarta'),
 
    ]);

     // Update atau buat entri baru untuk toko Bumiayu
     $tokobumiayu = $produk->tokobumiayu->first();
     if ($tokobumiayu) {
         $tokobumiayu->member_harga_bmy = $request->member_harga_bmy ?? $tokobumiayu->member_harga_bmy;
         $tokobumiayu->member_diskon_bmy = $request->member_diskon_bmy ?? $tokobumiayu->member_diskon_bmy;
         $tokobumiayu->non_harga_bmy = $request->non_harga_bmy ?? $tokobumiayu->non_harga_bmy;
         $tokobumiayu->non_diskon_bmy = $request->non_diskon_bmy ?? $tokobumiayu->non_diskon_bmy;
         $tokobumiayu->save();
     } else {
         $tokobumiayu = Tokobumiayu::create([
             'produk_id' => $produk->id,
             'member_harga_bmy' => $request->member_harga_bmy,
             'member_diskon_bmy' => $request->member_diskon_bmy,
             'non_harga_bmy' => $request->non_harga_bmy,
             'non_diskon_bmy' => $request->non_diskon_bmy,
         ]);
     }
 
     $harga_diskon_member = $request->member_harga_bmy * (1 - ($request->member_diskon_bmy / 100));
     $harga_diskon_non_member = $request->non_harga_bmy * (1 - ($request->non_diskon_bmy / 100));
     // Simpan ID toko Pemalang di detailtoko
     Detailtoko::create([
         'tokobumiayu_id' => $tokobumiayu->id,
         'member_harga' => $request->member_harga_bmy,
         'member_diskon' => $request->dikon_member_bmy,
         'non_member_harga' => $request->non_harga_bmy,
         'non_member_diskon' => $request->non_diskon_bmy,
         'harga_diskon_member' => $harga_diskon_member,
         'harga_diskon_non' => $harga_diskon_non_member,
         'created_at' => Carbon::now('Asia/Jakarta'),
         'updated_at' => Carbon::now('Asia/Jakarta'),
  
     ]);

    return response()->json(['success' => true]);
}

    public function cetakPdf(Request $request)
{
    $toko = $request->input('toko', 'tokoslawi');
    $produk = Produk::with([$toko])->get();

    // Periksa apakah view tersedia
    if (!view()->exists('admin.hargajual.cetak-pdf')) {
        return response()->json(['error' => 'View not found'], 404);
    }

    $pdf = FacadePdf::loadView('admin.hargajual.cetak-pdf', compact('produk', 'toko'))
        ->setPaper('a4', 'portrait');

    return $pdf->stream('updated-items.pdf');
}

    
    public function destroy($id)
    {
        //
    }
}
