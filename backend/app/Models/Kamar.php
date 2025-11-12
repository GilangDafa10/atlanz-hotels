<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';

    protected $fillable = [
        'nomor_kamar',
        'id_jenis_kamar'
    ];

    public function jenisKamar()
    {
        return $this->belongsTo(JenisKamar::class, 'id_jenis_kamar');
    }
}
