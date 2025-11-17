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
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function kamars()
    {
        return $this->belongsToMany(Kamar::class, 'booking_kamar', 'id_booking', 'id_kamar')
            ->withPivot('harga_saat_booking')
            ->withTimestamps();
    }

    public function additionalServices()
    {
        return $this->belongsToMany(AdditionalService::class, 'booking_additional_service', 'id_booking', 'id_service')
            ->withPivot('harga_saat_booking')
            ->withTimestamps();
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_booking');
    }

    public function setTotalHargaAttribute($value)
    {
        $this->attributes['total_harga'] = $value ?: $this->calculateTotalHarga();
    }

    public function calculateTotalHarga()
    {
        $hargaKamar = $this->kamars->sum('pivot.harga_saat_booking') * $this->total_malam;
        $hargaServices = $this->additionalServices->sum('pivot.harga_saat_booking');
        return $hargaKamar + $hargaServices;
    }
}
