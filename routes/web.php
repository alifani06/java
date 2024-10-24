<?php

use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SlawiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\Admin\AddpelangganController;
use App\Http\Controllers\Admin\EstimasiproduksiController;
use App\Http\Controllers\Admin\HargajualController;
use App\Http\Controllers\Admin\Inquery_estimasiproduksiController;
use App\Http\Controllers\Admin\Inquery_hasilpenjualanController;
use App\Http\Controllers\Admin\Inquery_pemesananprodukController;
use App\Http\Controllers\Admin\Inquery_pemindahanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pemusnahanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController;
use App\Http\Controllers\Admin\Inquery_pengirimanbarangjadipesananController;
use App\Http\Controllers\Admin\Inquery_pengirimanpesananController;
use App\Http\Controllers\Admin\Inquery_penjualanprodukController;
use App\Http\Controllers\Admin\Inquery_returbarangjadiController;
use App\Http\Controllers\Admin\Inquery_setoranpelunasanController;
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
use App\Http\Controllers\Admin\SurathasilproduksiController;
use App\Http\Controllers\Admin\SuratperintahproduksiController;
use App\Http\Controllers\KategoriDropdownController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Toko_banjaran\Inquery_pemindahanbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_penjualanprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_returbanjaranController;
use App\Http\Controllers\Toko_banjaran\Inquery_setorantunaibanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_pemesananprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_pemindahanbanjaranController;
use App\Http\Controllers\Toko_banjaran\Laporan_setoranpenjualanController;
use App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController;
use App\Http\Controllers\Toko_banjaran\PelunasanpemesananController;
use App\Http\Controllers\Toko_banjaran\PemesananprodukbanjaranController;
use App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController;
use App\Http\Controllers\Toko_banjaran\Pengirimanpemesanan_tokobanjaranController;
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
use App\Http\Controllers\Toko_pemalang\Inquery_pemindahanpemalangController;
use App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController;
use App\Http\Controllers\Toko_pemalang\Inquery_setorantunaipemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_pemindahanpemalangController;
use App\Http\Controllers\Toko_pemalang\Laporan_setoranpenjualanpmlController;
use App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController;
use App\Http\Controllers\Toko_pemalang\PelunasanpemesananPmlController;
use App\Http\Controllers\Toko_pemalang\PemesananprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\Pengirimanpemesanan_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\PenjualanprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController;
use App\Http\Controllers\Toko_pemalang\Setoran_tokopemalangController;
use App\Http\Controllers\Toko_pemalang\Stok_tokopemalangController;
use App\Http\Controllers\Toko_slawi\Inquery_pemindahanslawiController;
use App\Http\Controllers\Toko_slawi\Inquery_penjualanprodukController as Toko_slawiInquery_penjualanprodukController;
use App\Http\Controllers\Toko_slawi\Inquery_returslawiController;
use App\Http\Controllers\Toko_slawi\Laporan_pemindahanslawiController;
use App\Http\Controllers\Toko_slawi\Pengiriman_tokoslawiController as Toko_slawiPengiriman_tokoslawiController;
use App\Http\Controllers\Toko_slawi\Retur_tokoslawiController;
use App\Http\Controllers\Toko_tegal\Inquery_pemindahantegalController;
use App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController;
use App\Http\Controllers\Toko_tegal\Inquery_returtegalController;
use App\Http\Controllers\Toko_tegal\Inquery_setorantunaitegalController;
use App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController;
use App\Http\Controllers\Toko_tegal\Laporan_pemindahantegalController;
use App\Http\Controllers\Toko_tegal\Laporan_setoranpenjualantglController;
use App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController;
use App\Http\Controllers\Toko_tegal\PelunasanpemesananTglController;
use App\Http\Controllers\Toko_tegal\PemesananproduktegalController;
use App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController;
use App\Http\Controllers\Toko_tegal\PenjualanproduktegalController;
use App\Http\Controllers\Toko_tegal\PermintaanproduktegalController;
use App\Http\Controllers\Toko_tegal\Setoran_tokotegalController;
use App\Http\Controllers\Toko_tegal\Stok_tokotegalController;
use App\Models\Pengiriman_barangjadi;
use App\Models\Pengiriman_tokopemalang;
use App\Models\Pengiriman_tokotegal;
use App\Models\Pengirimanpemesanan_tokotegal;
use App\Models\Setoran_penjualan;
use App\Models\Stok_retur;
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

