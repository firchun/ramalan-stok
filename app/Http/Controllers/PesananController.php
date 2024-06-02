<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Daftar pemesanan',

        ];
        return view('admin.pemesanan.index', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        unset($data['_token']);
        $data['no_pesanan'] = 'INV-' . mt_rand(100000, 999999);
        $data['created_at'] = now();
        $data['updated_at'] = now();
        $cek_stok = Stok::cekStokUtama($data['id_produk'], $data['id_varian']);

        if ($data['jumlah'] > $cek_stok) {
            session()->flash('success', 'Berhasil mengirim pesanan dengan invoice = ' . $data['no_pesanan']);
            return back()->withInput($data);
        } else {
            $admin = User::where('role', 'Admin')->first();
            $stok = new Stok();
            $stok->jenis = 'Penjualan';
            $stok->id_user = $admin->id;
            $stok->jumlah = $data['jumlah'];
            $stok->id_varian = $data['id_varian'];
            $stok->id_produk = $data['id_produk'];
            $stok->created_at = now();
            $stok->save();
            $pesanan = DB::table('pesanan')->insert($data);
            session()->flash('success', 'Berhasil mengirim pesanan dengan invoice = ' . $data['no_pesanan']);
            return back()->withInput($data);
        }
    }
    public function getPesananDataTable()
    {
        $data  = Pesanan::with(['varian', 'produk'])->orderByDesc('id');
        return DataTables::of($data)
            ->addColumn('tanggal', function ($data) {
                return $data->created_at->format('d F Y');
            })

            ->addColumn('btn_wa', function ($data) {
                $ukuran_varian = $data->varian->jenis == 'ukuran' ? $data->varian->ukuran : $data->varian->nomor;
                //api wa
                $pesan = "No. Invoice: " . $data->no_pesanan . "\n" .
                    "Produk: " . $data->produk->nama_produk . "\n" .
                    "Varian: " . $data->varian->nama . " [" . $ukuran_varian . "]" . "\n" .
                    "Jumlah dipesan : " . $data->jumlah . "\n" .
                    "Total Tagihan : Rp. " . $data->jumlah * $data->produk->harga_discount . "\n" .
                    "-----------------------------------------\n" .
                    "Silahkan kirim bukti pembayaran ke nomor rekening:\n" .
                    "BRI: 348001059557536 | An. NUR RAHMA PUTRI\n\n" .
                    "Segera kirimkan bukti pembayaran 1x24 Jam atau akan dibatalkan\n";

                $pesan_encoded = urlencode($pesan);
                $url = 'https://api.whatsapp.com/send?phone=' . $data->no_hp . '&text=' . $pesan_encoded;
                //end
                $text_tagihan = $url;
                $text_kosong = 'https://api.whatsapp.com/send?phone=' . $data->no_hp;
                $link = $data->is_verified == 0 ? $text_kosong : $text_tagihan;
                $type = $data->is_verified == 0 ? 'secondary' : 'success';
                $tombol = '<a href="' . $link . '" class="btn btn-' . $type . '"><i class="bx bxl-whatsapp"></i></a>';
                return $tombol;
            })

            ->addColumn('konfirmasi', function ($data) {
                $pending = '<button type="button" onclick="konfirmasi(' . $data->id . ')" class="btn btn-sm btn-primary m-1">Konfirmasi</button>';
                $batal = '<button type="button" onclick="batal(' . $data->id . ')" class="btn btn-sm btn-danger my-1">Batalkan</button>';
                $bayar = '<button type="button" onclick="payment(' . $data->id . ')" class="btn btn-sm btn-info m-1">Bukti Bayar</button>';
                $success = '<span class="text-muted text-center">terkonfirmasi<br><a target="__blank" href="' . Storage::url($data->bukti_bayar) . '" class="btn btn-success btn-sm">lihat bukti bayar</a></span>';
                $terkonfirmasi = $data->bukti_bayar == null ? $bayar . $batal : $success;
                $view = $data->is_verified == 0 ? $pending . $batal : $terkonfirmasi;
                return $view;
            })
            ->addColumn('pemesan', function ($data) {
                return '<strong>' . $data->nama . '</strong><br><small class="text-success">' . $data->no_hp . '</small><br><small>' . $data->alamat . '</small>';
            })
            ->addColumn('produk', function ($data) {
                $ukuran = $data->varian->jenis == 'ukuran' ? $data->varian->ukuran : $data->varian->nomor;
                return '<strong>' . $data->produk->nama_produk . '</strong><br><small>' . $data->varian->nama . ' [' . $ukuran . ']</small>';
            })
            ->rawColumns(['konfirmasi', 'btn_wa', 'tanggal', 'pemesan', 'produk'])
            ->make(true);
    }
    public function konfirmasi($id)
    {
        $data = Pesanan::find($id);
        $data['is_verified'] = 1;
        $data->save();
        return response()->json(['message' => 'Pesanan terkonfirmasi']);
    }
    public function destroy($id)
    {
        $data = Pesanan::find($id);
        $stok = Stok::where('id_varian', $data->id_varian)->where('id_produk', $data->id_produk)->where('jenis', 'Penjualan')->latest()->first();
        $stok->delete();
        $data->delete();
        return response()->json(['message' => 'Pesanan berhasil dibatalkan']);
    }
    public function bukti_bayar($id, Request $request)
    {
        $data = Pesanan::find($id);
        if ($request->hasFile('bukti_bayar')) {
            $filename = Str::random(32) . '.' . $request->file('bukti_bayar')->getClientOriginalExtension();

            $image = $request->file('bukti_bayar');
            $image->storeAs('public/produk', $filename);


            $file_path = 'public/produk/' . $filename;
            $data['bukti_bayar'] = isset($file_path) ? $file_path : '';
        } else {
            return response()->json(['message' => 'gagal upload bukti']);
        }
        $data->save();
        return response()->json(['message' => 'Berhasil upload bukti bayar']);

        //terjual
        // $jumlah_bertambah = Stok::where('id_produk', $data->id_produk)
        //     ->where(function ($query) use ($data) {
        //         $query->where('id_varian', $data->id_varian)
        //             ->orWhereNull('id_varian');
        //     })
        //     ->where('jenis', 'Masuk')->sum('jumlah');
        // $jumlah_berkurang = Stok::where('id_produk', $data->id_produk)
        //     ->where(function ($query) use ($data) {
        //         $query->where('id_varian', $data->id_varian)
        //             ->orWhereNull('id_varian');
        //     })
        //     ->where('jenis', 'Keluar')->sum('jumlah');
        // $jumlah_penjualan = Stok::where('id_produk', $data->id_produk)
        //     ->where(function ($query) use ($data) {
        //         $query->where('id_varian', $data->id_varian)
        //             ->orWhereNull('id_varian');
        //     })
        //     ->where('jenis', 'Penjualan')->sum('jumlah');
        // $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;
        // if ($jumlah >= $data->jumlah) {
        //     $stok = new Stok();
        //     $stok->jenis = 'Penjualan';
        //     $stok->id_user = Auth::id();
        //     $stok->jumlah = $data->jumlah;
        //     $stok->id_varian = $data->id_varian ?? null;
        //     $stok->id_produk = $data->id_produk;
        //     $stok->created_at = now();
        //     $stok->save();
        //     $data->save();
        //     return response()->json(['message' => 'Berhasil upload bukti bayar']);
        // } else {
        //     return response()->json(['message' => 'Pesanan terkonfirmasi']);
        // }

    }
}
