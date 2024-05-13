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
            </div>
            <div class="text-center mt-4">

                {{ $produk->links('vendor.pagination.bootstrap-4') }}
            </div>
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
                                            @if (App\Models\Varian::where('id_produk', $item->id)->count() > 0)
                                                <h2 class="product-title " style="margin-top: 20px;">Varian</h2>
                                                <div class="color-swatches">
                                                    <span>Warna:</span>
                                                    <ul>
                                                        @foreach (App\Models\Varian::where('id_produk', $item->id)->pluck('kode_warna')->unique() as $key => $warna)
                                                            <li style="display:inline-block;">
                                                                <div
                                                                    style="background-color: {{ $warna }}; width:36px; height:36px; margin-right:5px;">
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @else
                                                <h5 class="product-title text-muted" style="margin-top: 20px;">Belum ada
                                                    varian</h5>
                                            @endif
                                            @php
                                                $varian = App\Models\Varian::where('id_produk', $item->id);
                                                $jenis = $varian->count() > 0 ? $varian->first()->jenis : '';
                                            @endphp
                                            @if ($varian->count() > 0)
                                                <div class="product-size">
                                                    <span>Ukuran:</span>
                                                    <select class="form-control">
                                                        @foreach (App\Models\Varian::where('id_produk', $item->id)->pluck($jenis)->unique() as $key => $ukuran)
                                                            <option>{{ $ukuran }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
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
    </section>
@endsection
