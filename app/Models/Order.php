<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',           // User yang login (opsional)
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI UTAMA: Satu Pesanan punya banyak barang (Detail)
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}