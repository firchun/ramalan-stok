<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk');
            $table->foreignId('id_user');
            $table->enum('jenis', ['Keluar', 'Masuk', 'Kembali', 'Penjualan'])->default('Masuk');
            $table->integer('jumlah');
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('produk');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stok');
    }
};
