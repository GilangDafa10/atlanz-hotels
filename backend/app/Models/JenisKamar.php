<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKamar extends Model
{
    use HasFactory;

    protected $table = 'jenis_kamar';
    protected $primaryKey = 'id_jenis_kamar';

    protected $fillable = [
        'jenis_kasur',
        'harga_permalam',
        'deskripsi',
        'url_gambar'
    ];

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_jenis_kamar');
    }
}
