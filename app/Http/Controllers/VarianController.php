<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\StokMitra;
use App\Models\Varian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Svg\Tag\Rect;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

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
            ->addColumn('ukuran_text', function ($Varian) {
                return $Varian->jenis == 'nomor' ? $Varian->nomor : $Varian->ukuran;
            })
            ->addColumn('foto_view', function ($Varian) {
                return '<img src="' . Storage::url($Varian->foto) . '" style="width:50px;height"50px;object-fit:cover;">';
            })
            ->addColumn('delete', function ($Varian) {
                $edit_button = '<button type="button" onclick="updateVarian(' . $Varian->id . ')" class="btn btn-warning btn-sm mx-2"><i class="bx bx-edit"></i></button>';
                return $edit_button . '<button type="button" onclick="deleteVarian(' . $Varian->id . ')" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></button>';
            })
            ->addColumn('jumlah', function ($Varian) {
                $jumlah_bertambah = Stok::where('id_varian', $Varian->id)
                    ->where('jenis', 'Masuk')
                    ->sum('jumlah');
                $jumlah_berkurang = Stok::where('id_varian', $Varian->id)
                    ->where('jenis', 'Keluar')
                    ->sum('jumlah');
                $jumlah_penjualan = Stok::where('jenis', 'Penjualan')
                    ->where('id_varian', $Varian->id)
                    ->sum('jumlah');

                $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;
                $color = $jumlah == 0 ? 'danger' : 'primary';
                return '<span class="badge bg-' . $color . '">' . $jumlah . '</span>';
            })
            ->rawColumns(['delete', 'warna', 'ukuran_text', 'jumlah', 'foto_view'])
            ->make(true);
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|string|max:255',
            'kode_warna' => 'required|string|max:255',
            'ukuran' => 'string|max:255',
            // 'nomor' => 'string|max:255',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'foto' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5048',
        ]);

        $varianData = [
            'id_produk' => $request->input('id_produk'),
            'kode_warna' => $request->input('kode_warna'),
            'nama' => $request->input('nama'),
            'jenis' => $request->input('jenis'),
            'nomor' => $request->input('nomor'),
            'ukuran' => $request->input('ukuran'),
        ];
        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            if ($image->isValid()) {

                $filename = Str::random(32) . '.' . $request->file('foto')->getClientOriginalExtension();

                $image = $request->file('foto');
                $image->storeAs('public/produk', $filename);


                $file_path = 'public/produk/' . $filename;
                $varianData['foto'] = isset($file_path) ? $file_path : '';
            }
        }

        // Jika jenis adalah 'ukuran', atur 'nomor' menjadi null
        if ($request->input('jenis') == 'ukuran') {
            $varianData['nomor'] = null;
        }
        // Jika jenis adalah 'nomor', atur 'ukuran' menjadi null
        elseif ($request->input('jenis') == 'nomor') {
            $varianData['ukuran'] = null;
        }

        if ($request->filled('id')) {
            $varian = Varian::find($request->input('id'));
            if (!$varian) {
                return response()->json(['message' => 'varian produk not found'], 404);
            }

            $varian->update($varianData);
            $message = 'varian updated successfully';
        } else {
            Varian::create($varianData);
            $message = 'varian created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $Varian = Varian::find($id);
        $cek_stok = Stok::where('id_varian', $id);
        $cek_stok_mitra = StokMitra::where('id_varian', $id);
        if ($cek_stok) {
            $cek_stok->delete();
        }
        if ($cek_stok_mitra) {
            $cek_stok_mitra->delete();
        }

        if (!$Varian) {
            return response()->json(['message' => 'Varian not found'], 404);
        }

        $Varian->delete();

        return response()->json(['message' => 'Varian deleted successfully']);
    }
    public function edit($id)
    {
        $Varian = Varian::find($id);
        if (!$Varian) {
            return response()->json(['message' => 'Varian not found'], 404);
        }
        return response()->json($Varian);
    }
}
