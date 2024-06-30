<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use App\Models\Produk;
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
        $request->validate([
            'id' => 'required|integer',
            'member_harga' => 'nullable|numeric',
            'diskon_member' => 'nullable|numeric',
            'non_member_harga' => 'nullable|numeric',
            'diskon_non_member' => 'nullable|numeric',
        ]);
    
        $item = Hargajual::find($request->input('id'));
        if ($item) {
            $updatedFields = [];
    
            if ($request->filled('member_harga') && $request->input('member_harga') != $item->member_harga) {
                $item->member_harga = $request->input('member_harga');
                $updatedFields['member_harga'] = $request->input('member_harga');
            }
            if ($request->filled('diskon_member') && $request->input('diskon_member') != $item->diskon_member) {
                $item->diskon_member = $request->input('diskon_member');
                $updatedFields['diskon_member'] = $request->input('diskon_member');
            }
            if ($request->filled('non_member_harga') && $request->input('non_member_harga') != $item->non_member_harga) {
                $item->non_member_harga = $request->input('non_member_harga');
                $updatedFields['non_member_harga'] = $request->input('non_member_harga');
            }
            if ($request->filled('diskon_non_member') && $request->input('diskon_non_member') != $item->diskon_non_member) {
                $item->diskon_non_member = $request->input('diskon_non_member');
                $updatedFields['diskon_non_member'] = $request->input('diskon_non_member');
            }
    
            $item->save();
    
            // Update harga di tabel produk jika diperlukan
            if ($request->filled('member_harga')) {
                $produk = $item->produk; // Asumsi relasi Hargajual ke Produk
                if ($produk) {
                    $produk->harga = $request->input('member_harga');
                    $produk->save();
                }
            }
    
            // Simpan data yang telah diubah di sesi
            $updatedItems = $request->session()->get('updated_items', []);
            $updatedItems[] = [
                'id' => $item->id,
                'updatedFields' => $updatedFields,
                'produk' => $item->produk->nama_produk,
            ];
            $request->session()->put('updated_items', $updatedItems);
        }
    
        return redirect()->route('updated.items.view')->with('success', 'Berhasil memperbarui barang');
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
