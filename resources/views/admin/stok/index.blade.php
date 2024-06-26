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
                    <table id="datatable-stok" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>
                                <th>Jenis</th>
                                <th>varian</th>
                                <th>Jumlah</th>
                                <th>Oleh</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>
                                <th>Jenis</th>
                                <th>varian</th>
                                <th>Jumlah</th>
                                <th>Oleh</th>
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
            $('#datatable-stok').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('stok-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },

                    {
                        data: 'produk.nama_produk',
                        name: 'produk.nama_produk'
                    },
                    {
                        data: 'jenis_txt',
                        name: 'jenis_txt'
                    },
                    {
                        data: 'varian',
                        name: 'varian'
                    },

                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                ],
                // initComplete: function() {
                //     var table = this;
                //     table.api().columns().every(function(index) {
                //         if (index === 1 || index === 2 || index === 3 || index === 4 ||
                //             index === 5 || index === 6) {
                //             var column = this;
                //             var title = column.header().textContent.trim();

                //             var input = document.createElement('input');
                //             input.placeholder = 'Search ' + title;
                //             input.classList.add('form-control-sm');
                //             // Menambahkan input ke dalam header
                //             $(table.api().column(index).header()).empty().append(input);

                //             $(input).on('keyup change clear', function() {
                //                 if (column.search() !== this.value) {
                //                     column.search(this.value).draw();
                //                 }
                //             });
                //         }
                //     });
                // },
            });
            $('.refresh').click(function() {
                $('#datatable-stok').DataTable().ajax.reload();
            });
        });
    </script>
@endpush
