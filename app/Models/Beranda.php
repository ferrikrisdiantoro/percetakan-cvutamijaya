<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beranda extends Model
{
    use HasFactory;

    protected $table = 'tabel_beranda';
    protected $primaryKey = 'id_beranda';

    protected $fillable = [
        'gambar_utama',
        'gambar_carousel1',
        'gambar_carousel2',
        'sec2_text1',
        'sec2_text2',
        'sec2_text3',
        'sec3_judul',
        'sec3_text1',
        'sec3_text2',
        'sec3_text3',
        'sec3_map',
    ];
}
