<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use App\Models\Produk;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class HargajualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $produks = Produk::all();
        // $tokoslawi = Tokoslawi::all();
        // $tokobenjaran = Tokobenjaran::latest()->first();
        // $harga = Hargajual::with(['produk', 'tokobenjaran'])->get();
        // return view('admin.hargajual.index', compact('harga', 'produks', 'tokoslawi', 'tokobenjaran'));
        // $produks = Produk::all();

        // $tokoslawi = Tokoslawi::latest()->first();
        // $tokobenjaran = Tokobenjaran::latest()->first();
        $tokoslawi = Tokoslawi::latest()->first();
        $tokobenjaran = Tokobenjaran::latest()->first();
        $produk = Produk::with(['tokoslawi', 'tokobenjaran'])->get();
        return view('admin.hargajual.index', compact('produk', 'tokoslawi', 'tokobenjaran'));
    }


    public function create()
    {
        //
    }

 
    public function store(Request $request)
    {
        //
    }

    // public function show()
    // {
    //     // Mendapatkan data harga jual yang telah berubah dari Toko Slawi atau Toko Benjaran
    //     $produk = Produk::with(['tokoslawi', 'tokobenjaran'])
    //         ->whereHas('tokoslawi', function ($query) {
    //             $query->whereRaw('member_harga_slw != produk.harga OR non_harga_slw != produk.harga');
    //         })
    //         ->orWhereHas('tokobenjaran', function ($query) {
    //             $query->whereRaw('member_harga_bnjr != produk.harga OR non_harga_bnjr != produk.harga');
    //         })
    //         ->get();
    
    //     // Cek apakah ada data yang telah berubah
    //     if ($produk->isEmpty()) {
    //         // Jika tidak ada, redirect kembali dengan pesan
    //         return redirect()->back()->with('info', 'Tidak ada data yang telah berubah.');
    //     }
    
    //     return view('admin.hargajual.show', compact('produk'));
    // }
    
public function show()
{
                    // Mendapatkan data harga jual yang diperbarui hari ini di Toko Slawi atau Toko Benjaran
                    $today = Carbon::today()->toDateString();

                    // Query untuk mendapatkan produk yang terkait dengan Toko Slawi atau Toko Benjaran yang diperbarui hari ini
                    $produk = Produk::with(['tokoslawi', 'tokobenjaran'])
                        ->whereHas('tokoslawi', function($query) use ($today) {
                            $query->whereDate('updated_at', $today)
                                ->where(function($q) {
                                    $q->whereRaw('tokoslawis.member_harga_slw != tokoslawis.harga_awal')
                                      ->orWhereRaw('tokoslawis.non_harga_slw != tokoslawis.harga_awal');
                                });
                        })
                        ->orWhereHas('tokobenjaran', function($query) use ($today) {
                            $query->whereDate('updated_at', $today)
                                ->where(function($q) {
                                    $q->whereRaw('tokobenjarans.member_harga_bnjr != tokobenjarans.harga_awal')
                                      ->orWhereRaw('tokobenjarans.non_harga_bnjr != tokobenjarans.harga_awal');
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

        return response()->json(['success' => true]);
    }

 

    
    public function destroy($id)
    {
        //
    }
}
