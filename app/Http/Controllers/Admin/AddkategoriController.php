<?php

namespace App\Http\Controllers\Admin;

use App\Console\Kernel;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Klasifikasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Subklasifikasi;
use Illuminate\Support\Facades\Validator;

class AddkategoriController extends Controller
{
    public function index()
    {
        $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
        return view('admin.klasifikasi.addkategori.index', compact('klasifikasis'));
    }

    public function create()
    {
        $klasifikasis = Klasifikasi::with('subklasifikasi')->get();
        return view('admin.klasifikasi.addkategori.create', compact('klasifikasis'));
        // $klasifikasis = Klasifikasi::all();

        // return view('admin.klasifikasi.addkategori.create', compact('klasifikasis'));
    }

  
  
    // public function store(Request $request)
    // {
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'nama' => 'required',     
    //             'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    //         ],
    //         [
    //             'nama.required' => 'Masukan nama kategori',             
    //             'gambar.image' => 'Gambar yang dimasukan salah!',
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         return back()->withInput()->with('error', $errors);
    //     }

    //     if ($request->gambar) {
    //         $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
    //         $namaGambar = 'karyawan/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
    //         $request->gambar->storeAs('public/uploads/', $namaGambar);
    //     } else {
    //         $namaGambar = null;
    //     }
    //     $kode = $this->kode();

    //     Klasifikasi::create(array_merge(
    //         $request->all(),
    //         [
    //             'gambar' => $namaGambar,
    //             'kode_klasifikasi' => $this->kode(),
    //             'qrcode_klasifikasi' => 'https://javabakery.id/klasifikasi/' . $kode,
    //             'tanggal' => Carbon::now('Asia/Jakarta'),

    //         ]
    //     ));

    //     return redirect('admin/addkategori')->with('success', 'Berhasil menambahkan kategori');
    // }

    public function tambahkategori(Request $request)
    {
        // $validasi_pelanggan = Validator::make(
        //     $request->all(),
        //     [
        //         'supplier_id' => 'required',

        //     ],
        //     [
        //         'supplier_id.required' => 'Pilih supplier',

        //     ]
        // );

        $error_pelanggans = array();

        // if ($validasi_pelanggan->fails()) {
        //     array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        // }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('klasifikasi_id')) {
            for ($i = 0; $i < count($request->klasifikasi_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'klasifikasi_id.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                  
                 
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang nomor " . $i + 1 . " belum dilengkapi!");
                }


                $klasifikasi_id = is_null($request->klasifikasi_id[$i]) ? '' : $request->klasifikasi_id[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
       

                $data_pembelians->push([
                    'klasifikasi_id' => $klasifikasi_id, 'nama_barang' => $nama_barang
                ]);
            }
        } else {
        }
        

        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

        // format tanggal indo
        // $tanggal1 = Carbon::now('Asia/Jakarta');
        // $format_tanggal = $tanggal1->format('d F Y');

        // $tanggal = Carbon::now()->format('Y-m-d');
        // $transaksi = Pembelian::create([
        //     'kode_pembelian' => $this->kode(),
        //     'supplier_id' => $request->supplier_id,
        //     'tanggal' => $format_tanggal,
        //     'tanggal_awal' => $tanggal,
        //     'grand_total' => str_replace(',', '.', str_replace('.', '', $request->grand_total)),
        //     'status' => 'posting',
        //     'status_notif' => false,
        // ]);

        // $transaksi_id = $transaksi->id;

        // if ($transaksi) {
            foreach ($data_pembelians as $data_pesanan) {
                // Create a new Detailpembelian
                Subklasifikasi::create([
                    'klasifikasi_id' => $data_pesanan['klasifikasi_id'],
                    'nama' => $data_pesanan['nama_barang'],
                 
                ]);

                // Increment the quantity of the barang
                // Barang::where('id', $data_pesanan['barang_id'])->increment('jumlah', $data_pesanan['jumlah']);
            // }
        }

        // $pembelians = Pembelian::find($transaksi_id);

        // $parts = Subklasifikasi::where('pembelian_id', $pembelians->id)->get();
   return back()->with('success', 'Berhasil Menambahkan  subklasifikasi');
       
    }

    public function kode()
    {
        $lastBarang = Klasifikasi::latest()->first();
        if (!$lastBarang) {
            $num = 1;
        } else {
            $lastCode = $lastBarang->kode_klasifikasi;
            $num = (int) substr($lastCode, strlen('FE')) + 1;
        }
        $formattedNum = sprintf("%06s", $num);
        $prefix = 'AA';
        $newCode = $prefix . $formattedNum;
        return $newCode;
    }
 

 
    public function qrcodeDepartemenExists($number)
    {
        return Klasifikasi::whereQrcodeDepartemen($number)->exists();
    }

    // public function cetakpdf($id)
    // {
    //     $cetakpdf = Departemen::where('id', $id)->first();
    //     $html = view('admin/departemen.cetak_pdf', compact('cetakpdf'));

    //     $dompdf = new Dompdf();
    //     $dompdf->loadHtml($html);
    //     $dompdf->setPaper('A4', 'landscape');

    //     $dompdf->render();

    //     $dompdf->stream();
    // }

    public function edit($id)
    {

            $klasifikasis = Klasifikasi::where('id', $id)->first();
            return view('admin.klasifikasi.addkategori.update', compact('klasifikasis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama tidak boleh Kosong',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Klasifikasi::where('id', $id)->update([
            'nama' => $request->nama,
        ]);

        return redirect('admin/addkategori')->with('success', 'Berhasil memperbarui Kategori');
    }

    // public function show($id)
    // {
    //     $klasifikasis = Klasifikasi::all();
    //     // $subs = Subklasifikasi::all();
    //     return view('admin/addkategori', compact('klasifikasis')); 
    // }

    // public function show($id){
        
    //     $klasifikasis = Klasifikasi::all();

    //     return view('admin.klasifikasi.addkategori.createsub', compact('klasifikasis'));
    // }
    public function destroy($id)
    {
        $klasifikasis = Klasifikasi::find($id);
        $klasifikasis->delete();

        return redirect('admin/addkategori')->with('success', 'Berhasil menghapus Kategori');
    }
}