@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="mb-3">
        <a target="__blank" href="{{ url('/peramalan/pdf') }}" class="btn btn-danger"><i class="bx bxs-file-pdf"></i> Download
        </a>
    </div>
    <div class="card">
        <div class="card-header">
            <h4>Hasil Peramalan semua produk bulan : {{ $bulan }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="dattable">
                    <thead>
                        <tr>
                            <th style="width: 15px;">No</th>
                            <th>Produk</th>
                            <th>Bulan Pertama</th>
                            <th>Bulan Kedua</th>
                            <th>Bulan Ketiga</th>
                            <th>Bulan Diramal</th>
                            <th>Tahun</th>
                            <th>Moving Average</th>
                            <th>MAD</th>
                            <th >MAPE</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->produk->nama_produk }}</td>
                                <td>
                                    <strong> {{ $item->periode_1 }} </strong><br>
                                    <small class="text-muted"> {{ $item->bulan_1 }}</small>
                                </td>
                                <td>
                                    <strong> {{ $item->periode_2 }} </strong><br>
                                    <small class="text-muted"> {{ $item->bulan_2 }}</small>
                                </td>
                                <td>
                                    <strong> {{ $item->periode_3 }} </strong><br>
                                    <small class="text-muted"> {{ $item->bulan_3 }}</small>
                                </td>
                                <td>
                                    <strong> {{ $item->periode_n }} </strong><br>
                                    <small class="text-muted"> {{ $item->bulan_n }}</small>
                                </td>
                                <td>{{ $item->tahun }}</td>
                                <td>{{ $item->total_ma }}</td>
                                <td>{{ $item->mad }}</td>
                                <td>{{ $item->mape }} %</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#datatable').DataTable({
            processing: true,
            serverSide: false,
        });
    </script>
@endpush
