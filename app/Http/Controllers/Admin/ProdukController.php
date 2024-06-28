<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produks = Produk::all();
        return view('admin/produk.create', compact('produks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        Produk::create(array_merge(
            $request->all(),
            [
                'gambar' => $namaGambar,
                'kode_produk' => $this->kode(),
                'qrcode_produk' => 'https://javabakery.id/produk/' . $kode,
                'tanggal' => Carbon::now('Asia/Jakarta'),

            ]
        ));

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
        //
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
