<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'id_user'; // Primary key adalah id_pelanggan
    public $incrementing = false;          // Non-auto increment karena format custom
    protected $keyType = 'string';         // Tipe primary key adalah string

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'password',
        'telepon',
        'alamat',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'id_pesanan'); // Relasi dengan Order, bukan Product
    }

    // Event untuk generate custom_id
     // Event untuk generate custom_id
     protected static function boot()
     {
         parent::boot();
 
         static::creating(function ($user) {
             // Ambil ID terakhir dari database
             $lastUser = self::orderBy('id_user', 'desc')->first();
             // Cek apakah ada user sebelumnya
             $lastNumber = $lastUser ? (int)Str::after($lastUser->id_user, 'PLG') : 0;
             // Generate ID baru dengan menambah satu pada nomor terakhir, lalu pad dengan 4 digit angka
             $user->id_user = 'PLG' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
         });
     }

}

