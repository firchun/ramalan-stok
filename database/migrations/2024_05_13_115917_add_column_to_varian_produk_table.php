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
        Schema::table('varian_produk', function (Blueprint $table) {
            $table->enum('jenis', ['nomor', 'ukuran'])->default('ukuran')->after('nama');
            $table->string('nomor', 5)->after('jenis')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('varian_produk', function (Blueprint $table) {
            //
        });
    }
};
