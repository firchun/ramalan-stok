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
            $table->foreignId('id_produk')->constrained('produk');
            $table->string('bulan', 5);
            $table->string('tahun', 5);
            $table->integer('nilai_1');
            $table->integer('nilai_2');
            $table->integer('nilai_3');
            $table->integer('total_pengeluaran');
            $table->double('total_wma', 8, 2);
            $table->double('total_error', 8, 2);
            $table->double('total_mad', 8, 2);
            $table->double('total_mse', 8, 2);
            $table->timestamps();

            // $table->foreign('id_produk')->references('id')->on('produk');
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
