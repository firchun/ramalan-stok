@extends('layouts.frontend.app')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h1 class="page-name">{{ $title }}</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li class="active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container my-4">
            <div class="row">
                <div class="col-md-6">
                    <img class="img-responsive" src="{{ asset('img') }}/logo.png">
                </div>
                <div class="col-md-6">
                    <h2 class="mt-40">Tentang {{ env('APP_NAME') ?? '' }}</h2>
                    <p>Rcollection ialah sebuah usaha dagang yang dirintis oleh Rahma Putri sejak tahun 2017. Usaha ini
                        dimulai dengan menjual kebutuhan sandang berupa pakaian yang dipasarkan melalui media online yaitu
                        facebook. Saat ini Rcollection beroperasi dengan menjual berbagai jenis kebutuhan berupa pakaian,
                        sepatu, tas, dan alat serta produk make up</p>
                    <hr>

                </div>
            </div>
            {{-- <hr>
            <h3 class="text-center mt-4">Mitra Kami </h3>
            <table class="table table-hover ">
                @foreach ($mitra as $item)
                    <tr>
                        <td style="width: 10px;">{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td>{{ $item->no_hp }}</td>
                    </tr>
                @endforeach
            </table> --}}
        </div>
    </section>
@endsection
