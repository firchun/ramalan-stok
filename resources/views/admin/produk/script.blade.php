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
                        data: 'varian',
                        name: 'varian'
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
            $('.penjualan').click(function() {
                $('#penjualan').modal('show');
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
                            dropdown.append($('<option></option>').attr('value', varian
                                .id).text(varian.nama + '  [' + varian.ukuran +
                                '] - Stok : ' + varian.stok));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
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
                $.ajax({
                    url: '/varian-list/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        var dropdown = $('#formTambahStokVarian');
                        dropdown.empty();
                        $.each(response, function(index, varian) {
                            dropdown.append($('<option></option>').attr('value', varian.id)
                                .text(
                                    varian.nama + '  [' + varian.ukuran + ']'));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });


            };
            window.editVarian = function(id) {
                $('#datatable-varian').DataTable().destroy();
                $('#varianProduk').modal('show');
                $('#formVarianIdProduk').val(id);

                $('#datatable-varian').DataTable({
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
                            data: 'ukuran',
                            name: 'ukuran'
                        },

                        {
                            data: 'delete',
                            name: 'delete'
                        }
                    ]
                });
            };
            $('#btnSimpanVarian').click(function() {
                var id_produk = $('#formVarianIdProduk').val();
                var kode_warna = $('#formKodeWarna').val();
                var nama = $('#FormNamaVarian').val();
                var ukuran = $('#formUkuranVarian').val();

                $.ajax({
                    type: 'POST',
                    url: '/varian/store',
                    data: {
                        kode_warna: kode_warna,
                        nama: nama,
                        ukuran: ukuran,
                        id_produk: id_produk,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // console.log(response);
                        $('#formVarianIdProduk').val('');
                        $('#formKodeWarna').val('');
                        $('#formNamaVarian').val('');
                        $('#formUkuranVarian').val('');
                        $('#datatable-varian').DataTable().ajax.reload();
                        $('#datatable-produk').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            window.kurangStok = function(id) {
                $('#kurangStok').modal('show');
                $('#btnKurangStok').data('id', id);
                $.ajax({
                    url: '/varian-list/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        var dropdown = $('#formKurangStokVarian');
                        dropdown.empty();
                        $.each(response, function(index, varian) {
                            dropdown.append($('<option></option>').attr('value', varian.id)
                                .text(
                                    varian.nama + '  [' + varian.ukuran +
                                    '] - Stok : ' +
                                    varian.stok));
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            };
            $('#btnPenjualan').click(function() {
                var jumlah = $('#formPenjualanJumlah').val();
                var id_produk = $('#formPenjualanIdProduk').val();
                var id_varian = $('#formPenjualanVarian').val();

                $.ajax({
                    type: 'POST',
                    url: '/penjualan/store',
                    data: {
                        jumlah: jumlah,
                        id_produk: id_produk,
                        id_varian: id_varian,
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
                var id_varian = $('#formTambahStokVarian').val();
                var id_produk = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '/tambah-stok/' + id_produk,
                    data: {
                        jumlah: jumlah,
                        id_varian: id_varian,
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
                var id_varian = $('#formKurangStokVarian').val();
                var id_produk = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '/kurang-stok/' + id_produk,
                    data: {
                        jumlah: jumlah,
                        id_varian: id_varian,
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
