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
        Schema::create('peramalan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk');
            $table->integer('periode_1');
            $table->integer('periode_2');
            $table->integer('periode_3');
            $table->integer('periode_n');
            $table->string('bulan_1', 50);
            $table->string('bulan_2', 50);
            $table->string('bulan_3', 50);
            $table->string('bulan_n', 50);
            $table->decimal('total_ma', 8, 2);
            $table->decimal('total_error', 8, 2);
            $table->integer('total_penjualan');
            $table->integer('tahun');

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
        Schema::dropIfExists('peramalan');
    }
};
