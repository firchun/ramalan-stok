<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\StokMitra;
use App\Models\User;
use App\Models\Varian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class StokMitraController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Stok Mitra',
        ];
        return view('admin.stok_mitra.index', $data);
    }
    public function return()
    {
        $data = [
            'title' => 'Pengajuan Return oleh mitra',
        ];
        return view('admin.stok_mitra.return', $data);
    }
    public function detail($id_user)
    {
        $user = User::find($id_user);
        $data = [
            'title' => 'Stok Mitra : ' . $user->name,
            'user' => $user
        ];
        return view('admin.stok_mitra.detail', $data);
    }
    public function getStokMitraDataTable($id_user)
    {
        $stok = StokMitra::select('id_produk', 'id_varian')
            ->selectRaw('SUM(CASE WHEN jenis = "Masuk" THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = "Keluar" THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = "Penjualan" THEN jumlah ELSE 0 END) - SUM(CASE WHEN jenis = "Return" AND konfirmasi = "1" THEN jumlah ELSE 0 END) AS total_jumlah')
            ->where('id_user', $id_user)
            ->with(['produk', 'user'])
            ->groupBy('id_produk', 'id_varian');

        return Datatables::of($stok)

            ->addColumn('nama', function ($stok) {
                $span = '<br><span class="badge bg-primary">' . $stok->produk->jenis->jenis . '</span>';
                return '<strong>' . $stok->produk->nama_produk . '</strong>' . $span;
            })
            ->addColumn('varian', function ($stok) {
                $varian = Varian::where('id_produk', $stok->id_produk)
                    ->where('id', $stok->id_varian)
                    ->first();
                return $varian ? $varian->nama . ' [' . $varian->ukuran . ']' : '';
            })
            ->addColumn('foto', function ($stok) {
                return '<img style="width:100px; height:100px; object-fit:cover;" src="' . ($stok->produk->foto_produk == null || $stok->produk->foto_produk == '' ? asset('/img/logo.png') : Storage::url($stok->produk->foto_produk)) . '"/>';
            })
            ->addColumn('action', function ($stok) {
                return '<img style="width:100px; height:100px; object-fit:cover;" src="' . ($stok->produk->foto_produk == null || $stok->produk->foto_produk == '' ? asset('/img/logo.png') : Storage::url($stok->produk->foto_produk)) . '"/>';
            })
            ->rawColumns(['foto', 'nama', 'varian'])
            ->make(true);
    }
    public function getRiwayatStokMitraDataTable(Request $request, $id_user)
    {
        $stok = StokMitra::where('id_user', $id_user)
            ->with(['produk', 'user'])
            ->orderByDesc('id');
        if ($request->tanggal_awal != null || $request->tanggal_awal != '') {
            $stok->where('created_at', '>=', $request->tanggal_awal)->where('created_at', '<=', $request->tanggal_akhir);
        }
        if ($request->jenis != null || $request->jenis != '') {
            $stok->where('jenis', $request->jenis);
        }
        return Datatables::of($stok)

            ->addColumn('tanggal', function ($stok) {
                return $stok->created_at->format('d F Y');
            })
            ->addColumn('nama', function ($stok) {
                $varian = Varian::find($stok->id_varian);
                $span = '<br><span class="badge bg-primary">' . $stok->jenis . '</span>';
                $varian = '<br>' . $varian->nama . ' [' . $varian->ukuran . ']';
                return '<strong>' . $stok->produk->nama_produk . '</strong>' . $span . $varian;
            })
            ->addColumn('varian', function ($stok) {
                $varian = Varian::where('id_produk', $stok->id_produk)
                    ->where('id', $stok->id_varian)
                    ->first();
                return $varian ? $varian->nama . ' [' . $varian->ukuran . ']' : '';
            })
            ->rawColumns(['nama', 'tanggal', 'varian'])
            ->make(true);
    }
    public function getAllStokMitraDataTable(Request $request)
    {
        $stok = StokMitra::with(['produk', 'user'])
            ->orderByDesc('id');

        if ($request->tanggal_awal != null || $request->tanggal_awal != '') {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $tanggalAwal = Carbon::createFromFormat('Y-m-d', $tanggalAwal)->startOfDay();
            $tanggalAkhir = Carbon::createFromFormat('Y-m-d', $tanggalAkhir)->endOfDay();
            // $stok->where('created_at', '>=', $request->tanggal_awal)->where('created_at', '<=', $request->tanggal_akhir);
            $stok->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir]);
        }
        if ($request->jenis != null || $request->jenis != '') {
            $stok->where('jenis', $request->jenis);
        }
        if ($request->mitra != null || $request->mitra != '') {
            $stok->where('id_user', $request->mitra);
        }

        return Datatables::of($stok)

            ->addColumn('tanggal', function ($stok) {
                return $stok->created_at->format('d F Y');
            })
            ->addColumn('nama', function ($stok) {
                $span = '<br><span class="badge bg-primary">' . $stok->jenis . '</span>';
                return '<strong>' . $stok->produk->nama_produk . '</strong>' . $span;
            })
            ->addColumn('return', function ($stok) {
                return view('admin.stok_mitra.action_return', compact('stok'));
            })
            ->addColumn('varian', function ($stok) {
                $varian = Varian::where('id_produk', $stok->id_produk)
                    ->where('id', $stok->id_varian)
                    ->first();
                return $varian ? $varian->nama . ' [' . $varian->ukuran . ']' : '';
            })
            ->rawColumns(['nama', 'tanggal', 'varian'])
            ->make(true);
    }
    public function getReturnStokMitraDataTable()
    {
        $stok = StokMitra::with(['produk', 'user'])
            ->where('jenis', 'Return')
            ->orderByDesc('id');

        return Datatables::of($stok)

            ->addColumn('tanggal', function ($stok) {
                return $stok->created_at->format('d F Y');
            })
            ->addColumn('varian', function ($stok) {
                $varian = Varian::where('id_produk', $stok->id_produk)
                    ->where('id', $stok->id_varian)
                    ->first();
                return $varian ? $varian->nama . ' [' . $varian->ukuran . ']' : '';
            })
            ->addColumn('nama', function ($stok) {
                $span = '<br><span class="badge bg-primary">' . $stok->jenis . '</span>';
                return '<strong>' . $stok->produk->nama_produk . '</strong>' . $span;
            })
            ->addColumn('return', function ($stok) {
                return view('admin.stok_mitra.action_return', compact('stok'));
            })
            ->rawColumns(['nama', 'tanggal', 'return'])
            ->make(true);
    }
    public function tambahStok(Request $request)
    {
        $jumlah_bertambah = Stok::where('id_produk',  $request->id_produk)
            ->where(function ($query) use ($request) {
                $query->where('id_varian', $request->id_varian)
                    ->orWhereNull('id_varian');
            })
            ->where('jenis', 'Masuk')->sum('jumlah');
        $jumlah_berkurang = Stok::where('id_produk',  $request->id_produk)
            ->where(function ($query) use ($request) {
                $query->where('id_varian', $request->id_varian)
                    ->orWhereNull('id_varian');
            })
            ->where('jenis', 'Keluar')->sum('jumlah');
        $jumlah_penjualan = Stok::where('id_produk',  $request->id_produk)
            ->where(function ($query) use ($request) {
                $query->where('id_varian', $request->id_varian)
                    ->orWhereNull('id_varian');
            })
            ->where('jenis', 'Penjualan')->sum('jumlah');
        $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;
        if ($jumlah >= $request->jumlah) {

            $stok = new StokMitra();
            $stok->id_varian = $request->id_varian ?? null;
            $stok->id_produk = $request->id_produk;
            $stok->jenis = 'Masuk';
            $stok->jumlah = $request->jumlah;
            $stok->id_user = $request->id_user;
            $stok->save();

            $stok_utama = new Stok();
            $stok_utama->id_varian = $request->id_varian ?? null;
            $stok_utama->id_produk = $request->id_produk;
            $stok_utama->jenis = 'Keluar';
            $stok_utama->jumlah = $request->jumlah;
            $stok_utama->id_user = Auth::id();
            $stok_utama->save();
        } else {
            return response()->json(['message' => 'Stok utama tidak mencukupi']);
        }
        return response()->json(['message' => 'Berhasil menambah stok']);
    }

    public function returnStok(Request $request)
    {
        $jumlah_bertambah = StokMitra::where('id_produk', $request->id_produk)
            ->where('id_varian', $request->id_varian)
            ->where('jenis', 'Masuk')
            ->sum('jumlah');
        $jumlah_berkurang = StokMitra::where('id_produk', $request->id_produk)
            ->where('id_varian', $request->id_varian)
            ->whereIn('jenis', ['Keluar', 'Penjualan'])
            ->sum('jumlah');
        $jumlah_return = StokMitra::where('id_produk', $request->id_produk)
            ->where('id_varian', $request->id_varian)
            ->where('jenis', 'Return')
            ->where('konfirmasi', 1)
            ->sum('jumlah');
        $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_return;

        if ($jumlah >= $request->jumlah) {

            $stok = new StokMitra();
            $stok->id_produk = $request->id_produk;
            $stok->id_varian = $request->id_varian;
            $stok->jenis = 'Return';
            $stok->jumlah = $request->jumlah;
            $stok->id_user = Auth::id();
            $stok->save();
            return response()->json(['message' => 'Berhasil melakukan pengajuan return']);
        } else {
            return response()->json(['message' => 'Stok mitra tidak mencukupi ']);
        }
    }
    public function konfirmasiReturn(Request $request)
    {
        $jumlah_awal = $request->jumlah_awal;
        if ($jumlah_awal >= $request->jumlah) {
            $stok_mitra = StokMitra::find($request->id);
            $stok_mitra->jumlah = $request->jumlah;
            $stok_mitra->konfirmasi = 1;
            $stok_mitra->save();

            $stok_utama = new Stok();
            $stok_utama->id_user = Auth::id();
            $stok_utama->jenis = 'Masuk';
            $stok_utama->jumlah = $request->jumlah;
            $stok_utama->id_produk = $stok_mitra->id_produk;
            $stok_utama->id_varian = $stok_mitra->id_varian;
            $stok_utama->save();
            return response()->json(['message' => 'Return terkonfirmasi']);
        } else {
            return response()->json(['message' => 'Jumlah tidak sesuai dengan pengajuan']);
        }
    }
}
