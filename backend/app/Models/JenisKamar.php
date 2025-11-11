<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKamar extends Model
{
    use HasFactory;

    protected $table = 'jenis_kamar';
    protected $primaryKey = 'id_jenis_kamar'; // primary key bukan 'id'
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false; // tambahkan ini kalau tabel kamu tidak punya created_at & updated_at

    protected $fillable = [
        'jenis_kasur',
        'harga_permalam',
        'deskripsi',
        'url_gambar'
    ];

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_jenis_kamar', 'id_jenis_kamar');
    }
}
