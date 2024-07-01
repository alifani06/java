<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use App\Models\Produk;
use App\Models\Tokoslawi;
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
        $produks = Produk::all();
        $harga = Hargajual::with('produk')->get();
        return view('admin.hargajual.index', compact('harga', 'produks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      // Mendapatkan data produk
      $produks = Produk::all();

      // Mendapatkan data harga jual yang diperbarui hari ini
      $today = Carbon::today()->toDateString();
      $harga = Hargajual::with('produk')
          ->whereDate('updated_at', $today)
          ->get();
  
      return view('admin.hargajual.show', compact('harga', 'produks'));
    }

 
 
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    public function updateHarga(Request $request)

{
    $validatedData = $request->validate([
        'id' => 'required|exists:hargajuals,id',
        'member_harga_slw' => 'nullable|numeric',
        'member_diskon_slw' => 'nullable|numeric',
        'non_harga_slw' => 'nullable|numeric',
        'non_diskon_slw' => 'nullable|numeric',
    ]);

    $hargajual = Hargajual::find($validatedData['id']);

    // Simpan data ke tabel hargajual
    if (isset($validatedData['member_harga_slw'])) {
        $hargajual->member_harga_slw = $validatedData['member_harga_slw'];
    }
    if (isset($validatedData['member_diskon_slw'])) {
        $hargajual->member_diskon_slw = $validatedData['member_diskon_slw'];
    }
    if (isset($validatedData['non_harga_slw'])) {
        $hargajual->non_harga_slw = $validatedData['non_harga_slw'];
    }
    if (isset($validatedData['non_diskon_slw'])) {
        $hargajual->non_diskon_slw = $validatedData['non_diskon_slw'];
    }
    $hargajual->save();

    // Simpan data ke tabel tokoslawi
    $tokoSlawi = TokoSlawi::updateOrCreate(
        ['produk_id' => $hargajual->produk_id],
        [
            'member_harga_slw' => $validatedData['member_harga_slw'] ?? $hargajual->member_harga_slw,
            'member_diskon_slw' => $validatedData['member_diskon_slw'] ?? $hargajual->member_diskon_slw,
            'non_harga_slw' => $validatedData['non_harga_slw'] ?? $hargajual->non_harga_slw,
            'non_diskon_slw' => $validatedData['non_diskon_slw'] ?? $hargajual->non_diskon_slw,
        ]
    );

    return response()->json(['success' => true]);
}
    
    public function showUpdatedItems()
    {
        $updatedItems = session('updated_items', []);
        return view('updated_items', compact('updatedItems'));
    }


    public function destroy($id)
    {
        //
    }
}
