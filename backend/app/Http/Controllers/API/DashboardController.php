<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // DATA DASHBOARD
            $totalBookingBerhasil = Booking::where('status_booking', 'berhasil')->count();

            $totalPemasukan = Booking::where('status_booking', 'berhasil')->sum('total_harga');

            // Ambil riwayat 10 booking terbaru
            $riwayat = Booking::with([
                'kamars:id_kamar,nomor_kamar,id_jenis_kamar',
                'kamars.jenisKamar:id_jenis_kamar,jenis_kasur,harga_permalam,deskripsi,url_gambar',
                'additionalServices:id_service,nama_service,harga_service,deskripsi_service,url_gambar',
                'pembayaran:id_pembayaran,metode,status_pembayaran,tanggal_bayar,id_transaksi',
                'user:id,name,email'
            ])
                ->orderBy('tgl_booking', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($booking) {

                    $kamarsResp = $booking->kamars->map(function ($k) {
                        return [
                            'id_kamar' => $k->id_kamar,
                            'nomor_kamar' => $k->nomor_kamar,
                            'jenis_kamar' => [
                                'jenis_kasur' => $k->jenisKamar->jenis_kasur,
                                'harga_permalam' => $k->jenisKamar->harga_permalam,
                                'deskripsi' => $k->jenisKamar->deskripsi,
                                'url_gambar' => $k->jenisKamar->url_gambar,
                            ],
                        ];
                    });

                    $servicesResp = $booking->additionalServices->map(function ($s) {
                        return [
                            'id' => $s->id_service,
                            'nama' => $s->nama_service,
                            'harga' => $s->harga_service,
                            'deskripsi' => $s->deskripsi_service,
                            'url_gambar' => $s->url_gambar,
                        ];
                    });

                    return [
                        'id_booking' => $booking->id_booking,
                        'id_user' => $booking->id_user,
                        'tgl_booking' => $booking->tgl_booking,
                        'tgl_checkin' => $booking->tgl_checkin,
                        'tgl_checkout' => $booking->tgl_checkout,
                        'total_malam' => $booking->total_malam,
                        'total_harga' => $booking->total_harga,
                        'status_booking' => $booking->status_booking,
                        'kamars' => $kamarsResp,
                        'additional_services' => $servicesResp,
                        'pembayaran' => $booking->pembayaran,
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Data dashboard berhasil diambil',
                'data' => [
                    'total_booking_berhasil' => $totalBookingBerhasil,
                    'total_pemasukan' => (int) $totalPemasukan,
                    'riwayat_booking' => $riwayat
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data dashboard',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
