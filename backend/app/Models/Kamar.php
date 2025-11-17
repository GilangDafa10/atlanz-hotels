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

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_kamar', 'id_kamar', 'id_booking')
            ->withPivot('harga_saat_booking')
            ->withTimestamps();
    }
    
    public function isAvailable($checkin, $checkout)
    {
        return !$this->bookings()
            ->where('status_booking', 'lunas')
            ->where(function($query) use ($checkin, $checkout) {
                $query->whereBetween('tgl_checkin', [$checkin, $checkout])
                      ->orWhereBetween('tgl_checkout', [$checkin, $checkout]);
            })
            ->exists();
    }
}
