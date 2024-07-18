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
use App\Models\Toko;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;




class PemesananprodukController extends Controller
{
 
    public function index(Request $request)
    // {
    //     $today = Carbon::today();

    //     $pemesanans = Detailpemesananproduk::whereDate('created_at', $today)->get();
    //     return view('admin.pemesanan_produk.index', compact('pemesanans'));
    // }

    {

            $today = Carbon::today();
            $inquery = Pemesananproduk::whereDate('created_at', $today)
                ->orWhere(function ($query) use ($today) {
                    $query->where('status', 'unpost')
                        ->whereDate('created_at', '<', $today);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            return view('admin.pemesanan_produk.index', compact('inquery'));
            // tidak memiliki akses
            return back()->with('error', array('Anda tidak memiliki akses'));
        
    }
    // {
    //     $status = $request->status;
    //     $tanggal_awal = $request->tanggal_awal;
    //     $tanggal_akhir = $request->tanggal_akhir;
    
    //     $inquery = Pemesananproduk::query();
    
    //     if ($status) {
    //         $inquery->where('status', $status);
    //     }
    
    //     if ($tanggal_awal && $tanggal_akhir) {
    //         $inquery->whereBetween('tanggal_pemesanan', [$tanggal_awal, $tanggal_akhir]);
    //     } elseif ($tanggal_awal) {
    //         $inquery->where('tanggal_pemesanan', '>=', $tanggal_awal);
    //     } elseif ($tanggal_akhir) {
    //         $inquery->where('tanggal_pemesanan', '<=', $tanggal_akhir);
    //     } else {
    //         // Jika tidak ada filter tanggal, gunakan tanggal hari ini
    //         $inquery->whereDate('tanggal_pemesanan', Carbon::today());
    //     }
    
    //     $inquery->orderBy('id', 'DESC');
    //     $inquery = $inquery->get();
    
    //     return view('admin.pemesanan_produk.index', compact('inquery'));
    // }
    

    public function pelanggan($id)
    {
        $user = Pelanggan::where('id', $id)->first();

        return json_decode($user);
    }


    public function create()
    {

        $barangs = Barang::all();
        $pelanggans = Pelanggan::all();
        $details = Detailbarangjadi::all();
        $tokoslawis = Tokoslawi::all();
        $tokos = Toko::all();
    
        $produks = Produk::with('tokoslawi')->get();

        $kategoriPelanggan = 'member';
    
        return view('admin.pemesanan_produk.create', compact('barangs', 'tokos', 'produks', 'details', 'tokoslawis', 'pelanggans', 'kategoriPelanggan'));
    }
    
    public function getCustomerByKode($kode)
    {
        $customer = Pelanggan::where('kode_pelanggan', $kode)->first();
        if ($customer) {
            return response()->json($customer);
        }
        return response()->json(['message' => 'Customer not found'], 404);
    }

    public function getCustomerData(Request $request)
    {
        $qrcode_pelanggan = $request->qrcode_pelanggan;

        // Query untuk mengambil data pelanggan berdasarkan qrcode_pelanggan
        $customer = Pelanggan::where('qrcode_pelanggan', $qrcode_pelanggan)->first();

        if ($customer) {
            // Jika data ditemukan, kembalikan data dalam bentuk JSON
            return response()->json([
                'nama_pelanggan' => $customer->nama_pelanggan,
                'telp' => $customer->telp,
                'alamat' => $customer->alamat,
            ]);
        } else {
            // Jika data tidak ditemukan, kembalikan respons kosong atau sesuaikan dengan kebutuhan
            return response()->json([
                'error' => 'Data pelanggan tidak ditemukan.',
            ], 404);
        }
    }
   
    
    public function store(Request $request)
    {
        // Validasi pelanggan
        $validasi_pelanggan = Validator::make(
            $request->all(),
            [
                'nama_pelanggan' => 'required',
                'telp' => 'required',
                'alamat' => 'required',
                'kategori' => 'required',
            ],
            [
                'nama_pelanggan.required' => 'Masukkan nama pelanggan',
                'telp.required' => 'Masukkan telepon',
                'alamat.required' => 'Masukkan alamat',
                'kategori.required' => 'Pilih kategori pelanggan',
            ]
        );

        // Handling errors for pelanggan
        $error_pelanggans = array();

        if ($validasi_pelanggan->fails()) {
            array_push($error_pelanggans, $validasi_pelanggan->errors()->all()[0]);
        }

        // Handling errors for pesanans
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
                    array_push($error_pesanans, "Barang no " . ($i + 1) . " belum dilengkapi!");
                }

                $produk_id = is_null($request->produk_id[$i]) ? '' : $request->produk_id[$i];
                $kode_produk = is_null($request->kode_produk[$i]) ? '' : $request->kode_produk[$i];
                $nama_produk = is_null($request->nama_produk[$i]) ? '' : $request->nama_produk[$i];
                $jumlah = is_null($request->jumlah[$i]) ? '' : $request->jumlah[$i];
                $diskon = is_null($request->diskon[$i]) ? '' : $request->diskon[$i];
                $harga = is_null($request->harga[$i]) ? '' : $request->harga[$i];
                $total = is_null($request->total[$i]) ? '' : $request->total[$i];

                $data_pembelians->push([
                    'kode_produk' => $kode_produk,
                    'produk_id' => $produk_id,
                    'nama_produk' => $nama_produk,
                    'jumlah' => $jumlah,
                    'diskon' => $diskon,
                    'harga' => $harga,
                    'total' => $total,
                ]);
            }
        }

