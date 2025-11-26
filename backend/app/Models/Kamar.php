<?php

namespace App\Models;

use Carbon\Carbon;
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
        $checkin = $checkin instanceof Carbon ? $checkin : Carbon::parse($checkin);
        $checkout = $checkout instanceof Carbon ? $checkout : Carbon::parse($checkout);

        $overlapExists = $this->bookings()
            ->whereIn('status_booking', ['berhasil', 'dibayar'])
            ->whereRaw('NOT (tgl_checkout <= ? OR tgl_checkin >= ?)', [
                $checkin->toDateTimeString(),
                $checkout->toDateTimeString()
            ])
            ->exists();

        return !$overlapExists;
    }

}
