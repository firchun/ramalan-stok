<?php

namespace App\Http\Controllers;

use App\Models\Peramalan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PeramalanController extends Controller
{
    public function index()
    {
        $bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $tahun = Produk::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
        $data = [
            'title' => 'Peramalan stok',
            'bulan' => $bulan,
            'tahun' => $tahun,
        ];
        return view('admin.peramalan.index', $data);
    }
    public function hasilProduk($id_produk)
    {
        $produk = Produk::find($id_produk);
        $data = [
            'title' => 'Hasil peramalan stok : ' . $produk->nama_produk,
            'produk' => $produk,
        ];
        return view('admin.peramalan.hasil', $data);
    }
    public function getPeramalanDataTable($id_produk)
    {
        $Peramalan = Peramalan::with('produk')->where('id_produk', $id_produk)->orderByDesc('id');
        return Datatables::of($Peramalan)
            ->rawColumns()
            ->make(true);
    }
}
