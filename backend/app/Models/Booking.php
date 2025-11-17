<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'total_malam',
        'total_harga',
        'status_booking',
        'tgl_checkin',
        'tgl_checkout',
        'tgl_booking',
        'id_user',
        'id_kamar'
    ];

    public function additionalServices()
    {
        return $this->belongsToMany(AdditionalService::class, 'booking_additional_service', 'id_booking', 'id_service')
            ->withPivot('harga_saat_booking')
            ->withTimestamps();
    }
}
