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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_produk');
            $table->foreignId('id_varian');
            $table->string('no_pesanan');
            $table->string('nama');
            $table->text('alamat');
            $table->text('catatan');
            $table->string('no_hp', 15);
            $table->string('bukti_bayar')->nullable();
            $table->boolean('is_verified')->default(0);
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('produk');
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
        Schema::dropIfExists('pesanan');
    }
};
