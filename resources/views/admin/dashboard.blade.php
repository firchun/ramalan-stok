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


    @include('admin.quick_action.penjualan_' . Auth::user()->role)
@endsection
