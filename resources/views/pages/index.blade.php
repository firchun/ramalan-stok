@extends('layouts.frontend.app')

@section('content')
    <section class="products section bg-gray">
        <div class="container">
            <div class="row">
                <div class="title text-center">
                    <h2>Daftar Produk</h2>
                </div>
            </div>
            <div class="row">
                @foreach ($produk as $item)
                    <div class="col-md-4">
                        <div class="product-item">
                            <div class="product-thumb">
                                <span class="bage">{{ $item->jenis->jenis }}</span>
                                <img class="img-responsive"
                                    src="{{ $item->foto_produk == null || $item->foto_produk == '' ? asset('/img/logo.png') : Storage::url($item->foto_produk) }}"
                                    alt="product-img" style="height:400px; object-fit:cover;" />
                                <div class="preview-meta">
                                    <ul>
                                        <li>
                                            <span data-toggle="modal" data-target="#product-modal{{ $item->id }}">
                                                <i class="tf-ion-ios-search-strong"></i>
                                            </span>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <div class="product-content">
                                <h4><a href="product-single.html">{{ $item->nama_produk }}</a></h4>

                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Modal -->
                @foreach ($produk as $item)
                    <div class="modal product-modal fade" id="product-modal{{ $item->id }}">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="tf-ion-close"></i>
                        </button>
                        <div class="modal-dialog " role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6 col-xs-12">
                                            <div class="modal-image">
                                                <img class="img-responsive"
                                                    src="{{ $item->foto_produk == null || $item->foto_produk == '' ? asset('/img/logo.png') : Storage::url($item->foto_produk) }}"
                                                    alt="product-img" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <div class="product-short-details">
                                                <h2 class="product-title">{{ $item->nama_produk }}</h2>
                                                <p class="product-short-description">
                                                    {{ $item->keterangan_produk }}
                                                </p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- /.modal -->

            </div>
        </div>
    </section>
@endsection
