<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // Kita ubah kolom product_id supaya boleh NULL (kosong)
        $table->unsignedBigInteger('product_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('product_id')->nullable(false)->change();
    });
}
};
