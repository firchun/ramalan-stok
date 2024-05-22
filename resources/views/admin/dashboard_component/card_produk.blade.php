<div class="col-sm-6 col-lg-3 mb-4">
    <div class="card ">
        <div class="row g-0 ">
            <div class="col-md-4">
                <img src="{{ $image ?? '' }}" class="card-img card-img-left" style="height: 100%; object-fit:cover;">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <h4 class="ms-1 mb-0 text-{{ $color ?? 'primary' }}">{{ number_format($jumlah ?? 0) . ' Pcs' }}
                        </h4>
                    </div>
                    <h5 class="mb-1">{{ $nama_produk ?? 'Title' }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
