<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
    'user_id', 
    'name', 
    'slug', 
    'logo', 
    'status', 
    'whatsapp', 
    'instagram',
    'open_time',
    'close_time',
    'is_active',
    'balance',
];

    // Relasi ke User (Pemilik Toko)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products() 
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}