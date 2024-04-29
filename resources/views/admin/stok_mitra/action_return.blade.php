<div class="btn-group">
    @if ($stok->konfirmasi == 0)
        <button class="btn btn-sm btn-primary"
            onclick="returnStok({{ $stok->id }},{{ $stok->jumlah }})">Konfirmasi</button>
    @else
        <span class="text-muted">Terkonfirmasi</span>
    @endif
</div>
