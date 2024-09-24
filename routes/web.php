<?php

use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SlawiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\Admin\AddpelangganController;
use App\Http\Controllers\Admin\HargajualController;
use App\Http\Controllers\Admin\Inquery_hasilpenjualanController;
use App\Http\Controllers\Admin\Inquery_pemesananprodukController;
use App\Http\Controllers\Admin\Inquery_pemindahanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pengirimanbarangjadipesananController;
use App\Http\Controllers\Admin\Inquery_pengirimanpesananController;
use App\Http\Controllers\Admin\Inquery_penjualanprodukController;
use App\Http\Controllers\Admin\Inquery_returbarangjadiController;
use App\Http\Controllers\Admin\Inquery_stokbarangjadiController;
use App\Http\Controllers\Admin\PemesananprodukController;
use App\Http\Controllers\Admin\KlasifikasiController as AdminKlasifikasiController;
use App\Http\Controllers\Admin\Laporan_hasilpenjualanController;
use App\Http\Controllers\Admin\Laporan_pemesananprodukController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PenjualanprodukController;
use App\Http\Controllers\Admin\PermintaanprodukController;
use App\Http\Controllers\Admin\Laporan_permintaanprodukController;
use App\Http\Controllers\Admin\Laporan_stoktokoController;
use App\Http\Controllers\Admin\PemusnahanbarangjadiController;
use App\Http\Controllers\Admin\Pengiriman_tokoslawiController;
use App\Http\Controllers\Admin\PengirimanbarangjadiController;
use App\Http\Controllers\Admin\PengirimanbarangjadipesananController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\Setoran_pelunasanController;
use App\Http\Controllers\Admin\Stok_barangjadiController;
use App\Http\Controllers\KategoriDropdownController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_pemindahanbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_setoranpenjualanController;
use App\Http\Controllers\Toko_banjaran\PelunasanpemesananController;
use App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController;
use App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Setoran_tokobanjaranController;
use App\Http\Controllers\Toko_banjaran\Stok_tokobanjaranController;
use App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController;
use App\Http\Controllers\Toko_bumiayu\PemesananprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController;
use App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController;
use App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController;
use App\Http\Controllers\Toko_slawi\Inquery_pemindahanslawiController;
use App\Http\Controllers\Toko_slawi\Inquery_returslawiController;
use App\Http\Controllers\Toko_slawi\Laporan_pemindahanslawiController;
use App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController as Toko_slawiPengiriman_tokoslawiController;
use App\Http\Controllers\Toko_slawi\Retur_tokoslawiController;
use App\Models\Pengiriman_barangjadi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|inde
*/

