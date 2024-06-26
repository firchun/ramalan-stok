@push('js')
    <script>
        $(function() {
            var tableProduk = $('#datatable-produk').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('produk-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id',
                        searchable: false,
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
                        data: 'harga_modal',
                        name: 'harga_modal'
                    },
                    {
                        data: 'harga_jual',
                        name: 'harga_jual'
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
                ],
                // initComplete: function() {
                //     var table = this;
                //     table.api().columns().every(function(index) {
                //         if (index === 2 || index === 4 || index === 5) {
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
                dom: 'lrtip',
            });
            $('#custom-search').on('keyup', function() {
                tableProduk.search($(this).val()).draw();
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
            $('.refresh').click(function() {
                $('#datatable-produk').DataTable().ajax.reload();
            });
            window.editCustomer = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/produk/edit/' + id,
                    success: function(response) {
                        //foto
                        if (response.foto_produk != null) {
                            $('#viewFotoForUpdate').css('display', 'block');
                            var fotoUrl = response.foto_url;
                            $('#viewFotoForUpdate').attr('src', fotoUrl);
                        } else {
                            $('#viewFotoForUpdate').css('display', 'none');

                        }
                        $('#formProdukId').val(response.id);
                        $('#formEditHargaModal').val(response.harga_modal);
                        $('#formEditHargaJual').val(response.harga_jual);
                        $('#formEditHargaDsicount').val(response.harga_discount);
                        $('#formEditDiscount').val(response.discount);
                        $('#formNamaEditProduk').val(response.nama_produk);
                        $('#formEditKeteranganProduk').val(response.keterangan_produk);
                        if (response.is_discount == 1) {
                            $('#formEditIsDiscount').prop('checked', true);
                        } else {
                            $('#formEditIsDiscount').prop('checked', false);
                        }
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
                            let text = varian.nama + '  [' + (varian.jenis == 'ukuran' ?
                                    varian.ukuran : varian.nomor) + '] Stok : ' + varian
                                .stok;
                            dropdown.append($('<option></option>').attr('value', varian.id)
                                .text(text));
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
                            data: 'foto_view',
                            name: 'foto_view'
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
                            data: 'delete',
                            name: 'delete'
                        }
                    ]
                });
            };
            window.updateVarian = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/varian/edit/' + id,
                    success: function(response) {

                        $('#formUpdateVarianId').val(response.id);
                        $('#formUpdateVarianIdProduk').val(response.id_produk);
                        $('#formUpdateNamaVarian').val(response.nama);
                        $('#formUpdateKodeWarna').val(response.kode_warna);
                        $('#FormUpdateNomorVarian').val(response.nomor);

                        $('#updateVarian').modal('show');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });

            };
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
            // $('#btnUpdateVarian').click(function() {
            //     var id = $('#formUpdateVarianId').val();
            //     var id_produk = $('#formUpdateVarianIdProduk').val();
            //     var kode_warna = $('#formUpdateKodeWarna').val();
            //     var nama = $('#formUpdateNamaVarian').val();
            //     var ukuran = $('#formUpdateUkuranVarian').val();
            //     var jenis = $('#formUpdateJenisVarian').val();
            //     var nomor = $('#FormUpdateNomorVarian').val();
            //     var file = $('#formFotoVarian')[0].files[0];

            //     $.ajax({
            //         type: 'POST',
            //         url: '/varian/store',
            //         data: {
            //             kode_warna: kode_warna,
            //             nama: nama,
            //             ukuran: ukuran,
            //             id: id,
            //             id_produk: id_produk,
            //             jenis: jenis,
            //             nomor: nomor,
            //             foto: file,
            //             _token: $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             alert(response.message);
            //             $('#updateVarian').modal('hide');
            //             $('#datatable-varian').DataTable().ajax.reload();
            //             $('#datatable-produk').DataTable().ajax.reload();
            //         },
            //         error: function(xhr) {
            //             alert('Terjadi kesalahan: ' + xhr.responseText);
            //         }
            //     });
            // });
            $('#btnUpdateVarian').click(function() {
                var id = $('#formUpdateVarianId').val();
                var id_produk = $('#formUpdateVarianIdProduk').val();
                var kode_warna = $('#formUpdateKodeWarna').val();
                var nama = $('#formUpdateNamaVarian').val();
                var ukuran = $('#formUpdateUkuranVarian').val();
                var jenis = $('#formUpdateJenisVarian').val();
                var nomor = $('#FormUpdateNomorVarian').val();
                var file = $('#formFotoVarian')[0].files[0];
                if (!file) {
                    file = null;
                }

                // Membuat objek FormData
                var formData = new FormData();
                formData.append('kode_warna', kode_warna);
                formData.append('nama', nama);
                formData.append('ukuran', ukuran);
                formData.append('id', id);
                formData.append('id_produk', id_produk);
                formData.append('jenis', jenis);
                formData.append('nomor', nomor);
                formData.append('foto', file); // Menambahkan file ke formData
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    type: 'POST',
                    url: '/varian/store',
                    data: formData, // Mengirim formData
                    processData: false, // Memproses data secara manual
                    contentType: false, // Tidak mengatur tipe konten secara otomatis
                    success: function(response) {
                        alert(response.message);
                        $('#updateVarian').modal('hide');
                        $('#datatable-varian').DataTable().ajax.reload();
                        $('#datatable-produk').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });

            // $('#btnSimpanVarian').click(function() {
            //     var id_produk = $('#formVarianIdProduk').val();
            //     var kode_warna = $('#formKodeWarna').val();
            //     var nama = $('#FormNamaVarian').val();
            //     var ukuran = $('#formUkuranVarian').val();
            //     var jenis = $('#formJenisVarian').val();
            //     var nomor = $('#FormNomorVarian').val();
            //     var file = $('#formFotoVarian')[0].files[0];
            //     $.ajax({
            //         type: 'POST',
            //         url: '/varian/store',
            //         data: {
            //             kode_warna: kode_warna,
            //             nama: nama,
            //             ukuran: ukuran,
            //             id_produk: id_produk,
            //             jenis: jenis,
            //             nomor: nomor,
            //             foto: file,
            //             _token: $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function(response) {
            //             alert(response.message);
            //             // console.log(response);
            //             // $('#formVarianIdProduk').val('');
            //             // $('#formKodeWarna').val('');
            //             $('#formNamaVarian').val('');
            //             // $('#formUkuranVarian').val('');
            //             $('#FormNomorVarian').val('');
            //             $('#datatable-varian').DataTable().ajax.reload();
            //             $('#datatable-produk').DataTable().ajax.reload();
            //         },
            //         error: function(xhr) {
            //             alert('Terjadi kesalahan: ' + xhr.responseText);
            //         }
            //     });
            // });
            $('#btnSimpanVarian').click(function() {
                var id_produk = $('#formVarianIdProduk').val();
                var kode_warna = $('#formKodeWarna').val();
                var nama = $('#FormNamaVarian').val();
                var ukuran = $('#formUkuranVarian').val();
                var jenis = $('#formJenisVarian').val();
                var nomor = $('#FormNomorVarian').val();
                var file = $('#FormFotoVarian')[0].files[0];
                if (!file) {
                    file = null;
                }
                // Membuat objek FormData
                var formData = new FormData();
                formData.append('kode_warna', kode_warna);
                formData.append('nama', nama);
                formData.append('ukuran', ukuran);
                formData.append('id_produk', id_produk);
                formData.append('jenis', jenis);
                formData.append('nomor', nomor);
                formData.append('foto', file);
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    type: 'POST',
                    url: '/varian/store',
                    data: formData,
                    processData: false, // Biarkan jQuery mengolah data secara otomatis
                    contentType: false, // Biarkan jQuery menentukan jenis konten secara otomatis
                    success: function(response) {
                        alert(response.message);
                        // Reset nilai form setelah berhasil disimpan
                        $('#formNamaVarian').val('');
                        $('#FormNomorVarian').val('');
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
                            let text = varian.nama + '  [' + (varian.jenis == 'ukuran' ?
                                    varian.ukuran : varian.nomor) + '] Stok : ' + varian
                                .stok;
                            dropdown.append($('<option></option>').attr('value', varian.id)
                                .text(text));
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
                var formData = new FormData($('#produkForm')[0]);

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
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-produk').DataTable().ajax.reload();
                        $('#formEditFotoProduk').val('');
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
            window.deleteVarian = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus  Varian Ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/varian/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-produk').DataTable().ajax.reload();
                            $('#datatable-varian').DataTable().ajax.reload();
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
