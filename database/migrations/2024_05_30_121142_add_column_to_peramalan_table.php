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
        Schema::table('peramalan', function (Blueprint $table) {
            $table->dropColumn('total_error');
            $table->decimal('mad', 8, 2);
            $table->decimal('mape', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peramalan', function (Blueprint $table) {
            //
        });
    }
};
