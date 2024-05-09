<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\StokMitra;
use App\Models\Varian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Svg\Tag\Rect;
use Yajra\DataTables\Facades\DataTables;

class VarianController extends Controller
{
    public function listApi($id_produk)
    {
        $data = Varian::where('id_produk', $id_produk)->get();
        foreach ($data as $item) {
            $role = Auth::user()->role ?? '';

            if ($role == 'Mitra') {
                $jumlah_bertambah = StokMitra::where('id_produk', $id_produk)
                    ->where('id_varian', $item->id)
                    ->where('jenis', 'Masuk')
                    ->sum('jumlah');
                $jumlah_berkurang = StokMitra::where('id_produk', $id_produk)
                    ->where('id_varian', $item->id)
                    ->whereIn('jenis', ['Keluar', 'Penjualan'])
                    ->sum('jumlah');
                $jumlah_return = StokMitra::where('id_produk', $id_produk)
                    ->where('id_varian', $item->id)
                    ->where('jenis', 'Return')
                    ->where('konfirmasi', 1)
                    ->sum('jumlah');
                $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_return;
            } else {
                $jumlah_bertambah = Stok::where('id_produk', $id_produk)
                    ->where('id_varian', $item->id)
                    ->where('jenis', 'Masuk')
                    ->sum('jumlah');
                $jumlah_berkurang = Stok::where('id_produk', $id_produk)
                    ->where('id_varian', $item->id)
                    ->whereIn('jenis', ['Keluar', 'Penjualan'])
                    ->sum('jumlah');
                $jumlah = $jumlah_bertambah - $jumlah_berkurang;
            }

            $item->stok = $jumlah;
        }
        return response()->json($data);
    }




    public function getVarianDataTable($id_produk)
    {
        $Varian = Varian::with('produk')->where('id_produk', $id_produk)->orderByDesc('id');
        return Datatables::of($Varian)
            ->addColumn('warna', function ($Varian) {
                return '<div class="d-flex"><div style="border-radius:5px;width:20px; height:20px;background-color:' . $Varian->kode_warna . ';"></div> &nbsp;' . $Varian->nama . '</div>';
            })
            ->addColumn('delete', function ($Varian) {
                return '<button type="button" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>';
            })
            ->rawColumns(['delete', 'warna'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|string|max:255',
            'kode_warna' => 'required|string|max:255',
            'ukuran' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
        ]);

        $varianData = [
            'id_produk' => $request->input('id_produk'),
            'kode_warna' => $request->input('kode_warna'),
            'ukuran' => $request->input('ukuran'),
            'nama' => $request->input('nama'),
        ];

        if ($request->filled('id')) {
            $Varian = Varian::find($request->input('id'));
            if (!$Varian) {
                return response()->json(['message' => 'varian produk not found'], 404);
            }

            $Varian->update($varianData);
            $message = 'varian updated successfully';
        } else {
            Varian::create($varianData);
            $message = 'varian created successfully';
        }

        return response()->json(['message' => $message]);
    }
}
