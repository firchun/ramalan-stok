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
                    <table id="datatable-pesanan" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th></th>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Pemesan</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Konfirmasi</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th></th>
                                <th>Tanggal</th>
                                <th>Invoice</th>
                                <th>Pemesan</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Konfirmasi</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bukti" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Form for Create and Edit -->
                    <form id="formBuktiPembayaran" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id_pesanan">
                        <div class="mb-3">
                            <label for="formBuktiPembayaran" class="form-label">Bukti Pembayaran</label>
                            <input type="file" id="formBuktiBayar" class="form-control" name="bukti_bayar">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnUploadPemabayaran">Upload</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function() {
             $('#datatable-pesanan').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('pesanan-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'btn_wa',
                        name: 'btn_wa'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'no_pesanan',
                        name: 'no_pesanan'
                    },
                    {
                        data: 'pemesan',
                        name: 'pemesan'
                    },
                    {
                        data: 'produk',
                        name: 'produk'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'konfirmasi',
                        name: 'konfirmasi'
                    },
                
                ],
                

                
            });
            $('.refresh').click(function() {
                $('#datatable-pesanan').DataTable().ajax.reload();
            });
            window.confirm = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/pesanan/konfirmasi/' + id,
                    success: function(response) {
                        alert(response.message);
                        $('#datatable-pesanan').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        $('#datatable-pesanan').DataTable().ajax.reload();
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            window.payment = function(id) {
                $('#id_pesanan').val(id);
                $('#bukti').modal('show');
            };
            $('#btnUploadPemabayaran').click(function() {
                var id = $('#id_pesanan').val();
                var file = $('#formBuktiBayar')[0].files[0];

                // Membuat objek FormData
                var formData = new FormData();
                
                formData.append('id', id);
                formData.append('bukti_bayar', file);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    type: 'POST',
                    url: '/pesanan/bayar/'+id,
                    data: formData,
                    processData: false, // Biarkan jQuery mengolah data secara otomatis
                    contentType: false, // Biarkan jQuery menentukan jenis konten secara otomatis
                    success: function(response) {
                        alert(response.message);
                        // Reset nilai form setelah berhasil disimpan
                        $('#datatable-pesanan').DataTable().ajax.reload();
                        $('#bukti').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
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
