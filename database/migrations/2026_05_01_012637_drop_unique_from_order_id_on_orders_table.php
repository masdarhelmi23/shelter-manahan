<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Hapus aturan unik pada kolom order_id
            $table->dropUnique('orders_order_id_unique');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kembalikan seperti semula jika di-rollback
            $table->unique('order_id');
        });
    }
};