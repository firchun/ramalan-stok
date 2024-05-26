<!-- Modal for Create and Edit -->
<div class="modal fade" id="customersModal" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Update Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="produkForm" enctype="multipart/form-data">
                    <input type="hidden" id="formProdukId" name="id">
                    <div class="mb-3 d-flex justify-content-center">
                        <img src="" id="viewFotoForUpdate"
                            style="height: 200px; width:200px; object-fit:cover; display:none;" />
                    </div>
                    <div class="mb-3">
                        <label for="formEditFotoProduk" class="form-label">Foto Produk</label>
                        <input type="file" class="form-control" id="formEditFotoProduk" name="foto_produk" required>
                    </div>
                    <div class="mb-3">
                        <label for="formNamaEditProduk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="formNamaEditProduk" name="nama_produk" required>
                    </div>
                    <div class="mb-3">
                        <label for="formEditKeteranganProduk" class="form-label">Keterangan Produk</label>
                        <textarea class="form-control" id="formEditKeteranganProduk" name="keterangan_produk"></textarea>
                    </div>
                    <hr>
                    <strong>harga :</strong>
                    <div class="mb-3">
                        <label for="formEditHargaModal" class="form-label">Harga Modal</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon33">Rp</span>
                            <input type="number" class="form-control" id="formEditHargaModal" name="harga_modal">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formEditHargaJual" class="form-label">Harga Jual</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon33">Rp</span>
                            <input type="number" class="form-control" id="formEditHargaJual" name="harga_jual">
                        </div>
                    </div>
                    <hr>
                    <strong>Discount :</strong>
                    <div class="form-check form-switch mb-2">
                        <label class="form-check-label" for="formEditIsDiscount">Discount </label>
                        <input class="form-check-input" type="checkbox" id="formEditIsDiscount"  name="is_discount">
                    </div>
                    <div class="mb-3">
                        <label for="formEditDiscount" class="form-label">Persentase</label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formEditDiscount" name="discount">
                            <span class="input-group-text" id="basic-addon33">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formEditHargaDsicount" class="form-label">Harga Discount</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon33">Rp</span>
                            <input type="number" class="form-control" id="formEditHargaDsicount" name="harga_discount">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveProdukBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createProdukForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formCreateFotoProduk" class="form-label">Foto Produk</label>
                        <input type="file" class="form-control" id="formCreateFotoProduk" name="foto_produk"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateNamaProduk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="formCreateNamaProduk" name="nama_produk"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateJenisProduk" class="form-label">Jenis Produk</label>
                        <select class="form-select" id="formCreateJenisProduk" name="id_jenis_produk">
                            @foreach (App\Models\JenisProduk::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->jenis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateKeteranganProduk" class="form-label">Keterangan Produk</label>
                        <textarea class="form-control" id="formCreateKeteranganProduk" name="keterangan_produk" required>-</textarea>
                    </div>
                    <hr>
                    <strong>harga :</strong>
                    <div class="mb-3">
                        <label for="formCreateHargaModal" class="form-label">Harga Modal</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon33">Rp</span>
                            <input type="number" class="form-control" id="formCreateHargaModal" value="0" name="harga_modal">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateHargaJual" class="form-label">Harga Jual</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon33">Rp</span>
                            <input type="number" class="form-control" id="formCreateHargaJual" value="0" name="harga_jual">
                        </div>
                    </div>
                    <hr>
                    <strong>Discount :</strong>
                    <div class="form-check form-switch mb-2">
                        <label class="form-check-label" for="formCreateIsDiscount">Discount </label>
                        <input class="form-check-input" type="checkbox" id="formCreateIsDiscount"  name="is_discount">
                    </div>
                    <div class="mb-3">
                        <label for="formCreateDiscount" class="form-label">Persentase</label>
                        <div class="input-group input-group-merge">
                            <input type="number" class="form-control" id="formCreateDiscount" value="0" name="discount">
                            <span class="input-group-text" id="basic-addon33">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="formCreateHargaDsicount" class="form-label">Harga Discount</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="basic-addon33">Rp</span>
                            <input type="number" class="form-control" id="formCreateHargaDsicount" value="0" name="harga_discount">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createProdukBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="varianProduk" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <div class="p-2 border border-primary mb-3" style="border-radius: 10px;">

                    <form id="formVarianProduk" enctype="multipart/form-data">
                        <input type="hidden" id="formVarianIdProduk" name="id_produk">
                        <div class="mb-3">
                            <label for="formNamaVarian" class="form-label">Nama Varian</label>
                            <input type="text" class="form-control" id="FormNamaVarian" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFotoVarian" class="form-label">Foto Varian</label>
                            <input type="file" class="form-control" id="FormFotoVarian" name="foto">
                        </div>
                        <div class="mb-3">
                            <label for="formKodeWarna" class="form-label">Warna</label>
                            <input type="color" class="form-control" id="formKodeWarna" name="kode_warna"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="formUkuranVarian" class="form-label">Ukuran</label>
                            <select class="form-select" name="ukuran" id="formUkuranVarian">
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">XXXL</option>
                                <option value="XXXXL">XXXXL</option>
                                <option value="ALL SIZE">All Size</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formJenisVarian" class="form-label">Jenis</label>
                            <select class="form-select" name="jenis" id="formJenisVarian" required>
                                <option value="ukuran">Ukuran</option>
                                <option value="nomor">Nomor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="formNomorVarian" class="form-label">Nomor</label>
                            <input type="number" class="form-control" id="FormNomorVarian" name="nomor">
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" id="btnSimpanVarian"><i
                                class="bx bx-plus"></i> Tambah</button>
                    </form>
                </div>

                <table id="datatable-varian" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama Varian</th>
                            <th>Ukuran</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="tambahStok" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="formTambahStok" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formTambahStokJumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="formTambahStokJumlah" name="jumlah"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formTambahStokVarian" class="form-label">Pilih Varian</label>
                        <select id="formTambahStokVarian" class="form-select" name="id_varian"></select>
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
<div class="modal fade" id="kurangStok" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="formKurangStok" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formKurangStokJumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="formKurangStokJumlah" name="jumlah"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formKurangStokVarian" class="form-label">Pilih Varian</label>
                        <select id="formKurangStokVarian" class="form-select" name="id_varian"></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-warning" id="btnKurangStok">Kurangi</button>
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
<div class="modal fade" id="lihatSemuaVarian" tabindex="-1" aria-labelledby="customersModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <h3>Semua Varian</h3>
                <table id="datatable-varian-2" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Varian</th>
                            <th>Ukuran</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="updateVarian" tabindex="-1" aria-labelledby="customersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="formUpdateVarianProduk" enctype="multipart/form-data">
                    <h3>Update Varian</h3>
                    <input type="hidden" id="formUpdateVarianId" name="id">
                    <input type="hidden" id="formUpdateVarianIdProduk" name="id_produk">
                    <div class="mb-3">
                        <label for="formUpdateNamaVarian" class="form-label">Nama Varian</label>
                        <input type="text" class="form-control" id="formUpdateNamaVarian" name="nama"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formFotoVarian" class="form-label">Foto Varian</label>
                        <input type="file" class="form-control" id="formFotoVarian" name="foto">
                    </div>
                    <div class="mb-3">
                        <label for="formUpdateKodeWarna" class="form-label">Warna</label>
                        <input type="color" class="form-control" id="formUpdateKodeWarna" name="kode_warna"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="formUpdateUkuranVarian" class="form-label">Ukuran</label>
                        <select class="form-select" name="ukuran" id="formUpdateUkuranVarian">
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="XXXL">XXXL</option>
                            <option value="XXXXL">XXXXL</option>
                            <option value="ALL SIZE">All Size</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formUpdateJenisVarian" class="form-label">Jenis</label>
                        <select class="form-select" name="jenis" id="formUpdateJenisVarian" required>
                            <option value="ukuran">Ukuran</option>
                            <option value="nomor">Nomor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="FormUpdateNomorVarian" class="form-label">Nomor</label>
                        <input type="number" class="form-control" id="FormUpdateNomorVarian" name="nomor">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnUpdateVarian">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
