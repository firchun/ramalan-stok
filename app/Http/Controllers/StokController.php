<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {

        $data = [
            'title' => 'Riwayat Stok',
        ];
        return view('admin.stok.index', $data);
    }

    public function tambahStok(Request $request, $id_produk)
    {
        $stok = new Stok();
        $stok->id_produk = $id_produk;
        $stok->jenis = 'Masuk';
        $stok->jumlah = $request->jumlah;
        $stok->id_user = Auth::id();
        $stok->save();
        return response()->json(['message' => 'Berhasil menambah stok']);
    }
    public function kurangStok(Request $request, $id_produk)
    {
        $jumlah_bertambah = Stok::where('id_produk', $id_produk)->where('jenis', 'Masuk')->sum('jumlah');
        $jumlah_berkurang = Stok::where('id_produk', $id_produk)->where('jenis', 'Keluar')->sum('jumlah');
        $jumlah_penjualan = Stok::where('id_produk', $id_produk)->where('jenis', 'Penjualan')->sum('jumlah');
        $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;

        if ($jumlah >= $request->jumlah) {
            $stok = new Stok();
            $stok->id_produk = $id_produk;
            $stok->jenis = 'Keluar';
            $stok->jumlah = $request->jumlah;
            $stok->id_user = Auth::id();
            $stok->save();
        } else {
            return response()->json(['message' => 'Stok tidak sesuai']);
        }
        return response()->json(['message' => 'Berhasil mengurangi stok']);
    }
    public function getStokDataTable(Request $request)
    {
        $stok = Stok::with(['produk', 'user'])->orderByDesc('id');
        if ($request->tanggal_awal != null || $request->tanggal_awal != '') {
            $stok->where('created_at', '>=', $request->tanggal_awal)->where('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->jenis != null || $request->jenis != '') {
            $stok->where('jenis', $request->jenis);
        }
        return Datatables::of($stok)
            ->addColumn('jenis_txt', function ($stok) {
                $warna = $stok->jenis == 'Masuk' ? 'bg-success' : 'bg-danger';
                return '<span class="badge ' . $warna . '">' . $stok->jenis . '</span>';
            })
            ->addColumn('tanggal', function ($stok) {
                return $stok->created_at->format('d F Y');
            })
            ->rawColumns(['jenis_txt', 'tanggal'])
            ->make(true);
    }
}
