<div class="demo-inline-spacing mt-3">
    <ul class="list-group  ">
        @foreach ($varian as $item)
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
                {{ $item->nama }} [{{ $item->ukuran }}]
                <span class="badge bg-{{ $jumlah == 0 ? 'danger' : 'primary' }}">{{ $jumlah }}</span>
            </li>
        @endforeach
    </ul>
</div>
