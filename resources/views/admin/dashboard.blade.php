@extends('layouts.backend.admin')

@section('content')
    <div class="jumbroton text-center mb-5">
        <img src="{{ asset('/img/logo.png') }}" style="width: 100px;">
        <h4>Selamat Datang di {{ env('APP_NAME') ?? 'Laravel' }}</h4>
    </div>


    <div class="d-grid col-lg-4 col-md-6 col-8 mx-auto mb-3">
        <button class="btn btn-lg btn-secondary penjualan btn-primary" type="button" data-bs-toggle="modal"
            data-bs-target="#penjualan">
            <span>
                <i class="bx bx-cart me-sm-1"> </i>
                <span class="d-none d-sm-inline-block">Penjualan</span>
            </span>
        </button>
    </div>
    @if (Auth::user()->role == 'Admin')
        <hr>
        <div class="row justify-content-center">
            @include('admin.dashboard_component.card1', [
                'count' => $mitra,
                'title' => 'Mitra',
                'subtitle' => 'Total mitra',
                'color' => 'primary',
                'icon' => 'user',
            ])
            @include('admin.dashboard_component.card1', [
                'count' => $produk,
                'title' => 'Produk',
                'subtitle' => 'Total Produk',
                'color' => 'success',
                'icon' => 'folder',
            ])
        </div>
        <hr>
        <h3 class="text-center">Daftar Produk</h3>
        <div class="row justify-content-center">
            @foreach ($produk_list as $item)
                @php
                    $jumlah_bertambah = App\Models\Stok::where('id_produk', $item->id)
                        ->where('jenis', 'Masuk')
                        ->sum('jumlah');
                    $jumlah_berkurang = App\Models\Stok::where('id_produk', $item->id)
                        ->where('jenis', 'Keluar')
                        ->sum('jumlah');
                    $jumlah_penjualan = App\Models\Stok::where('id_produk', $item->id)
                        ->where('jenis', 'Penjualan')
                        ->sum('jumlah');
                    $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;

                    if ($jumlah <= 5) {
                        $color = 'danger';
                    } elseif ($jumlah <= 15) {
                        $color = 'warning';
                    } else {
                        $color = 'primary';
                    }
                @endphp
                @include('admin.dashboard_component.card_produk', [
                    'jumlah' => $jumlah,
                    'nama_produk' => $item->nama_produk,
                    'color' => $color,
                    'image' =>
                        $item->foto_produk != null || $item->foto_produk != ''
                            ? Storage::url($item->foto_produk)
                            : asset('img/logo.png'),
                ])
            @endforeach

            <div class="mt-4">
                {{ $produk_list->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    @endif

    @include('admin.quick_action.penjualan_' . Auth::user()->role)
@endsection
