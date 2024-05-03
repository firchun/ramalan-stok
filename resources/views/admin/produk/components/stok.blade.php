<div class="text-center">
    <h4 class="{{ $jumlah == 0 ? 'text-danger' : 'text-primary' }}">{{ $jumlah }}</h4>
    @if (Auth::user()->role == 'Admin')
        <div class="btn-group">
            <button onclick="tambahStok({{ $produk->id }})" class="btn btn-sm btn-primary"> + </button>
            <button onclick="kurangStok({{ $produk->id }})" class="btn btn-sm btn-warning"> - </button>
        </div>
    @endif
</div>
