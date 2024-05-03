@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="card mb-5">
        <div class="card-body">
            <strong class="mb-3">Buat Ramalan</strong>
            <div class="d-flex justify-content-center align-items-center row mt-2">
                <div class="col-md-3 col-12 mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Produk</span>
                        <select id="selectProduk" name="produk" class="form-select">
                            <option value="">Semua</option>
                            @foreach (App\Models\Produk::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Bulan</span>
                        <select id="selectBulan" name="bulan" class="form-select">
                            @foreach ($bulan as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-3">
                    <div class="input-group">
                        <span class="input-group-text">Tahun</span>
                        <select id="selectTahun" name="tahun" class="form-select">
                            @foreach ($tahun as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-12 mb-3 ">
                    <button type="button" id="btnRamal" class="btn btn-primary"><i class="bx bx-braille"></i>
                        Buat Ramalan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }}</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Perbarui data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-produk" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Ramalan</th>
                                <th style="width:150px;">Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Jumlah Ramalan</th>
                                <th style="width:150px;">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-produk').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('produk-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'hasil_ramalan',
                        name: 'hasil_ramalan'
                    },
                    {
                        data: 'action_ramalan',
                        name: 'action_ramalan'
                    }
                ]
            });

            $('.refresh').click(function() {
                $('#datatable-produk').DataTable().ajax.reload();
            });
            $('#btnRamal').click(function() {
                var id_produk = $('#selectProduk').val();
                var bulan = $('#selectBulan').val();
                var tahun = $('#selectTahun').val();

                $.ajax({
                    type: 'POST',
                    url: '/peramalan/store',
                    data: {
                        id_produk: id_produk,
                        bulan: bulan,
                        tahun: tahun,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                        $('#datatable-produk').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.hasilRamalan = function(id) {
                var url = "{{ url('/peramalan/hasil') }}/" + id;
                window.location.href = url;
            };
        });
    </script>
@endpush
