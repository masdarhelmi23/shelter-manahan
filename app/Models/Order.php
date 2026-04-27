<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'amount',
        'status',
        'snap_token'
    ];

    // RELASI KE USER (pembeli)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI KE PRODUCT
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}