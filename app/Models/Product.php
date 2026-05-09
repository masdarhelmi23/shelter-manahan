<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import Trait SoftDeletes

class Product extends Model
{
    use HasFactory, SoftDeletes; // Gunakan Trait SoftDeletes di sini

    // Nama tabel (opsional, Laravel otomatis mencari 'products')
    protected $table = 'products';

    /**
     * Kolom yang bisa diisi (Mass Assignment)
     * Tambahkan 'category_id' jika memang ada di database agar tidak error saat create/update
     */
    protected $fillable = [
        'shop_id',
        'category_id',
        'nama_produk',
        'harga',
        'status',
        'foto',
    ];

    /**
     * Kolom yang harus dikonversi ke tipe data Carbon (tanggal)
     */
    protected $dates = ['deleted_at'];

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

    /**
     * Relasi ke OrderDetail
     * Digunakan untuk mengecek riwayat transaksi sebelum benar-benar dihapus (opsional)
     */
    public function details()
    {
        // Pastikan nama modelnya OrderDetail atau sesuai dengan file Model Anda
        return $this->hasMany(OrderDetail::class, 'product_id');
    }

    /**
     * Relasi ke Orders (Jika melalui tabel detail)
     * Biasanya produk tidak langsung ke Order, tapi lewat OrderDetail
     */
    public function orders()
    {
        return $this->hasMany(Order::class); 
    }
}