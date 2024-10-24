<?php

namespace App\Http\Controllers\Toko_pemalang;

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
use App\Models\Retur_barangjadi;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_barangjadipesanan;
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
use App\Exports\StokBarangExportBR;






class Laporan_historipemalangController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
        $produk_id = $request->produk_id; // Pastikan ini sesuai dengan name di form

        // Ambil data toko dan produk untuk dropdown
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $produks = Produk::all();

        // Query dasar
        $query = Pengiriman_barangjadi::join('produks', 'pengiriman_barangjadis.produk_id', '=', 'produks.id')
            ->join('klasifikasis', 'produks.klasifikasi_id', '=', 'klasifikasis.id')
            ->select('pengiriman_barangjadis.*', 'produks.kode_lama')
            ->with('produk.klasifikasi');

        // Filter berdasarkan status
        if ($status) {
            $query->where('pengiriman_barangjadis.status', $status);
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('pengiriman_barangjadis.toko_id', $toko_id);
        }

        // Jika produk dipilih, abaikan klasifikasi
        if ($produk_id) {
            $query->where('pengiriman_barangjadis.produk_id', $produk_id);
        } else {
            // Filter klasifikasi hanya jika produk tidak dipilih
            if ($klasifikasi_id) {
                $query->where('produks.klasifikasi_id', $klasifikasi_id);
            }
        }

        // Filter berdasarkan tanggal pengiriman
        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('pengiriman_barangjadis.tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('pengiriman_barangjadis.tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('pengiriman_barangjadis.tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            // Tampilkan data hari ini jika tidak ada filter tanggal
            $query->whereDate('pengiriman_barangjadis.tanggal_pengiriman', Carbon::today());
        }

        // Ambil data dan urutkan berdasarkan kode_lama
        $stokBarangJadi = $query->orderBy('produks.kode_lama', 'asc')->get();

        // Kirim data ke view
        return view('toko_pemalang.laporan_historipemalang.index', compact('stokBarangJadi', 'tokos', 'klasifikasis', 'produks'));
    }

    public function barangMasukpesananbanjaran(Request $request)
    {
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
        $produk_id = $request->produk_id; // Pastikan ini sesuai dengan name di form

        // Ambil data toko dan produk untuk dropdown
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $produks = Produk::all();

        // Query dasar
        $query = Pengiriman_barangjadipesanan::join('produks', 'pengiriman_barangjadipesanans.produk_id', '=', 'produks.id')
            ->join('klasifikasis', 'produks.klasifikasi_id', '=', 'klasifikasis.id')
            ->select('pengiriman_barangjadipesanans.*', 'produks.kode_lama')
            ->with('produk.klasifikasi');

        // Filter berdasarkan status
        if ($status) {
            $query->where('pengiriman_barangjadipesanans.status', $status);
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('pengiriman_barangjadipesanans.toko_id', $toko_id);
        }

        // Jika produk dipilih, abaikan klasifikasi
        if ($produk_id) {
            $query->where('pengiriman_barangjadipesanans.produk_id', $produk_id);
        } else {
            // Filter klasifikasi hanya jika produk tidak dipilih
            if ($klasifikasi_id) {
                $query->where('produks.klasifikasi_id', $klasifikasi_id);
            }
        }

        // Filter berdasarkan tanggal pengiriman
        if ($tanggal_pengiriman && $tanggal_akhir) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('pengiriman_barangjadipesanans.tanggal_pengiriman', [$tanggal_pengiriman, $tanggal_akhir]);
        } elseif ($tanggal_pengiriman) {
            $tanggal_pengiriman = Carbon::parse($tanggal_pengiriman)->startOfDay();
            $query->where('pengiriman_barangjadipesanans.tanggal_pengiriman', '>=', $tanggal_pengiriman);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('pengiriman_barangjadipesanans.tanggal_pengiriman', '<=', $tanggal_akhir);
        } else {
            // Tampilkan data hari ini jika tidak ada filter tanggal
            $query->whereDate('pengiriman_barangjadipesanans.tanggal_pengiriman', Carbon::today());
        }

        // Ambil data dan urutkan berdasarkan kode_lama
        $stokBarangJadi = $query->orderBy('produks.kode_lama', 'asc')->get();

        // Kirim data ke view
        return view('toko_pemalang.laporan_historipemalang.barangmasukpesanan', compact('stokBarangJadi', 'tokos', 'klasifikasis', 'produks'));
    }


    public function barangKeluarbanjaran(Request $request)
    {
        $status = $request->status;
        $tanggal_penjualan = $request->tanggal_penjualan;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
        $produk_id = $request->produk; 
    
        // Query dasar untuk mengambil data penjualan produk
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
            });
    
        // Ambil data penjualan
        $inquery = $inquery->get();
    
        // Gabungkan hasil berdasarkan produk_id
        $finalResults = [];
    
        foreach ($inquery as $penjualan) {
            foreach ($penjualan->detailPenjualanProduk as $detail) {
                // Pastikan produk tidak null sebelum mengakses properti
                if ($detail->produk) {
                    // Filter produk berdasarkan klasifikasi jika klasifikasi_id dipilih
                    if ($klasifikasi_id && $detail->produk->klasifikasi_id != $klasifikasi_id) {
                        continue; // Lewati produk yang tidak sesuai dengan klasifikasi
                    }
    
                    // Filter ulang berdasarkan produk_id jika diperlukan
                    if ($produk_id && $detail->produk_id != $produk_id) {
                        continue; // Lewati produk yang tidak sesuai dengan filter
                    }
    
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
                        $diskonPerItem = round($detail->produk->harga * 0.10); // Diskon per unit
                        $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                    }
                }
            }
        }
    
        // Mengurutkan finalResults berdasarkan kode_lama
        uasort($finalResults, function ($a, $b) {
            return strcmp($a['kode_lama'], $b['kode_lama']);
        });
    
        // Ambil semua data toko dan klasifikasi untuk dropdown
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();
        $produks = Produk::all(); // Ambil semua produk untuk dropdown
    
        return view('toko_pemalang.laporan_historipemalang.barangkeluar', [
            'finalResults' => $finalResults,
            'tokos' => $tokos,
            'produks' => $produks,
            'klasifikasis' => $klasifikasis,
        ]);
    }
    

    public function barangKeluarRincibanjaran(Request $request)
{
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;
    $produk_id = $request->produk; // Tambahkan filter produk

    // Query dasar untuk mengambil data penjualan produk
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
        });

    // Ambil data penjualan
    $inquery = $inquery->get();

    // Gabungkan hasil berdasarkan produk_id
    $finalResults = [];

    foreach ($inquery as $penjualan) {
        foreach ($penjualan->detailPenjualanProduk as $detail) {
            // Pastikan produk tidak null sebelum mengakses properti
            if ($detail->produk) {
                // Filter ulang berdasarkan klasifikasi_id jika diperlukan
                if ($klasifikasi_id && $detail->produk->klasifikasi_id != $klasifikasi_id) {
                    continue; // Lewati produk yang tidak sesuai dengan klasifikasi
                }

                // Filter ulang berdasarkan produk_id jika diperlukan
                if ($produk_id && $detail->produk_id != $produk_id) {
                    continue; // Lewati produk yang tidak sesuai dengan filter
                }

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

    // Ambil semua data toko dan klasifikasi untuk dropdown
    $tokos = Toko::all();
    $klasifikasis = Klasifikasi::all();
    $produks = Produk::all(); // Ambil semua produk untuk dropdown

    return view('toko_pemalang.laporan_historipemalang.barangkeluarrinci', [
        'finalResults' => $finalResults,
        'tokos' => $tokos,
        'produks' => $produks,
        'klasifikasis' => $klasifikasis,
    ]);
}

    
    public function barangReturbanjaran(Request $request)
    {
        $status = $request->status;
        $tanggal_retur = $request->tanggal_retur;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;
    
        // Query dasar
        $query = Retur_barangjadi::with('produk.klasifikasi');
    
        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }
    
        // Filter berdasarkan tanggal
        if ($tanggal_retur && $tanggal_akhir) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
        } elseif ($tanggal_retur) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $query->where('tanggal_retur', '>=', $tanggal_retur);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('tanggal_retur', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data hari ini
            $query->whereDate('tanggal_retur', Carbon::today());
        }
    
        // Filter berdasarkan toko
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }
    
        // Filter berdasarkan klasifikasi
        if ($klasifikasi_id) {
            $query->whereHas('produk.klasifikasi', function ($query) use ($klasifikasi_id) {
                $query->where('id', $klasifikasi_id);
            });
        }
    
        $stokBarangJadi = $query->orderBy('created_at', 'desc')->get()->groupBy('kode_retur');

        $totalJumlah = 0;
        $grandTotal = 0;

        foreach ($stokBarangJadi as $returGroup) {
            foreach ($returGroup as $retur) {
                $totalJumlah += $retur->jumlah;
                $grandTotal += $retur->jumlah * $retur->produk->harga;
            }
        }

        // Ambil semua data toko dan klasifikasi untuk dropdown
        $tokos = Toko::all();
        $klasifikasis = Klasifikasi::all();

        return view('toko_pemalang.laporan_historipemalang.barangretur', compact('stokBarangJadi', 'tokos', 'klasifikasis', 'totalJumlah', 'grandTotal'));
    }
    
    public function printLaporanBmbanjaran(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id; // Ambil filter toko_id dari request
        $klasifikasi_id = $request->klasifikasi_id; // Ambil filter klasifikasi_id dari request
        $produk_id = $request->produk_id; // Ambil filter produk_id dari request
        
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
        $query = Pengiriman_barangjadi::join('produks', 'pengiriman_barangjadis.produk_id', '=', 'produks.id')
            ->join('klasifikasis', 'produks.klasifikasi_id', '=', 'klasifikasis.id')
            ->select('pengiriman_barangjadis.*', 'produks.kode_lama', 'produks.nama_produk', 'produks.harga')
            ->with('produk.klasifikasi');

        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }

        // Jika produk dipilih, abaikan klasifikasi dan filter berdasarkan produk
        if ($produk_id) {
            $query->where('pengiriman_barangjadis.produk_id', $produk_id);
        } else {
            // Filter berdasarkan klasifikasi_id jika produk tidak dipilih
            if ($klasifikasi_id) {
                $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                    $q->where('klasifikasi_id', $klasifikasi_id);
                });
            }
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
        $stokBarangJadi = $query->orderBy('produks.kode_lama', 'asc')->get();

        // Mengelompokkan produk yang sama dan menjumlahkan jumlah serta total
        $groupedData = $stokBarangJadi->groupBy('produk_id')->map(function ($items) {
            return [
                'produk' => $items->first()->produk,
                'jumlah' => $items->sum('jumlah'),
                'total' => $items->sum(function ($item) {
                    return $item->jumlah * $item->produk->harga;
                }),
            ];
        });

        // Memuat konten HTML dari view
        $pdf = FacadePdf::loadView('toko_pemalang.laporan_historipemalang.printbm', [
            'groupedData' => $groupedData,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'branchName' => $branchName,
        ]);

        // Menambahkan nomor halaman di kanan bawah
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Page $pageNumber of $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 8;

            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);

            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('laporan_penjualan_produk.pdf');
    }

    public function printLaporanBmpesananbanjaran(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_pengiriman = $request->tanggal_pengiriman;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id; // Ambil filter toko_id dari request
        $klasifikasi_id = $request->klasifikasi_id; // Ambil filter klasifikasi_id dari request
        $produk_id = $request->produk_id; // Ambil filter produk_id dari request
        
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
        $query = Pengiriman_barangjadipesanan::join('produks', 'pengiriman_barangjadipesanans.produk_id', '=', 'produks.id')
            ->join('klasifikasis', 'produks.klasifikasi_id', '=', 'klasifikasis.id')
            ->select('pengiriman_barangjadipesanans.*', 'produks.kode_lama', 'produks.nama_produk', 'produks.harga')
            ->with('produk.klasifikasi');

        // Filter berdasarkan status
        if ($status) {
            $query->where('status', $status);
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('toko_id', $toko_id);
        }

        // Jika produk dipilih, abaikan klasifikasi dan filter berdasarkan produk
        if ($produk_id) {
            $query->where('pengiriman_barangjadipesanans.produk_id', $produk_id);
        } else {
            // Filter berdasarkan klasifikasi_id jika produk tidak dipilih
            if ($klasifikasi_id) {
                $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                    $q->where('klasifikasi_id', $klasifikasi_id);
                });
            }
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
        $stokBarangJadi = $query->orderBy('produks.kode_lama', 'asc')->get();

        // Mengelompokkan produk yang sama dan menjumlahkan jumlah serta total
        $groupedData = $stokBarangJadi->groupBy('produk_id')->map(function ($items) {
            return [
                'produk' => $items->first()->produk,
                'jumlah' => $items->sum('jumlah'),
                'total' => $items->sum(function ($item) {
                    return $item->jumlah * $item->produk->harga;
                }),
            ];
        });

        // Memuat konten HTML dari view
        $pdf = FacadePdf::loadView('toko_pemalang.laporan_historipemalang.printbmpesanan', [
            'groupedData' => $groupedData,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'branchName' => $branchName,
        ]);

        // Menambahkan nomor halaman di kanan bawah
        $pdf->output();
        $dompdf = $pdf->getDomPDF();
        $canvas = $dompdf->getCanvas();
        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $text = "Page $pageNumber of $pageCount";
            $font = $fontMetrics->getFont('Arial', 'normal');
            $size = 8;

            // Menghitung lebar teks
            $width = $fontMetrics->getTextWidth($text, $font, $size);

            // Mengatur koordinat X dan Y
            $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
            $y = $canvas->get_height() - 15; // 15 pixel dari bawah

            // Menambahkan teks ke posisi yang ditentukan
            $canvas->text($x, $y, $text, $font, $size);
        });

        // Output PDF ke browser
        return $pdf->stream('laporan_penjualan_produk.pdf');
    }

    

    public function printLaporanBKbanjaran(Request $request)
{
    // Ambil parameter filter dari request
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;
    $produk_id = $request->produk; // Tambahkan filter produk

    // Query dasar untuk mengambil data Penjualanproduk
    $query = Penjualanproduk::with('detailPenjualanProduk.produk')
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($toko_id, function ($query, $toko_id) {
            return $query->where('toko_id', $toko_id);
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
        });

    // Eksekusi query dan dapatkan hasilnya
    $inquery = $query->get();

    // Gabungkan hasil berdasarkan produk_id
    $finalResults = [];

    foreach ($inquery as $penjualan) {
        foreach ($penjualan->detailPenjualanProduk as $detail) {
            $produk = $detail->produk;

            // Pastikan produk tidak null sebelum mengakses properti
            if ($produk) {
                // Filter produk berdasarkan klasifikasi jika klasifikasi_id dipilih
                if ($klasifikasi_id && $produk->klasifikasi_id != $klasifikasi_id) {
                    continue; // Lewati produk yang tidak sesuai dengan klasifikasi
                }

                // Filter ulang berdasarkan produk_id jika diperlukan
                if ($produk_id && $produk->id != $produk_id) {
                    continue; // Lewati produk yang tidak sesuai dengan filter
                }

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
                        'penjualan_kotor' => 0, // Tambahkan ini
                        'penjualan_bersih' => 0, // Tambahkan ini untuk penjualan bersih
                    ];
                }

                // Jumlahkan jumlah dan total
                $finalResults[$key]['jumlah'] += $detail->jumlah;
                $finalResults[$key]['penjualan_kotor'] += $detail->jumlah * $produk->harga;
                $finalResults[$key]['total'] += $detail->total;

                // Hitung diskon 10% dari jumlah * harga
                if ($detail->diskon > 0) {
                    $diskonPerItem = $produk->harga * 0.10; // Diskon per unit
                    $finalResults[$key]['diskon'] += $detail->jumlah * $diskonPerItem;
                }

                // Kalkulasi penjualan bersih (penjualan kotor - diskon)
                $finalResults[$key]['penjualan_bersih'] = $finalResults[$key]['penjualan_kotor'] - $finalResults[$key]['diskon'];
            }
        }
    }

    // Mengurutkan finalResults berdasarkan kode_lama
    uasort($finalResults, function ($a, $b) {
        return strcmp($a['kode_lama'], $b['kode_lama']);
    });

    // Ambil data untuk filter
    $tokos = Toko::all(); // Model untuk tabel toko
    $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi
    $produks = Produk::all(); // Model untuk tabel produk

    // Dapatkan nama toko berdasarkan toko_id
    $branchName = 'Semua Toko';
    if ($toko_id) {
        $toko = Toko::find($toko_id);
        $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
    }
    $klasifikasiName = 'Semua Divisi';
    if ($klasifikasi_id) {
        $klasifikasi = Klasifikasi::find($klasifikasi_id);
        $klasifikasiName = $klasifikasi ? $klasifikasi->nama : 'Semua Klasifikasi';
    }

    // Pass raw dates ke view
    $startDate = $tanggal_penjualan;
    $endDate = $tanggal_akhir;

    // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
    $pdf = FacadePdf::loadView('toko_pemalang.laporan_historipemalang.printbk', [
        'finalResults' => $finalResults,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'branchName' => $branchName,
        'klasifikasiName' => $klasifikasiName,
        'tokos' => $tokos,
        'produks' => $produks, // Tambahkan data produk
        'klasifikasis' => $klasifikasis,
    ]);

    $pdf->output();
    $dompdf = $pdf->getDomPDF();
    $canvas = $dompdf->getCanvas();
    $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
        $text = "Page $pageNumber of $pageCount";
        $font = $fontMetrics->getFont('Arial', 'normal');
        $size = 8;

        // Menghitung lebar teks
        $width = $fontMetrics->getTextWidth($text, $font, $size);

        // Mengatur koordinat X dan Y
        $x = $canvas->get_width() - $width - 10; // 10 pixel dari kanan
        $y = $canvas->get_height() - 15; // 15 pixel dari bawah

        // Menambahkan teks ke posisi yang ditentukan
        $canvas->text($x, $y, $text, $font, $size);
    });

    // Output PDF ke browser
    return $pdf->stream('laporan_penjualan_produk.pdf');
}

    


    public function printLaporanBKrincibanjaran(Request $request)
{
    // Ambil parameter filter dari request
    $status = $request->status;
    $tanggal_penjualan = $request->tanggal_penjualan;
    $tanggal_akhir = $request->tanggal_akhir;
    $toko_id = $request->toko_id;
    $klasifikasi_id = $request->klasifikasi_id;
    $produk_id = $request->produk; // Tambahkan filter produk

    // Query dasar untuk mengambil data Penjualanproduk
    $inquery = Penjualanproduk::with('detailPenjualanProduk.produk')
        ->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })
        ->when($toko_id, function ($query, $toko_id) {
            return $query->where('toko_id', $toko_id);
        })
        ->when($tanggal_penjualan && $tanggal_akhir, function ($query) use ($tanggal_penjualan, $tanggal_akhir) {
            $start = Carbon::parse($tanggal_penjualan)->startOfDay();
            $end = Carbon::parse($tanggal_akhir)->endOfDay();
            return $query->whereBetween('tanggal_penjualan', [$start, $end]);
        })
        ->when($tanggal_penjualan, function ($query, $tanggal_penjualan) {
            $start = Carbon::parse($tanggal_penjualan)->startOfDay();
            return $query->where('tanggal_penjualan', '>=', $start);
        })
        ->when($tanggal_akhir, function ($query, $tanggal_akhir) {
            $end = Carbon::parse($tanggal_akhir)->endOfDay();
            return $query->where('tanggal_penjualan', '<=', $end);
        })
        ->when($klasifikasi_id, function ($query, $klasifikasi_id) {
            return $query->whereHas('detailPenjualanProduk.produk', function ($q) use ($klasifikasi_id) {
                return $q->where('klasifikasi_id', $klasifikasi_id);
            });
        })
        ->when($produk_id, function ($query, $produk_id) {
            return $query->whereHas('detailPenjualanProduk', function ($q) use ($produk_id) {
                return $q->where('produk_id', $produk_id);
            });
        });

    // Eksekusi query dan dapatkan hasilnya
    $inquery = $inquery->get();

    // Gabungkan hasil berdasarkan kode_penjualan
    $finalResults = [];

    foreach ($inquery as $penjualan) {
        $kode_penjualan = $penjualan->kode_penjualan; // Menggunakan kode_penjualan sebagai kunci

        if (!isset($finalResults[$kode_penjualan])) {
            $finalResults[$kode_penjualan] = [
                'penjualan' => $penjualan,
                'detailProduk' => []
            ];
        }

        foreach ($penjualan->detailPenjualanProduk as $detail) {
            $produk = $detail->produk;

            // Pastikan produk tidak null sebelum mengakses properti dan cocok dengan filter produk_id
            if ($produk && (!$produk_id || $produk->id == $produk_id)) {
                $finalResults[$kode_penjualan]['detailProduk'][] = [
                    'tanggal_penjualan' => $penjualan->tanggal_penjualan,
                    'kode_lama' => $produk->kode_lama,
                    'nama_produk' => $produk->nama_produk,
                    'harga' => $produk->harga,
                    'jumlah' => $detail->jumlah,
                    'totalasli' => $detail->totalasli,
                    'diskon' => $detail->diskon > 0 ? $produk->harga * 0.10 * $detail->jumlah : 0,
                    'total' => $detail->total
                ];
            }
        }
    }

    // Ambil data untuk filter
    $tokos = Toko::all(); // Model untuk tabel toko
    $klasifikasis = Klasifikasi::all(); // Model untuk tabel klasifikasi
    $produks = Produk::all(); // Model untuk tabel produk

    // Dapatkan nama toko berdasarkan toko_id
    $branchName = 'Semua Toko';
    if ($toko_id) {
        $toko = Toko::find($toko_id);
        $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
    }

    // Pass raw dates ke view
    $startDate = $tanggal_penjualan;
    $endDate = $tanggal_akhir;

    // Menggunakan Barryvdh\DomPDF\Facade\Pdf untuk memuat dan menghasilkan PDF
    $pdf = FacadePdf::loadView('toko_pemalang.laporan_historipemalang.printbkrinci', [
        'finalResults' => $finalResults,
        'startDate' => $startDate,
        'endDate' => $endDate,
        'branchName' => $branchName,
        'tokos' => $tokos,
        'produks' => $produks,
        'klasifikasis' => $klasifikasis,
    ]);

    // Menambahkan nomor halaman di kanan bawah
    $pdf->setOption('isHtml5ParserEnabled', true);
    $pdf->setOption('isRemoteEnabled', true);

    $pdf->setPaper('A4', 'portrait');

    // Output PDF ke browser
    return $pdf->stream('laporan_barang_keluar.pdf', ['Attachment' => false]);
}


   
    public function printLaporanBRbanjaran(Request $request)
    {
        // Ambil parameter filter dari request
        $status = $request->status;
        $tanggal_retur = $request->tanggal_retur;
        $tanggal_akhir = $request->tanggal_akhir;
        $toko_id = $request->toko_id;
        $klasifikasi_id = $request->klasifikasi_id;

        // Format tanggal untuk tampilan
        $formattedStartDate = $tanggal_retur ? Carbon::parse($tanggal_retur)->format('d-m-Y') : 'N/A';
        $formattedEndDate = $tanggal_akhir ? Carbon::parse($tanggal_akhir)->format('d-m-Y') : 'N/A';

        // Ambil nama toko berdasarkan ID
        $branchName = 'Semua Toko'; // Default jika tidak ada filter toko
        if ($toko_id) {
            $toko = Toko::find($toko_id);
            $branchName = $toko ? $toko->nama_toko : 'Semua Toko';
        }

        // Query dasar untuk mengambil data Retur_barangjadi
        $query = Retur_barangjadi::with('produk.klasifikasi')
            ->join('produks', 'produks.id', '=', 'retur_barangjadis.produk_id') // Join with 'produks'
            ->orderBy('produks.kode_lama', 'asc') // Order by 'kode_lama'
            ->orderBy('retur_barangjadis.tanggal_retur', 'desc'); // Then order by 'tanggal_retur'

        // Filter berdasarkan status
        if ($status) {
            $query->where('retur_barangjadis.status', $status);
        }

        // Filter berdasarkan toko_id
        if ($toko_id) {
            $query->where('retur_barangjadis.toko_id', $toko_id);
        }

        // Filter berdasarkan klasifikasi_id
        if ($klasifikasi_id) {
            $query->whereHas('produk', function ($q) use ($klasifikasi_id) {
                $q->where('klasifikasi_id', $klasifikasi_id);
            });
        }

        // Filter berdasarkan tanggal retur
        if ($tanggal_retur && $tanggal_akhir) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->whereBetween('retur_barangjadis.tanggal_retur', [$tanggal_retur, $tanggal_akhir]);
        } elseif ($tanggal_retur) {
            $tanggal_retur = Carbon::parse($tanggal_retur)->startOfDay();
            $query->where('retur_barangjadis.tanggal_retur', '>=', $tanggal_retur);
        } elseif ($tanggal_akhir) {
            $tanggal_akhir = Carbon::parse($tanggal_akhir)->endOfDay();
            $query->where('retur_barangjadis.tanggal_retur', '<=', $tanggal_akhir);
        } else {
            // Jika tidak ada filter tanggal, tampilkan data untuk hari ini
            $query->whereDate('retur_barangjadis.tanggal_retur', Carbon::today());
        }

        // Eksekusi query dan dapatkan hasilnya
        $stokBarangJadi = $query->select('retur_barangjadis.*') // Select only 'retur_barangjadi' fields
            ->get()
            ->groupBy('kode_retur');

        // Hitung total jumlah dan grand total
        $totalJumlah = 0;
        $grandTotal = 0;

        foreach ($stokBarangJadi as $returGroup) {
            foreach ($returGroup as $retur) {
                $totalJumlah += $retur->jumlah;
                $grandTotal += $retur->jumlah * $retur->produk->harga;
            }
        }

        // Menggunakan FacadePdf untuk menghasilkan PDF
        $pdf = FacadePdf::loadView('toko_pemalang.laporan_historipemalang.printbr', [
            'stokBarangJadi' => $stokBarangJadi,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
            'branchName' => $branchName,
            'totalJumlah' => $totalJumlah,
            'grandTotal' => $grandTotal,
        ]);

        // Menambahkan nomor halaman di kanan bawah
        $pdf->setPaper('A4', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->setOption('footer-right', 'Halaman [page] dari [topage]');

        // Output PDF ke browser
        return $pdf->stream('laporan_barangretur.pdf', ['Attachment' => false]);
    }

    public function exportExcelBK(Request $request)
    {
        return Excel::download(new StokBarangExport($request), 'BK.xlsx');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new StokBarangExportBM($request), 'BM.xlsx');
    }

    public function exportExcelBR(Request $request)
    {
        return Excel::download(new StokBarangExportBR($request), 'BR.xlsx');
    }
    

    public function getByKlasifikasi($id)
    {
        $produks = Produk::where('klasifikasi_id', $id)->get(['id', 'nama_produk']);
        return response()->json($produks);
    }
    
}