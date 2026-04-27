<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('products', function (Blueprint $table) {
        // Cek dulu, kalau belum ada baru tambah
        if (!Schema::hasColumn('products', 'nama_produk')) {
            $table->string('nama_produk')->after('shop_id');
        }
        if (!Schema::hasColumn('products', 'harga')) {
            $table->integer('harga')->after('nama_produk');
        }
        if (!Schema::hasColumn('products', 'stok')) {
            $table->integer('stok')->after('harga');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
