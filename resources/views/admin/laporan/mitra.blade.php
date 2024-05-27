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
                    <hr>
                    <div style="margin-left:24px; margin-right: 24px;">
                        <strong>Filter Data</strong>
                        <div class="d-flex justify-content-center align-items-center row gap-3 gap-md-0">

                            <div class="col-md-4 col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Periode Tanggal</span>
                                    <input type="date" id="tanggalAwal" name="tanggal_awal" class="form-control">
                                    <input type="date" id="tanggalAkhir" name="tanggal_akhir" class="form-control">

                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Jenis</span>
                                    <select id="selectJenis" name="jenis" class="form-select">
                                        <option value="">Semua</option>
                                        <option value="Penjualan">Penjualan</option>
                                        <option value="Masuk">Masuk</option>
                                        <option value="Keluar">Keluar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="input-group">
                                    <span class="input-group-text">Mitra</span>
                                    <select id="selectMitra" name="mitra" class="form-select">
                                        <option value="">Semua</option>
                                        @foreach (App\Models\User::where('role', 'Mitra')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-12 ">
                                <button type="button" id="filter" class="btn btn-primary"><i class="bx bx-filter"></i>
                                    Filter</button>

                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="card-datatable ">
                    <table id="datatable-riwayat-stok" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Mitra</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Mitra</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Jenis</th>
                                <th>Jumlah</th>
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
            var table = $('#datatable-riwayat-stok').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('all-stok-mitra-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'user.name',
                        name: 'user.name'
                    },
                    {
                        data: 'produk.nama_produk',
                        name: 'produk.nama_produk'
                    },
                    {
                        data: 'varian',
                        name: 'varian'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
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

                dom: 'Blfrtip',
                buttons: [{
                        text: '<i class="bx bxs-file-pdf"></i> PDF',
                        className: 'btn-danger mx-3',
                        action: function(e, dt, button, config) {
                            window.location = '{{ url('/report/printMitra') }}?tanggal_awal=' + $(
                                    '#tanggalAwal').val() +
                                '&tanggal_akhir=' + $('#tanggalAkhir').val() + '&jenis=' +
                                $('#selectJenis').val() + '&mitra=' + $('#selectMitra').val()
                            '';
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="bx bxs-file-export"></i> Excel',
                        className: 'btn-success',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });
            $('.refresh').click(function() {
                $('#datatable-riwayat-stok').DataTable().ajax.reload();
            });
            $('#filter').click(function() {
                table.ajax.url('{{ url('all-stok-mitra-datatable') }}?tanggal_awal=' + $(
                        '#tanggalAwal')
                    .val() + '&tanggal_akhir=' + $('#tanggalAkhir').val() +
                    '&jenis=' + $('#selectJenis').val() + '&mitra=' + $('#selectMitra').val()).load();
            });
        });
    </script>
    <!-- JS DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush
