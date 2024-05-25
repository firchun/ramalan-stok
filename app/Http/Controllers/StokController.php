<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\Varian;
use Carbon\Carbon;
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
        $stok->id_varian = $request->id_varian ?? null;
        $stok->jenis = 'Masuk';
        $stok->jumlah = $request->jumlah;
        $stok->id_user = Auth::id();
        $stok->save();
        return response()->json(['message' => 'Berhasil menambah stok']);
    }
    public function kurangStok(Request $request, $id_produk)
    {
        $jumlah_bertambah = Stok::where('id_produk', $id_produk)
            ->where(function ($query) use ($request) {
                $query->where('id_varian', $request->id_varian)
                    ->orWhereNull('id_varian');
            })
            ->where('jenis', 'Masuk')
            ->sum('jumlah');
        $jumlah_berkurang = Stok::where('id_produk', $id_produk)
            ->where(function ($query) use ($request) {
                $query->where('id_varian', $request->id_varian)
                    ->orWhereNull('id_varian');
            })
            ->where('jenis', 'Keluar')
            ->sum('jumlah');
        $jumlah_penjualan = Stok::where('id_produk', $id_produk)
            ->where(function ($query) use ($request) {
                $query->where('id_varian', $request->id_varian)
                    ->orWhereNull('id_varian');
            })
            ->where('jenis', 'Penjualan')
            ->sum('jumlah');
        $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;

        if ($jumlah >= $request->jumlah) {
            $stok = new Stok();
            $stok->id_produk = $id_produk;
            $stok->id_varian = $request->id_varian ?? null;
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
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $tanggalAwal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->startOfDay();
            $tanggalAkhir = Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->endOfDay();
            $stok->where('created_at', '>=', $tanggalAwal)->where('created_at', '<=', $tanggalAkhir);
            // $stok->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
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
            ->addColumn('varian', function ($stok) {
                $varian = Varian::find($stok->id_varian);
                $ukuran = $varian->jenis == 'ukuran' ? $varian->ukuran : $varian->nomor;
                $text = '<b>' . $varian->nama . '</b><br>Ukuran : ' . $ukuran;
                return $varian ? $text : null;
            })
            ->rawColumns(['jenis_txt', 'tanggal', 'varian'])
            ->make(true);
    }
}
