@extends('layouts.backend.admin')

@section('content')
    <div class="jumbroton text-center mb-5">
        <img src="{{ asset('/img/logo.png') }}" style="width: 100px;">
        <h4>Selamat Datang di {{ env('APP_NAME') ?? 'Laravel' }}</h4>
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
@endsection
