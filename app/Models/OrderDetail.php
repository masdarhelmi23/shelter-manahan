<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',   // Connect ke ID di tabel orders
        'product_id', // Barang yang dibeli
        'qty',        // Jumlah (PCS/Porsi)
        'price',      // Harga satuan saat dibeli
        'subtotal'    // qty * price
    ];

    // Relasi balik ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi ke Produk (untuk ambil nama/foto barang)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}