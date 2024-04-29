@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
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
                                    <span class="d-none d-sm-inline-block">Muat Ulang Data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-mitra" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Nama mitra</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Avatar</th>
                                <th>Nama mitra</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Action</th>
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
            $('#datatable-mitra').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('mitra-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'avatar',
                        name: 'avatar'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'produk',
                        name: 'produk'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'action_stok',
                        name: 'action_stok'
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-mitra').DataTable().ajax.reload();
            });
            window.detailMitra = function(id) {
                var redirectUrl = '/stok/mitra/detail/' + id;
                window.location.href = redirectUrl;
            };
        });
    </script>
@endpush
