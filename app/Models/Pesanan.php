<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';
    protected $guarded = [];
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
    public function varian(): BelongsTo
    {
        return $this->belongsTo(Varian::class, 'id_varian');
    }
}
