<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'amount',
        'bank_info',
        'status',
    ];

    // Relasi ke Toko (Agar Admin tahu ini penarikan punya siapa)
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}