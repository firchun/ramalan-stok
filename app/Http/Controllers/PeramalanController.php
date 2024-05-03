<?php

namespace App\Http\Controllers;

use App\Models\Peramalan;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\StokMitra;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $tahun = Stok::selectRaw('YEAR(created_at) as year')
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
    public function store(Request $request)
    {
        //ambil semua request dari form
        $id_produk = $request->id_produk;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        //ambil data produk
        $produk = Produk::find($id_produk);
        //variabel untuk menampung semua data perbulan
        $total_bulanan = [];

        //looping untuk 3 bulan
        for ($i = 0; $i < 3; $i++) {

            //buat bulan berdasarkan loop (3bulan)
            $date = Carbon::create($tahun, $bulan, 1)->subMonths($i);
            $start_date = $date->copy()->startOfMonth();
            $end_date = $date->copy()->endOfMonth();

            //membuat tanggal awal dan tanggal akhir
            $tanggal_awal = $start_date->format('Y-m-d');
            $tanggal_akhir = $end_date->format('Y-m-d');

            //ambil total stok berdasarkan data penjualan

            //data penjualan admin
            $stok = Stok::whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                ->where('id_produk', $id_produk)
                ->where('jenis', 'Penjualan')
                ->sum('jumlah');
            //data penjualan mitra
            $stok_mitra = StokMitra::whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                ->where('id_produk', $id_produk)
                ->where('jenis', 'Penjualan')
                ->sum('jumlah');

            //mengambil data bulan pertama, kedua dan ketiga
            $total_bulanan[] = ['stok' => $stok, 'stok_mitra' => $stok_mitra];
        };

        //hitung perbulan
        $total_stok = 0;
        $total_stok_mitra = 0;
        foreach ($total_bulanan as $total) {
            $total_stok += $total['stok'];
            $total_stok_mitra += $total['stok_mitra'];
        }

        //hitung rata-rata
        $total_pengeluaran = $total_stok + $total_stok_mitra;
        $total_average = ($total_stok + $total_stok_mitra) / 3;

        //hitung MSE
        // $mse = pow($total_average, 2);

        // //hitung total error
        // $total_error = 0;
        // foreach ($total_bulanan as $total) {
        //     $total_error += pow($total['stok'] + $total['stok_mitra'] - $total_average, 2);
        // }


        //menampilkan data berbentuk json
        $data = [
            'id_produk' => $produk->id,
            'total_penjualan' => $total_pengeluaran,
            'total_ma' => $total_average,
            'nilai_1' => $total_bulanan[0]['stok'] + $total_bulanan[0]['stok_mitra'],
            'nilai_2' => $total_bulanan[1]['stok'] + $total_bulanan[1]['stok_mitra'],
            'nilai_3' => $total_bulanan[2]['stok'] + $total_bulanan[2]['stok_mitra'],
            // 'total_mse' => $mse,
            // 'total_error' => $total_error,
        ];

        //cek data json pada console
        return response()->json($data);

        //menyimpan dalam database
        // $simpan = DB::insert($data);
        // if ($simpan) {
        //     // berpindah halaman untuk menampilkan hasil
        //     session()->flash('success', 'Berhasil membuat ramalan');
        //     return redirect()->to('/peramalan/hasil', $produk->id);
        // } else {
        //     return response()->json(['message' => 'Gagal menyimpan ramalan']);
        // }
    }
}
