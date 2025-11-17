<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasJenisKamar extends Model
{
    use HasFactory;

    protected $table = 'fasilitas_jenis_kamar';
    protected $primaryKey = 'id_fasilitas_jenis_kamar';
    protected $fillable = [
        'id_fasilitas',
        'id_jenis_kamar',
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas', 'id_fasilitas');
    }

    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class, 'id_jenis_kamar', 'id_jenis_kamar');
    }
}
