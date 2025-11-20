<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalService extends Model
{
    protected $primaryKey = 'id_service';

    protected $table = 'additional_services';
    protected $fillable = [
        'nama_service',
        'deskripsi_service',
        'harga_service',
        'url_gambar'
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_additional_service', 'id_service', 'id_booking')
            ->withPivot('harga_saat_booking')
            ->withTimestamps();
    }
}
