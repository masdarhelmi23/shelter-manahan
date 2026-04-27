<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak jamak (optional, biasanya otomatis 'products')
    protected $table = 'products';

    // Daftarkan kolom yang bisa diisi (Mass Assignment)
    // Sesuaikan dengan nama kolom di database kamu (Anjay, sesuaikan ya!)

    // Sesuaikan beneran sama database kamu yang baru (Foto dihapus)
    protected $fillable = [
        'shop_id',
        'nama_produk',
        'harga',
        'status',
        'foto',
    ];

    /**
     * Relasi ke Toko (Shop)
     * Satu produk dimiliki oleh satu toko
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    /**
     * Relasi ke Kategori (Category)
     * Satu produk memiliki satu kategori
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}