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
        Schema::table('stok', function (Blueprint $table) {
            $table->foreignId('id_varian')->nullable()->after('id_produk');

            $table->foreign('id_varian')->references('id')->on('varian_produk');
        });
        Schema::table('stok_mitra', function (Blueprint $table) {
            $table->foreignId('id_varian')->nullable()->after('id_produk');

            $table->foreign('id_varian')->references('id')->on('varian_produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stok', function (Blueprint $table) {
            //
        });
        Schema::table('stok_mitra', function (Blueprint $table) {
            //
        });
    }
};
