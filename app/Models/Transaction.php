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
        'id_user',       // Menggunakan id_user sesuai dengan kolom pada tabel
        'id_pesanan',       // Menggunakan id_user sesuai dengan kolom pada tabel
        'mode_pembayaran',
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
        return $this->belongsTo(User::class, 'id_user'); // Menggunakan 'id_pelanggan' yang sesuai dengan tabel
    }

        // Relasi ke model User
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_pesanan'); // Menggunakan 'id_pelanggan' yang sesuai dengan tabel
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class, 'id_transaksi', 'id_transaksi');
    }
    

}
