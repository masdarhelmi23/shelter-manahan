<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// Import yang wajib ada untuk relasi agar tidak error TypeError
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',           // User yang login (opsional)
        'shop_id',           // ID Toko/Warung (PENTING: Harus ada agar relasi shop jalan)
        'order_id',          // Kode Invoice (INV-XXXXX)
        'customer_name',     // Nama yang diinput pembeli
        'customer_whatsapp', // WA yang diinput pembeli
        'payment_method',    // 'kasir' atau 'midtrans'
        'admin_fee',         // Biaya tambahan Rp 2.500
        'amount',            // Total harga keseluruhan
        'status',            // pending/success/expired
        'snap_token'         // Token dari Midtrans
    ];

    // Relasi ke User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Toko/Shop
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    // RELASI UTAMA: Satu Pesanan punya banyak barang (Detail)
    public function details(): HasMany
    {
        // Pastikan foreign key di tabel order_details memang bernama 'order_id'
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}