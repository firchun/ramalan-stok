<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\StokMitraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VarianController;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
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
    $produk = Produk::with(['jenis'])->latest()->paginate(12);
    return view('pages.index', ['produk' => $produk, 'title' => 'Beranda']);
});
Route::get('/about', function () {
    $mitra = User::where('role', 'Mitra')->get();
    return view('pages.about', ['mitra' => $mitra, 'title' => 'About']);
});
Route::get('/discount', function () {
    $produk = Produk::with(['jenis'])->where('is_discount', 1)->latest()->paginate(12);
    return view('pages.discount', ['produk' => $produk, 'title' => 'Discount Produk']);
});
Route::get('/search-produk', function (Request $request) {
    $search = $request->input('search');
    $produk = Produk::with(['jenis'])->where('nama_produk', 'like', '%' . $search . '%')->latest()->paginate(12);
    return view('pages.index', ['produk' => $produk, 'title' => 'Search : ' . $search, 'searchInput' => $search]);
});
Route::get('/search-discount', function (Request $request) {
    $search = $request->input('search');
    $produk = Produk::with(['jenis'])->where('nama_produk', 'like', '%' . $search . '%')->where('is_discount', 1)->latest()->paginate(12);
    return view('pages.discount', ['produk' => $produk, 'title' => 'Search Discount : ' . $search, 'searchInput' => $search]);
});

Route::post('/kirim-pesanan',[PesananController::class,'store'])->name('kirim-pesanan');

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
    Route::get('/report/printMitra/{id}', [LaporanController::class, 'printMitraOne']);
    //akun managemen
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //produk managemen
    Route::get('/produk/mitra', [ProdukController::class, 'mitra'])->name('produk.mitra');
    Route::get('/produk-datatable', [ProdukController::class, 'getProdukDataTable']);
    //list
    Route::get('/varian-list/{id}', [VarianController::class, 'listApi'])->name('varian-list');
    Route::get('/varian/edit/{id}', [VarianController::class, 'edit'])->name('varian.edit');
    //varian
    Route::get('/varian-datatable/{id}', [VarianController::class, 'getVarianDataTable'])->name('varian-datatable');
});
Route::middleware(['auth:web', 'role:Admin'])->group(function () {
    //pesanan managemen
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan');
    Route::get('/pesanan/konfirmasi/{id}', [PesananController::class, 'konfirmasi'])->name('pesanan.konfirmasi');
    Route::post('/pesanan/bayar/{id}', [PesananController::class, 'bukti_bayar'])->name('pesanan.bayar');
    Route::get('/pesanan-datatable', [PesananController::class, 'getPesananDataTable']);
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
    // Route::get('/produk-datatable', [ProdukController::class, 'getProdukDataTable']);
    //jenis_produk managemen
    Route::get('/jenis_produk', [ProdukController::class, 'jenis'])->name('jenis_produk');
    Route::post('/jenis_produk/store',  [ProdukController::class, 'storeJenis'])->name('jenis_produk.store');
    Route::get('/jenis_produk/edit/{id}',  [ProdukController::class, 'editJenis'])->name('jenis_produk.edit');
    Route::delete('/jenis_produk/delete/{id}',  [ProdukController::class, 'destroyJenis'])->name('jenis_produk.delete');
    Route::get('/jenis_produk-datatable', [ProdukController::class, 'getjenisProdukDataTable']);
    //varian managemen
    // Route::get('/varian-datatable/{id}', [VarianController::class, 'getVarianDataTable'])->name('varian-datatable');
    // Route::get('/varian-list/{id}', [VarianController::class, 'listApi'])->name('varian-list');
    Route::post('/varian/store', [VarianController::class, 'store'])->name('varian.store');
    Route::delete('/varian/delete/{id}', [VarianController::class, 'destroy'])->name('varian.delete');
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
    Route::get('/report/printMitra', [LaporanController::class, 'printMitra'])->name('report.printMitra');
    Route::get('/report/printUtama', [LaporanController::class, 'printUtama'])->name('report.printUtama');
    //peramalan
    Route::get('/peramalan', [PeramalanController::class, 'index'])->name('peramalan');
    Route::get('/peramalan/show/{id}', [PeramalanController::class, 'show'])->name('peramalan.show');
    Route::delete('/peramalan/delete/{id}', [PeramalanController::class, 'destroy'])->name('peramalan.delete');
    Route::get('/peramalan/hasil/{id_produk}', [PeramalanController::class, 'hasilProduk'])->name('peramalan.hasil');
    Route::post('/peramalan/store', [PeramalanController::class, 'store'])->name('peramalan.store');
    Route::get('/hasil-ramalan-datatable/{id_produk}', [PeramalanController::class, 'getPeramalanDataTable']);
});
