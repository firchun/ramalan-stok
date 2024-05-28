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
                                    <span class="d-none d-sm-inline-block"></span>
                                </span>
                            </button>
                            <button class="btn btn-secondary create-new btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#create">
                                <span>
                                    <i class="bx bx-plus me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Tambah Data</span>
                                </span>
                            </button>
                            <button class="btn btn-secondary penjualan btn-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#penjualan">
                                <span>
                                    <i class="bx bx-cart me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block">Penjualan</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="mt-5">
                        Cari nama produk : <input type="text" class="form-control" id="custom-search"
                            placeholder="Cari Nama produk">
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-produk" class="table table-sm table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width:100px;">Foto Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th style="width:200px;">Varian</th>
                                <th style="width:100px;">Stok</th>
                                <th style="width:200px;">Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Foto Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Varian</th>
                                <th>Stok</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('admin.produk.components.modal')
@endsection
@include('admin.produk.script')
