<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
    return view('pages.index');
});

Auth::routes();
Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
});
