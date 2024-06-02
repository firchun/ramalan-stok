<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stok extends Model
{
    use HasFactory;
    protected $table = 'stok';
    protected $guarded = [];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk');
    }
    public function varian(): BelongsTo
    {
        return $this->belongsTo(Varian::class, 'id_varian');
    }
    public static function cekStokUtama($id_produk, $id_varian)
    {
        $jumlah_bertambah = self::where('id_produk', $id_produk)
            ->where('id_varian', $id_varian)
            ->where('jenis', 'Masuk')
            ->sum('jumlah');
        $jumlah_berkurang = self::where('id_produk', $id_produk)
            ->where('id_varian', $id_varian)
            ->whereIn('jenis', ['Keluar', 'Penjualan'])
            ->sum('jumlah');
        $jumlah = $jumlah_bertambah - $jumlah_berkurang;

        return $jumlah;
    }
}
