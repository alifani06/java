<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produks = Produk::all();
        return view('admin.produk.index', compact('produks'));
    }

    public function getkategori($id)
    {

        $klasifikasi = Klasifikasi::where('kategori', $id)->get();
        return response()->json($klasifikasi);
    }

    public function get_klasifikasi($klasifikasi_id)
    {
        $klasifikasis = Subklasifikasi::where('klasifikasi_id', $klasifikasi_id)->get();
        return response()->json($klasifikasis);
    }


    public function create()
    {
        $produks = Produk::all();
        $klasifikasis = Klasifikasi::all();
        $subs = Subklasifikasi::all();
        $subs1 = Subsub::all();
        return view('admin/produk.create', compact('produks', 'klasifikasis', 'subs', 'subs1'));
    }

 
    public function store(Request $request)
 
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_produk' => 'required',
                'satuan' => 'required',
                'harga' => 'required|numeric',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'nama_produk.required' => 'Masukan nama produk',
                'satuan.required' => 'Masukkan satuan',
                'harga.required' => 'Masukkan harga',
                'gambar.image' => 'Gambar yang dimasukan salah!',
            ]
        );
    
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->with('error', $errors);
        }
    
        if ($request->gambar) {
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namaGambar = 'produk/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = null;
        }
        $kode = $this->kode();
    
        $produk = Produk::create(array_merge(
            $request->all(),
            [
                'diskon' => 0,
                'gambar' => $namaGambar,
                'kode_produk' => $kode,
                'qrcode_produk' => 'https://javabakery.id/produk/' . $kode,
                'tanggal' => Carbon::now('Asia/Jakarta'),
                // 'harga' =>$produk->subsub_id = $request->subsub_id,
            ]
        ));
    
        $hargaJual = HargaJual::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $request->harga,
            'non_harga_slw' => $request->harga,
            'member_diskon_slw' => 0,
            'non_diskon_slw' => 0,
            // 'member_harga_bnjr' => $request->harga,
            // 'non_harga_bnjr' => $request->harga,
            // 'diskon_bnjr' => 0,
            // 'hargajual' => $request->harga,
           
        ]);
    
        TokoSlawi::create([
            'produk_id' => $produk->id,
            'member_harga_slw' => $request->harga,
            'harga_awal' => $request->harga,
            'diskon_awal' =>  0,
            'member_diskon_slw' => 0,
            'non_harga_slw' => $request->harga,
            'non_diskon_slw' => 0,
        ]);
    
        Tokobenjaran::create([
            'produk_id' => $produk->id,
            'harga_awal' => $request->harga,
            'diskon_awal' => 0,
            'member_harga_bnjr' => $request->harga,
            'member_diskon_bnjr' => 0,
            'non_harga_bnjr' => $request->harga,
            'non_diskon_bnjr' => 0,
        ]);

        Tokotegal::create([
            'produk_id' => $produk->id,
            'harga_awal' => $request->harga,
            'diskon_awal' => 0,
            'member_harga_tgl' => $request->harga,
            'member_diskon_tgl' => 0,
            'non_harga_tgl' => $request->harga,
            'non_diskon_tgl' => 0,
        ]);
        Tokopemalang::create([
            'produk_id' => $produk->id,
            'harga_awal' => $request->harga,
            'diskon_awal' => 0,
            'member_harga_pml' => $request->harga,
            'member_diskon_pml' => 0,
            'non_harga_pml' => $request->harga,
            'non_diskon_pml' => 0,
        ]);
        Tokobumiayu::create([
            'produk_id' => $produk->id,
            'harga_awal' => $request->harga,
            'diskon_awal' => 0,
            'member_harga_bmy' => $request->harga,
            'member_diskon_bmy' => 0,
            'non_harga_bmy' => $request->harga,
            'non_diskon_bmy' => 0,
        ]);
        Tokocilacap::create([
            'produk_id' => $produk->id,
            'harga_awal' => $request->harga,
            'diskon_awal' => 0,
            'member_harga_clc' => $request->harga,
            'member_diskon_clc' => 0,
            'non_harga_clc' => $request->harga,
            'non_diskon_clc' => 0,
        ]);
    
        return redirect('admin/produk')->with('success', 'Berhasil menambahkan produk');
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

        $produk = Produk::where('id', $id)->first();
        return view('admin/produk.update', compact('produk'));  
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
        $validator = Validator::make(
            $request->all(),
            [
                'nama_produk' => 'required',
                'satuan' => 'required',
                'harga' => 'required',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'nama_produk.required' => 'Masukan nama produk',
                'satuan.required' => 'Masukkan satuan',
                'harga.required' => 'Masukkan harga',
                'gambar.image' => 'Gambar yang dimasukan salah!',
            ]
        );

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $produk = Produk::findOrFail($id);

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $produk->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namaGambar = 'produk/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namaGambar);
        } else {
            $namaGambar = $produk->gambar;
        }

        $produk->nama_produk = $request->nama_produk;
        $produk->satuan = $request->satuan;
        $produk->harga = $request->harga;
        $produk->gambar = $namaGambar;
        $produk->save();

        return redirect('admin/produk')->with('success', 'Berhasil mengubah produk');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $produk->delete();

        return redirect('admin/produk')->with('success', 'Berhasil menghapus data produk');
    }

    public function kode()
    {
        $lastBarang = Produk::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_produk;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'PR';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
}
