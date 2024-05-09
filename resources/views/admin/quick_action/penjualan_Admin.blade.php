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
                            <option value="">Pilih Produk
                            </option>
                            @foreach (App\Models\Produk::latest()->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->jenis->jenis }} -
                                    {{ $item->nama_produk }}
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(function() {
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
                            '] | Stok : ' + varian.stok));

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
    });
</script>
