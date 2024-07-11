<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Klasifikasi;
use App\Models\Subklasifikasi;
use App\Models\Subsub;
use App\Models\Pelanggan;
use App\Models\Hargajual;
use App\Models\Tokoslawi;
use App\Models\Tokobenjaran;
use App\Models\Tokotegal;
use App\Models\Tokopemalang;
use App\Models\Tokobumiayu;
use App\Models\Tokocilacap;
use App\Models\Barang;
use App\Models\Detailbarangjadi;
use App\Models\Detailpemesananproduk;
use App\Models\Detailtokoslawi;
use App\Models\Input;
use App\Models\Karyawan;
use App\Models\Pemesananproduk;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;



class PemesananprodukController extends Controller
{
 
    public function index()
    {
        $produks = Produk::all();
        return view('admin.pemesanan_produk.index', compact('produks'));

    }

    public function create()
    {

        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
    
        $produks = Produk::with('tokoslawi')->get();

        $kategoriPelanggan = 'member';
    
        return view('admin.pemesanan_produk.create', compact('barangs', 'produks', 'details', 'tokoslawis', 'pelanggans', 'kategoriPelanggan'));
    }
    
 
    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                // 'kode_pemesanan' => 'required',
                'nama_pelanggan' => 'required',
                'telp' => 'required',
                'alamat' => 'required',
                'kategori' => 'required',
            ],
            [
                // 'kode_pemesanan.required' => 'Pilih no_faktur',
                'nama_pelanggann.required' => 'masukan nama pelangggan',
                'telp.required' => 'Masukkan telepon',
                'alamat.required' => 'Masukkan alamat',
                'kategori.required' => 'pilih kategori pelanggan',
            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('produk_id')) {
            for ($i = 0; $i < count($request->produk_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'kode_produk.' . $i => 'required',
                    'produk_id.' . $i => 'required',
                    'nama_produk.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'total.' . $i => 'required',
                 
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang no " . ($i + 1) . " belum dilengkapi!"); // Corrected the syntax for concatenation and indexing
                }

                $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
                $kode_produk = is_null($request->kode_produk[$i]) ? '' : $request->kode_produk[$i];
                $nama_produk = is_null($request->nama_produk[$i]) ? '' : $request->nama_produk[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];
             

                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'total' => $total,
             
                ]);
            }
        }


        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }

      
        $tanggal1 = Carbon::now('Asia/Jakarta');
        $format_tanggal = $tanggal1->format('d F Y');
        $kode = $this->kode();
        $tanggal = Carbon::now()->format('Y-m-d');

        $cetakpdf = Pemesananproduk::create([
            // 'user_id' => auth()->user()->id,
            // 'kode_faktur' => $kode,
            // 'kode_pemesanan' => $request->kode_pemesanan,
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'sub_total' => $request->sub_total,
            'kode_pemesanan' => $this->kode(),
            'qrcode_pemesanan' => 'https://javabakery.id/pemesanan/' . $kode,
            // 'tanggal_pengiriman' => $request->tanggal_pengiriman,
           
        ]);

        $transaksi_id = $cetakpdf->id;

        if ($cetakpdf) {
            foreach ($data_pembelians as $data_pesanan) {
                $detailTagihan = Detailpemesananproduk::create([
                    'pemesanan_id' => $cetakpdf->id,
                    'kode_produk' => $data_pesanan['kode_produk'],
                    // 'produk_id' => $data_pesanan['produk_id'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'harga' => $data_pesanan['harga'],
                    // 'no_po' => $data_pesanan['no_po'],
                    'total' => $data_pesanan['total'],
            
                  
                ]);
                Pemesananproduk::where('id', $detailTagihan->pemesanan_id);
            }
        }

        $details = Detailpemesananproduk::where('pemesanan_id', $cetakpdf->id)->get();
        return back()->with('success', 'Berhasil menambahkan barang jadi');;
    }
  
 
    public function kode()
    {
        $lastPemesanan = Pemesananproduk::latest()->first();
        if (!$lastPemesanan) {
            $num = 1;
        } else {
            $lastCode = $lastPemesanan->kode_pemesanan;
            $num = (int) substr($lastCode, 3) + 1; // Mengambil angka setelah prefix 'SPP'
        }
        
        $formattedNum = sprintf("%06s", $num); // Mengformat nomor urut menjadi 6 digit
        $prefix = 'SPP';
        $newCode = $prefix . $formattedNum; // Gabungkan prefix dengan nomor urut yang diformat
    
        return $newCode;
    }
    


    public function show($id)
    {
        //
    }

  
    public function edit($id)
    {


    }

 
    public function update(Request $request, $id)
    {
       
    }


    public function destroy($id)
    {
        //
    }

}