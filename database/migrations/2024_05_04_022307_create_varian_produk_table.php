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
        Schema::create('varian_produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk');
            $table->string('kode_warna');
            $table->string('nama');
            $table->enum('ukuran', ['S', 'M', 'XL', 'XXL', 'XXXL', 'XXXXL', 'ALL SIZE'])->default('ALL SIZE')->nullable();
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('varian_produk');
    }
};
