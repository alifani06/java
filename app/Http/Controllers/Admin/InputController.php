<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang; // Import model Barang untuk mengambil data barang
use App\Models\Detailbarangjadi;
use App\Models\Subsub; // Import model Subsub untuk mengambil data subsub

class InputController extends Controller
{
    public function index()
    {
        
        $barangs = Barang::all();
        $subs1 = Subsub::all();
        $inputs = Input::with('barang')->get();
        return view('admin/input.index', compact('inputs', 'barangs', 'subs1'));
    }
   
    public function store(Request $request)
    {
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'no_faktur' => 'required',
                'tanggal' => 'required',
                'cabang' => 'required',
                'sub_total' => 'required',
                'catatan' => 'required',
                'tanggal_pengiriman' => 'required',
            ],
            [
                'no_faktur.required' => 'Pilih no_faktur',
                'tanggal.required' => 'Pilih Pelanggan',
                'cabang.required' => 'Masukkan grand total',
            ]
        );

        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        $error_pesanans = array();
        $data_pembelians = collect();

        if ($request->has('barang_id')) {
            for ($i = 0; $i < count($request->barang_id); $i++) {
                $validasi_produk = Validator::make($request->all(), [
                    'kode_barang.' . $i => 'required',
                    'barang_id.' . $i => 'required',
                    'nama_barang.' . $i => 'required',
                    'harga.' . $i => 'required',
                    'total.' . $i => 'required',
                 
                ]);

                if ($validasi_produk->fails()) {
                    array_push($error_pesanans, "Barang no " . ($i + 1) . " belum dilengkapi!"); // Corrected the syntax for concatenation and indexing
                }

                $barang_id = is_null($request->barang_id[$i]) ? '' : $request->barang_id[$i];
                $kode_barang = is_null($request->kode_barang[$i]) ? '' : $request->kode_barang[$i];
                $nama_barang = is_null($request->nama_barang[$i]) ? '' : $request->nama_barang[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];
             

                $data_pembelians->push([
                    'kode_barang' => $kode_barang,
                    'barang_id' => $barang_id,
                    'nama_barang' => $nama_barang,
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

        $tanggal = Carbon::now()->format('Y-m-d');
        $cetakpdf = Input::create([
            // 'user_id' => auth()->user()->id,
            'no_faktur' => $request->no_faktur,
            'tanggal' => $request->tanggal,
            'cabang' => $request->cabang,
            'sub_total' => $request->sub_total,
            'catatan' => $request->catatan,
            'tanggal_pengiriman' => $request->tanggal_pengiriman,
           
        ]);

        $transaksi_id = $cetakpdf->id;

        if ($cetakpdf) {
            foreach ($data_pembelians as $data_pesanan) {
                $detailTagihan = Detailbarangjadi::create([
                    'input_id' => $cetakpdf->id,
                    'kode_barang' => $data_pesanan['kode_barang'],
                    'barang_id' => $data_pesanan['barang_id'],
                    'nama_barang' => $data_pesanan['nama_barang'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'harga' => $data_pesanan['harga'],
                    // 'no_po' => $data_pesanan['no_po'],
                    'total' => $data_pesanan['total'],
            
                  
                ]);

                Input::where('id', $detailTagihan->input_id);
            }
        }

        $details = Detailbarangjadi::where('input_id', $cetakpdf->id)->get();
return back()->with('success', 'Berhasil menambahkan barang jadi');;
    }

// public function store(Request $request)
// {
//     $validasi_pelanggan = Validator::make(
//         $request->all(),
//         [
//             'no_faktur' => 'required',
//             'tanggal' => 'required',
//             'cabang' => 'required',
//             'sub_total' => 'required',
//             'catatan' => 'required',
//             'tanggal_pengiriman' => 'required',
//         ],
//         [
//             'no_faktur.required' => 'Pilih no_faktur',
//             'tanggal.required' => 'Pilih Pelanggan',
//             'cabang.required' => 'Masukkan grand total',
//         ]
//     );

//     if ($validasi_pelanggan->fails()) {
//         return back()
//             ->withInput()
//             ->withErrors($validasi_pelanggan)
//             ->with('error_pesanans', []);
//     }

//     $data_pembelians = collect();

//     if ($request->has('barang_id')) {
//         for ($i = 0; $i < count($request->barang_id); $i++) {
//             $validasi_produk = Validator::make($request->all(), [
//                 'kode_barang.' . $i => 'required',
//                 'barang_id.' . $i => 'required',
//                 'nama_barang.' . $i => 'required',
//                 'harga.' . $i => 'required',
//                 'total.' . $i => 'required',
//             ]);

//             if ($validasi_produk->fails()) {
//                 return back()
//                     ->withInput()
//                     ->withErrors($validasi_produk)
//                     ->with('error_pesanans', ["Barang no " . ($i + 1) . " belum dilengkapi!"]);
//             }

//             $data_pembelians->push([
//                 'kode_barang' => $request->kode_barang[$i],
//                 'barang_id' => $request->barang_id[$i],
//                 'nama_barang' => $request->nama_barang[$i],
//                 'jumlah' => $request->jumlah[$i],
//                 'harga' => $request->harga[$i],
//                 'total' => $request->total[$i],
//             ]);
//         }
//     }

//     // Simpan data input
//     $input = Input::create([
//         'no_faktur' => $request->no_faktur,
//         'tanggal' => $request->tanggal,
//         'cabang' => $request->cabang,
//         'sub_total' => $request->sub_total,
//         'catatan' => $request->catatan,
//         'tanggal_pengiriman' => $request->tanggal_pengiriman,
//     ]);

//     // Simpan detail barang jadi
//     if ($input) {
//         foreach ($data_pembelians as $data_pesanan) {
//             Detailbarangjadi::create([
//                 'input_id' => $input->id,
//                 'kode_barang' => $data_pesanan['kode_barang'],
//                 'barang_id' => $data_pesanan['barang_id'],
//                 'nama_barang' => $data_pesanan['nama_barang'],
//                 'jumlah' => $data_pesanan['jumlah'],
//                 'harga' => $data_pesanan['harga'],
//                 'total' => $data_pesanan['total'],
//             ]);
//         }
//     }

//     // Redirect back with success message or errors
//     return back()->with('success', 'Data berhasil disimpan.');
// }

}