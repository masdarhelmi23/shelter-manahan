<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // user yang beli
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // produk yang dibeli
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // ID transaksi Midtrans
            $table->string('order_id')->unique();

            // total harga
            $table->integer('amount');

            // status pembayaran
            $table->string('status')->default('pending');
            // pending | paid | failed

            // snap token midtrans
            $table->text('snap_token')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};