<?php

namespace App\Http\Controllers;

use App\Models\Peramalan;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\StokMitra;
use Carbon\Carbon;
use Illuminate\Database\DBAL\TimestampType;
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
    public function getPeramalanDataTable($id_produk, Request $request)
    {
        $Peramalan = Peramalan::with('produk')->where('id_produk', $id_produk)->orderByDesc('id');
        return Datatables::of($Peramalan)
            ->addColumn('action', function ($Peramalan) {
                $deleteButton = '<button onclick="deleteRamalan(' . $Peramalan->id . ')" class="btn btn-sm text-danger"><i class="bx bx-trash"></i></button>';
                $showButton = '<button onclick="show(' . $Peramalan->id . ')" class="btn btn-sm text-primary"><i class="bx bx-show"></i></button>';
                return $showButton . $deleteButton;
            })
            ->addColumn('tanggal', function ($Peramalan) {
                return $Peramalan->created_at->format('d F Y') . '<br><small class="text-muted">Jam ' . $Peramalan->created_at->format('H:i A') . '</small>';
            })
            ->addColumn('bulan_pertama', function ($Peramalan) {
                return '<strong>' . $Peramalan->periode_1 . '</strong><br><small class="text-muted">' . $Peramalan->bulan_1 . '</small>';
            })
            ->addColumn('bulan_kedua', function ($Peramalan) {
                return '<strong>' . $Peramalan->periode_2 . '</strong><br><small class="text-muted">' . $Peramalan->bulan_2 . '</small>';
            })
            ->addColumn('bulan_ketiga', function ($Peramalan) {
                return '<strong>' . $Peramalan->periode_3 . '</strong><br><small class="text-muted">' . $Peramalan->bulan_3 . '</small>';
            })
            ->addColumn('bulan_diramal', function ($Peramalan) {
                return '<strong>' . $Peramalan->periode_n . '</strong><br><small class="text-muted">' . $Peramalan->bulan_n . '</small>';
            })
            ->rawColumns(['action', 'bulan_pertama', 'bulan_kedua', 'bulan_ketiga', 'bulan_diramal', 'tanggal'])
            ->make(true);
    }
    public function store(Request $request)
    {
        //ambil semua request dari form
        $id_produk = $request->id_produk;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        if ($id_produk == null || $id_produk == '') {
            return response()->json(['message' => 'Harap pilih produk terlebih dahulu', 'success' => false]);
        }

        //ambil data produk
        $produk = Produk::find($id_produk);
        //variabel untuk menampung semua data perbulan
        $total_bulanan = [];

        //looping untuk 3 bulan
        for ($i = 0; $i < 4; $i++) {

            //buat bulan berdasarkan loop (3bulan)
            $date = Carbon::create($tahun, $bulan, 1)->subMonths($i);
            $start_date = $date->copy()->startOfMonth();
            $end_date = $date->copy()->endOfMonth();

            //membuat tanggal awal dan tanggal akhir
            $tanggal_awal = $start_date->format('Y-m-d');
            $tanggal_akhir = $end_date->format('Y-m-d');

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
            $total_bulanan[] = [
                'bulan' => $date->format('F Y'),
                'stok' => $stok,
                'stok_mitra' => $stok_mitra
            ];
        };

        //hitung perbulan
        $total_stok = 0;
        $total_stok_mitra = 0;
        foreach ($total_bulanan as $total) {
            $total_stok += $total['stok'];
            $total_stok_mitra += $total['stok_mitra'];
        }

        //hitung total penjualan
        $total_penjualan = ($total_stok + $total_stok_mitra) - ($total_bulanan[0]['stok'] + $total_bulanan[0]['stok_mitra']);
        //hitung rata-rata
        $total_average = round($total_penjualan / 3);
        //aktual bulan ini
        $aktual_bulan_ini = $total_bulanan[0]['stok'] + $total_bulanan[0]['stok_mitra'];
        // if ($aktual_bulan_ini <= 0) {
        //     return response()->json(['message' => 'Data aktual bulan ini 0 sehingga tidak bisa menghitung MAPE', 'success' => false]);
        // }
        //nilai error = aktual bulan ini - average
        $error = round(abs($aktual_bulan_ini - $total_average));
        //error kuadrat = nilai (error )^
        $error_2 = round(($error / $aktual_bulan_ini) * 100, 2);

        $peramalanData = [
            'id_produk' => $produk->id,
            'periode_1' => $total_bulanan[3]['stok'] + $total_bulanan[3]['stok_mitra'],
            'periode_2' => $total_bulanan[2]['stok'] + $total_bulanan[2]['stok_mitra'],
            'periode_3' => $total_bulanan[1]['stok'] + $total_bulanan[1]['stok_mitra'],
            'periode_n' => $total_bulanan[0]['stok'] + $total_bulanan[0]['stok_mitra'],
            'bulan_1' => $total_bulanan[3]['bulan'],
            'bulan_2' => $total_bulanan[2]['bulan'],
            'bulan_3' => $total_bulanan[1]['bulan'],
            'bulan_n' => $total_bulanan[0]['bulan'],
            'total_penjualan' => $aktual_bulan_ini,
            'total_ma' => $total_average,
            'mad' => $error,
            'mape' => $error_2 ?? 0,
            'tahun' => $tahun,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        //pengujian tanpa menyimpan dengan tampilkan data json
        // return response()->json([$peramalanData, 'message' => 'Berhasil', 'success' => true]);

        //menyimpan dalam database
        $simpan = DB::table('peramalan')->insert($peramalanData);
        $peramalanData['produk'] = $produk->nama_produk;
        if ($simpan) {
            session()->flash('success', 'Berhasil membuat ramalan');
            // return redirect()->to('/peramalan/hasil', $produk->id);
            return response()->json([$peramalanData, 'message' => 'Berhasil membuat ramalan', 'success' => true]);
        } else {
            return response()->json(['message' => 'Gagal menyimpan ramalan', 'success' => false]);
        }
    }
    public function show($id)
    {
        $Peramalan = Peramalan::find($id);
        return response()->json([$Peramalan]);
    }
    public function destroy($id)
    {
        $peramalan = Peramalan::find($id);

        if (!$peramalan) {
            return response()->json(['message' => 'ramalan not found'], 404);
        }

        $peramalan->delete();

        return response()->json(['message' => 'ramalan deleted successfully']);
    }
}
