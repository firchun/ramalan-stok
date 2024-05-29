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
                                    <span class="d-none d-sm-inline-block">Perbarui data</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-hasil" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Bulan Pertama</th>
                                <th>Bulan Kedua</th>
                                <th>Bulan Ketiga</th>
                                <th>Bulan Diramal</th>
                                <th>Tahun</th>
                                <th>Moving Average</th>
                                {{-- <th>Error</th> --}}
                                <th style="width:150px;">Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Bulan Pertama</th>
                                <th>Bulan Kedua</th>
                                <th>Bulan Ketiga</th>
                                <th>Bulan Diramal</th>
                                <th>Tahun</th>
                                <th>Moving Average</th>
                                {{-- <th>Error</th> --}}
                                <th style="width:150px;">Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- modal --}}
    <div class="modal fade" id="show" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <!-- Form for Create and Edit -->
                    <h3 id="tanggal"></h3>
                    <div class="" id="hasil-ramalan"></div>
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
            $('#datatable-hasil').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                ajax: '{{ url('hasil-ramalan-datatable', $produk->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'bulan_pertama',
                        name: 'bulan_pertama'
                    },
                    {
                        data: 'bulan_kedua',
                        name: 'bulan_kedua'
                    },
                    {
                        data: 'bulan_ketiga',
                        name: 'bulan_ketiga'
                    },
                    {
                        data: 'bulan_diramal',
                        name: 'bulan_diramal'
                    },
                    {
                        data: 'tahun',
                        name: 'tahun'
                    },
                    {
                        data: 'total_ma',
                        name: 'total_ma'
                    },
                    // {
                    //     data: 'total_error',
                    //     name: 'total_error',
                    //     render: function(data, type, row) {
                    //         if (type === 'display' && data !== null) {
                    //             var roundedData = Math.round(parseFloat(data));
                    //             return roundedData.toString() + ' %';
                    //         } else {
                    //             return data;
                    //         }
                    //     }
                    // },

                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                dom: 'Blrtip',
                buttons: [

                    {
                        extend: 'excelHtml5',
                        text: '<i class="bx bxs-file-export "></i> Excel',
                        className: 'btn-success mx-3',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ]
            });

            $('.refresh').click(function() {
                $('#datatable-hasil').DataTable().ajax.reload();
            });
            window.show = function(id) {

                $.ajax({
                    type: 'GET',
                    url: '/peramalan/show/' + id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // alert(response.message);
                        // console.log(response);
                        $('#show').modal('show');
                        // Clear existing content
                        $('#hasil-ramalan').empty();

                        var data = response[0];
                        var createdAtFormatted = new Date(data.created_at);
                        var formattedDate = ("0" + createdAtFormatted.getDate()).slice(-2);
                        var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                        ];
                        var formattedMonth = monthNames[createdAtFormatted.getMonth()];
                        var formattedYear = createdAtFormatted.getFullYear();
                        var formattedHours = ("0" + createdAtFormatted.getHours()).slice(-2);
                        var formattedMinutes = ("0" + createdAtFormatted.getMinutes()).slice(-2);
                        var ampm = formattedHours >= 12 ? 'PM' : 'AM';
                        formattedHours = formattedHours % 12;
                        formattedHours = formattedHours ? formattedHours : 12;
                        var formattedTime = formattedHours + ':' + formattedMinutes + ' ' + ampm;
                        var htmlContent = '<div class="card mb-4">';
                        htmlContent +=
                            '<div class="card-header"><strong>Hasil Ramalan Produk yang dilakukan pada ' +
                            formattedDate + ' ' + formattedMonth + ' ' + formattedYear + ' ' +
                            formattedTime + '</strong></div>';
                        htmlContent +=
                            '<div class="card-body"><table class="table table-hover table-bordered">';

                        htmlContent += '<tbody>';
                        htmlContent += '<tr>';
                        htmlContent += '<td>Data Aktual Bulan ' + data.bulan_1 + '</td>';
                        htmlContent += '<td> ' + data.periode_1 + '</td>';
                        htmlContent += '<tr>';
                        htmlContent += '<td>Data Aktual Bulan ' + data.bulan_2 + '</td>';
                        htmlContent += '<td> ' + data.periode_2 + '</td>';
                        htmlContent += '</tr>';
                        htmlContent += '<tr>';
                        htmlContent += '<td>Data Aktual Bulan ' + data.bulan_3 + '</td>';
                        htmlContent += '<td> ' + data.periode_3 + '</td>';
                        htmlContent += '</tr>';
                        htmlContent += '<tr>';
                        htmlContent += '<td>Data Aktual Bulan ' + data.bulan_n + '</td>';
                        htmlContent += '<td> ' + data.periode_n + '</td>';
                        htmlContent += '</tr>';
                        htmlContent += '<tr>';
                        htmlContent += '<td>Moving Average (MA) </td>';
                        htmlContent += '<td> (P1+P2+P3)/3 <br> = (' + data.periode_1 +
                            ' + ' + data
                            .periode_2 + ' + ' + data.periode_3 + ')/3 <br>= ' + data
                            .total_ma + '</td>';
                        htmlContent += '</tr>';
                        // htmlContent += '<td>Error </td>';
                        // htmlContent += '<td> MA<sup>2</sup> <br> = ' + data
                        //     .total_ma + '<sup>2</sup> <br> = ' + Math.round(parseFloat(data
                        //         .total_error)) + ' %</td>';
                        // htmlContent += '</tr>';
                        htmlContent += '</tbody></table>';
                        htmlContent +=
                            '<h4 class="mt-2 text-primary" >Jadi, untuk prediksi stok penjualan di bulan :  ' +
                            data.bulan_n + ', adalah sebanyak : ' + data.total_ma +
                            ' </h4>';
                        htmlContent += '</div>';
                        htmlContent += '</div>';

                        // Append HTML content to the container
                        $('#hasil-ramalan').append(htmlContent);
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            window.deleteRamalan = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus hasil peramalan ini ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/peramalan/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            // alert(response.message);
                            $('#datatable-hasil').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };
        });
    </script>
    <!-- JS DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush
