<?php

namespace App\Http\Controllers;

use App\Models\Stok;
use App\Models\StokMitra;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function utama()
    {
        $data = [
            'title' => 'Laporan Stok Utama',
        ];
        return view('admin.laporan.utama', $data);
    }
    public function mitra()
    {
        $data = [
            'title' => 'Laporan Stok Mitra',
        ];
        return view('admin.laporan.mitra', $data);
    }
    public function mitraOne($id)
    {
        $user = User::find($id);
        $data = [
            'title' => 'Laporan Stok Mitra : ' . $user->name,
            'user' => $user,
        ];
        return view('admin.laporan.one', $data);
    }
    public function printUtama(Request $request)
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

        if (!$stok->exists()) {
            return redirect()->back()->with('danger', 'Data tidak tersedia');
        }
        $pdf = \PDF::loadview('admin/laporan/pdf/utama', [
            'data' => $stok->get(),
            'title' => 'Laporan Stok Utama R Collection',
            'from_date' => $request->tanggal_awal,
            'to_date' => $request->tanggal_akhir,
        ])
            ->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_stok_utama_' . $request->tanggal_awal . '-' . $request->tanggal_akhir . '.pdf');
    }
    public function printMitra(Request $request)
    {
        $stok = StokMitra::with(['produk', 'user'])->orderByDesc('id');
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
        if ($request->mitra != null || $request->mitra != '') {
            $stok->where('id_user', $request->mitra);
        }

        if (!$stok->exists()) {
            return redirect()->back()->with('danger', 'Data tidak tersedia');
        }
        $pdf = \PDF::loadview('admin/laporan/pdf/mitra', [
            'data' => $stok->get(),
            'title' => 'Laporan Stok Mitra R Collection',
            'from_date' => $request->tanggal_awal,
            'to_date' => $request->tanggal_akhir,
        ])
            ->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_stok_mitra_' . $request->tanggal_awal . '-' . $request->tanggal_akhir . '.pdf');
    }
    public function printMitraOne(Request $request, $id)
    {
        $user = User::find($id);
        $nama_mitra = $user->name;
        $stok = StokMitra::where('id_user', $id)->with(['produk', 'user'])->orderByDesc('id');
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
        if ($request->mitra != null || $request->mitra != '') {
            $stok->where('id_user', $request->mitra);
        }

        if (!$stok->exists()) {
            return redirect()->back()->with('danger', 'Data tidak tersedia');
        }
        $pdf = \PDF::loadview('admin/laporan/pdf/one', [
            'data' => $stok->get(),
            'title' => 'Laporan Stok Mitra : ' . $nama_mitra,
            'from_date' => $request->tanggal_awal,
            'to_date' => $request->tanggal_akhir,
            'nama_mitra' => $nama_mitra,
        ])
            ->setPaper('a4', 'landscape');
        return $pdf->stream('Laporan_stok_mitra_' . $request->tanggal_awal . '-' . $request->tanggal_akhir . '.pdf');
    }
}
