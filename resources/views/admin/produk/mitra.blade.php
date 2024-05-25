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
                                    <span class="d-none d-sm-inline-block"></span>
                                </span>
                            </button>

                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-produk" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th style="width:100px;">ID</th>
                                <th style="width:100px;">Foto Produk</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th style="width:100px;">Stok</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Foto Produk</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Stok</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lihatSemuaVarian" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Form for Create and Edit -->
                    <h3>Semua Varian</h3>
                    <table id="datatable-varian-2" class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Varian</th>
                                <th>Ukuran</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'varian',
                        name: 'varian'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    }
                ],
                initComplete: function() {
                    var table = this;
                    table.api().columns().every(function(index) {
                        if (index === 2 || index === 3 || index === 4) {
                            var column = this;
                            var title = column.header().textContent.trim();

                            var input = document.createElement('input');
                            input.placeholder = 'Search ' + title;
                            input.classList.add('form-control-sm');
                            // Menambahkan input ke dalam header
                            $(table.api().column(index).header()).empty().append(input);

                            $(input).on('keyup change clear', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                        }
                    });
                }
            });

            $('.refresh').click(function() {
                $('#datatable-produk').DataTable().ajax.reload();
            });
            window.lihatSemuaVarian = function(id) {
                $('#datatable-varian-2').DataTable().destroy();
                $('#lihatSemuaVarian').modal('show');
                $('#datatable-varian-2').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: '/varian-datatable/' + id,
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'warna',
                            name: 'warna'
                        },
                        {
                            data: 'ukuran_text',
                            name: 'ukuran_text'
                        },
                        {
                            data: 'jumlah',
                            name: 'jumlah'
                        },


                    ]
                });
            };


        });
    </script>
@endpush
