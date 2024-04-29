<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    }
    public function printMitra(Request $request)
    {
    }
    public function printMitraOne(Request $request)
    {
    }
}
