@extends('layouts.frontend.app')

@section('content')
    <section class="products section bg-gray">
        <div class="container">
            <form action="{{ url('/search-produk') }}" method="GET">
                <div class="my-2 row justify-content-center">
                    <div class="col-lg-10 col-md-8 col-6">
                        <input type="text" name="search" class="form-control" placeholder="Cari Produk disini..."
                            value="{{ $searchInput ?? '' }}">
                    </div>
                    <div class="col-lg-2 col-md-4 col-6 mt-2">
                        <button type="submit" class="btn btn-main btn-block">Cari</button>
                    </div>
                </div>
            </form>
            <div class="title text-center">
                <h2>Daftar Produk</h2>
            </div>
            <div class="row d-flex justify-items-center">
                @forelse ($produk as $item)
                    <div class="col-md-4">
                        <div class="product-item">
                            <div class="product-thumb">
                                <span class="bage"
                                    @if ($item->is_discount == 1) style="background:red;" @endif>{{ $item->jenis->jenis }}
                                    @if ($item->is_discount == 1)
                                        | Discount
                                    @endif
                                </span>
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
                                <h4><a href="#" data-toggle="modal"
                                        data-target="#product-modal{{ $item->id }}">{{ $item->nama_produk }}</a></h4>
                                <p class="price">
                                    @if ($item->is_discount == 1)
                                        <del>{{ 'Rp ' . number_format($item->harga_jual) }}</del>
                                        <b style="color:red !important;">
                                            {{ 'Rp ' . number_format($item->harga_discount) }}</b>
                                    @else
                                        {{ 'Rp ' . number_format($item->harga_jual) }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-muted text-center">
                            Belum ada produk
                        </div>
                    </div>
                @endforelse
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
                                        <div class="single-product-slider">
                                            <div class='carousel-outer'>
                                                <!-- Carousel Bootstrap (Carousel Induk) -->
                                                <div id="carousel-custom-induk" class="carousel slide" data-ride="carousel">
                                                    <!-- Wrapper for slides -->
                                                    <div class='carousel-inner'>
                                                        <div class='item active'>
                                                            <img src='{{ $item->foto_produk == null || $item->foto_produk == '' ? asset('/img/logo.png') : Storage::url($item->foto_produk) }}'
                                                                alt='' data-zoom-image="{{ $item->foto_produk == null || $item->foto_produk == '' ? asset('/img/logo.png') : Storage::url($item->foto_produk) }}" style="height:400px; object-fit:cover;">
                                                        </div>
                                                        <!-- Variasi Foto -->
                                                        @foreach(App\Models\Varian::where('id_produk',$item->id)->where('foto','!=',null)->get() as $foto)
                                                        <div class='item'>
                                                            <img src='{{ Storage::url($foto->foto) }}'
                                                                alt=''  data-zoom-image="{{ Storage::url($foto->foto) }}" style="height:400px; object-fit:cover;">
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <!-- Navigation -->
                                                    <a class='left carousel-control' href='#carousel-custom-induk' role='button' data-slide='prev'>
                                                        <i class="tf-ion-ios-arrow-left"></i>
                                                    </a>
                                                    <a class='right carousel-control' href='#carousel-custom-induk' role='button' data-slide='next'>
                                                        <i class="tf-ion-ios-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            
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
                                            <a href="{{url('/kontak')}}" class="btn btn-main">Kontak Mitra</a>
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
