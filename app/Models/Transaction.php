<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Pastikan ini ada

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_transaksi'; // Set primary key ke 'id_pesanan'
    public $incrementing = false; // Karena id_pesanan kemungkinan adalah string
    protected $keyType = 'string'; // Pastikan key type adalah string

    protected $casts = [
        'tanggal_transaksi' => 'date', // atau 'datetime' jika menyertakan waktu
    ];

    protected $fillable = [
        'id_transaksi',       // Tambahkan id_transaksi ke $fillable jika perlu
        'id_produk',       // Tambahkan id_transaksi ke $fillable jika perlu
        'id_pelanggan',       // Menggunakan id_pelanggan sesuai dengan kolom pada tabel
        'id_pesanan',       // Menggunakan id_pelanggan sesuai dengan kolom pada tabel
        'mode_pembayaran',
        'dokumen_tambahan',
        'total_pembayaran',
        'transfer',
        'bukti_pembayaran',
        'status'
    ];

    // Relasi ke model Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk'); // Perbaiki jika kolom relasi berbeda
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pelanggan'); // Menggunakan 'id_pelanggan' yang sesuai dengan tabel
    }

        // Relasi ke model User
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pesanan'); // Menggunakan 'id_pelanggan' yang sesuai dengan tabel
    }

    // Mengatur penomoran otomatis id_transaksi
    protected static function boot()
{
    parent::boot();

    static::creating(function ($transaction) {
        $lastOrder = self::orderBy('id_transaksi', 'desc')->first();
        $lastNumber = $lastOrder ? (int)Str::after($lastOrder->id_transaksi, 'TR') : 0;
        $transaction->id_transaksi = 'TR' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT); // Assign id_transaksi
    });
}

}
