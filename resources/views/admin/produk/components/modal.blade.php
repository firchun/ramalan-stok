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
                <form id="produkForm">
                    <input type="hidden" id="formProdukId" name="id">
                    <div class="mb-3">
                        <label for="formNamaProduk" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" id="formNamaProduk" name="nama_produk" required>
                    </div>
                    <div class="mb-3">
                        <label for="formKeteranganProduk" class="form-label">Keterangan Produk</label>
                        <textarea class="form-control" id="formKeteranganProduk" name="keterangan_produk"></textarea>
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
    <div class="modal-dialog modal-dialog-centered">
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
                            <label for="formKodeWarna" class="form-label">Warna</label>
                            <input type="color" class="form-control" id="formKodeWarna" name="kode_warna"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="formUkuranVarian" class="form-label">Ukuran</label>
                            <select class="form-select" name="ukuran" id="formUkuranVarian" required>
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
                        <button type="button" class="btn btn-sm btn-primary" id="btnSimpanVarian"><i
                                class="bx bx-plus"></i> Tambah</button>
                    </form>
                </div>

                <table id="datatable-varian" class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
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
