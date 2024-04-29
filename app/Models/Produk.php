<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Produk extends Model
{
    use HasFactory;
    protected $table = 'produk';
    protected $guarded = [];

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class, 'id_jenis_produk');
    }
}
