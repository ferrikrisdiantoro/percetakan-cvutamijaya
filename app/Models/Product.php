<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $primaryKey = 'id_produk'; // Primary key adalah id_produk
    public $incrementing = false;       // Non-auto increment
    protected $keyType = 'string';      // Tipe primary key string

    protected $fillable = [
        'nama_produk',
        'harga',
        'bahan',
        'ukuran',
        'stok',
        'gambar',
    ];

    public function orders()
{
    return $this->hasMany(Order::class, 'id_produk'); // Relasi ke Order
}

    protected static function boot()
    {
    parent::boot();

    static::creating(function ($product) {
        // Get the last product to determine the last number
        $lastProduct = self::orderBy('id_produk', 'desc')->first();
        // Extract the number part and increment it
        $lastNumber = $lastProduct ? (int)Str::after($lastProduct->id_produk, 'PRD') : 0;
        // Generate the new id_produk
        $product->id_produk = 'PRD' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    });
    }

}
