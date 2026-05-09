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
    Schema::table('orders', function (Blueprint $table) {
        // Menambahkan kolom shop_id setelah user_id
        $table->unsignedBigInteger('shop_id')->nullable()->after('user_id');
        
        // Optional: Tambahkan foreign key agar data konsisten
        $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['shop_id']);
        $table->dropColumn('shop_id');
    });
}

    
};
