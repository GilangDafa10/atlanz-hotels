<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_booking',
        'metode',
        'status_pembayaran',
        'tanggal_bayar',
        'id_transaksi',
        'link_pembayaran',
        'qris_data'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'id_booking');
    }
}
