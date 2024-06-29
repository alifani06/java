<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hargajual;
use App\Models\Produk;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
     // Validasi data yang diterima
    $request->validate([
        'member_harga' => 'required|numeric',
        'diskon_member' => 'required|numeric|min:0|max:100',
        'non_member_harga' => 'required|numeric',
        'diskon_non_member' => 'required|numeric|min:0|max:100',
    ]);

    // Temukan harga jual berdasarkan ID
    $hargaJual = HargaJual::findOrFail($id);

    // Perbarui harga dan diskon
    $hargaJual->member_harga = $request->input('member_harga');
    $hargaJual->diskon_member = $request->input('diskon_member');
    $hargaJual->non_member_harga = $request->input('non_member_harga');
    $hargaJual->diskon_non_member = $request->input('diskon_non_member');

    // Hitung harga jual setelah diskon
    $hargaJual->harga_jual_member = $hargaJual->member_harga * (1 - ($hargaJual->diskon_member / 100));
    $hargaJual->harga_jual_non_member = $hargaJual->non_member_harga * (1 - ($hargaJual->diskon_non_member / 100));

    // Simpan perubahan
    $hargaJual->save();

    // Redirect kembali dengan pesan sukses
    return redirect()->back()->with('success', 'Harga jual berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
