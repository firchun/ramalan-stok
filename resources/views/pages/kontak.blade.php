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
           
            <h3 class="text-center mt-4">Mitra Kami </h3>
            <table class="table table-hover ">
                @foreach ($mitra as $item)
                    <tr>
                        <td style="width: 10px;">{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td><a href="https://wa.me/" target="__blank" class="text-success">{{ $item->no_hp }}</a></td>
                        <td>Alamat : {{$item->alamat}} </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </section>
@endsection
