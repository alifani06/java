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
use App\Models\Pengiriman_barangjadi;
use App\Models\Pemesananproduk;
use App\Models\Penjualanproduk;
use Carbon\Carbon;
use App\Models\Toko;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StokBarangExport;
use App\Exports\StokBarangExportBM;






class Laporan_hasilpenjualanController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id; // Menambahkan filter toko_id
        $klasifikasi_id = $request->klasifikasi_id; // Menambahkan filter klasifikasi_id
    
        // Ambil data toko untuk dropdown
        $tokos = Toko::all();  // Mengambil semua data toko
        $klasifikasis = Klasifikasi::all(); // Mengambil semua data klasifikasi
    
        // Query dasar dengan relasi ke produk dan klasifikasi
        $query = Pengiriman_barangjadi::with('produk.klasifikasi');
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi_id
        if ($klasifikasi_id) {
            $query->whereHas('produk', function($q) use ($klasifikasi_id) {
                $q->where('klasifikasi_id', $klasifikasi_id);
            });
        }
    
        // Filter berdasarkan tanggal pengiriman
        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_pengiriman', Carbon::today());
        }
    
        // Ambil data dengan pengurutan berdasarkan tanggal pengiriman terbaru
        $stokBarangJadi = $query->orderBy('tanggal_pengiriman', 'desc')->get();
    
        // Kirim variabel ke view
        return view('admin.laporan_hasilpenjualan.index', compact('stokBarangJadi', 'tokos', 'klasifikasis'));
    }

    public function barangKeluar(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        $inquery = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
            })
            ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('detailPenjualanProduk.produk', function ($query) use ($klasifikasi_id) {
                    return $query->where('klasifikasi_id', $klasifikasi_id);
                });
            });
    
        // Ambil data penjualan
        $inquery = $inquery->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
    
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                // Pastikan produk tidak null sebelum mengakses properti
                if ($detail->produk) {
                    $key = $detail->produk_id;
        
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $detail->produk->kode_lama,
                            'nama_produk' => $detail->produk->nama_produk,
                            'harga' => $detail->produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                        ];
                    }
        
                    // Jumlahkan jumlah dan total
                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['total'] += $detail->total;
        
                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $detail->harga * 0.10; // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
                }
            }
        }
        
    
        $tokos = Toko::all(); // Assuming Toko is a model for your toko table
        $klasifikasis = Klasifikasi::all(); // Assuming Klasifikasi is a model for your klasifikasi table
    
        return view('admin.laporan_hasilpenjualan.barangkeluar', [
            'finalResults' => $finalResults,
            'tokos' => $tokos,
            'klasifikasis' => $klasifikasis
        ]);
    }
    
    public function barangRetur(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        $inquery = Penjualanproduk::with('detailPenjualanProduk.produk')
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($toko_id, function ($query, $toko_id) {
                return $query->where('toko_id', $toko_id);
            })
            ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
                $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
            })
            ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
                $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
                return $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
            })
            ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
                $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
                return $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
            })
            ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
                return $query->whereHas('detailPenjualanProduk.produk', function ($query) use ($klasifikasi_id) {
                    return $query->where('klasifikasi_id', $klasifikasi_id);
                });
            });
    
        // Ambil data penjualan
        $inquery = $inquery->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
    
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                // Pastikan produk tidak null sebelum mengakses properti
                if ($detail->produk) {
                    $key = $detail->produk_id;
        
                    if (!isset($finalResults[$key])) {
                        $finalResults[$key] = [
                            'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                            'kode_lama' => $detail->produk->kode_lama,
                            'nama_produk' => $detail->produk->nama_produk,
                            'harga' => $detail->produk->harga,
                            'jumlah' => 0,
                            'diskon' => 0,
                            'total' => 0,
                        ];
                    }
        
                    // Jumlahkan jumlah dan total
                    $finalResults[$key]['jumlah'] += $detail->jumlah;
                    $finalResults[$key]['total'] += $detail->total;
        
                    // Hitung diskon 10% dari jumlah * harga
                    if ($detail->diskon > 0) {
                        $diskonPerItem = $detail->harga * 0.10; // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
                }
            }
        }
        
    
        $tokos = Toko::all(); // Assuming Toko is a model for your toko table
        $klasifikasis = Klasifikasi::all(); // Assuming Klasifikasi is a model for your klasifikasi table
    
        return view('admin.laporan_hasilpenjualan.barangretur', [
            'finalResults' => $finalResults,
            'tokos' => $tokos,
            'klasifikasis' => $klasifikasis
        ]);
    }
    

    // public function printLaporanBm(Request $request)
    // {
    //     // Ambil parameter filter dari request
    //     $status = $request->status;
    //     $tanggal_pengiriman = $request->tanggal_pengiriman;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $toko_id = $request->toko_id; // Ambil filter toko_id dari request
    //     $klasifikasi_id = $request->klasifikasi_id; // Ambil filter klasifikasi_id dari request
    //     $formattedStartDate = $tanggal_pengiriman ? Carbon::parse($tanggal_pengiriman)->format('d/m/Y') : 'N/A';
    //     $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d/m/Y') : 'N/A';

    
    //     // Ambil daftar toko dan klasifikasi untuk filter
    //     $tokos = Toko::all();
    //     $klasifikasis = Klasifikasi::all();
    
    //     // Query dasar untuk mengambil data Pengiriman_barangjadi
    //     $query = Pengiriman_barangjadi::with('produk.klasifikasi')
    //         ->orderBy('tanggal_pengiriman', 'desc');
    
    //     // Filter berdasarkan status
    //     if ($status) {
    //         $query->where('status', $status);
    //     }
    
    //     // Filter berdasarkan toko_id
    //     if ($toko_id) {
    //         $query->where('toko_id', $toko_id);
    //     }
    
    //     // Filter berdasarkan klasifikasi_id
    //     if ($klasifikasi_id) {
    //         $query->whereHas('produk', function($q) use ($klasifikasi_id) {
    //             $q->where('klasifikasi_id', $klasifikasi_id);
    //         });
    //     }
    
    //     // Filter berdasarkan tanggal pengiriman
    //     if ($tanggal_pengiriman && $tanggal_akhir) {
    //         $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
    //     } elseif ($tanggal_pengiriman) {
    //         $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
    //         $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
    //     } elseif ($tanggal_akhir) {
    //         $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //         $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
    //     } else {
    //         // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
    //         $query->whereDate('tanggal_pengiriman', Carbon::today());
    //     }
    
    //     // Eksekusi query dan dapatkan hasilnya
    //     $stokBarangJadi = $query->get();
    
    //     // Format tanggal untuk tampilan PDF
    //     $formattedStartDate = $tanggal_pengiriman ? Carbon::parse($tanggal_pengiriman)->format('d-m-Y') : 'N/A';
    //     $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';
    
    //     // Inisialisasi DOMPDF
    //     $options = new \Dompdf\Options();
    //     $options->set('isHtml5ParserEnabled', true);
    //     $options->set('isRemoteEnabled', true);
    
    //     $dompdf = new \Dompdf\Dompdf($options);
    
    //     // Memuat konten HTML dari view
    //     $html = view('admin.laporan_hasilpenjualan.printbm', [
    //         'stokBarangJadi' => $stokBarangJadi,
    //         'startDate' => $formattedStartDate,
    //         'endDate' => $formattedEndDate,
    //         'tokos' => $tokos,
    //         'klasifikasis' => $klasifikasis,
    //     ])->render();
    
    //     $dompdf->loadHtml($html);
    
    //     // Set ukuran kertas dan orientasi
    //     $dompdf->setPaper('A4', 'portrait');
    
    //     // Render PDF
    //     $dompdf->render();
    
    //     // Menambahkan nomor halaman di kanan bawah
    //     $canvas = $dompdf->getCanvas();
    //     $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    //         $text = "Halaman $pageNumber dari $pageCount";
    //         $font = $fontMetrics->getFont('Arial', 'normal');
    //         $size = 10;
    
    //         // Menghitung lebar teks
    //         $width = $fontMetrics->getTextWidth($text, $font, $size);
    
    //         // Mengatur koordinat X dan Y
    //         $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
    //         $y = $canvas->get_height() - 15; // 15 pixel dari bawah
    
    //         // Menambahkan teks ke posisi yang ditentukan
    //         $canvas->text($x, $y, $text, $font, $size);
    //     });
    
    //     // Output PDF ke browser
    //     return $dompdf->stream('laporan_hasilpenjualan.pdf', ['Attachment' => false]);
    // }
    
    public function printLaporanBm(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id; // Ambil filter toko_id dari request
        $klasifikasi_id = $request->klasifikasi_id; // Ambil filter klasifikasi_id dari request
        
        // Format tanggal untuk tampilan
        $formattedStartDate = $tanggal_pengiriman ? Carbon::parse($tanggal_pengiriman)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';
        
        // Ambil nama toko berdasarkan ID
        $branchName = 'Semua Toko'; // Default jika tidak ada filter toko
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }
    
        // Query dasar untuk mengambil data Pengiriman_barangjadi
        $query = Pengiriman_barangjadi::with('produk.klasifikasi')
            ->orderBy('tanggal_pengiriman', 'desc');
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi_id
        if ($klasifikasi_id) {
            $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                $q->where('klasifikasi_id', $klasifikasi_id);
            });
        }
    
        // Filter berdasarkan tanggal pengiriman
        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('tanggal_pengiriman', Carbon::today());
        }
    
        // Eksekusi query dan dapatkan hasilnya
        $stokBarangJadi = $query->get();
    
        // Inisialisasi DOMPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
    
        $dompdf = new \Dompdf\Dompdf($options);
    
        // Memuat konten HTML dari view
        $html = view('admin.laporan_hasilpenjualan.printbm', [
            'stokBarangJadi' => $stokBarangJadi,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'branchName' => $branchName,
        ])->render();
    
        $dompdf->loadHtml($html);
    
        // Set ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');
    
        // Render PDF
        $dompdf->render();
    
        // Menambahkan nomor halaman di kanan bawah
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Halaman $pageNumber dari $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 10;
    
            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);
    
            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah
    
            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });
    
        // Output PDF ke browser
        return $dompdf->stream('laporan_hasilpenjualan.pdf', ['Attachment' => false]);
    }
    

    // public function printLaporanBK(Request $request)
    // {
    //     $status = $request->status;
    //     $tanggal_penjualan = $request->tanggal_penjualan;
    //     $tanggal_akhir = $request->tanggal_akhir;
    //     $toko_id = $request->toko_id;
    //     $klasifikasi_id = $request->klasifikasi_id;

    //     $inquery = Penjualanproduk::with('detailPenjualanProduk.produk')
    //         ->when($status, function ($query, $status) {
    //             return $query->where('status', $status);
    //         })
    //         ->when($toko_id, function ($query, $toko_id) {
    //             return $query->where('toko_id', $toko_id);
    //         })
    //         ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
    //             $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             return $query->whereBetween('tanggal_penjualan', [$tanggal_penjualan, $tanggal_akhir]);
    //         })
    //         ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
    //             $tanggal_penjualan = Carbon::parse($tanggal_penjualan)->startOfDay();
    //             return $query->where('tanggal_penjualan', '>=', $tanggal_penjualan);
    //         })
    //         ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
    //             $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
    //             return $query->where('tanggal_penjualan', '<=', $tanggal_akhir);
    //         })
    //         ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
    //             return $query->whereHas('detailPenjualanProduk.produk', function ($query) use ($klasifikasi_id) {
    //                 return $query->where('klasifikasi_id', $klasifikasi_id);
    //             });
    //         });

    //     // Ambil data penjualan
    //     $inquery = $inquery->get();

    //     // Gabungkan hasil berdasarkan produk_id
    //     $finalResults = [];

    //     foreach ($inquery as $penjualan) {
    //         foreach ($penjualan->detailPenjualanProduk as $detail) {
    //             $key = $detail->produk_id;

    //             if (!isset($finalResults[$key])) {
    //                 $finalResults[$key] = [
    //                     'tanggal_penjualan' => $penjualan->tanggal_penjualan,
    //                     'kode_lama' => $detail->produk->kode_lama,
    //                     'nama_produk' => $detail->produk->nama_produk,
    //                     'harga' => $detail->produk->harga,
    //                     'jumlah' => 0,
    //                     'diskon' => 0,
    //                     'total' => 0,
    //                 ];
    //             }

    //             // Jumlahkan jumlah dan total
    //             $finalResults[$key]['jumlah'] += $detail->jumlah;
    //             $finalResults[$key]['total'] += $detail->total;

    //             // Hitung diskon 10% dari jumlah * harga
    //             if ($detail->diskon > 0) {
    //                 $diskonPerItem = $detail->harga * 0.10; // Diskon per unit
    //                 $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
    //             }
    //         }
    //     }

    //     // Ambil data untuk filter
    //     $tokos = Toko::all(); // Assuming Toko is a model for your toko table
    //     $klasifikasis = Klasifikasi::all(); // Assuming Klasifikasi is a model for your klasifikasi table

    //     // Format tanggal untuk tampilan PDF
    //     $formattedStartDate = $tanggal_penjualan ? Carbon::parse($tanggal_penjualan)->format('d-m-Y') : 'N/A';
    //     $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

    //     // Inisialisasi DOMPDF
    //     $options = new \Dompdf\Options();
    //     $options->set('isHtml5ParserEnabled', true);
    //     $options->set('isRemoteEnabled', true);

    //     $dompdf = new \Dompdf\Dompdf($options);

    //     // Memuat konten HTML dari view
    //     $html = view('admin.laporan_hasilpenjualan.printbk', [
    //         'finalResults' => $finalResults,
    //         'startDate' => $formattedStartDate,
    //         'endDate' => $formattedEndDate,
    //         'tokos' => $tokos,
    //         'klasifikasis' => $klasifikasis,
    //     ])->render();

    //     $dompdf->loadHtml($html);

    //     // Set ukuran kertas dan orientasi
    //     $dompdf->setPaper('A4', 'portrait');

    //     // Render PDF
    //     $dompdf->render();

    //     // Menambahkan nomor halaman di kanan bawah
    //     $canvas = $dompdf->getCanvas();
    //     $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    //         $text = "Halaman $pageNumber dari $pageCount";
    //         $font = $fontMetrics->getFont('Arial', 'normal');
    //         $size = 10;

    //         // Menghitung lebar teks
    //         $width = $fontMetrics->getTextWidth($text, $font, $size);

    //         // Mengatur koordinat X dan Y
    //         $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
    //         $y = $canvas->get_height() - 15; // 15 pixel dari bawah

    //         // Menambahkan teks ke posisi yang ditentukan
    //         $canvas->text($x, $y, $text, $font, $size);
    //     });

    //     // Output PDF ke browser
    //     return $dompdf->stream('laporan_barang_keluar.pdf', ['Attachment' => false]);
    // }

    public function printLaporanBK(Request $request)
{
    // Ambil parameter filter dari request
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;

    // Query dasar untuk mengambil data Penjualanproduk
    $query = Penjualanproduk::with('detailPenjualanProduk.produk')
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($toko_id, function ($query, $toko_id) {
            return $query->where('toko_id', $toko_id);
        })
        ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
            return $query->whereHas('detailPenjualanProduk.produk', function ($q) use ($klasifikasi_id) {
                return $q->where('klasifikasi_id', $klasifikasi_id);
            });
        })
        ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
            $start = Carbon::parse($tanggal_penjualan)->startOfDay();
            $end = Carbon::parse($tanggal_akhir)->endOfDay();
            return $query->whereBetween('tanggal_penjualan', [$start, $end]);
        })
        ->when($tanggal_penjualan && !$tanggal_akhir, function ($query) use ($tanggal_penjualan) {
            $start = Carbon::parse($tanggal_penjualan)->startOfDay();
            return $query->where('tanggal_penjualan', '>=', $start);
        })
        ->when(!$tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
            $end = Carbon::parse($tanggal_akhir)->endOfDay();
            return $query->where('tanggal_penjualan', '<=', $end);
        })
        ->orderBy('tanggal_penjualan', 'desc');

    // Eksekusi query dan dapatkan hasilnya
    $inquery = $query->get();

    // Gabungkan hasil berdasarkan produk_id
    $finalResults = [];

    foreach ($inquery as $penjualan) {
        foreach ($penjualan->detailPenjualanProduk as $detail) {
            $produk = $detail->produk;
    
            // Pastikan produk tidak null sebelum mengakses properti
            if ($produk) {
                $key = $produk->id; // Menggunakan ID produk sebagai key
    
                if (!isset($finalResults[$key])) {
                    $finalResults[$key] = [
                        'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                        'kode_lama' => $produk->kode_lama,
                        'nama_produk' => $produk->nama_produk,
                        'harga' => $produk->harga,
                        'jumlah' => 0,
                        'diskon' => 0,
                        'total' => 0,
                    ];
                }
    
                // Jumlahkan jumlah dan total
                $finalResults[$key]['jumlah'] += $detail->jumlah;
                $finalResults[$key]['total'] += $detail->total;
    
                // Hitung diskon 10% dari jumlah * harga
                if ($detail->diskon > 0) {
                    $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                    $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                }
            }
        }
    }
    

    // Ambil data untuk filter
    $tokos = Toko::all(); // Model untuk tabel toko
    $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi

    // Dapatkan nama toko berdasarkan toko_id
    $branchName = 'Semua Toko';
    if ($toko_id) {
        $toko = Toko::find($toko_id);
        $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
    }

    // Pass raw dates ke view
    $startDate = $tanggal_penjualan;
    $endDate = $tanggal_akhir;

    // Inisialisasi DOMPDF
    $options = new \Dompdf\Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new \Dompdf\Dompdf($options);

    // Memuat konten HTML dari view
    $html = view('admin.laporan_hasilpenjualan.printbk', [
        'finalResults' => $finalResults,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'branchName' => $branchName,
        'tokos' => $tokos,
        'klasifikasis' => $klasifikasis,
    ])->render();

    $dompdf->loadHtml($html);

    // Set ukuran kertas dan orientasi
    $dompdf->setPaper('A4', 'portrait');

    // Render PDF
    $dompdf->render();

    // Menambahkan nomor halaman di kanan bawah
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Halaman $pageNumber dari $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 10;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
        $y = $canvas->get_height() - 15; // 15 pixel dari bawah

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $dompdf->stream('laporan_barang_keluar.pdf', ['Attachment' => false]);
}
    

public function exportExcelBK(Request $request)
{
    return Excel::download(new StokBarangExport($request), 'BK.xlsx');
}
public function exportExcel(Request $request)
{
    return Excel::download(new StokBarangExportBM($request), 'BM.xlsx');
}
   
}