Route::get('/', [AuthController::class, 'index']);
Route::get('login', [AuthController::class, 'index']);
Route::get('loginn', [AuthController::class, 'tologin']);
Route::get('register', [AuthController::class, 'toregister']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'registeruser']);
Route::get('logout', [AuthController::class, 'logout']);
Route::get('check-user', [HomeController::class, 'check_user']);

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index']);
    
    Route::resource('akses', \App\Http\Controllers\Admin\AksesController::class);
    Route::get('akses/access/{id}', [\App\Http\Controllers\Admin\AksesController::class, 'access']);
    Route::post('akses-access/{id}', [\App\Http\Controllers\Admin\AksesController::class, 'access_user']);

    Route::resource('karyawan', \App\Http\Controllers\Admin\KaryawanController::class);

    Route::resource('user', \App\Http\Controllers\Admin\UserController::class);
    Route::get('user/karyawan/{id}', [\App\Http\Controllers\Admin\UserController::class, 'karyawan']);

    Route::resource('kartu', \App\Http\Controllers\Admin\KartuController::class);

    Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Admin\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    Route::post('admin/pelanggan/import', [PelangganController::class, 'import'])->name('pelanggan.import');

    Route::resource('departemen', \App\Http\Controllers\Admin\DepartemenController::class); 

    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class); 
    Route::get('klasifikasi/get_klasifikasi/{id}', [\App\Http\Controllers\Admin\BarangController::class, 'get_klasifikasi']);

    Route::resource('klasifikasi', \App\Http\Controllers\Admin\KlasifikasiController::class); 
    Route::get('/klasifikasi/{id}/sub', [\App\Http\Controllers\Admin\KlasifikasiController::class, 'getSubCategories']);
    Route::get('klasifikasi/get_subklasifikasi/{id}', [\App\Http\Controllers\Admin\KlasifikasiController::class, 'get_subklasifikasi']);

    Route::resource('addkategori', \App\Http\Controllers\Admin\AddkategoriController::class);
    Route::post('tambahkategori', [\App\Http\Controllers\Admin\AddkategoriController::class, 'tambahkategori']);

    Route::resource('addsub', \App\Http\Controllers\Admin\AddsubController::class); 
    Route::get('addsub/get_subklasifikasi/{id}', [\App\Http\Controllers\Admin\AddsubController::class, 'get_subklasifikasi']);

    Route::resource('subklasifikasi', \App\Http\Controllers\Admin\SubklasifikasiController::class); 

    Route::resource('member', \App\Http\Controllers\Admin\MemberController::class); 

    Route::resource('input', \App\Http\Controllers\Admin\InputController::class);

    Route::resource('produk', \App\Http\Controllers\Admin\ProdukController::class);
    // Route::post('admin/produk/import', [ProdukController::class, 'import']);
    Route::post('admin/produk/import', [ProdukController::class, 'import'])->name('produk.import');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');


    Route::resource('toko', \App\Http\Controllers\Admin\TokoController::class);

    Route::resource('hargajual', \App\Http\Controllers\Admin\HargajualController::class);
    Route::get('admin/hargajual/all', [HargajualController::class, 'all'])->name('admin.hargajual.all');
    Route::post('/admin/update-harga', [HargajualController::class, 'updateHarga'])->name('update.harga');
    Route::get('admin/hargajual/show', [App\Http\Controllers\Admin\HargajualController::class, 'show'])->name('show');
    Route::get('/cetak-pdf', [HargajualController::class, 'cetakPdf'])->name('cetak.pdf');
    Route::get('/admin/hargajual/filter', [HargajualController::class, 'all'])->name('admin.hargajual.filter');
    Route::get('/produk/perubahan', [HargajualController::class, 'showPerubahanProduk'])->name('produk.showPerubahan');
    Route::get('/print-reporthargajual', [HargajualController::class, 'print'])->name('print.reporthargajual');

    Route::resource('pemesanan_produk', \App\Http\Controllers\Admin\PemesananprodukController::class);
    Route::get('/admin/pemesanan_produk/cetak/{id}', [PemesananProdukController::class, 'cetak'])->name('admin.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananProdukController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Admin\PemesananprodukController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/admin/pemesanan_produk/update/{id}', [PemesananprodukController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/admin/pemesanan_produk/cetak-pdf{id}', [PemesananprodukController::class, 'cetakPdf'])->name('admin.pemesanan_produk.cetak-pdf');
    Route::delete('admin/pemesanan_produk/{id}', [PemesananProdukController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/admin/pemesanan_produk/{id}/cetak', [PemesananProdukController::class, 'cetak'])->name('admin.pemesanan_produk.cetak');



    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Admin\Inquery_pemesananprodukController::class);
    Route::get('/admin/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('admin.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Admin\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Admin\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananproduk', \App\Http\Controllers\Admin\Laporan_pemesananprodukController::class);
    Route::get('print_pemesanan', [\App\Http\Controllers\Admin\Laporan_pemesananprodukController::class, 'print_pemesanan']);
    Route::get('printReportpemesanan', [Laporan_pemesananprodukController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobal', [\App\Http\Controllers\Admin\Laporan_pemesananprodukController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobal', [Laporan_pemesananprodukController::class, 'printReportPemesananglobal'])->name('printReportPemesananglobal');
    Route::get('printReportpemesananglobal1', [Laporan_pemesananprodukController::class, 'printReportPemesananglobal1'])->name('printReportPemesananglobal1');
    Route::get('/laporan/pemesanan', [Laporan_pemesananprodukController::class, 'printReportPemesananglobal'])->name('laporan.pemesanan.printglobal');


    Route::resource('penjualan_produk', \App\Http\Controllers\Admin\PenjualanprodukController::class);
    Route::get('/admin/penjualan_produk/cetak/{id}', [PenjualanprodukController::class, 'cetak'])->name('admin.penjualan_produk.cetak');
    Route::get('/admin/penjualan_produk/cetak-pdf{id}', [PenjualanprodukController::class, 'cetakPdf'])->name('admin.penjualan_produk.cetak-pdf');
    Route::get('/admin/penjualan_produk/pelunasan', [PenjualanprodukController::class, 'pelunasan'])->name('admin.penjualan_produk.pelunasan');
    Route::get('admin/penjualan_produk/create', [PenjualanProdukController::class, 'create'])->name('admin.penjualan_produk.create');
    Route::get('/admin/penjualan_produk/pelunasan', [PenjualanprodukController::class, 'pelunasan'])->name('admin.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukController::class, 'fetchDataByKode'])->name('admin.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Admin\PenjualanprodukController::class, 'metode']);



    Route::resource('inquery_penjualanproduk', \App\Http\Controllers\Admin\Inquery_penjualanprodukController::class);
    Route::get('/admin/inquery_penjualanproduk', [Inquery_penjualanprodukController::class, 'index'])->name('admin.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanproduk/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Admin\Inquery_penjualanprodukController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanproduk/posting_penjualanproduk/{id}', [\App\Http\Controllers\Admin\Inquery_penjualanprodukController::class, 'posting_penjualanproduk']);

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Admin\Laporan_penjualanprodukController::class);
    Route::get('printReportpenjualan', [\App\Http\Controllers\Admin\Laporan_penjualanprodukController::class, 'printReportpenjualan']);
    Route::get('printReportglobal', [\App\Http\Controllers\Admin\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Admin\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Admin\PermintaanprodukController::class);
    Route::post('admin/permintaan_produk', [PermintaanprodukController::class, 'store']);
    Route::get('admin/permintaan_produk', [PermintaanprodukController::class, 'show']);
    Route::post('admin/permintaan_produk/import', [ProdukController::class, 'import'])->name('permintaan_produk.import');
    Route::get('/permintaan-produk/{id}/print', [PermintaanProdukController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Admin\PermintaanprodukController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Admin\PermintaanprodukController::class, 'posting_permintaanproduk']);
    Route::delete('admin/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('admin.permintaan_produk.destroy');

    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Admin\Inquery_permintaanprodukController::class);
  

    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Admin\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('printReport2', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'printReporttoko']);
    Route::get('indexpermintaanrinci', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'indexpermintaanrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Admin\Laporan_permintaanprodukController::class, 'printReportRinci']);

    Route::resource('stok_barangjadi', \App\Http\Controllers\Admin\Stok_barangjadiController::class);
    Route::get('/stok_barangjadi/{id}/print', [Stok_barangjadiController::class, 'print'])->name('stok_barangjadi.print');
    Route::get('stok_barangjadi/unpost_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Stok_barangjadiController::class, 'unpost_stokbarangjadi']);
    Route::get('stok_barangjadi/posting_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Stok_barangjadiController::class, 'posting_stokbarangjadi']);
   
    Route::resource('inquery_stokbarangjadi', \App\Http\Controllers\Admin\Inquery_stokbarangjadiController::class);
    Route::get('inquery_stokbarangjadi/unpost_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_stokbarangjadiController::class, 'unpost_stokbarangjadi']);
    Route::get('inquery_stokbarangjadi/posting_stokbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_stokbarangjadiController::class, 'posting_stokbarangjadi']);
    Route::get('admin/inquery_stokbarangjadi/{id}/edit', [Inquery_stokbarangjadiController::class, 'edit'])->name('stokbarangjadi.edit');
    Route::put('admin/inquery_stokbarangjadi/{id}', [Inquery_stokbarangjadiController::class, 'update'])->name('stokbarangjadi.update');

    Route::resource('laporan_stokbarangjadi', \App\Http\Controllers\Admin\Laporan_stokbarangjadiController::class);
    Route::get('print1', [\App\Http\Controllers\Admin\Laporan_stokbarangjadiController::class, 'printReport']);

    Route::resource('laporan_stoktoko', \App\Http\Controllers\Admin\Laporan_stoktokoController::class);
    Route::get('stoktokopesanan', [\App\Http\Controllers\Admin\Laporan_stoktokoController::class, 'stoktokopesanan']);
    Route::get('printstoktoko', [\App\Http\Controllers\Admin\Laporan_stoktokoController::class, 'printReport']);
    Route::get('printstoktokopesanan', [\App\Http\Controllers\Admin\Laporan_stoktokoController::class, 'printReportstokpesanan']);
    Route::get('printExcelStok', [Laporan_stoktokoController::class, 'exportExcelStok'])->name('printExcelStok');



    Route::resource('data_deposit', \App\Http\Controllers\Admin\DepositController::class);

    Route::resource('inquery_deposit', \App\Http\Controllers\Admin\Inquery_depositController::class);

    Route::resource('laporan_deposit', \App\Http\Controllers\Admin\Laporan_depositController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Admin\Laporan_depositController::class, 'printReportsaldo']);

    Route::resource('data_stokbarangjadi', \App\Http\Controllers\Admin\Data_stokbarangjadiController::class);

    Route::resource('data_stokretur', \App\Http\Controllers\Admin\Data_stokreturController::class);

    Route::resource('pengiriman_barangjadi', \App\Http\Controllers\Admin\PengirimanbarangjadiController::class);
    Route::get('/pengiriman_barangjadi/{id}/print', [PengirimanbarangjadiController::class, 'print'])->name('pengiriman_barangjadi.print');
    Route::get('admin/pengiriman_barangjadi/create', [PengirimanbarangjadiController::class, 'create'])->name('admin.pengiriman_barangjadi.create');
    Route::get('/admin/pengiriman_barangjadi/pengiriman_pemesanan', [PengirimanbarangjadiController::class, 'pengiriman_pemesanan'])->name('admin.pengiriman_barangjadi.pengiriman_pemesanan');
    Route::post('admin/pengiriman_barangjadi/pengiriman_pemesanan', [PengirimanbarangjadiController::class, 'SimpanPengirimanpemesanan'])->name('pengiriman_barangjadi.pengirimanpemesanan.simpan');
    // Route::get('pengiriman_barangjadi/{id}', [PengirimanbarangjadiController::class, 'showPesanan'])->name('pengiriman_barangjadi.showpesanan');


    Route::resource('pengiriman_barangjadipesanan', \App\Http\Controllers\Admin\PengirimanbarangjadipesananController::class);
    Route::get('admin/pengiriman_barangjadipesanan/create', [PengirimanbarangjadipesananController::class, 'create'])->name('admin.pengiriman_barangjadipesanan.create');
    Route::get('/pengiriman_barangjadipesanan/{id}/print', [PengirimanbarangjadipesananController::class, 'print'])->name('pengiriman_barangjadipesanan.print');

    Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class);
    Route::get('/inquery_pengirimanbarangjadi/{id}/print', [Inquery_pengirimanbarangjadiController::class, 'print'])->name('inquery_pengirimanbarangjadi.print');
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);
   
    Route::resource('inquery_pengirimanpesanan', \App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class);
    Route::get('/inquery_pengirimanpesanan/{id}/print', [Inquery_pengirimanpesananController::class, 'print'])->name('inquery_pengirimanpesanan.print');

    Route::resource('laporan_pengirimanbarangjadi', \App\Http\Controllers\Admin\Laporan_pengirimanbarangjadiController::class);
    Route::get('print', [\App\Http\Controllers\Admin\Laporan_pengirimanbarangjadiController::class, 'printReport']);

    Route::resource('laporan_pengirimanpesanan', \App\Http\Controllers\Admin\Laporan_pengirimanpesananController::class);
    Route::get('printpesanan', [\App\Http\Controllers\Admin\Laporan_pengirimanpesananController::class, 'printReport']);

    Route::resource('retur_barangjadi', \App\Http\Controllers\Admin\ReturbarangjadiController::class);

    Route::resource('inquery_returbarangjadi', \App\Http\Controllers\Admin\Inquery_returbarangjadiController::class);
    Route::get('inquery_returbarangjadi/unpost_retur/{id}', [\App\Http\Controllers\Admin\Inquery_returbarangjadiController::class, 'unpost_retur']);
    Route::get('inquery_returbarangjadi/posting_retur/{id}', [\App\Http\Controllers\Admin\Inquery_returbarangjadiController::class, 'posting_retur']);
    Route::get('/inquery_returbarangjadi/{id}/print', [Inquery_returbarangjadiController::class, 'print'])->name('inquery_returbarangjadi.print');

    Route::resource('pemindahan_barangjadi', \App\Http\Controllers\Admin\Pemindahan_barangjadiController::class);


    Route::resource('pemusnahan_barangjadi', \App\Http\Controllers\Admin\PemusnahanbarangjadiController::class);
    Route::get('/getReturData', [PemusnahanbarangjadiController::class, 'getReturData'])->name('getReturData');
    Route::get('admin/getProductsByKodeRetur/{kodeRetur}', [PemusnahanbarangjadiController::class, 'getProductsByKodeRetur']);
    Route::get('admin/get-products-by-kode-retru/{kodeRetur}', [PemusnahanbarangjadiController::class, 'getProductsByKodeRetur'])->name('getProductsByKodeRetur');

    Route::resource('inquery_pemusnahanbarangjadi', \App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController::class);
    Route::get('inquery_pemusnahanbarangjadi/unpost_pemusnahan/{id}', [\App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController::class, 'unpost_pemusnahan']);
    Route::get('inquery_pemusnahanbarangjadi/posting_pemusnahan/{id}', [\App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController::class, 'posting_pemusnahan']);
    Route::get('/inquery_pemusnahanbarangjadi/{id}/print', [Inquery_pemusnahanbarangjadiController::class, 'print'])->name('inquery_pemusnahanbarangjadi.print');

    Route::resource('estimasi_produksi', \App\Http\Controllers\Admin\EstimasiproduksiController::class);

    Route::resource('inquery_estimasiproduksi', \App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class);

    Route::resource('laporan_estimasiproduksi', \App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class);
    Route::get('printReport', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReport']);
    Route::get('printReportPermintaan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPermintaan']);
    Route::get('printReportPermintaantoko', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPermintaantoko']);
    Route::get('printReportPemesanan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPemesanan']);
    Route::get('printReportPemesanantoko', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportPemesanantoko']);
    Route::get('printReportAll', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'printReportAll']);
    Route::get('indexpemesanan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'indexpemesanan']);
    Route::get('indexpermintaan', [\App\Http\Controllers\Admin\Laporan_estimasiproduksiController::class, 'indexpermintaan']);

    Route::resource('inquery_pemindahanbarangjadi', \App\Http\Controllers\Admin\Inquery_pemindahanbarangjadiController::class);
    Route::get('/inquery_pemindahanbarangjadi/{id}/print', [Inquery_pemindahanbarangjadiController::class, 'print'])->name('inquery_pemindahanbarangjadi.print');
    Route::get('/inquery_pemindahanbarangjadi/{id}/print', [Inquery_pemindahanbarangjadiController::class, 'print'])->name('inquery_pemindahanbarangjadi.print');

    Route::resource('laporan_returbarangjadi', \App\Http\Controllers\Admin\Laporan_returbarangjadiController::class);
    Route::get('printReportretur', [\App\Http\Controllers\Admin\Laporan_returbarangjadiController::class, 'printReportretur']);

    Route::resource('laporan_pemindahanbarangjadi', \App\Http\Controllers\Admin\Laporan_pemindahanbarangjadiController::class);
    Route::get('printReportpemindahan/{id}', [\App\Http\Controllers\Admin\Laporan_pemindahanbarangjadiController::class, 'printReportpemindahan']);

    Route::resource('laporan_pemusnahanbarangjadi', \App\Http\Controllers\Admin\Laporan_pemusnahanbarangjadiController::class);
    Route::get('printReportpemusnahan', [\App\Http\Controllers\Admin\Laporan_pemusnahanbarangjadiController::class, 'printReportpemusnahan']);

    Route::resource('inquery_hasilpenjualan', \App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class, 'barangKeluar']);
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class, 'barangRetur']);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Inquery_hasilpenjualanController::class, 'barangKeluar'])->name('barangKeluar');

    Route::resource('laporan_hasilpenjualan', \App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class);
    Route::get('barangMasukpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangMasukpesanan']);
    Route::get('barangMasukpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangMasukpesanan'])->name('barangMasukpesanan');
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluar']);
    Route::get('barangKeluarRinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluarRinci']);
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangRetur']);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluar'])->name('barangKeluar');
    Route::get('barangKeluarRinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluarRinci'])->name('barangKeluarRinci');
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangRetur'])->name('barangRetur');
    Route::get('/print-report', [Laporan_hasilpenjualanController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBm', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBm']);
    Route::get('printLaporanBmpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBmpesanan']);
    Route::get('printLaporanBK', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBK']);
    Route::get('printLaporanBKrinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBKrinci']);
    Route::get('printLaporanBR', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBR']);
    Route::get('printExcelBm', [Laporan_hasilpenjualanController::class, 'exportExcel'])->name('printExcelBm');
    Route::get('printExcelBk', [Laporan_hasilpenjualanController::class, 'exportExcelBK'])->name('printExcelBk');
    Route::get('printExcelBr', [Laporan_hasilpenjualanController::class, 'exportExcelBR'])->name('printExcelBr');

    //TOKO SLAWI
    Route::resource('stok_tokoslawi', \App\Http\Controllers\Admin\Stok_tokoslawiController::class);

    Route::resource('pengiriman_tokoslawi', \App\Http\Controllers\Admin\Pengiriman_tokoslawiController::class);
    Route::get('pengiriman_tokoslawi/unpost_pengiriman/{id}', [\App\Http\Controllers\Admin\Pengiriman_tokoslawiController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokoslawi/posting_pengiriman/{id}', [\App\Http\Controllers\Admin\Pengiriman_tokoslawiController::class, 'posting_pengiriman']);

    Route::resource('retur_tokoslawi', \App\Http\Controllers\Admin\Retur_tokoslawiController::class);


    Route::resource('inquery_perubahanharga', \App\Http\Controllers\Admin\Inquery_perubahanhargaController::class);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Admin\Metode_pembayaranController::class);
    
    Route::resource('setoran_pelunasan', \App\Http\Controllers\Admin\Setoran_pelunasanController::class);
    Route::post('/get-penjualan-kotor', [Setoran_pelunasanController::class, 'getdata1'])->name('getdata1');

});


