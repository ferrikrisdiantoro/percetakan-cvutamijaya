<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pesanan'; // Set primary key ke 'id_pesanan'
    public $incrementing = false; // Karena id_pesanan kemungkinan adalah string
    protected $keyType = 'string'; // Pastikan key type adalah string

    protected $fillable = [
        'id_pesanan',
        'id_produk',
        'id_pelanggan',
        'kuantitas',
        'total_pembayaran',
    ];

    // Relasi ke model Product
    // Relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk'); // Menambahkan relasi ke Product
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pelanggan');
    }

    public function transactionDetails()
    {
        return $this->hasMany(DetailTransaction::class, 'id_pesanan', 'id_pesanan');
    }


    // Generate id_pesanan
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $lastOrder = self::lockForUpdate()->orderBy('id_pesanan', 'desc')->first();
            $lastNumber = $lastOrder ? (int) Str::after($lastOrder->id_pesanan, 'PSN') : 0;
            $order->id_pesanan = 'PSN' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        });
    }



}
