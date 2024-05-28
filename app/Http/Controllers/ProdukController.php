<?php

namespace App\Http\Controllers;

use App\Models\JenisProduk;
use App\Models\Peramalan;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\StokMitra;
use App\Models\Varian;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class ProdukController extends Controller
{
    public function produk()
    {

        $data = [
            'title' => 'Daftar Produk',
        ];
        return view('admin.produk.index', $data);
    }
    public function mitra()
    {

        $data = [
            'title' => 'Daftar Produk',
        ];
        return view('admin.produk.mitra', $data);
    }
    public function jenis()
    {

        $data = [
            'title' => 'Jenis Produk',
        ];
        return view('admin.jenis_produk.index', $data);
    }
    public function getjenisProdukDataTable()
    {
        $jenis_produk = JenisProduk::orderByDesc('id');

        return Datatables::of($jenis_produk)
            ->addColumn('action', function ($jenis) {
                return view('admin.jenis_produk.components.actions', compact('jenis'));
            })
            ->addColumn('jumlah', function ($jenis) {
                $produk = Produk::where('id_jenis_produk', $jenis->id)->count();
                return $produk > 0 ? $produk . ' Produk' : '<span class="text-danger">Belum ada</span>';
            })
            ->rawColumns(['action', 'jumlah'])
            ->make(true);
    }
    public function storeJenis(Request $request)
    {
        $request->validate([
            'jenis' => 'required|string|max:255',
        ]);

        $jenisData = [
            'jenis' => $request->input('jenis'),
        ];

        if ($request->filled('id')) {
            $JenisProduk = JenisProduk::find($request->input('id'));
            if (!$JenisProduk) {
                return response()->json(['message' => 'jenis produk not found'], 404);
            }

            $JenisProduk->update($jenisData);
            $message = 'jenis Produk updated successfully';
        } else {
            JenisProduk::create($jenisData);
            $message = 'jenis Produk created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroyJenis($id)
    {
        $customers = JenisProduk::find($id);

        if (!$customers) {
            return response()->json(['message' => 'jenis Produk not found'], 404);
        }

        $customers->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
    public function editJenis($id)
    {
        $customer = JenisProduk::find($id);

        if (!$customer) {
            return response()->json(['message' => 'jenis Produk not found'], 404);
        }

        return response()->json($customer);
    }
    public function getProdukDataTable(Request $request)
    {
        $search = $request->input('search.value');
        $produk = Produk::with('jenis')->orderByDesc('id');
        if (!empty($search)) {
            $produk->where('nama_produk', 'like', '%' . $search . '%');
        }
        return Datatables::of($produk)
            ->addColumn('foto', function ($produk) {
                return '<img style="width:100px;height:100px; object-fit:cover;" src="' . ($produk->foto_produk == null || $produk->foto_produk == '' ? asset('/img/logo.png') : Storage::url($produk->foto_produk)) . '"/>';
            })
            ->addColumn('nama', function ($produk) {
                $span = '<br><span class="badge bg-primary">' . $produk->jenis->jenis . '</span>';
                return '<strong>' . $produk->nama_produk . '</strong>' . $span;
            })

            ->addColumn('stok', function ($produk) {
                $jumlah_bertambah = Stok::where('id_produk', $produk->id)->where('jenis', 'Masuk')->sum('jumlah');
                $jumlah_berkurang = Stok::where('id_produk', $produk->id)->where('jenis', 'Keluar')->sum('jumlah');
                $jumlah_penjualan = Stok::where('id_produk', $produk->id)->where('jenis', 'Penjualan')->sum('jumlah');

                $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;

                return view('admin.produk.components.stok', compact(['produk', 'jumlah']));
            })
            ->addColumn('action', function ($produk) {
                return view('admin.produk.components.actions', compact('produk'));
            })
            ->addColumn('varian', function ($produk) {
                $varian = Varian::where('id_produk', $produk->id);

                return view('admin.produk.components.varian', compact('produk', 'varian'));
            })
            ->addColumn('action_ramalan', function ($produk) {
                return view('admin.peramalan.actions', compact('produk'));
            })
            ->addColumn('keterangan', function ($produk) {
                return Str::limit($produk->keterangan_produk, 100);
            })
            ->addColumn('hasil_ramalan', function ($produk) {
                $total = Peramalan::where('id_produk', $produk->id)->count();
                return $total ?? 0;
            })
            ->addColumn('harga', function ($produk) {
                $harga_modal = '<strong>Harga Modal </strong>: '.number_format($produk->harga_modal);
                $harga_jual = '<br><strong>Harga Jual </strong>: '.number_format($produk->harga_jual);
                $harga_diskon = $produk->is_discount == 1 ? '<br><strong>Harga Diskon </strong>: '.number_format($produk->harga_discount).' <sup class="text-danger">'.$produk->discount.'%</sup>' : '';
                return $harga_modal.$harga_jual.$harga_diskon;
            })
            ->rawColumns(['action', 'keterangan', 'foto', 'nama', 'stok', 'action_ramalan', 'hasil_ramalan', 'varian','harga'])
            ->make(true);
    }
    public function store(Request $request)
    {
        if ($request->filled('id')) {
            $request->validate([
                'nama_produk' => 'string|max:255',
                'id_jenis_produk' => 'string',
                'foto_produk' => 'file|mimes:jpeg,png,jpg,gif,webp|max:5048',
                'keterangan_produk' => 'string',
                'harga_modal' => 'numeric',
                'harga_jual' => 'numeric',
                'harga_discount' => 'numeric',
                'discount' => 'numeric',
            ]);
            $produkData = [
                'nama_produk' => $request->input('nama_produk'),
                'keterangan_produk' => $request->input('keterangan_produk'),
                'harga_modal' => $request->input('harga_modal'),
                'harga_jual' => $request->input('harga_jual'),
                'harga_discount' => $request->input('harga_discount'),
                'discount' => $request->input('discount'),
            ];
        } else {

            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'id_jenis_produk' => 'required|string',
                'keterangan_produk' => 'required|string',
                'foto_produk' => 'file|mimes:jpeg,png,jpg,gif,webp|max:5048',
                'harga_modal' => 'string',
                'harga_jual' => 'string',
                'harga_discount' => 'string',
                'discount' => 'string',
            ]);
            $produkData = [
                'nama_produk' => $request->input('nama_produk'),
                'id_jenis_produk' =>  $request->input('id_jenis_produk'),
                'keterangan_produk' => $request->input('keterangan_produk'),
                'harga_modal' => $request->input('harga_modal'),
                'harga_jual' => $request->input('harga_jual'),
                'harga_discount' => $request->input('harga_discount'),
                'discount' => $request->input('discount'),
            ];
        }
        if($request->input('is_discount') == 'on'){
            $produkData['is_discount'] = 1;
        }else{
            $produkData['is_discount'] = 0;

        }
        if ($request->hasFile('foto_produk')) {
            $filename = Str::random(32) . '.' . $request->file('foto_produk')->getClientOriginalExtension();

            $image = $request->file('foto_produk');
            $image->storeAs('public/produk', $filename);


            $file_path = 'public/produk/' . $filename;
            $produkData['foto_produk'] = isset($file_path) ? $file_path : '';
        }


        if ($request->filled('id')) {
            $Produk = Produk::find($request->input('id'));
            if (!$Produk) {
                return response()->json(['message' => ' produk not found'], 404);
            }

            $Produk->update($produkData);
            $message = ' Produk updated successfully';
        } else {
            Produk::create($produkData);
            $message = ' Produk created successfully';
        }

        return response()->json(['message' => $message]);
    }
    public function destroy($id)
    {
        $produk = Produk::find($id);
        $varian = Varian::where('id_produk', $produk->id);
        $stok = Stok::where('id_produk', $produk->id);
        $stok_mitra = StokMitra::where('id_produk', $produk->id);
        if ($varian) {
            foreach ($varian->get() as $item) {
                $varian_stok = Stok::where('id_varian', $item->id);
                $varian_stok_mitra = StokMitra::where('id_varian', $item->id);
                $varian_stok->delete();
                $varian_stok_mitra->delete();
            }

            $varian->delete();
        }
        if ($stok) {
            $stok->delete();
        }
        if ($stok_mitra) {
            $stok_mitra->delete();
        }
        if (!$produk) {
            return response()->json(['message' => ' Produk not found'], 404);
        }

        $produk->delete();

        return response()->json(['message' => 'Customer deleted successfully']);
    }
    public function edit($id)
    {
        $customer = Produk::find($id);

        if (!$customer) {
            return response()->json(['message' => ' Produk not found'], 404);
        }
        $foto_url = Storage::url($customer->foto_produk);
        $customer['foto_url'] = $foto_url;

        return response()->json($customer);
    }
}
