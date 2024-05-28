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
                <div class="card-datatable ">
                    <table id="datatable-riwayat-stok" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Mitra</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Jumlah</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Mitra</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Jumlah</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="konfirmasi" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <h5>Konfirmasi Return Stok</h5>
                    <!-- Form for Create and Edit -->
                    <form id="formKonfirmasi" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="idStok">
                        <input type="hidden" name="jumlah_awal" id="jumlahAwal">
                        <div class="mb-3">
                            <label for="formKonfirmasiJumlah" class="form-label">Konfirmasi Jumlah</label>
                            <input type="number" class="form-control" id="formKonfirmasiJumlah" name="jumlah" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnKonfirmasiStok">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-riwayat-stok').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('return-stok-datatable') }}',
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
                        data: 'nama',
                        name: 'nama'
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
                        data: 'return',
                        name: 'return'
                    },
                ],
                // initComplete: function() {
                //     var table = this;
                //     table.api().columns().every(function(index) {
                //         if (index === 1 || index === 2 || index === 3 || index === 4 ||
                //             index === 5) {
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
                // dom: '<"top"l>rt<"bottom"p>'
            });
            $('.refresh').click(function() {
                $('#datatable-riwayat-stok').DataTable().ajax.reload();
            });
            window.returnStok = function(id, jumlah) {
                $('#idStok').val(id);
                $('#jumlahAwal').val(jumlah);
                $('#konfirmasi').modal('show');
            };
            $('#btnKonfirmasiStok').click(function() {
                var jumlah = $('#formKonfirmasiJumlah').val();
                var id = $('#idStok').val();
                var jumlah_awal = $('#jumlahAwal').val();

                $.ajax({
                    type: 'POST',
                    url: '/konfirmasi-return',
                    data: {
                        jumlah: jumlah,
                        id: id,
                        jumlah_awal: jumlah_awal,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formKonfirmasiJumlah').val('');
                        $('#datatable-riwayat-stok').DataTable().ajax.reload();
                        $('#konfirmasi').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
