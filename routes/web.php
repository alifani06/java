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
use App\Http\Controllers\Admin\Inquery_pemesananprodukController;
use App\Http\Controllers\Admin\PemesananprodukController;
use App\Http\Controllers\Admin\KlasifikasiController as AdminKlasifikasiController;
use App\Http\Controllers\Admin\Laporan_pemesananprodukController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\KategoriDropdownController;
use App\Http\Controllers\SubcategoryController;
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

    // Route::resource('addpelanggan', \App\Http\Controllers\Admin\AddpelangganController::class);
    Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Admin\PelangganController::class, 'getpelanggan']);
    Route::get('pelanggan/cetak_pdf/{id}', [PelangganController::class, 'cetak_pdf'])->name('pelanggan.cetak_pdf');

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

    Route::resource('toko', \App\Http\Controllers\Admin\TokoController::class);

    Route::resource('hargajual', \App\Http\Controllers\Admin\HargajualController::class);
    Route::get('admin/hargajual/all', [HargajualController::class, 'all'])->name('admin.hargajual.all');
    Route::post('/admin/update-harga', [HargajualController::class, 'updateHarga'])->name('update.harga');
    Route::get('admin/hargajual/show', [App\Http\Controllers\Admin\HargajualController::class, 'show'])->name('show');
    Route::get('/cetak-pdf', [HargajualController::class, 'cetakPdf'])->name('cetak.pdf');
    Route::get('/admin/hargajual/filter', [HargajualController::class, 'all'])->name('admin.hargajual.filter');

    Route::resource('pemesanan_produk', \App\Http\Controllers\Admin\PemesananprodukController::class);
    Route::get('/admin/pemesanan_produk/cetak/{id}', [PemesananProdukController::class, 'cetak'])->name('admin.pemesanan_produk.cetak');
    Route::get('/get-customer/{kode}', [PemesananProdukController::class, 'getCustomerByKode']);
    Route::get('pemesanan/pelanggan/{id}', [\App\Http\Controllers\Admin\PemesananprodukController::class, 'pelanggan']);
    // Route::get('/admin/pemesanan_produk/{id}/cetak-pdf', [PemesananprodukController::class, 'cetakPdf'])->name('admin.pemesanan_produk.cetak-pdf');
    // Route::get('/admin/pemesanan_produk/{id}/cetak-pdf', [PemesananprodukController::class, 'cetakPdf'])->name('admin.pemesanan_produk.cetak-pdf');
    Route::get('/get-customer-data', [PemesananprodukController::class, 'getCustomerData'])->name('get.customer.data');
    Route::get('/admin/pemesanan_produk/update/{id}', [PemesananprodukController::class, 'edit'])->name('pemesanan_produk.update');
    Route::get('/admin/pemesanan_produk/cetak-pdf{id}', [PemesananprodukController::class, 'cetakPdf'])->name('admin.pemesanan_produk.cetak-pdf');
    Route::delete('admin/pemesanan_produk/{id}', [PemesananProdukController::class, 'destroy'])->name('pemesanan_produk.destroy');


    Route::resource('inquery_pemesananproduk', \App\Http\Controllers\Admin\Inquery_pemesananprodukController::class);
    Route::get('/admin/inquery_pemesananproduk', [Inquery_pemesananprodukController::class, 'index'])->name('admin.inquery_pemesananproduk.index');
    Route::get('inquery_pemesananproduk/unpost_pemesananproduk/{id}', [\App\Http\Controllers\Admin\Inquery_pemesananprodukController::class, 'unpost_pemesananproduk']);
    Route::get('inquery_pemesananproduk/posting_pemesananproduk/{id}', [\App\Http\Controllers\Admin\Inquery_pemesananprodukController::class, 'posting_pemesananproduk']);

    Route::resource('laporan_pemesananproduk', \App\Http\Controllers\Admin\Laporan_pemesananprodukController::class);
    // Route::get('laporan_pemesananproduk/print', [Laporan_pemesananprodukController::class, 'print'])->name('print');
    Route::get('print_pemesanan', [\App\Http\Controllers\Admin\Laporan_pemesananprodukController::class, 'print_pemesanan']);

    // Route::get('/data', [\App\Http\Controllers\Admin\InputController::class, 'data']); 
    // Route::get('/pelanggan/cetak_pdf/{id}', [\App\Http\Controllers\Admin\PelangganController::class, 'cetak_pdf']);
    // Route::get('klasifikasi/addkategori/create', [\App\Http\Controllers\Admin\AddkategoriController::class, 'create']);
    });
    Route::get('/toko/slawi', [SlawiController::class, 'index']);
    Route::get('/addpelanggan', [AddpelangganController::class, 'create']);
    Route::post('/tambah', [AddpelangganController::class, 'store']);
    Route::get('/tambah', [AddpelangganController::class, 'getbarang']);
    

    // In web.php




