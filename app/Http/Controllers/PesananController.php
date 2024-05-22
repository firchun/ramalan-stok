<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'Daftar pemesanan',

        ];
        return view('admin.pemesanan.index', $data);
    }
}
