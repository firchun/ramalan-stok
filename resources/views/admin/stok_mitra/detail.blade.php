@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row ">
        <div class="d-flex justify-content-end mb-4">
            <div class="dt-action-buttons text-end pt-3 pt-md-0">
                <div class=" btn-group " role="group">
                    <button class="btn btn-md btn-secondary refresh btn-default" type="button">
                        <span>
                            <i class="bx bx-sync me-sm-1"> </i>
                            {{-- <span class="d-none d-sm-inline-block"></span> --}}
                        </span>
                    </button>
                    @if (Auth::user()->role == 'Mitra')
                        <button class="btn btn-secondary return btn-primary" type="button" data-bs-toggle="modal"
                            data-bs-target="#return">
                            <span>
                                <i class="bx bx-undo me-sm-1"> </i>
                                <span class="d-none d-sm-inline-block">Return Stok</span>
                            </span>
                        </button>
                        <button class="btn btn-secondary penjualan btn-primary" type="button" data-bs-toggle="modal"
                            data-bs-target="#penjualan">
                            <span>
                                <i class="bx bx-cart me-sm-1"> </i>
                                <span class="d-none d-sm-inline-block">Penjualan</span>
                            </span>
                        </button>
                    @else
                        <button class="btn btn-secondary create-new btn-primary" type="button" data-bs-toggle="modal"
                            data-bs-target="#create">
                            <span>
                                <i class="bx bx-plus me-sm-1"> </i>
                                <span class="d-none d-sm-inline-block">Tambah Stok</span>
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }}</h5>
                    </div>

                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-stok" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width:100px;">Foto Produk</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Jumlah</th>

                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th style="width:100px;">Foto Produk</th>
                                <th>Nama Produk</th>
                                <th>Varian</th>
                                <th>Jumlah</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">Riwayat Stok</h5>
                    </div>

                </div>
                <div class="card-datatable ">
                    <table id="datatable-riwayat-stok" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama Produk</th>

                                <th>Jumlah</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal  --}}
    @if (Auth::user()->role != 'Mitra')
        <div class="modal fade" id="tambahStok" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <!-- Form for Create and Edit -->
                        <form id="formTambahStok" enctype="multipart/form-data">
                            <input type="hidden" name="id_user" id="idUser" value="{{ $user->id }}">
                            <div class="mb-3">
                                <label for="formSelectProduk" class="form-label">Produk</label>
                                <select class="form-select" id="formSelectProduk" name="id_produk">
                                    @foreach (App\Models\Produk::latest()->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->jenis->jenis }} -
                                            {{ $item->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="formTambahStokStokVarian" class="form-label">Pilih Varian</label>
                                <select id="formTambahStokStokVarian" class="form-select" name="id_varian"></select>
                            </div>
                            <div class="mb-3">
                                <label for="formTambahStokJumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="formTambahStokJumlah" name="jumlah"
                                    required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnTambahStok">Tambah</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if (Auth::user()->role == 'Mitra')
        <div class="modal fade" id="return" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-body">
                        <h5>Return Stok</h5>
                        <!-- Form for Create and Edit -->
                        <form id="formReturnStok" enctype="multipart/form-data">
                            <input type="hidden" name="id_user" id="idUserMitra" value="{{ Auth::id() }}">
                            <input type="hidden" name="jenis" id="jenis" value="Return">
                            <div class="mb-3">
                                <label for="formReturnSelectProduk" class="form-label">Produk</label>
                                <select class="form-select" id="formReturnSelectProduk" name="id_produk">
                                    <option value="">--Pilih Produk--</option>
                                    @foreach (App\Models\StokMitra::select('id_produk')->selectRaw('id_produk')->with('produk')->where('id_user', Auth::id())->groupBy('id_produk')->get() as $item)
                                        <option value="{{ $item->produk->id }}">{{ $item->produk->jenis->jenis }} -
                                            {{ $item->produk->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="formReturnStokVarian" class="form-label">Pilih Varian</label>
                                <select id="formReturnStokVarian" class="form-select" name="id_varian"></select>
                            </div>
                            <div class="mb-3">
                                <label for="formReturnStokJumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="formReturnStokJumlah" name="jumlah"
                                    required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnReturnStok">Ajukan Return</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="penjualan" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <!-- Form for Create and Edit -->
                        <form id="formPenjualan" enctype="multipart/form-data">
                            <h3>Penjualan</h3>
                            <div class="mb-3">
                                <label for="formPenjualanIdProduk" class="form-label">Pilih Produk</label>
                                <select class="form-select" id="formPenjualanIdProduk" name="id_produk">
                                    <option value="">--Pilih Produk--</option>
                                    @foreach (App\Models\StokMitra::select('id_produk')->selectRaw('id_produk')->with('produk')->where('id_user', Auth::id())->groupBy('id_produk')->get() as $item)
                                        <option value="{{ $item->id_produk }}">{{ $item->produk->jenis->jenis }} -
                                            {{ $item->produk->nama_produk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="formPenjualanVarian" class="form-label">Pilih Varian</label>
                                <select id="formPenjualanVarian" class="form-select" name="id_varian"></select>
                            </div>
                            <div class="mb-3">
                                <label for="formPenjualanJumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="formPenjualanJumlah" name="jumlah"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="formPenjualanTanggal" class="form-label">Tanggal Penjualan</label>
                                <input type="datetime-local" class="form-control" id="formPenjualanTanggal"
                                    name="created_at" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnPenjualan">Jual</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- end modal  --}}
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-stok').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('stok-mitra-datatable', $user->id) }}',
                columns: [{
                        data: 'id_produk',
                        name: 'id_produk',
                    },

                    {
                        data: 'foto',
                        name: 'foto'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        searchable: true
                    },
                    {
                        data: 'varian',
                        name: 'varian',
                        searchable: true
                    },

                    {
                        data: 'total_jumlah',
                        name: 'total_jumlah',
                        searchable: true
                    },
                ],
                search: {
                    smart: true,
                    regex: true
                },
                // initComplete: function() {
                //     var table = this;
                //     table.api().columns().every(function(index) {
                //         if (index === 2 || index === 3 || index === 4) {
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
                // }


                // dom: 'lrtip',
            });

            $('#datatable-riwayat-stok').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('riwayat-stok-mitra-datatable', $user->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },

                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                ],

                dom: '<"top"f>rt<"bottom"p>',
                search: {
                    smart: true
                }
            });
            $('.refresh').click(function() {
                $('#datatable-stok').DataTable().ajax.reload();
                $('#datatable-riwayat-stok').DataTable().ajax.reload();
            });

            $('.return').click(function() {
                $('#return').modal('show');
            });
            $('.penjualan').click(function() {
                $('#penjualan').modal('show');
            });
            $('.create-new').click(function() {
                $('#tambahStok').modal('show');
            });
            $('#formPenjualanIdProduk').change(function() {
                var id_produk = $(this).val();
                $.ajax({
                    url: '/varian-list/' + id_produk,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var dropdown = $('#formPenjualanVarian');
                        dropdown.empty();
                        $.each(response, function(index, varian) {
                            let text = varian.nama + '  [' + (varian.jenis == 'ukuran' ?
                                    varian.ukuran : varian.nomor) + '] Stok : ' + varian
                                .stok;
                            dropdown.append($('<option></option>').attr('value', varian
                                    .id)
                                .text(text));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
            $('#formSelectProduk').change(function() {
                var id_produk = $(this).val();
                $.ajax({
                    url: '/varian-list/' + id_produk,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var dropdown = $('#formTambahStokStokVarian');
                        dropdown.empty();
                        $.each(response, function(index, varian) {
                            let text = varian.nama + '  [' + (varian.jenis == 'ukuran' ?
                                    varian.ukuran : varian.nomor) + '] Stok : ' + varian
                                .stok;
                            dropdown.append($('<option></option>').attr('value', varian
                                    .id)
                                .text(text));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
            $('#formReturnSelectProduk').change(function() {
                var id_produk = $(this).val();
                $.ajax({
                    url: '/varian-list/' + id_produk,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var dropdown = $('#formReturnStokVarian');
                        dropdown.empty();
                        $.each(response, function(index, varian) {
                            let text = varian.nama + '  [' + (varian.jenis == 'ukuran' ?
                                    varian.ukuran : varian.nomor) + '] Stok : ' + varian
                                .stok;
                            dropdown.append($('<option></option>').attr('value', varian
                                    .id)
                                .text(text));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
            $('#btnPenjualan').click(function() {
                var jumlah = $('#formPenjualanJumlah').val();
                var id_produk = $('#formPenjualanIdProduk').val();
                var id_varian = $('#formPenjualanVarian').val();
                var created_at = $('#formPenjualanTanggal').val();

                $.ajax({
                    type: 'POST',
                    url: '/penjualan/store',
                    data: {
                        jumlah: jumlah,
                        id_produk: id_produk,
                        id_varian: id_varian,
                        created_at: created_at,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formPenjualanJumlah').val('');
                        $('#datatable-riwayat-stok').DataTable().ajax.reload();
                        $('#datatable-stok').DataTable().ajax.reload();
                        $('#penjualan').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#btnReturnStok').click(function() {
                var jumlah = $('#formReturnStokJumlah').val();
                var id_produk = $('#formReturnSelectProduk').val();
                var id_user = $('#idUserMitra').val();
                var id_varian = $('#formReturnStokVarian').val();

                $.ajax({
                    type: 'POST',
                    url: '/return-stok-mitra',
                    data: {
                        jumlah: jumlah,
                        id_produk: id_produk,
                        id_user: id_user,
                        id_varian: id_varian,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formReturnStokJumlah').val('');
                        $('#datatable-riwayat-stok').DataTable().ajax.reload();
                        $('#datatable-stok').DataTable().ajax.reload();
                        $('#return').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#btnTambahStok').click(function() {
                var jumlah = $('#formTambahStokJumlah').val();
                var id_produk = $('#formSelectProduk').val();
                var id_user = $('#idUser').val();
                var id_varian = $('#formTambahStokStokVarian').val();

                $.ajax({
                    type: 'POST',
                    url: '/tambah-stok-mitra',
                    data: {
                        jumlah: jumlah,
                        id_produk: id_produk,
                        id_user: id_user,
                        id_varian: id_varian,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formTambahStokJumlah').val('');
                        $('#datatable-riwayat-stok').DataTable().ajax.reload();
                        $('#datatable-stok').DataTable().ajax.reload();
                        $('#tambahStok').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>
@endpush
