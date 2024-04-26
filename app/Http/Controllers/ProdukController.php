<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function produk()
    {

        $data = [
            'title' => 'Daftar Produk',
        ];
        return view('admin.produk.produk.index', $data);
    }
    public function jenis()
    {

        $data = [
            'title' => 'Jenis Produk',
        ];
        return view('admin.produk.jenis.index', $data);
    }
}
