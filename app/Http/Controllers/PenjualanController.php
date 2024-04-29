<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\StokMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    public function penjualan(Request $request)
    {
        $role = Auth::user()->role;

        if ($role == 'Mitra') {
            $jumlah_bertambah = StokMitra::where('id_produk', $request->id_produk)->where('jenis', 'Masuk')->sum('jumlah');
            $jumlah_berkurang = StokMitra::where('id_produk', $request->id_produk)->where('jenis', 'Keluar')->sum('jumlah');
            $jumlah_penjualan = StokMitra::where('id_produk', $request->id_produk)->where('jenis', 'Penjualan')->sum('jumlah');
            $jumlah_return = StokMitra::where('id_produk', $request->id_produk)->where('jenis', 'Return')->where('konfirmasi', 1)->sum('jumlah');
            $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan - $jumlah_return;
            if ($jumlah >= $request->jumlah) {
                $stok = new StokMitra();
                $stok->jenis = 'Penjualan';
                $stok->id_user = Auth::id();
                $stok->jumlah = $request->jumlah;
                $stok->id_produk = $request->id_produk;
                $stok->save();
                return response()->json(['message' => 'Berhasil melakukan penjualan']);
            } else {
                return response()->json(['message' => 'Jumlah tidak mencukupi']);
            }
        } elseif ($role == 'Admin') {
            $jumlah_bertambah = Stok::where('id_produk', $request->id_produk)->where('jenis', 'Masuk')->sum('jumlah');
            $jumlah_berkurang = Stok::where('id_produk', $request->id_produk)->where('jenis', 'Keluar')->sum('jumlah');
            $jumlah_penjualan = Stok::where('id_produk', $request->id_produk)->where('jenis', 'Penjualan')->sum('jumlah');
            $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;
            if ($jumlah >= $request->jumlah) {
                $stok = new Stok();
                $stok->jenis = 'Penjualan';
                $stok->id_user = Auth::id();
                $stok->jumlah = $request->jumlah;
                $stok->id_produk = $request->id_produk;
                $stok->save();
                return response()->json(['message' => 'Berhasil melakukan penjualan']);
            } else {
                return response()->json(['message' => 'Jumlah tidak mencukupi']);
            }
        }
    }
}
