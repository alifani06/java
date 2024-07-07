<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use App\Models\Produk;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
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
        $produk = Produk::with(['tokoslawi', 'tokobenjaran','tokotegal'])->get();
        return view('admin.hargajual.index', compact('produk', 'tokoslawi', 'tokobenjaran', 'tokotegal'));
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
            
        ]);

        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($request->id);

        // Update harga dan diskon untuk toko Slawi
        $tokoslawi = $produk->tokoslawi->first();
        if ($tokoslawi) {
            $tokoslawi->member_harga_slw = $request->member_harga_slw ?? $tokoslawi->member_harga_slw;
            $tokoslawi->member_diskon_slw = $request->member_diskon_slw ?? $tokoslawi->member_diskon_slw;
            $tokoslawi->non_harga_slw = $request->non_harga_slw ?? $tokoslawi->non_harga_slw;
            $tokoslawi->non_diskon_slw = $request->non_diskon_slw ?? $tokoslawi->non_diskon_slw;
            $tokoslawi->save();
        } else {
            Tokoslawi::create([
                'produk_id' => $produk->id,
                'member_harga_slw' => $request->member_harga_slw,
                'member_diskon_slw' => $request->member_diskon_slw,
                'non_harga_slw' => $request->non_harga_slw,
                'non_diskon_slw' => $request->non_diskon_slw,
            ]);
        }

        // Update harga dan diskon untuk toko Benjaran
        $tokobenjaran = $produk->tokobenjaran->first();
        if ($tokobenjaran) {
            $tokobenjaran->member_harga_bnjr = $request->member_harga_bnjr ?? $tokobenjaran->member_harga_bnjr;
            $tokobenjaran->member_diskon_bnjr = $request->member_diskon_bnjr ?? $tokobenjaran->member_diskon_bnjr;
            $tokobenjaran->non_harga_bnjr = $request->non_harga_bnjr ?? $tokobenjaran->non_harga_bnjr;
            $tokobenjaran->non_diskon_bnjr = $request->non_diskon_bnjr ?? $tokobenjaran->non_diskon_bnjr;
            $tokobenjaran->save();
        } else {
            Tokobenjaran::create([
                'produk_id' => $produk->id,
                'member_harga_bnjr' => $request->member_harga_bnjr,
                'member_diskon_bnjr' => $request->member_diskon_bnjr,
                'non_harga_bnjr' => $request->non_harga_bnjr,
                'non_diskon_bnjr' => $request->non_diskon_bnjr,
            ]);
        }

         // Update harga dan diskon untuk toko Tegal
         $tokotegal = $produk->tokotegal->first();
         if ($tokotegal) {
             $tokotegal->member_harga_tgl = $request->member_harga_tgl ?? $tokotegal->member_harga_tgl;
             $tokotegal->member_diskon_tgl = $request->member_diskon_tgl ?? $tokotegal->member_diskon_tgl;
             $tokotegal->non_harga_tgl = $request->non_harga_tgl ?? $tokotegal->non_harga_tgl;
             $tokotegal->non_diskon_tgl = $request->non_diskon_tgl ?? $tokotegal->non_diskon_tgl;
             $tokotegal->save();
         } else {
             Tokotegal::create([
                 'produk_id' => $produk->id,
                 'member_harga_tgl' => $request->member_harga_tgl,
                 'member_diskon_tgl' => $request->member_diskon_tgl,
                 'non_harga_tgl' => $request->non_harga_tgl,
                 'non_diskon_tgl' => $request->non_diskon_tgl,
             ]);
         }
 
        return response()->json(['success' => true]);
    }

 
    public function cetakPdf(Request $request)
    {
        $toko = $request->input('toko', 'tokoslawi');
        $produk = Produk::with([$toko])->get();

        $pdf = FacadePdf::loadView('admin/hargajual/cetak-pdf', compact('produk', 'toko'))
        ->setPaper('a4', 'portrait'); // [left, top, width, height]
        return $pdf->stream('updated-items.pdf');
      
    }


    
    public function destroy($id)
    {
        //
    }
}