        // Handling errors for pelanggans or pesanans
        if ($error_pelanggans || $error_pesanans) {
            return back()
                ->withInput()
                ->with('error_pelanggans', $error_pelanggans)
                ->with('error_pesanans', $error_pesanans)
                ->with('data_pembelians', $data_pembelians);
        }
        $kode = $this->kode();
        // Buat pemesanan baru
        $cetakpdf = Pemesananproduk::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'kategori' => $request->kategori,
            'sub_total' => $request->sub_total,
            'nama_penerima' => $request->nama_penerima,
            'telp_penerima' => $request->telp_penerima,
            'alamat_penerima' => $request->alamat_penerima,
            'tanggal_kirim' => $request->tanggal_kirim,
            'toko_id' => $request->toko,
            'kode_pemesanan' => $this->kode(),
            'qrcode_pemesanan' => 'https://javabakery.id/pemesanan/' . $kode,
            'tanggal_pemesanan' => Carbon::now('Asia/Jakarta'),
            'status' => 'posting',

        ]);

        // Dapatkan ID transaksi baru
        $transaksi_id = $cetakpdf->id;

        // Simpan detail pemesanan
        if ($cetakpdf) {
            foreach ($data_pembelians as $data_pesanan) {
                $detailTagihan = Detailpemesananproduk::create([
                    'pemesananproduk_id' => $cetakpdf->id,
                    'kode_produk' => $data_pesanan['kode_produk'],
                    'nama_produk' => $data_pesanan['nama_produk'],
                    'jumlah' => $data_pesanan['jumlah'],
                    'diskon' => $data_pesanan['diskon'],
                    'harga' => $data_pesanan['harga'],
                    'total' => $data_pesanan['total'],
                ]);
            }
        }

        // Ambil detail pemesanan untuk ditampilkan di halaman cetak
        $details = Detailpemesananproduk::where('pemesananproduk_id', $cetakpdf->id)->get();

        // Redirect ke halaman cetak dengan menyertakan data sukses dan detail pemesanan
        return redirect()->route('admin.pemesanan_produk.cetak', ['id' => $cetakpdf->id])->with([
            'success' => 'Berhasil menambahkan barang jadi',
            'pemesanan' => $cetakpdf,
            'details' => $details,
        ]);
    }


    public function cetak($id)
    {
        // Retrieve the specific pemesanan by ID along with its details
        $pemesanan = Pemesananproduk::with('detailpemesananproduk')->findOrFail($id);
    
        // Retrieve all pelanggans (assuming you need this for the view)
        $pelanggans = Pelanggan::all();
    
        // Pass the retrieved data to the view
        return view('admin.pemesanan_produk.cetak', compact('pemesanan', 'pelanggans'));
    }
    
    public function cetakPdf($id)
    {
        $pemesanan = Pemesananproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();
        
    
        $tokos = $pemesanan->toko;
    
        $pdf = FacadePdf::loadView('admin.pemesanan_produk.cetak-pdf', compact('pemesanan', 'tokos', 'pelanggans'));
        $pdf->setPaper('a4', 'portrait');
    
        return $pdf->stream('pemesanan.pdf');
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
        $pemesanan = Pemesananproduk::findOrFail($id);
        $pelanggans = Pelanggan::all();
        
    
        $tokos = $pemesanan->toko;
    
        return view('admin.pemesanan_produk.cetak', compact('pemesanan', 'pelanggans', 'tokos'));}
    

    public function edit($id)
    {
        $inquery = Pemesananproduk::with('detailpemesananproduk')->where('id', $id)->first();

        return view('admin.pemesanan_produk.update', compact('inquery'));
    }
    

 
    public function update(Request $request, $id)
    {
       
    }


    public function destroy($id)
    {
        //
    }

}