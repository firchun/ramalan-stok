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
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.create-new').click(function() {
                $('#create').modal('show');
            });
            $('.refresh').click(function() {
                $('#datatable-produk').DataTable().ajax.reload();
            });
            window.editCustomer = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/produk/edit/' + id,
                    success: function(response) {
                        $('#formProdukId').val(response.id);
                        $('#formNamaProduk').val(response.nama_produk);
                        $('#formKeteranganProduk').val(response.keterangan_produk);
                        $('#customersModal').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            window.tambahStok = function(id) {
                $('#tambahStok').modal('show');
                $('#btnTambahStok').data('id', id);
            };
            window.kurangStok = function(id) {
                $('#kurangStok').modal('show');
                $('#btnKurangStok').data('id', id);
            };
            $('#btnPenjualan').click(function() {
                var jumlah = $('#formPenjualanJumlah').val();
                var id_produk = $('#formPenjualanIdProduk').val();

                $.ajax({
                    type: 'POST',
                    url: '/penjualan/store',
                    data: {
                        jumlah: jumlah,
                        id_produk: id_produk,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formPenjualanJumlah').val('');
                        $('#datatable-produk').DataTable().ajax.reload();
                        $('#penjualan').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#btnTambahStok').click(function() {
                var jumlah = $('#formTambahStokJumlah').val();
                var id_produk = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '/tambah-stok/' + id_produk,
                    data: {
                        jumlah: jumlah,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formTambahStokJumlah').val('');
                        $('#datatable-produk').DataTable().ajax.reload();
                        $('#tambahStok').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#btnKurangStok').click(function() {
                var jumlah = $('#formKurangStokJumlah').val();
                var id_produk = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '/kurang-stok/' + id_produk,
                    data: {
                        jumlah: jumlah,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#formKurangStokJumlah').val('');
                        $('#datatable-produk').DataTable().ajax.reload();

                        $('#kurangStok').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#saveProdukBtn').click(function() {
                var formData = $('#produkForm').serialize()[0];

                $.ajax({
                    type: 'POST',
                    url: '/produk/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-produk').DataTable().ajax.reload();
                        $('#customersModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createProdukBtn').click(function() {
                var formData = new FormData($('#createProdukForm')[0]);
                $.ajax({
                    type: 'POST',
                    url: '/produk/store',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#formCreateNamaProduk').val('');
                        $('#formCreateIdJenisProduk').val('');
                        $('#formCreateFotoProduk').val('');
                        $('#formCreateKeteranganProduk').val('');
                        $('#datatable-produk').DataTable().ajax.reload();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.deleteCustomers = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus  Produk ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/produk/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-produk').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };
        });
    </script>
@endpush
