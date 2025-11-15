<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';
    protected $primaryKey = 'id_fasilitas';

    protected $fillable = [
        'nama_fasilitas',
    ];

    public function fasilitasJenisKamar()
    {
        return $this->hasMany(FasilitasJenisKamar::class, 'id_fasilitas', 'id_fasilitas');
    }

    // optional: convenience relation ke JenisKamar melalui pivot
    public function jenisKamar()
    {
        return $this->belongsToMany(JenisKamar::class, 'fasilitas_jenis_kamar', 'id_fasilitas', 'id_jenis_kamar');
    }
}
