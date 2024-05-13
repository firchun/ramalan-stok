<div class="demo-inline-spacing mt-3">
    @if ($varian->count() <= 5)
        <ul class="list-group  ">
            @foreach ($varian->get() as $item)
                @php
                    $jumlah_bertambah = App\Models\Stok::where('id_produk', $produk->id)
                        ->where('id_varian', $item->id)
                        ->where('jenis', 'Masuk')
                        ->sum('jumlah');
                    $jumlah_berkurang = App\Models\Stok::where('id_produk', $produk->id)
                        ->where('id_varian', $item->id)
                        ->where('jenis', 'Keluar')
                        ->sum('jumlah');
                    $jumlah_penjualan = App\Models\Stok::where('id_produk', $produk->id)
                        ->where('jenis', 'Penjualan')
                        ->where('id_varian', $item->id)
                        ->sum('jumlah');

                    $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center py-1">
                    {{ $item->nama }} [{{ $item->jenis == 'ukuran' ? $item->ukuran : $item->nomor }}]
                    <span class="badge bg-{{ $jumlah == 0 ? 'danger' : 'primary' }}">{{ $jumlah }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <ul class="list-group  ">
            @foreach ($varian->limit(3)->get() as $item)
                @php
                    $jumlah_bertambah = App\Models\Stok::where('id_produk', $produk->id)
                        ->where('id_varian', $item->id)
                        ->where('jenis', 'Masuk')
                        ->sum('jumlah');
                    $jumlah_berkurang = App\Models\Stok::where('id_produk', $produk->id)
                        ->where('id_varian', $item->id)
                        ->where('jenis', 'Keluar')
                        ->sum('jumlah');
                    $jumlah_penjualan = App\Models\Stok::where('id_produk', $produk->id)
                        ->where('jenis', 'Penjualan')
                        ->where('id_varian', $item->id)
                        ->sum('jumlah');

                    $jumlah = $jumlah_bertambah - $jumlah_berkurang - $jumlah_penjualan;
                @endphp
                <li class="list-group-item d-flex justify-content-between align-items-center py-1">
                    {{ $item->nama }} [{{ $item->jenis == 'ukuran' ? $item->ukuran : $item->nomor }}]
                    <span class="badge bg-{{ $jumlah == 0 ? 'danger' : 'primary' }}">{{ $jumlah }}</span>
                </li>
            @endforeach
        </ul>
        <button type="button" onclick="lihatSemuaVarian({{ $produk->id }})" class="btn btn-sm btn-primary">Semua
            Varian</button>
    @endif
</div>
