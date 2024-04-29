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
                            @foreach (App\Models\Produk::latest()->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->jenis->jenis }} -
                                    {{ $item->nama_produk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formPenjualanJumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="formPenjualanJumlah" name="jumlah" required>
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
<script>
    $(function() {
        $('.penjualan').click(function() {
            $('#penjualan').modal('show');
        });
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
    });
</script>
