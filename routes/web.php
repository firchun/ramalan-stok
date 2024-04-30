<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\StokMitraController;
use App\Http\Controllers\UserController;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $produk = Produk::with(['jenis'])->latest()->get();
    return view('pages.index', ['produk' => $produk, 'title' => 'Beranda']);
});

Auth::routes();
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //penjualan
    Route::post('/penjualan/store',  [PenjualanController::class, 'penjualan'])->name('penjualan.store');
    //stok mitra managemen
    Route::get('/stok/mitra', [StokMitraController::class, 'index'])->name('stok.mitra');
    Route::post('/stok/mitra/store',  [StokMitraController::class, 'store'])->name('stok.mitra.store');
    Route::get('/stok/mitra/detail/{id}',  [StokMitraController::class, 'detail'])->name('stok.mitra.detail');
    Route::delete('/stok/mitra/delete/{id}',  [StokMitraController::class, 'destroy'])->name('stok.mitra.delete');
    Route::get('/stok-mitra-datatable/{id_user}', [StokMitraController::class, 'getStokMitraDataTable']);
    Route::get('/riwayat-stok-mitra-datatable/{id_user}', [StokMitraController::class, 'getRiwayatStokMitraDataTable']);
    Route::post('/return-stok-mitra',  [StokMitraController::class, 'returnStok'])->name('return-stok-mitra');
    //laporan managemen
    Route::get('/report/mitra/{id}', [LaporanController::class, 'mitraOne']);
    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    //user managemen
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/mitra', [UserController::class, 'mitra'])->name('mitra');
    Route::post('/users/store',  [UserController::class, 'store'])->name('users.store');
    Route::get('/users/edit/{id}',  [UserController::class, 'edit'])->name('users.edit');
    Route::delete('/users/delete/{id}',  [UserController::class, 'destroy'])->name('users.delete');
    Route::get('/users-datatable', [UserController::class, 'getUsersDataTable']);
    Route::get('/mitra-datatable', [UserController::class, 'getUsersMitraDataTable']);
    //produk managemen
    Route::get('/produk', [ProdukController::class, 'produk'])->name('produk');
    Route::post('/produk/store',  [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/edit/{id}',  [ProdukController::class, 'edit'])->name('produk.edit');
    Route::delete('/produk/delete/{id}',  [ProdukController::class, 'destroy'])->name('produk.delete');
    Route::get('/produk-datatable', [ProdukController::class, 'getProdukDataTable']);
    //jenis_produk managemen
    Route::get('/jenis_produk', [ProdukController::class, 'jenis'])->name('jenis_produk');
    Route::post('/jenis_produk/store',  [ProdukController::class, 'storeJenis'])->name('jenis_produk.store');
    Route::get('/jenis_produk/edit/{id}',  [ProdukController::class, 'editJenis'])->name('jenis_produk.edit');
    Route::delete('/jenis_produk/delete/{id}',  [ProdukController::class, 'destroyJenis'])->name('jenis_produk.delete');
    Route::get('/jenis_produk-datatable', [ProdukController::class, 'getjenisProdukDataTable']);
    //stok managemen
    Route::get('/stok', [StokController::class, 'index'])->name('stok');
    Route::post('/stok/store',  [StokController::class, 'store'])->name('stok.store');
    Route::get('/stok/edit/{id}',  [StokController::class, 'edit'])->name('stok.edit');
    Route::delete('/stok/delete/{id}',  [StokController::class, 'destroy'])->name('stok.delete');
    Route::get('/stok-datatable', [StokController::class, 'getStokDataTable']);
    //stok mitra managemen
    Route::get('/stok/mitra/return', [StokMitraController::class, 'return'])->name('stok.mitra.return');
    Route::get('/return-stok-datatable', [StokMitraController::class, 'getReturnStokMitraDataTable']);
    Route::get('/all-stok-mitra-datatable', [StokMitraController::class, 'getAllStokMitraDataTable']);
    // Route::get('/stok/mitra', [StokMitraController::class, 'index'])->name('stok.mitra');
    // Route::post('/stok/mitra/store',  [StokMitraController::class, 'store'])->name('stok.mitra.store');
    // Route::get('/stok/mitra/detail/{id}',  [StokMitraController::class, 'detail'])->name('stok.mitra.detail');
    // Route::delete('/stok/mitra/delete/{id}',  [StokMitraController::class, 'destroy'])->name('stok.mitra.delete');
    // Route::get('/riwayat-stok-mitra-datatable/{id_user}', [StokMitraController::class, 'getRiwayatStokMitraDataTable']);
    // stok utama
    Route::post('/tambah-stok/{id_produk}',  [StokController::class, 'tambahStok'])->name('tambah-stok');
    Route::post('/tambah-stok-mitra',  [StokMitraController::class, 'tambahStok'])->name('tambah-stok-mitra');
    Route::post('/kurang-stok/{id_produk}',  [StokController::class, 'kurangStok'])->name('kurang-stok');
    Route::post('/konfirmasi-return',  [StokMitraController::class, 'konfirmasiReturn'])->name('konfirmasi-return');
    // penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan');
    // laporan
    Route::get('/report/mitra', [LaporanController::class, 'mitra'])->name('report.mitra');
    Route::get('/report/utama', [LaporanController::class, 'utama'])->name('report.utama');
    //peramalan
    Route::get('/peramalan', [PeramalanController::class, 'index'])->name('peramalan');
    Route::get('/peramalan/hasil/{id_produk}', [PeramalanController::class, 'hasilProduk'])->name('peramalan.hasil');
    Route::get('/peramalan-datatable/{id_produk}', [PeramalanController::class, 'getPeramalanDataTable']);
});