Route::get('produk/{kode}', [\App\Http\Controllers\ProdukController::class, 'detail']);


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
    // Route::post('admin/pelanggan/import', [PelangganController::class, 'import'])->name('pelanggan.import');
    Route::post('admin/pelanggan/import', [PelangganController::class, 'importPelanggan'])->name('pelanggan.import');

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
    Route::post('admin/produk/import', [ProdukController::class, 'import'])->name('produk.import');
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');        
    Route::get('/subklasifikasi/fetch', [ProdukController::class, 'fetch'])->name('subklasifikasi.fetch');
    Route::get('admin/produk/{id}/print', [ProdukController::class, 'print'])->name('produk.print');
    Route::get('admin/produk/{id}/cetak_barcode', [ProdukController::class, 'cetak_barcode'])->name('produk.cetak_barcode');




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
    Route::get('/inquery_penjualanproduk/{id}/edit', [Inquery_penjualanprodukController::class, 'edit'])->name('inquery_penjualanproduk.edit');
    Route::post('/inquery_penjualanproduk/{id}/update', [Inquery_penjualanprodukController::class, 'update'])->name('inquery_penjualanproduk.update');
    Route::post('/hapus-produk', [Inquery_penjualanprodukController::class, 'hapusProduk'])->name('hapus.produk');
    Route::get('/metodepembayaran/{id}', [Inquery_penjualanprodukController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Admin\Inquery_penjualanprodukController::class, 'metode']);

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
   
    Route::resource('stok_hasilproduksi', \App\Http\Controllers\Admin\Stok_hasilproduksiController::class);

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
    Route::post('admin/pengiriman_barangjadi/store', [PengirimanBarangJadiController::class, 'store'])->name('admin.pengiriman_barangjadi.store');


    Route::resource('pengiriman_barangjadipesanan', \App\Http\Controllers\Admin\PengirimanbarangjadipesananController::class);
    Route::get('admin/pengiriman_barangjadipesanan/create', [PengirimanbarangjadipesananController::class, 'create'])->name('admin.pengiriman_barangjadipesanan.create');
    Route::get('/pengiriman_barangjadipesanan/{id}/print', [PengirimanbarangjadipesananController::class, 'print'])->name('pengiriman_barangjadipesanan.print');

    Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class);
    Route::get('/inquery_pengirimanbarangjadi/{id}/print', [Inquery_pengirimanbarangjadiController::class, 'print'])->name('inquery_pengirimanbarangjadi.print');
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);
    Route::get('admin/inquery_pengirimanbarangjadi/{id}/cetak_barcode', [Inquery_pengirimanbarangjadiController::class, 'cetak_barcode'])->name('inquery_pengirimanbarangjadi.cetak_barcode');
    Route::get('admin/inquery_pengirimanbarangjadi', [Inquery_pengirimanbarangjadiController::class, 'index'])->name('admin.inquery_pengirimanbarangjadi.index');
    Route::delete('inquery_pengirimanbarangjadi/deleteprodukpengiriman/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanbarangjadiController::class, 'deleteprodukpengiriman']);
    Route::post('/inquery_pengirimanbarangjadi/cetak_banyak_barcode', [Inquery_pengirimanbarangjadiController::class, 'cetak_banyak_barcode'])->name('inquery_pengirimanbarangjadi.cetak_banyak_barcode');
    Route::get('/admin/inquery_pengirimanbarangjadi/print_qr/{id}', [Inquery_pengirimanbarangjadiController::class, 'showPrintQr'])->name('inquery_pengirimanbarangjadi.print_qr');

    Route::resource('inquery_pengirimanpesanan', \App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class);
    Route::get('/inquery_pengirimanpesanan/{id}/print', [Inquery_pengirimanpesananController::class, 'print'])->name('inquery_pengirimanpesanan.print');
    Route::get('admin/inquery_pengirimanpesanan/{id}/cetak_barcodepesanan', [Inquery_pengirimanpesananController::class, 'cetak_barcodepesanan'])->name('inquery_pengirimanpesanan.cetak_barcodepesanan');
    Route::get('admin/inquery_pengirimanpesanan', [Inquery_pengirimanpesananController::class, 'index'])->name('admin.inquery_pengirimanpesanan.index');
    Route::get('inquery_pengirimanpesanan/unpost_pengirimanpesanan/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class, 'unpost_pengirimanpesanan']);
    Route::get('inquery_pengirimanpesanan/posting_pengirimanpesanan/{id}', [\App\Http\Controllers\Admin\Inquery_pengirimanpesananController::class, 'posting_pengirimanpesanan']);
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
    Route::get('admin/estimasi_produksi/{estimasi_produksi}', [EstimasiProduksiController::class, 'show'])->name('estimasi_produksi.show');
    Route::get('admin/estimasi_produksi/{id}/edit', [EstimasiProduksiController::class, 'edit'])->name('estimasi_produksi.edit');
    Route::get('/estimasi_produksi/{id}/print', [EstimasiproduksiController::class, 'print'])->name('estimasi_produksi.print');
    Route::delete('estimasi_produksi/deletedetailpermintaan/{id}', [\App\Http\Controllers\Admin\EstimasiproduksiController::class, 'deletedetailpermintaan']);
    Route::post('admin/estimasi_produksi', [EstimasiproduksiController::class, 'store'])->name('estimasi_produksi.store');



    Route::resource('surat_perintahproduksi', \App\Http\Controllers\Admin\SuratperintahproduksiController::class);
    // Route::get('admin/printReportestimasi', [SuratperintahproduksiController::class, 'printReportestimasi'])->name('printReportestimasi');
    Route::get('printReportestimasi', [\App\Http\Controllers\Admin\SuratperintahproduksiController::class, 'printReportestimasi']);
    // Route::get('/estimasi-produksi/cetak', [EstimasiProduksiController::class, 'cetak'])->name('estimasi.produksi.cetak');
    Route::get('printReportestimasirinci', [\App\Http\Controllers\Admin\SuratperintahproduksiController::class, 'printReportestimasirinci']);

    Route::resource('surat_hasilproduksi', \App\Http\Controllers\Admin\SurathasilproduksiController::class);
    Route::post('/save-realisasi', [SurathasilproduksiController::class, 'saveRealisasi'])->name('saveRealisasi');
    Route::get('/surathasilproduksi/{id}', [SurathasilproduksiController::class, 'show'])->name('surathasilproduksi.show');
    Route::get('/hasilproduksi/{id}/print', [SurathasilproduksiController::class, 'print'])->name('hasilproduksi.print');

    Route::resource('inquery_hasilproduksi', \App\Http\Controllers\Admin\Inquery_hasilproduksiController::class);


    Route::resource('inquery_estimasiproduksi', \App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class);
    Route::delete('admin/inquery_estimasiproduksi/{id}', [Inquery_estimasiproduksiController::class, 'destroy'])->name('admin.inquery_estimasiproduksi.destroy');
    Route::get('inquery_estimasiproduksi/unpost_estimasiproduksi/{id}', [\App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class, 'unpost_estimasiproduksi']);
    Route::get('inquery_estimasiproduksi/posting_estimasiproduksi/{id}', [\App\Http\Controllers\Admin\Inquery_estimasiproduksiController::class, 'posting_estimasiproduksi']);

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
    Route::get('barangMasuksemua', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangMasuksemua'])->name('barangMasuksemua');
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluar']);
    Route::get('barangKeluarRinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluarRinci']);
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangRetur']);
    Route::get('barangKeluar', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluar'])->name('barangKeluar');
    Route::get('barangKeluarRinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangKeluarRinci'])->name('barangKeluarRinci');
    Route::get('barangRetur', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'barangRetur'])->name('barangRetur');
    Route::get('/print-report', [Laporan_hasilpenjualanController::class, 'printReport'])->name('print.report');
    Route::get('printLaporanBm', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBm']);
    Route::get('printLaporanBmpesanan', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBmpesanan']);
    Route::get('printLaporanBmsemua', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBmsemua']);
    Route::get('printLaporanBK', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBK']);
    Route::get('printLaporanBKrinci', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBKrinci']);
    Route::get('printLaporanBR', [\App\Http\Controllers\Admin\Laporan_hasilpenjualanController::class, 'printLaporanBR']);
    Route::get('printExcelBm', [Laporan_hasilpenjualanController::class, 'exportExcel'])->name('printExcelBm');
    Route::get('printExcelBk', [Laporan_hasilpenjualanController::class, 'exportExcelBK'])->name('printExcelBk');
    Route::get('printExcelBr', [Laporan_hasilpenjualanController::class, 'exportExcelBR'])->name('printExcelBr');
    Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_hasilpenjualanController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');
    

    Route::resource('setoran_pelunasan', \App\Http\Controllers\Admin\Setoran_pelunasanController::class);
    Route::post('/get-penjualan-kotor', [Setoran_pelunasanController::class, 'getdata1'])->name('getdata1');
    Route::get('/print-penjualan-kotor', [Setoran_pelunasanController::class, 'printPenjualanKotor'])->name('print.penjualan.kotor');
    Route::get('/print-diskon-penjualan', [Setoran_pelunasanController::class, 'printDiskonPenjualan'])->name('print.diskon.penjualan');
    Route::get('/print-deposit-keluar', [Setoran_pelunasanController::class, 'printDepositKeluar'])->name('print.deposit.keluar');
    Route::post('/setoran_pelunasan/store', [Setoran_pelunasanController::class, 'store'])->name('setoran_pelunasan.store');
    Route::post('/setoran_pelunasan/update-status', [Setoran_pelunasanController::class, 'updateStatus'])->name('setoran_pelunasan.update_status');

    Route::resource('inquery_setoranpelunasan', \App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class);
    Route::get('inquery_setoranpelunasan/unpost_setorantunai/{id}', [\App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class, 'unpost_setorantunai']);
    Route::get('inquery_setoranpelunasan/posting_setorantunai/{id}', [\App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class, 'posting_setorantunai']);
    Route::get('inquery_setoranpelunasan/approve_setorantunai/{id}', [\App\Http\Controllers\Admin\Inquery_setoranpelunasanController::class, 'approve_setorantunai']);
    Route::get('/admin/inquery_setoranpelunasan/{id}/print', [Inquery_setoranpelunasanController::class, 'print'])->name('inquery_setoranpelunasan.print');
    Route::get('/inquery_setoranpelunasan/edit/{id}', [Inquery_setoranpelunasanController::class, 'edit'])->name('setoranpelunasan.edit');
    Route::post('/inquery_setoranpelunasan/update-status', [Inquery_setoranpelunasanController::class, 'updateStatus'])->name('inquery_setoranpelunasan.update_status');

    Route::resource('grafik_penjualan', \App\Http\Controllers\Admin\Grafik_penjualanController::class);

    Route::resource('inquery_perubahanharga', \App\Http\Controllers\Admin\Inquery_perubahanhargaController::class);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Admin\Metode_pembayaranController::class);
    




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
    // Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukbanjaranController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukbanjaranController::class, 'fetchDataByKode'])->name('toko_banjaran.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukbanjaranController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_banjaran\PenjualanprodukbanjaranController::class, 'metode']);
    Route::post('toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukbanjaranController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukbanjaranController::class, 'fetchProductData'])->name('toko_banjaran.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukbanjaranController::class, 'getProduk']);
    Route::get('/get-produks', [PenjualanprodukbanjaranController::class, 'getProduks']);
    Route::get('/produk/search', [PenjualanprodukbanjaranController::class, 'search'])->name('produk.search');
    Route::get('/cari-produk', [PenjualanprodukbanjaranController::class, 'cariProduk1'])->name('cari.produk');
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

    Route::resource('inquery_pelunasanbanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_pelunasanbanjaranController::class);


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_banjaran\Inquery_permintaanprodukController::class);
  

    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_banjaran\Laporan_permintaanprodukController::class, 'printReportRinci']);

    // Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class);
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_banjaran\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_banjaran\Metode_pembayaranController::class);

 
    Route::resource('stok_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Stok_tokobanjaranController::class);
    Route::delete('/toko_banjaran/stok_tokobanjaran/deleteAll', [Stok_tokobanjaranController::class, 'deleteAll'])->name('stok_tokobanjaran.deleteAll');
    Route::post('toko_banjaran/stok_tokobanjaran/import', [Stok_tokobanjaranController::class, 'import'])->name('stok_tokobanjaran.import');
    Route::get('toko_banjaran/stok_tokobanjaran/{id}/edit', [Stok_tokobanjaranController::class, 'edit'])->name('stok_tokobanjaran.edit');

    Route::put('toko_banjaran/stok_tokobanjaran/{id}', [Stok_tokobanjaranController::class, 'update'])->name('stok_tokobanjaran.update');

    Route::resource('stokpesanan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Stokpesanan_tokobanjaranController::class);


    Route::resource('pengiriman_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class);
    Route::get('pengiriman_tokobanjaran/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokobanjaran/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokobanjaran/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokobanjaran/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_banjaran\Pengiriman_tokobanjaranController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokobanjaran/{id}/print', [Pengiriman_tokobanjaranController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/printpemesanan/{id}', [Pengiriman_tokobanjaranController::class, 'printpemesanan'])->name('pengiriman_tokobanjaran.printpemesanan');
    Route::get('toko_banjaran/pengiriman_tokobanjaran/index', [Pengiriman_tokobanjaranController::class, 'index'])->name('toko_banjaran.pengiriman_tokobanjaran.index');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/pengiriman_pemesanan', [Pengiriman_tokobanjaranController::class, 'pengiriman_pemesanan'])->name('toko_banjaran.pengiriman_tokobanjaran.pengiriman_pemesanan');
    Route::get('/toko_banjaran/pengiriman_tokobanjaran/showpemesanan/{id}', [Pengiriman_tokobanjaranController::class, 'showpemesanan'])->name('toko_banjaran.pengiriman_tokobanjaran.showpemesanan');

    Route::resource('pengirimanpemesanan_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Pengirimanpemesanan_tokobanjaranController::class);
    Route::get('/pengirimanpemesanan_tokobanjaran/print/{id}', [Pengirimanpemesanan_tokobanjaranController::class, 'print'])->name('pengirimanpemesanan_tokobanjaran.print');


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
    Route::get('stoktokopesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'stoktokopesananbanjaran']);
    Route::get('printstoktokopesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'printReportstokpesananbanjaran']);
    Route::get('semuastoktokobanjaran', [Laporan_stoktokobanjaranController::class, 'semuaStokTokoBanjaran'])->name('laporan.semuaStokTokoBanjaran');
    Route::get('printsemuastoktokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_stoktokobanjaranController::class, 'printReportsemuastokbanjaran']);


    Route::resource('laporan_pengirimantokobanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_pengirimantokobanjaranController::class);
    Route::get('printpengirimantokobanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_pengirimantokobanjaranController::class, 'printReport']);

    Route::resource('setoran_tokobanjaran', \App\Http\Controllers\Toko_banjaran\Setoran_tokobanjaranController::class);
    Route::post('/get-penjualan-kotor', [Setoran_tokobanjaranController::class, 'getdata'])->name('getdata');
    Route::post('toko_banjaran/setoran_tokobanjaran', [Setoran_tokobanjaranController::class, 'store'])->name('setoran.store');


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
    
    Route::resource('inquery_setorantunaibanjaran', \App\Http\Controllers\Toko_banjaran\Inquery_setorantunaibanjaranController::class);
    Route::get('/toko_banjaran/inquery_setorantunai/{id}/print', [Inquery_setorantunaibanjaranController::class, 'print'])->name('inquery_setorantunai.print');

    Route::resource('laporan_setorantunaibanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_setorantunaibanjaranController::class);

    Route::resource('laporan_returbanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_returbanjaranController::class);
    Route::get('printReportreturbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_returbanjaranController::class, 'printReportreturbanjaran']);

    // Route::resource('laporan_historibanjaran', \App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class);
    // Route::get('barangMasukpesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangMasukpesananbanjaran']);
    // Route::get('barangKeluarbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangKeluarbanjaran']);
    // Route::get('barangKeluarRincibanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangKeluarRincibanjaran']);
    // Route::get('barangReturbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangReturbanjaran']);
    // Route::get('barangKeluarbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangKeluarbanjaran'])->name('barangKeluar');
    // Route::get('barangKeluarRincibanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangKeluarRincibanjaran'])->name('barangKeluarRinci');
    // Route::get('barangReturbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'barangReturbanjaran'])->name('barangRetur');
    // Route::get('/print-report', [Laporan_historibanjaranController::class, 'printReport'])->name('print.report');
    // Route::get('printLaporanBmbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBmbanjaran']);
    // Route::get('printLaporanBmpesananbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBmpesananbanjaran']);
    // Route::get('printLaporanBKbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBKbanjaran']);
    // Route::get('printLaporanBKrincibanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBKrincibanjaran']);
    // Route::get('printLaporanBRbanjaran', [\App\Http\Controllers\Toko_banjaran\Laporan_historibanjaranController::class, 'printLaporanBRbanjaran']);
    // Route::get('printExcelBmbanjaran', [Laporan_historibanjaranController::class, 'exportExcel'])->name('printExcelBmbanjaran');
    // Route::get('printExcelBkbanjaran', [Laporan_historibanjaranController::class, 'exportExcelBK'])->name('printExcelBkbanjaran');
    // Route::get('printExcelBrbanjaran', [Laporan_historibanjaranController::class, 'exportExcelBR'])->name('printExcelBrbanjaran');
    // Route::get('/get-produk-by-klasifikasi/{id}', [Laporan_historibanjaranController::class, 'getByKlasifikasi'])->name('getProdukByKlasifikasi');

});

Route::middleware('toko_tegal')->prefix('toko_tegal')->group(function () {

    Route::get('/', [\App\Http\Controllers\Toko_tegal\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_tegal\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_tegal\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');
    Route::get('admin/pelanggan', [PelangganController::class, 'index'])->name('admin.pelanggan');

    Route::resource('produk', \App\Http\Controllers\Toko_tegal\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_tegal\PemesananproduktegalController::class);
    Route::get('/toko_tegal/pemesanan_produk/cetak/{id}', [PemesananproduktegalController::class, 'cetak'])->name('toko_tegal.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananproduktegalController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_tegal\PemesananproduktegalController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananproduktegalController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_tegal/pemesanan_produk/update/{id}', [PemesananproduktegalController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_tegal/pemesanan_produk/cetak-pdf{id}', [PemesananproduktegalController::class, 'cetakPdf'])->name('toko_tegal.pemesanan_produk.cetak-pdf');
    Route::delete('toko_tegal/pemesanan_produk/{id}', [PemesananproduktegalController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_tegal/pemesanan_produk/{id}/cetak', [PemesananproduktegalController::class, 'cetak'])->name('toko_tegal.pemesanan_produk.cetak');
    Route::get('/toko_tegal/pemesanan-produk/create', [PemesananproduktegalController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_tegal\Inquery_pemesananprodukController::class);
    Route::get('/toko_tegal/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_banjaran.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananproduktgl', \App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController::class);
    Route::get('print_pemesanantgl', [\App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController::class, 'print_pemesanan']);
    Route::get('printReportpemesanantgl', [Laporan_pemesananproduktegalController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobaltgl', [\App\Http\Controllers\Toko_tegal\Laporan_pemesananproduktegalController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobaltgl', [Laporan_pemesananproduktegalController::class, 'printReportpemesananglobaltgl'])->name('printReportpemesananglobaltgl');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_tegal\PenjualanproduktegalController::class);
    Route::get('/toko_tegal/penjualan_produk/cetak/{id}', [PenjualanproduktegalController::class, 'cetak'])->name('toko_tegal.penjualan_produk.cetak');
    Route::get('/toko_tegal/penjualan_produk/cetak-pdf{id}', [PenjualanproduktegalController::class, 'cetakPdf'])->name('toko_tegal.penjualan_produk.cetak-pdf');
    Route::get('/toko_tegal/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'pelunasan'])->name('toko_tegal.penjualan_produk.pelunasan');
    Route::get('toko_tegal/penjualan_produk/create', [PenjualanproduktegalController::class, 'create'])->name('toko_tegal.penjualan_produk.create');
    Route::get('/toko_tegal/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'pelunasan'])->name('toko_tegal.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanproduktegalController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanproduktegalController::class, 'fetchDataByKode'])->name('toko_tegal.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanproduktegalController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_tegal\PenjualanproduktegalController::class, 'metode']);
    Route::post('toko_tegal/penjualan_produk/pelunasan', [PenjualanproduktegalController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanproduktegalController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanproduktegalController::class, 'fetchProductData'])->name('toko_tegal.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanproduktegalController::class, 'searchProduct']);


    Route::resource('pelunasan_pemesananTgl', \App\Http\Controllers\Toko_tegal\PelunasanpemesananTglController::class);
    Route::get('/toko_tegal/pelunasan_pemesananTgl/cetak-pdf{id}', [PelunasanpemesananTglController::class, 'cetakPdf'])->name('toko_tegal.pelunasan_pemesananTgl.cetak-pdf');
    Route::get('/pelunasan-pemesananTgl/cetak/{id}', [PelunasanpemesananTglController::class, 'cetak'])->name('toko_tegal.pelunasan_pemesananTgl.cetak');
    Route::get('/pelunasan_pemesananTgl', [PelunasanPemesananTglController::class, 'index'])->name('toko_tegal.pelunasan_pemesananTgl.index');

    Route::resource('inquery_penjualanproduktegal', \App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class);
    Route::get('/toko_tegal/inquery_penjualanproduktegal', [Inquery_penjualanprodukbanjaranController::class, 'index'])->name('toko_tegal.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanproduktegal/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanproduktegal/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_penjualanproduktegalController::class, 'posting_penjualanproduk']);
    Route::get('/toko_tegal/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanproduktegalController::class, 'cetakPdf'])->name('toko_tegal.inquery_penjualanproduk.cetak-pdf');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_tegal\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_tegal\PermintaanproduktegalController::class);
    Route::post('toko_tegal/permintaan_produk', [PermintaanproduktegalController::class, 'store']);
    Route::get('toko_tegal/permintaan_produk', [PermintaanproduktegalController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanproduktegalController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_tegal\PermintaanproduktegalController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_tegal\PermintaanproduktegalController::class, 'posting_permintaanproduk']);
    Route::post('toko_tegal/permintaan/import', [PermintaanproduktegalController::class, 'import'])->name('permintaan.import');


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_tegal\Inquery_permintaanprodukController::class);
  
    Route::resource('inquery_pelunasantegal', \App\Http\Controllers\Toko_tegal\Inquery_pelunasantegalController::class);


    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_tegal\Laporan_permintaanprodukController::class, 'printReportRinci']);

    // Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Toko_tegal\Inquery_pengirimanbarangjadiController::class);
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_tegal\Metode_pembayaranController::class);

 
    Route::resource('stok_tokotegal', \App\Http\Controllers\Toko_tegal\Stok_tokotegalController::class);
    Route::delete('/toko_tegal/stok_tokotegal/deleteAll', [Stok_tokotegalController::class, 'deleteAll'])->name('stok_tokotegal.deleteAll');
    Route::post('toko_tegal/stok_tokotegal/import', [Stok_tokotegalController::class, 'import'])->name('stok_tokotegal.import');

    Route::resource('stokpesanan_tokotegal', \App\Http\Controllers\Toko_tegal\Stokpesanan_tokotegalController::class);


    Route::resource('pengiriman_tokotegal', \App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class);
    Route::get('pengiriman_tokotegal/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokotegal/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokotegal/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokotegal/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_tegal\Pengiriman_tokotegalController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokotegal/{id}/print', [Pengiriman_tokotegalController::class, 'print'])->name('pengiriman_tokobanjaran.print');
    Route::get('/toko_tegal/pengiriman_tokotegal/printpemesanan/{id}', [Pengiriman_tokotegalController::class, 'printpemesanan'])->name('pengiriman_tokotegal.printpemesanan');
    Route::get('toko_tegal/pengiriman_tokotegal/index', [Pengiriman_tokotegalController::class, 'index'])->name('toko_tegal.pengiriman_tokotegal.index');
    Route::get('/toko_tegal/pengiriman_tokotegal/pengiriman_pemesanan', [Pengiriman_tokotegalController::class, 'pengiriman_pemesanan'])->name('toko_tegal.pengiriman_tokotegal.pengiriman_pemesanan');
    Route::get('/toko_tegal/pengiriman_tokotegal/showpemesanan/{id}', [Pengiriman_tokotegalController::class, 'showpemesanan'])->name('toko_tegal.pengiriman_tokotegal.showpemesanan');

    Route::resource('pengirimanpemesanan_tokotegal', \App\Http\Controllers\Toko_tegal\Pengirimanpemesanan_tokotegalController::class);
    Route::get('/pengirimanpemesanan_tokotegal/print/{id}', [Pengirimanpemesanan_tokotegal::class, 'print'])->name('pengirimanpemesanan_tokotegal.print');


    Route::resource('retur_tokotegal', \App\Http\Controllers\Toko_tegal\Retur_tokotegalController::class);
  
    Route::resource('inquery_returtegal', \App\Http\Controllers\Toko_tegal\Inquery_returtegalController::class);
    Route::get('inquery_returtegal/unpost_retur/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_returtegalController::class, 'unpost_retur']);
    Route::get('inquery_returtegal/posting_retur/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_returtegalController::class, 'posting_retur']);
    Route::get('/inquery_returtegal/{id}/print', [Inquery_returtegalController::class, 'print'])->name('inquery_returtegal.print');


    Route::resource('pemindahan_tokotegal', \App\Http\Controllers\Toko_tegal\Pemindahan_tokotegalController::class);

    Route::resource('inquery_pemindahantegal', \App\Http\Controllers\Toko_tegal\Inquery_pemindahantegalController::class);
    Route::get('inquery_pemindahantegal/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_tegal\Inquery_pemindahantegalController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahantegal/{id}/print', [Inquery_pemindahantegalController::class, 'print'])->name('inquery_pemindahantegal.print');

    Route::resource('laporan_pemindahantegal', \App\Http\Controllers\Toko_tegal\Laporan_pemindahantegalController::class);
    Route::get('/Toko_tegal/print_report', [Laporan_pemindahantegalController::class, 'printReport'])->name('print.report');

    Route::resource('laporan_stoktokotegal', \App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class);
    Route::get('printstoktokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'printReport']);
    Route::get('stoktokopesanantegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'stoktokopesanantegal']);
    Route::get('printstoktokopesanantegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'printReportstokpesanantegal']);
    Route::get('semuastoktokotegal', [Laporan_stoktokotegalController::class, 'semuaStokTokoTegal'])->name('laporan.semuaStokTokoTegal');
    Route::get('printsemuastoktokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_stoktokotegalController::class, 'printReportsemuastoktegal']);

    Route::resource('laporan_pengirimantokotegal', \App\Http\Controllers\Toko_tegal\Laporan_pengirimantokotegalController::class);
    Route::get('printpengirimantokotegal', [\App\Http\Controllers\Toko_tegal\Laporan_pengirimantokotegalController::class, 'printReport']);

    Route::resource('setoran_tokotegal', \App\Http\Controllers\Toko_tegal\Setoran_tokotegalController::class);
    Route::post('/get-penjualan-kotor', [Setoran_tokotegalController::class, 'getdata'])->name('getdata');
    Route::post('toko_tegal/setoran_tokobanjaran', [Setoran_tokotegalController::class, 'store'])->name('setoran.store');


    Route::resource('laporan_setorantokotegal', \App\Http\Controllers\Toko_tegal\Laporan_setoranpenjualantglController::class);
    Route::get('printReportsetorantgl', [Laporan_setoranpenjualantglController::class, 'printReportsetorantgl'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_deposittegal', \App\Http\Controllers\Toko_tegal\Inquery_deposittegalController::class);

    Route::resource('laporan_deposittegal', \App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_tegal\Laporan_deposittegalController::class, 'printReportsaldo']);
    
    Route::resource('inquery_setorantunaibanjaran', \App\Http\Controllers\Toko_tegal\Inquery_setorantunaitegalController::class);
    Route::get('/toko_banjaran/inquery_setorantunai/{id}/print', [Inquery_setorantunaitegalController::class, 'print'])->name('inquery_setorantunai.print');

    Route::resource('laporan_setorantunaitegal', \App\Http\Controllers\Toko_tegal\Laporan_setorantunaitegalController::class);

    Route::resource('laporan_returtegal', \App\Http\Controllers\Toko_tegal\Laporan_returtegalController::class);
    Route::get('printReportreturtegal', [\App\Http\Controllers\Toko_tegal\Laporan_returtegalController::class, 'printReportreturbanjaran']);

});

Route::middleware('toko_pemalang')->prefix('toko_pemalang')->group(function () {
    Route::get('/', [\App\Http\Controllers\Toko_pemalang\DashboardController::class, 'index']);

    Route::resource('pelanggan', \App\Http\Controllers\Toko_pemalang\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Toko_pemalang\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');

    Route::resource('produk', \App\Http\Controllers\Toko_pemalang\ProdukController::class);

    Route::resource('pemesanan_produk', \App\Http\Controllers\Toko_pemalang\PemesananprodukpemalangController::class);
    Route::get('/toko_pemalang/pemesanan_produk/cetak/{id}', [PemesananprodukpemalangController::class, 'cetak'])->name('toko_pemalang.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananprodukbanjaranController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Toko_pemalang\PemesananprodukpemalangController::class, 'pelanggan']);
    Route::get('/get-customer-data', [PemesananprodukpemalangController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/toko_pemalang/pemesanan_produk/update/{id}', [PemesananprodukbanjaranController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/toko_pemalang/pemesanan_produk/cetak-pdf{id}', [PemesananprodukbanjaranController::class, 'cetakPdf'])->name('toko_pemalang.pemesanan_produk.cetak-pdf');
    Route::delete('toko_pemalang/pemesanan_produk/{id}', [PemesananprodukbanjaranController::class, 'destroy'])->name('pemesanan_produk.destroy');
    Route::get('/toko_pemalang/pemesanan_produk/{id}/cetak', [PemesananprodukbanjaranController::class, 'cetak'])->name('toko_pemalang.pemesanan_produk.cetak');
    Route::get('/toko_banjaran/pemesanan-produk/create', [PemesananprodukbanjaranController::class, 'create'])->name('pemesanan-produk.create');

    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Toko_pemalang\Inquery_pemesananprodukController::class);
    Route::get('/toko_pemalang/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('toko_pemalang.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananprodukpml', \App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController::class);
    Route::get('print_pemesananpml', [\App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController::class, 'print_pemesanan']);
    Route::get('printReportpemesananpml', [Laporan_pemesananprodukpemalangController::class, 'printReportPemesanan'])->name('printReportPemesanan');
    Route::get('indexpemesananglobalpml', [\App\Http\Controllers\Toko_pemalang\Laporan_pemesananprodukpemalangController::class, 'indexpemesananglobal']);
    Route::get('printReportpemesananglobalpml', [Laporan_pemesananprodukpemalangController::class, 'printReportpemesananglobalpml'])->name('printReportpemesananglobalpml');

    Route::resource('penjualan_produk', \App\Http\Controllers\Toko_pemalang\PenjualanprodukpemalangController::class);
    Route::get('/toko_pemalang/penjualan_produk/cetak/{id}', [PenjualanprodukpemalangController::class, 'cetak'])->name('toko_pemalang.penjualan_produk.cetak');
    Route::get('/toko_pemalang/penjualan_produk/cetak-pdf{id}', [PenjualanprodukpemalangController::class, 'cetakPdf'])->name('toko_pemalang.penjualan_produk.cetak-pdf');
    Route::get('/toko_pemalang/penjualan_produk/pelunasan', [PenjualanprodukpemalangController::class, 'pelunasan'])->name('toko_pemalang.penjualan_produk.pelunasan');
    Route::get('toko_pemalang/penjualan_produk/create', [PenjualanprodukpemalangController::class, 'create'])->name('toko_pemalang.penjualan_produk.create');
    // Route::get('/toko_banjaran/penjualan_produk/pelunasan', [PenjualanprodukbanjaranController::class, 'pelunasan'])->name('toko_banjaran.penjualan_produk.pelunasan');
    Route::get('/products/{tokoId}', [PenjualanprodukpemalangController::class, 'getProductsByToko'])->name('products.byToko');
    Route::get('/fetch-data-by-kode', [PenjualanprodukpemalangController::class, 'fetchDataByKode'])->name('toko_pemalang.penjualan_produk.fetchData');
    Route::get('/metodepembayaran/{id}', [PenjualanprodukpemalangController::class, 'getMetodePembayaran']);
    Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_pemalang\PenjualanprodukpemalangController::class, 'metode']);
    Route::post('toko_pemalang/penjualan_produk/pelunasan', [PenjualanprodukpemalangController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
    Route::get('/get-product', [PenjualanprodukpemalangController::class, 'getProductByKode']);
    Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukpemalangController::class, 'fetchProductData'])->name('toko_pemalang.penjualan_produk.fetchProductData');
    Route::get('/search-product', [PenjualanprodukpemalangController::class, 'getProduk']);
    Route::get('/get-produks', [PenjualanprodukpemalangController::class, 'getProduks']);
    Route::get('/produk/search', [PenjualanprodukpemalangController::class, 'search'])->name('produk.search');
    Route::get('/cari-produk', [PenjualanprodukpemalangController::class, 'cariProduk1'])->name('cari.produk');
    Route::get('/search-product', [PenjualanprodukpemalangController::class, 'searchProduct']);

    // Route::resource('pelunasan_pemesananTgl', \App\Http\Controllers\Toko_tegal\PelunasanpemesananTglController::class);
    // Route::get('/toko_tegal/pelunasan_pemesananTgl/cetak-pdf{id}', [PelunasanpemesananTglController::class, 'cetakPdf'])->name('toko_tegal.pelunasan_pemesananTgl.cetak-pdf');
    // Route::get('/pelunasan-pemesananTgl/cetak/{id}', [PelunasanpemesananTglController::class, 'cetak'])->name('toko_tegal.pelunasan_pemesananTgl.cetak');
    // Route::get('/pelunasan_pemesananTgl', [PelunasanPemesananTglController::class, 'index'])->name('toko_tegal.pelunasan_pemesananTgl.index');

    Route::resource('pelunasan_pemesananPml', \App\Http\Controllers\Toko_pemalang\PelunasanpemesananPmlController::class);
    Route::get('/toko_pemalang/pelunasan_pemesananPml/cetak-pdf{id}', [PelunasanpemesananPmlController::class, 'cetakPdf'])->name('toko_pemalang.pelunasan_pemesananPml.cetak-pdf');
    Route::get('/pelunasan-pemesananPml/cetak/{id}', [PelunasanpemesananPmlController::class, 'cetak'])->name('toko_pemalang.pelunasan_pemesananPml.cetak');

    Route::resource('inquery_penjualanprodukpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class);
    Route::get('/toko_banjaran/inquery_penjualanprodukbanajran', [Inquery_penjualanprodukpemalangController::class, 'index'])->name('toko_banjaran.inquery_penjualanproduk.index');
    Route::get('inquery_penjualanprodukpemalang/unpost_penjualanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class, 'unpost_penjualanproduk']);
    Route::get('inquery_penjualanprodukpemalang/posting_penjualanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_penjualanprodukpemalangController::class, 'posting_penjualanproduk']);
    Route::get('/toko_banjaran/inquery_penjualanproduk/cetak-pdf{id}', [Inquery_penjualanprodukpemalangController::class, 'cetakPdf'])->name('toko_banjaran.inquery_penjualanproduk.cetak-pdf');

    Route::resource('laporan_penjualanproduk', \App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class);
    Route::get('printReport', [\App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class, 'printReport']);
    Route::get('printReportglobal', [\App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class, 'printReportglobal']);
    Route::get('indexglobal', [\App\Http\Controllers\Toko_pemalang\Laporan_penjualanprodukController::class, 'indexglobal']);

    Route::resource('permintaan_produk', \App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController::class);
    Route::post('toko_pemalang/permintaan_produk', [PermintaanprodukpemalangController::class, 'store']);
    Route::get('toko_pemalang/permintaan_produk', [PermintaanprodukpemalangController::class, 'show']);
    Route::get('/permintaan-produk/{id}/print', [PermintaanprodukpemalangController::class, 'print'])->name('permintaan_produk.print');
    Route::get('permintaan_produk/unpost_permintaanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController::class, 'unpost_permintaanproduk']);
    Route::get('permintaan_produk/posting_permintaanproduk/{id}', [\App\Http\Controllers\Toko_pemalang\PermintaanprodukpemalangController::class, 'posting_permintaanproduk']);
    // Route::delete('Toko_pemalang/permintaan_produk/{id}', [PermintaanProdukController::class, 'destroy'])->name('Toko_pemalang.permintaan_produk.destroy');
    Route::post('toko_pemalang/permintaan/import', [PermintaanprodukpemalangController::class, 'import'])->name('permintaan.import');

    Route::resource('inquery_pelunasanpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_pelunasanpemalangController::class);


    Route::resource('inquery_permintaanproduk', \App\Http\Controllers\Toko_pemalang\Inquery_permintaanprodukController::class);
  

    Route::resource('laporan_permintaanproduk', \App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class);
    Route::get('printReport1', [\App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class, 'printReport']);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class, 'indexrinci']);
    Route::get('printReportRinci', [\App\Http\Controllers\Toko_pemalang\Laporan_permintaanprodukController::class, 'printReportRinci']);

    // Route::resource('inquery_pengirimanbarangjadi', \App\Http\Controllers\Toko_pemalang\Inquery_pengirimanbarangjadiController::class);
    Route::get('inquery_pengirimanbarangjadi/unpost_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pengirimanbarangjadiController::class, 'unpost_pengirimanbarangjadi']);
    Route::get('inquery_pengirimanbarangjadi/posting_pengirimanbarangjadi/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pengirimanbarangjadiController::class, 'posting_pengirimanbarangjadi']);

    Route::resource('metode_pembayaran', \App\Http\Controllers\Toko_pemalang\Metode_pembayaranController::class);

 
    Route::resource('stok_tokopemalang', \App\Http\Controllers\Toko_pemalang\Stok_tokopemalangController::class);
    Route::delete('/toko_pemalang/stok_tokopemalang/deleteAll', [Stok_tokopemalangController::class, 'deleteAll'])->name('stok_tokopemalang.deleteAll');
    Route::post('toko_pemalang/stok_tokopemalang/import', [Stok_tokopemalangController::class, 'import'])->name('stok_tokopemalang.import');
    Route::get('toko_pemalang/stok_tokopemalang/{id}/edit', [Stok_tokopemalangController::class, 'edit'])->name('stok_tokopemalang.edit');

    Route::put('toko_pemalang/stok_tokopemalang/{id}', [Stok_tokopemalangController::class, 'update'])->name('stok_tokopemalang.update');

    Route::resource('stokpesanan_tokopemalang', \App\Http\Controllers\Toko_pemalang\Stokpesanan_tokopemalangController::class);


    Route::resource('pengiriman_tokopemalang', \App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class);
    Route::get('pengiriman_tokopemalang/unpost_pengiriman/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'unpost_pengiriman']);
    Route::get('pengiriman_tokopemalang/posting_pengiriman/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'posting_pengiriman']);
    Route::get('pengiriman_tokopemalang/unpost_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'unpost_pengirimanpemesanan']);
    Route::get('pengiriman_tokopemalang/posting_pengirimanpemesanan/{id}', [\App\Http\Controllers\Toko_pemalang\Pengiriman_tokopemalangController::class, 'posting_pengirimanpemesanan']);
    Route::get('/pengiriman_tokopemalang/{id}/print', [Pengiriman_tokopemalangController::class, 'print'])->name('pengiriman_tokopemalang.print');
    Route::get('/toko_pemalang/pengiriman_tokopemalang/printpemesanan/{id}', [Pengiriman_tokopemalangController::class, 'printpemesanan'])->name('pengiriman_tokopemalang.printpemesanan');
    Route::get('toko_pemalang/pengiriman_tokopemalang/index', [Pengiriman_tokopemalangController::class, 'index'])->name('toko_pemalang.pengiriman_tokopemalang.index');
    Route::get('/toko_pemalang/pengiriman_tokopemalang/pengiriman_pemesanan', [Pengiriman_tokopemalangController::class, 'pengiriman_pemesanan'])->name('toko_pemalang.pengiriman_tokopemalang.pengiriman_pemesanan');
    Route::get('/toko_pemalang/pengiriman_tokopemalang/showpemesanan/{id}', [Pengiriman_tokopemalangController::class, 'showpemesanan'])->name('toko_pemalang.pengiriman_tokopemalang.showpemesanan');

    Route::resource('pengirimanpemesanan_tokopemalang', \App\Http\Controllers\Toko_pemalang\Pengirimanpemesanan_tokopemalangController::class);
    Route::get('/pengirimanpemesanan_tokopemalang/print/{id}', [Pengirimanpemesanan_tokopemalangController::class, 'print'])->name('pengirimanpemesanan_tokopemalang.print');


    Route::resource('retur_tokopemalang', \App\Http\Controllers\Toko_pemalang\Retur_tokopemalangController::class);
  
    Route::resource('inquery_returpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController::class);
    Route::get('inquery_returpemalang/unpost_retur/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController::class, 'unpost_retur']);
    Route::get('inquery_returpemalang/posting_retur/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_returpemalangController::class, 'posting_retur']);
    Route::get('/inquery_returpemalang/{id}/print', [Inquery_returpemalangController::class, 'print'])->name('inquery_returpemalang.print');


    Route::resource('pemindahan_tokobanjaran', \App\Http\Controllers\Toko_pemalang\Pemindahan_tokopemalangController::class);

    Route::resource('inquery_pemindahanpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_pemindahanpemalangController::class);
    Route::get('inquery_pemindahanpemalang/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_pemalang\Inquery_pemindahanpemalangController::class, 'posting_pemindahan']);
    Route::get('/inquery_pemindahanpemalang/{id}/print', [Inquery_pemindahanpemalangController::class, 'print'])->name('inquery_pemindahanpemalang.print');

    Route::resource('laporan_pemindahanpemalang', \App\Http\Controllers\Toko_pemalang\Laporan_pemindahanpemalangController::class);
    Route::get('/Toko_pemalang/print_report', [Laporan_pemindahanpemalangController::class, 'printReport'])->name('print.report');

    Route::resource('laporan_stoktokopemalang', \App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class);
    Route::get('printstoktokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'printReport']);
    Route::get('stoktokopesananpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'stoktokopesananpemalang']);
    Route::get('printstoktokopesananpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'printReportstokpesananpemalang']);
    Route::get('semuastoktokopemalang', [Laporan_stoktokopemalangController::class, 'semuaStokTokoPemalang'])->name('laporan.semuaStokTokopemalang');
    Route::get('printsemuastoktokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_stoktokopemalangController::class, 'printReportsemuastokpemalang']);


    Route::resource('laporan_pengirimantokopemalang', \App\Http\Controllers\Toko_pemalang\Laporan_pengirimantokopemalangController::class);
    Route::get('printpengirimantokopemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_pengirimantokopemalangController::class, 'printReport']);

    Route::resource('setoran_toko', \App\Http\Controllers\Toko_pemalang\Setoran_tokopemalangController::class);
    Route::post('/get-penjualan-kotor', [Setoran_tokopemalangController::class, 'getdata'])->name('getdata');
    Route::post('toko_banjaran/setoran_toko', [Setoran_tokopemalangController::class, 'store'])->name('setoran.store');


    Route::resource('laporan_setorantokopemalang', \App\Http\Controllers\Toko_pemalang\Laporan_setoranpenjualanpmlController::class);
    Route::get('printReportsetoranpml', [Laporan_setoranpenjualanpmlController::class, 'printReportsetoranpml'])->name('laporan_setoranpenjualan.print');

    Route::resource('inquery_depositpemalang', \App\Http\Controllers\Toko_pemalang\Inquery_depositpemalangController::class);

    Route::resource('laporan_depositpemalang', \App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class);
    Route::get('indexrinci', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'indexrinci']);
    Route::get('indexsaldo', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'indexsaldo']);
    Route::get('saldo', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'saldo']);
    Route::get('printReportdeposit', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'printReportdeposit']);
    Route::get('printReportdepositrinci', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'printReportdepositrinci']);
    Route::get('printReportsaldo', [\App\Http\Controllers\Toko_pemalang\Laporan_depositpemalangController::class, 'printReportsaldo']);
    
    Route::resource('inquery_setorantunaipemalang', \App\Http\Controllers\Toko_pemalang\Inquery_setorantunaipemalangController::class);
    Route::get('/toko_pemalang/inquery_setorantunai/{id}/print', [Inquery_setorantunaipemalangController::class, 'print'])->name('inquery_setorantunai.print');

    Route::resource('laporan_setorantunaipemalang', \App\Http\Controllers\Toko_pemalang\Laporan_setorantunaipemalangController::class);

    Route::resource('laporan_returpemalang', \App\Http\Controllers\Toko_pemalang\Laporan_returpemalangController::class);
    Route::get('printReportreturpemalang', [\App\Http\Controllers\Toko_pemalang\Laporan_returpemalangController::class, 'printReportreturbanjaran']);

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
//     // Route::get('/toko_bumiayu/penjualan_produk/cetak/{id}', [PenjualanprodukbumiayuController::class, 'cetak'])->name('toko_bumiayu.penjualan_produk.cetak');
//     // Route::get('/toko_bumiayu/penjualan_produk/cetak-pdf{id}', [PenjualanprodukbumiayuController::class, 'cetakPdf'])->name('toko_bumiayu.penjualan_produk.cetak-pdf');
//     // Route::get('/toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'pelunasan'])->name('toko_bumiayu.penjualan_produk.pelunasan');
//     // Route::get('toko_bumiayu/penjualan_produk/create', [PenjualanprodukbumiayuController::class, 'create'])->name('toko_bumiayu.penjualan_produk.create');
//     Route::get('/toko_bumiayu/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'pelunasan'])->name('toko_bumiayu.penjualan_produk.pelunasan');
//     // Route::get('/products/{tokoId}', [PenjualanprodukbumiayuController::class, 'getProductsByToko'])->name('products.byToko');
//     // Route::get('/fetch-data-by-kode', [PenjualanprodukbumiayuController::class, 'fetchDataByKode'])->name('toko_bumiayu.penjualan_produk.fetchData');
//     // Route::get('/metodepembayaran/{id}', [PenjualanprodukbumiayuController::class, 'getMetodePembayaran']);
//     // Route::get('metodebayar/metode/{id}', [\App\Http\Controllers\Toko_bumiayu\PenjualanprodukbumiayuController::class, 'metode']);
//     // Route::post('admin/penjualan_produk/pelunasan', [PenjualanprodukbumiayuController::class, 'SimpanPelunasan'])->name('penjualan_produk.pelunasan.simpan');
//     // Route::get('/get-product', [PenjualanprodukbumiayuController::class, 'getProductByKode']);
//     // Route::get('/penjualan-produk/fetch-product-data', [PenjualanprodukbumiayuController::class, 'fetchProductData'])->name('toko_bumiayu.penjualan_produk.fetchProductData');
//     // Route::get('/search-product', [PenjualanprodukbumiayuController::class, 'searchProduct']);
    



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


//     Route::resource('pemindahan_tokobumiayu', \App\Http\Controllers\Toko_bumiayu\Pemindahan_tokobumiayuController::class);

//     Route::resource('inquery_pemindahanbumiayu', \App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbumiayuController::class);
//     Route::get('inquery_pemindahanbanjaran/posting_pemindahan/{id}', [\App\Http\Controllers\Toko_bumiayu\Inquery_pemindahanbumiayuController::class, 'posting_pemindahan']);
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







