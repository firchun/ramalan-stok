@extends('layouts.frontend.app')

@section('content')
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="content">
                        <h1 class="page-name">{{ $title }}</h1>
                        <ol class="breadcrumb">
                            <li><a href="{{ url('/') }}">Home</a></li>
                            <li class="active">{{ $title }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="products section bg-gray">
        <div class="container">
            <form action="{{ url('/search-discount') }}" method="GET">
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
                <h2>Daftar Discount Produk</h2>
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
                                            <span data-toggle="modal" data-target="#product-pesan{{ $item->id }}">
                                                <i class="tf-ion-android-cart"></i>
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
                                        <b
                                            style="color:red !important;">{{ 'Rp ' . number_format($item->harga_discount) }}</b>
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
                <div class="modal product-modal fade" id="product-pesan{{ $item->id }}">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="tf-ion-close"></i>
                    </button>
                    <div class="modal-dialog " role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h4 class="widget-title">Form Pemesanan : {{ $item->nama_produk }}</h4>
                                <form class="checkout-form" action="{{ route('kirim-pesanan') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_produk" value="{{ $item->id }}">
                                    <div class="form-group">
                                        <label for="full_name">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="full_name" placeholder=""
                                            name="nama" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_address">Alamat</label>
                                        <input type="text" class="form-control" id="user_address" placeholder=""
                                            name="alamat" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_country">Nomor HP/WA (aktif )</label>
                                        <input type="phone" class="form-control" id="user_country"
                                            placeholder="+628xxxxxxx" name="no_hp" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="user_country">Jumlah </label>
                                        <input type="number" class="form-control" placeholder="" name="jumlah"
                                            min="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Catatan</label>
                                        <textarea class="form-control" name="catatan">-</textarea>
                                    </div>
                                    @php
                                        $varian = App\Models\Varian::where('id_produk', $item->id);
                                    @endphp
                                    @if ($varian->count() > 0)
                                        <span>Pilih Varian :</span>
                                        <div class="form-group mb-4">
                                            <select class="form-control" name="id_varian" required>
                                                @foreach (App\Models\Varian::where('id_produk', $item->id)->get() as $varian)
                                                    @php
                                                        $jumlah_bertambah = App\Models\Stok::where(
                                                            'id_produk',
                                                            $item->id,
                                                        )
                                                            ->where('id_varian', $varian->id)
                                                            ->where('jenis', 'Masuk')
                                                            ->sum('jumlah');
                                                        $jumlah_berkurang = App\Models\Stok::where(
                                                            'id_produk',
                                                            $item->id,
                                                        )
                                                            ->where('id_varian', $varian->id)
                                                            ->whereIn('jenis', ['Keluar', 'Penjualan'])
                                                            ->sum('jumlah');
                                                        $jumlah = $jumlah_bertambah - $jumlah_berkurang;
                                                    @endphp
                                                    <option value="{{ $varian->id }}">
                                                        {{ $varian->nama }} -
                                                        {{ $varian->jenis == 'ukuran' ? $varian->ukuran : $varian->nomor }}
                                                        , Stok : {{ $jumlah }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <button type="submit" class="btn btn-main mt-20">Pesan Sekarang</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <!-- /.modal -->
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ Session::get('success') }}',
                showConfirmButton: true,
            });
        @endif
    </script>
@endsection
