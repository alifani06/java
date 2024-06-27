<?php

use App\Http\Controllers\Admin\KaryawanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SlawiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\Admin\AddpelangganController;
use App\Http\Controllers\Admin\KlasifikasiController as AdminKlasifikasiController;
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
    Route::resource('kartu', \App\Http\Controllers\Admin\KartuController::class);
    // Route::resource('addpelanggan', \App\Http\Controllers\Admin\AddpelangganController::class);
    Route::resource('pelanggan', \App\Http\Controllers\Admin\PelangganController::class);
    Route::resource('departemen', \App\Http\Controllers\Admin\DepartemenController::class); 
    Route::resource('barang', \App\Http\Controllers\Admin\BarangController::class); 
    Route::resource('klasifikasi', \App\Http\Controllers\Admin\KlasifikasiController::class); 
    Route::resource('addkategori', \App\Http\Controllers\Admin\AddkategoriController::class); 
    Route::resource('addsub', \App\Http\Controllers\Admin\AddsubController::class); 
    Route::resource('subklasifikasi', \App\Http\Controllers\Admin\SubklasifikasiController::class); 
    Route::resource('member', \App\Http\Controllers\Admin\MemberController::class); 
    Route::resource('input', \App\Http\Controllers\Admin\InputController::class); 
    Route::get('pelanggan/getpelanggan/{id}', [\App\Http\Controllers\Admin\PelangganController::class, 'getpelanggan']);
    Route::post('tambahkategori', [\App\Http\Controllers\Admin\AddkategoriController::class, 'tambahkategori']);
    Route::get('/klasifikasi/{id}/sub', [\App\Http\Controllers\Admin\KlasifikasiController::class, 'getSubCategories']);
    Route::get('klasifikasi/get_subklasifikasi/{id}', [\App\Http\Controllers\Admin\KlasifikasiController::class, 'get_subklasifikasi']);
    Route::get('addsub/get_subklasifikasi/{id}', [\App\Http\Controllers\Admin\AddsubController::class, 'get_subklasifikasi']);
    Route::get('klasifikasi/get_klasifikasi/{id}', [\App\Http\Controllers\Admin\BarangController::class, 'get_klasifikasi']);
    // Route::get('klasifikasi/addkategori/create', [\App\Http\Controllers\Admin\AddkategoriController::class, 'create']);

    });
    Route::get('/toko/slawi', [SlawiController::class, 'index']);
    Route::get('/addpelanggan', [AddpelangganController::class, 'create']);
    Route::post('/tambah', [AddpelangganController::class, 'store']);
    Route::get('/tambah', [AddpelangganController::class, 'getbarang']);
    

    // In web.php