Route::middleware('toko_banjaran')->prefix('toko_banjaran')->group(function () {
    // Route::get('/', [\App\Http\Controllers\Toko_slawi\DashboardController::class, 'index']);
    // Route::resource('karyawan', \App\Http\Controllers\Toko_slawi\KaryawanController::class);
    Route::get('/', [\App\Http\Controllers\Toko_banjaran\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_banjaran\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_banjaran\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');

    Route::resource('produk', \App\Http\Controllers\Toko_banjaran\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController::class);
    Route::get('/toko_banjaran/pemesanan_produk/cetak/{id}', [PemesananprodukbanjaranController::class, 'cetak'])->name('toko_banjaran.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananprodukbanjaranController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukbanjaranController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_banjaran/pemesanan_produk/update/{id}', [PemesananprodukbanjaranController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_banjaran/pemesanan_produk/cetak-pdf{id}', [PemesananprodukbanjaranController::class, 'cetakPdf'])->name('toko_banjaran.pemesanan_produk.cetak-pdf');
    Route::delete('toko_banjaran/pemesanan_produk/{id}', [PemesananprodukbanjaranController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_banjaran/pemesanan_produk/{id}/cetak', [PemesananprodukbanjaranController::class, 'cetak'])->name('toko_banjaran.pemesanan_produk.cetak');
    Route::get('/toko_banjaran/pemesanan-produk/create', [PemesananprodukbanjaranController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class);
    Route::get('/toko_banjaran/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_banjaran.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukbnjr', \App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController::class);
    Route::get('print_pemesananbnjr', [\App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController::class, 'print_pemesanan']);
    Route::get('printReportpemesananbnjr', [Laporan_pemesananprodukController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalbnjr', [\App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalbnjr', [Laporan_pemesananprodukbanjaranController::class, 'printReportpemesananglobalbnjr'])->name('printReportpemesananglobalbnjr');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController::class);
    Route::get('/toko_banjaran/penjualan_produk/cetak/{id}', [PenjualanprodukbanjaranController::class, 'cetak'])->name('toko_banjaran.penjualan_produk.cetak');
    Route::get('/toko_banjaran/penjualan_produk/cetak-pdf{id}', [PenjualanprodukbanjaranController::class, 'cetakPdf'])->name('toko_banjaran.penjualan_produk.cetak-pdf');
    Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('toko_banjaran/penjualan_produk/create', [PenjualanprodukbanjaranController::class, 'create'])->name('toko_banjaran.penjualan_produk.create');
    Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukbanjaranController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukbanjaranController::class, 'fetchDataByKode'])->name('toko_banjaran.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukbanjaranController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController::class, 'metode']);
    Route::post('admin/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukbanjaranController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukbanjaranController::class, 'fetchProductData'])->name('toko_banjaran.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukbanjaranController::class, 'searchProduct']);
    



    Route::resource('pelunasan_pemesanan', \App\Http\Controllers\Toko_banjaran\PelunasanpemesananController::class);
    Route::get('/toko_banjaran/pelunasan_pemesanan/cetak-pdf{id}', [PelunasanpemesananController::class, 'cetakPdf'])->name('toko_banjaran.pelunasan_pemesanan.cetak-pdf');
    Route::get('/pelunasan-pemesanan/cetak/{id}', [PelunasanpemesananController::class, 'cetak'])->name('toko_banjaran.pelunasan_pemesanan.cetak');

    Route::resource('inquery_penjualanprodukbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class);
    Route::get('/toko_banjaran/inquery_penjualanprodukbanajran', [Inquery_penjualanprodukbanjaranController::class, 'index'])->name('toko_banjaran.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukbanjaran/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukbanjaran/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController::class, 'posting_penjualanproduk']);
    Route::get('/toko_banjaran/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukbanjaranController::class, 'cetakPdf'])->name('toko_banjaran.inquery_penjualanproduk.cetak-pdf');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_banjaran\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController::class);
    Route::post('toko_banjaran/permintaan_produk', [PermintaanprodukbanjaranController::class, 'store']);
    Route::get('toko_banjaran/permintaan_produk', [PermintaanprodukbanjaranController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukbanjaranController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_banjaran\PermintaanprodukbanjaranController::class, 'posting_permintaanproduk']);
    // Route::delete('Toko_banjaran/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('Toko_banjaran.permintaan_produk.destroy');
    Route::post('toko_banjaran/permintaan/import', [PermintaanProdukBanjaranController::class, 'import'])->name('permintaan.import');


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_banjaran\Inquery_permintaanprodukController::class);
  

    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'printReportRinci']);

    // Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class);
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_banjaran\Metode_pembayaranController::class);

    //TOKO SLAWI
    Route::resource('stok_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Stok_tokobanjaranController::class);
    Route::resource('stokpesanan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Stokpesanan_tokobanjaranController::class);


    Route::resource('pengiriman_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class);
    Route::get('pengiriman_tokobanjaran/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokobanjaran/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokobanjaran/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokobanjaran/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokobanjaran/{id}/print', [Pengiriman_tokobanjaranController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('toko_banjaran/pengiriman_barangjadi/index', [Pengiriman_tokobanjaranController::class, 'index'])->name('toko_banjaran.pengiriman_tokobanjaran.index');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/pengiriman_pemesanan', [Pengiriman_tokobanjaranController::class, 'pengiriman_pemesanan'])->name('toko_banjaran.pengiriman_tokobanjaran.pengiriman_pemesanan');

    Route::resource('retur_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Retur_tokobanjaranController::class);
  
    Route::resource('inquery_returbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController::class);
    Route::get('inquery_returbanjaran/unpost_retur/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController::class, 'unpost_retur']);
    Route::get('inquery_returbanjaran/posting_retur/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController::class, 'posting_retur']);
    Route::get('/inquery_returbanjaran/{id}/print', [Inquery_returbanjaranController::class, 'print'])->name('inquery_returbanjaran.print');


    Route::resource('pemindahan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pemindahan_tokobanjaranController::class);

    Route::resource('inquery_pemindahanbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController::class);
    Route::get('inquery_pemindahanbanjaran/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahanbanjaran/{id}/print', [Inquery_pemindahanbanjaranController::class, 'print'])->name('inquery_pemindahanbanjaran.print');

    Route::resource('laporan_pemindahanbanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_pemindahanbanjaranController::class);
    Route::get('/Toko_banjaran/print_report', [Laporan_pemindahanbanjaranController::class, 'printReport'])->name('print.report');

    Route::resource('laporan_stoktokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class);
    Route::get('printstoktokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'printReport']);

    Route::resource('laporan_pengirimantokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_pengirimantokobanjaranController::class);
    Route::get('printpengirimantokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_pengirimantokobanjaranController::class, 'printReport']);

    Route::resource('setoran_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Setoran_tokobanjaranController::class);
    Route::post('/get-penjualan-kotor', [Setoran_tokobanjaranController::class, 'getdata'])->name('getdata');
    // Route::post('toko_banjaran/setoran_tokobanjaran', [Setoran_tokobanjaranController::class, 'store'])->name('setoran_tokobanjaran.store');

    Route::resource('laporan_setorantokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_setoranpenjualanController::class);
    Route::get('printReportsetoran', [Laporan_setoranpenjualanController::class, 'printReportsetoran'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_depositbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_depositbanjaranController::class);

    Route::resource('laporan_depositbanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_banjaran\Laporan_depositbanjaranController::class, 'printReportsaldo']);
});




// Route::middleware('toko_bumiayu')->prefix('toko_bumiayu')->group(function () {
   
//     Route::get('/', [\App\Http\Controllers\Toko_bumiayu\DashboardController::class, 'index']);

//     Route::resource('pelanggan', \App\Http\Controllers\Toko_bumiayu\PelangganController::class);
//     Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_bumiayu\PelangganController::class, 'getpelanggan']);
//     Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');

//     Route::resource('produk', \App\Http\Controllers\Toko_bumiayu\ProdukController::class);

//     Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_bumiayu\PemesananprodukbumiayuController::class);
//     Route::get('/toko_bumiayu/pemesanan_produk/cetak/{id}', [PemesananprodukbumiayuController::class, 'cetak'])->name('toko_bumiayu.pemesanan_produk.cetak');
//     Route::get('/get-customer/{kode}', [PemesananprodukbumiayuController::class, 'getCustomerByKode']);
//     Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_bumiayu\PemesananprodukbumiayuController::class, 'pelanggan']);
//     Route::get('/get-customer-data', [PemesananprodukbumiayuController::class, 'getCustomerData'])->name('get.customer.data');
//     Route::get('/toko_bumiayu/pemesanan_produk/update/{id}', [PemesananprodukbumiayuController::class, 'edit'])->name('pemesanan_produk.update');
//     Route::get('/toko_bumiayu/pemesanan_produk/cetak-pdf{id}', [PemesananprodukbumiayuController::class, 'cetakPdf'])->name('toko_bumiayu.pemesanan_produk.cetak-pdf');
//     Route::delete('toko_bumiayu/pemesanan_produk/{id}', [PemesananprodukbumiayuController::class, 'destroy'])->name('pemesanan_produk.destroy');
//     Route::get('/toko_bumiayu/pemesanan_produk/{id}/cetak', [PemesananprodukbumiayuController::class, 'cetak'])->name('toko_bumiayu.pemesanan_produk.cetak');
//     Route::get('/toko_bumiayu/pemesanan-produk/create', [PemesananprodukbumiayuController::class, 'create'])->name('pemesanan-produk.create');

//     Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_bumiayu\Inquery_pemesananprodukController::class);
//     Route::get('/Toko_bumiayu/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('Toko_bumiayu.inquery_pemesananproduk.index');
//     Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
//     Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

//     Route::resource('laporan_pemesananprodukbmy', \App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController::class);
//     Route::get('print_pemesananbmy', [\App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController::class, 'print_pemesanan']);
//     Route::get('printReportpemesananbmy', [Laporan_pemesananprodukController::class, 'printReportPemesanan'])->name('printReportPemesanan');
//     Route::get('indexpemesananglobalbmy', [\App\Http\Controllers\Toko_bumiayu\Laporan_pemesananprodukbumiayuController::class, 'indexpemesananglobal']);
//     Route::get('printReportpemesananglobalbmy', [Laporan_pemesananprodukbanjaranController::class, 'printReportpemesananglobalbmy'])->name('printReportpemesananglobalbmy');

//     Route::resource('penjualan_produk', \App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController::class);
//     Route::get('/toko_bumiayu/penjualan_produk/cetak/{id}', [PenjualanprodukbumiayuController::class, 'cetak'])->name('toko_bumiayu.penjualan_produk.cetak');
//     Route::get('/toko_bumiayu/penjualan_produk/cetak-pdf{id}', [PenjualanprodukbumiayuController::class, 'cetakPdf'])->name('toko_bumiayu.penjualan_produk.cetak-pdf');
//     Route::get('/toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'pelunasan'])->name('toko_bumiayu.penjualan_produk.pelunasan');
//     Route::get('toko_bumiayu/penjualan_produk/create', [PenjualanprodukbumiayuController::class, 'create'])->name('toko_bumiayu.penjualan_produk.create');
//     Route::get('/toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'pelunasan'])->name('toko_bumiayu.penjualan_produk.pelunasan');
//     Route::get('/products/{tokoId}', [PenjualanprodukbumiayuController::class, 'getProductsByToko'])->name('products.byToko');
//     Route::get('/fetch-data-by-kode', [PenjualanprodukbumiayuController::class, 'fetchDataByKode'])->name('toko_bumiayu.penjualan_produk.fetchData');
//     Route::get('/metodepembayaran/{id}', [PenjualanprodukbumiayuController::class, 'getMetodePembayaran']);
//     Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController::class, 'metode']);
//     Route::post('admin/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
//     Route::get('/get-product', [PenjualanprodukbumiayuController::class, 'getProductByKode']);
//     Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukbumiayuController::class, 'fetchProductData'])->name('toko_bumiayu.penjualan_produk.fetchProductData');
//     Route::get('/search-product', [PenjualanprodukbumiayuController::class, 'searchProduct']);
    



//     Route::resource('pelunasan_pemesanan', \App\Http\Controllers\Toko_bumiayu\PelunasanpemesananController::class);
//     Route::get('/toko_bumiayu/pelunasan_pemesanan/cetak-pdf{id}', [PelunasanpemesananController::class, 'cetakPdf'])->name('toko_bumiayu.pelunasan_pemesanan.cetak-pdf');
//     Route::get('/pelunasan-pemesanan/cetak/{id}', [PelunasanpemesananController::class, 'cetak'])->name('toko_bumiayu.pelunasan_pemesanan.cetak');

//     Route::resource('inquery_penjualanprodukbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class);
//     Route::get('/toko_bumiayu/inquery_penjualanprodukbumiayu', [Inquery_penjualanprodukbumiayuController::class, 'index'])->name('toko_bumiayu.inquery_penjualanproduk.index');
//     Route::get('inquery_penjualanprodukbumiayu/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class, 'unpost_penjualanproduk']);
//     Route::get('inquery_penjualanprodukbumiayu/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_penjualanprodukbumiayuController::class, 'posting_penjualanproduk']);
//     Route::get('/toko_bumiayu/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukbumiayuController::class, 'cetakPdf'])->name('toko_bumiayu.inquery_penjualanproduk.cetak-pdf');

//     Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class);
//     Route::get('printReport', [\App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class, 'printReport']);
//     Route::get('printReportglobal', [\App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class, 'printReportglobal']);
//     Route::get('indexglobal', [\App\Http\Controllers\Toko_bumiayu\Laporan_penjualanprodukController::class, 'indexglobal']);

//     Route::resource('permintaan_produk', \App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController::class);
//     Route::post('toko_bumiayu/permintaan_produk', [PermintaanprodukbumiayuController::class, 'store']);
//     Route::get('toko_bumiayu/permintaan_produk', [PermintaanprodukbumiayuController::class, 'show']);
//     Route::get('/permintaan-produk/{id}/print', [PermintaanprodukbumiayuController::class, 'print'])->name('permintaan_produk.print');
//     Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController::class, 'unpost_permintaanproduk']);
//     Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_bumiayu\PermintaanprodukbumiayuController::class, 'posting_permintaanproduk']);
//     // Route::delete('Toko_bumiayu/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('Toko_bumiayu.permintaan_produk.destroy');
//     Route::post('Toko_bumiayu/permintaan/import', [PermintaanprodukbumiayuController::class, 'import'])->name('permintaan.import');


//     Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_bumiayu\Inquery_permintaanprodukController::class);
  

//     Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class);
//     Route::get('printReport1', [\App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class, 'printReport']);
//     Route::get('indexrinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class, 'indexrinci']);
//     Route::get('printReportRinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_permintaanprodukController::class, 'printReportRinci']);

//     // Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Toko_bumiayu\Inquery_pengirimanbarangjadiController::class);
//     Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
//     Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

//     Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_bumiayu\Metode_pembayaranController::class);

//     //TOKO SLAWI
//     Route::resource('stok_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Stok_tokobumiayuController::class);
//     Route::resource('stokpesanan_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Stokpesanan_tokobumiayuController::class);


//     Route::resource('pengiriman_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class);
//     Route::get('pengiriman_tokobumiayu/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'unpost_pengiriman']);
//     Route::get('pengiriman_tokobumiayu/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'posting_pengiriman']);
//     Route::get('pengiriman_tokobumiayu/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'unpost_pengirimanpemesanan']);
//     Route::get('pengiriman_tokobumiayu/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_bumiayu\Pengiriman_tokobumiayuController::class, 'posting_pengirimanpemesanan']);
//     Route::get('/pengiriman_tokobumiayu/{id}/print', [Pengiriman_tokobumiayuController::class, 'print'])->name('pengiriman_tokobumiayu.print');
//     Route::get('Toko_bumiayu/pengiriman_barangjadi/index', [Pengiriman_tokobumiayuController::class, 'index'])->name('toko_bumiayu.pengiriman_tokobumiayu.index');
//     Route::get('/Toko_bumiayu/pengiriman_tokobumiayu/pengiriman_pemesanan', [Pengiriman_tokobumiayuController::class, 'pengiriman_pemesanan'])->name('toko_bumiayu.pengiriman_tokobumiayu.pengiriman_pemesanan');

//     Route::resource('retur_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Retur_tokobumiayuController::class);
  
//     Route::resource('inquery_returbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController::class);
//     Route::get('inquery_returbumiayu/unpost_retur/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController::class, 'unpost_retur']);
//     Route::get('inquery_returbumiayu/posting_retur/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_returbumiayuController::class, 'posting_retur']);
//     Route::get('/inquery_returbumiayu/{id}/print', [Inquery_returbumiayuController::class, 'print'])->name('inquery_returbumiayu.print');


//     Route::resource('pemindahan_tokobanjaran', \App\Http\Controllers\Toko_bumiayu\Pemindahan_tokobanjaranController::class);

//     Route::resource('inquery_pemindahanbanjaran', \App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbanjaranController::class);
//     Route::get('inquery_pemindahanbanjaran/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbanjaranController::class, 'posting_pemindahan']);
//     Route::get('/inquery_pemindahanbanjaran/{id}/print', [Inquery_pemindahanbanjaranController::class, 'print'])->name('inquery_pemindahanbanjaran.print');

//     Route::resource('laporan_pemindahanbanjaran', \App\Http\Controllers\Toko_bumiayu\Laporan_pemindahanbanjaranController::class);
//     Route::get('/Toko_bumiayu/print_report', [Laporan_pemindahanbanjaranController::class, 'printReport'])->name('print.report');

//     Route::resource('laporan_stoktokobanjaran', \App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobanjaranController::class);
//     Route::get('printstoktokobanjaran', [\App\Http\Controllers\Toko_bumiayu\Laporan_stoktokobanjaranController::class, 'printReport']);

//     Route::resource('laporan_pengirimantokobanjaran', \App\Http\Controllers\Toko_bumiayu\Laporan_pengirimantokobanjaranController::class);
//     Route::get('printpengirimantokobanjaran', [\App\Http\Controllers\Toko_bumiayu\Laporan_pengirimantokobanjaranController::class, 'printReport']);

//     Route::resource('laporan_setorantokobanjaran', \App\Http\Controllers\Toko_bumiayu\Laporan_setoranpenjualanController::class);
//     Route::get('printReportsetoran', [Laporan_setoranpenjualanController::class, 'printReportsetoran'])->name('laporan_setoranpenjualan.print');

//     Route::resource('inquery_depositbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_depositbumiayuController::class);

//     Route::resource('laporan_depositbumiayu', \App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class);
//     Route::get('indexrinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'indexrinci']);
//     Route::get('indexsaldo', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'indexsaldo']);
//     Route::get('saldo', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'saldo']);
//     Route::get('printReportdeposit', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'printReportdeposit']);
//     Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'printReportdepositrinci']);
//     Route::get('printReportsaldo', [\App\Http\Controllers\Toko_bumiayu\Laporan_depositbumiayuController::class, 'printReportsaldo']);
// });







