<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Notification;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function createInvoice(Request $request, $id_booking)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::with(['user', 'pembayaran'])->find($id_booking);

            if (!$booking) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data booking tidak ditemukan',
                    'data' => null
                ], 404);
            }

            if ($booking->status_booking != 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => "Booking sudah dalam status {$booking->status_booking}",
                    'status_booking' => $booking->status_booking
                ], 400);
            }

            $existingPayment = $booking->pembayaran;
            if ($existingPayment && $existingPayment->status_pembayaran === 'pending') {
                $expiryTime = $existingPayment->created_at->addMinutes(15);
                
                if ($expiryTime->isFuture()) {
                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Invoice QRIS masih aktif',
                        'data' => [
                            'id_pembayaran' => $existingPayment->id_pembayaran,
                            'id_transaksi' => $existingPayment->id_transaksi,
                            'qris_url' => $existingPayment->qris_data,
                            'expiry_time' => $expiryTime->format('Y-m-d H:i:s'),
                            'total_harga' => $booking->total_harga
                        ]
                    ]);
                } else {
                    $existingPayment->delete();
                }

            $transactionId = 'ATLANZ-' . strtoupper(substr(uniqid(), -8)) . '-' . $id_booking;
            
            $params = [
                'transaction_details' => [
                    'order_id' => $transactionId,
                    'gross_amount' => (int) round($booking->total_harga),
                ],
                'customer_details' => [
                    'name' => $booking->user->name ?? 'Pelanggan',
                    'email' => $booking->user->email ?? 'pelanggan@hotel.com',
                    'no_hp' => $booking->user->no_hp ?? '081234567890',
                ],
                'enabled_payments' => ['qris'],
                'qris' => [
                    'acquirer' => 'gopay',
                    'expired_time' => 15 * 60,
                ],
                'item_details' => [
                    [
                        'id' => 'BOOKING-' . $id_booking,
                        'price' => (int) round($booking->total_harga),
                        'quantity' => 1,
                        'name' => "Booking Kamar {$booking->id_kamar}",
                        'brand' => 'Atlanz Hotel',
                        'category' => 'Accommodation',
                    ]
                ],
                'expiry' => [
                    'start_time' => date('Y-m-d H:i:s T'),
                    'unit' => 'minute',
                    'duration' => 15
                ]
            ];

            $snapResponse = Snap::createTransaction($params);
            
            $pembayaran = Pembayaran::create([
                'id_booking' => $id_booking,
                'metode' => 'qris',
                'status_pembayaran' => 'pending',
                'id_transaksi' => $transactionId,
                'link_pembayaran' => $snapResponse->redirect_url,
                'qris_data' => $snapResponse->actions[0]->url ?? null, 
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Invoice berhasil dibuat',
                'data' => [
                    'id_pembayaran' => $pembayaran->id_pembayaran,
                    'id_transaksi' => $pembayaran->id_transaksi,
                    'qris_url' => $snapResponse->actions[0]->url,
                    'expiry_time' => now()->addMinutes(15)->format('Y-m-d H:i:s'),
                    'total_harga' => $booking->total_harga,
                ]
            ], 201);

            }
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat invoice QRIS. Silakan coba lagi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        try {
            $notif = new Notification();
            
            if (!$notif->isSignatureKeyVerified()) {
                $logData = [
                    'ip' => $request->ip(),
                    'payload' => $request->all(),
                    'signature' => $request->header('X-Midtrans-Signature-Key')
                ];
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid signature',
                    'data' => null
                ], 403);
            }

            $transactionId = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status ?? 'accept';
            $grossAmount = $notif->gross_amount ?? 0;

            $pembayaran = Pembayaran::where('id_transaksi', $transactionId)->first();
            
            if (!$pembayaran) {
                return response()->json([
                    'status' => false,
                    'error' => 'Transaksi tidak ditemukan',
                    'data' => null
                ], 404);
            }

            $booking = $pembayaran->booking;
            if (!$booking) {
                return response()->json([
                    'status' => false,
                    'error' => 'Booking terkait tidak ditemukan',
                    'data' => null
                ], 404);
            }

            DB::beginTransaction();

            try {
                $oldStatus = $pembayaran->status_pembayaran;
                $newStatus = $oldStatus;
                $bookingStatus = $booking->status_booking;

                switch ($transactionStatus) {
                    case 'capture':
                    case 'settlement':
                        $newStatus = 'dibayar';
                        $bookingStatus = 'berhasil';
                        break;
                        
                    case 'deny':
                    case 'cancel':
                        $newStatus = 'gagal';
                        $bookingStatus = 'batal';
                        break;
                        
                    case 'expire':
                        $newStatus = 'gagal';
                        $bookingStatus = 'batal';
                        break;
                        
                    case 'pending':
                        $newStatus = 'pending';
                        break;
                }

                if ($newStatus !== $oldStatus) {
                    $updateData = [
                        'status_pembayaran' => $newStatus
                    ];
                    
                    if ($newStatus === 'dibayar') {
                        $updateData['tanggal_bayar'] = now();
                    }
                    
                    $pembayaran->update($updateData);
                    $booking->update(['status_booking' => $bookingStatus]);
                }

                DB::commit();

                $this->sendPaymentNotification($booking, $pembayaran, $newStatus);

                return response()->json([
                    'status' => true,
                    'message' => "Status updated to {$newStatus}",
                    'transaction_id' => $transactionId
                ], 200);

            } catch (Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'terjadi kesalahan',
                    'error' => $e->getMessage(),
                    'data' => null
                ], 500);
            }

        } catch (Exception $e) {
            return response()->json([
                    'status' => false,
                    'message' => 'terjadi kesalahan',
                    'error' => $e->getMessage(),
                    'data' => null
            ], 500);
        }
    }

    public function checkStatus($id_pembayaran)
    {
        try {
            $pembayaran = Pembayaran::with('booking')->findOrFail($id_pembayaran);
            $booking = $pembayaran->booking;
            
            $expiryTime = $pembayaran->created_at->addMinutes(15);
            $isExpired = $expiryTime->isPast() && $pembayaran->status_pembayaran === 'pending';
            
            if ($isExpired) {
                $pembayaran->update(['status_pembayaran' => 'gagal']);
                $booking->update(['status_booking' => 'batal']);
            }

            $statusText = match($pembayaran->status_pembayaran) {
                'dibayar' => 'Pembayaran Berhasil',
                'gagal' => 'Pembayaran Gagal',
                default => 'Menunggu Pembayaran'
            };

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mendapatkan status pembayaran',
                'data' => [
                    'id_pembayaran' => $pembayaran->id_pembayaran,
                    'id_transaksi' => $pembayaran->id_transaksi,
                    'status_pembayaran' => $pembayaran->status_pembayaran,
                    'status_text' => $statusText,
                    'status_booking' => $booking->status_booking,
                    'qris_url' => $pembayaran->status_pembayaran === 'pending' && !$isExpired ? $pembayaran->qris_data : null,
                    'expiry_time' => $expiryTime->format('Y-m-d H:i:s'),
                    'is_expired' => $isExpired,
                    'total_harga' => $booking->total_harga,
                    'booking_details' => [
                        'tgl_checkin' => $booking->tgl_checkin->format('d M Y'),
                        'tgl_checkout' => $booking->tgl_checkout->format('d M Y'),
                        'kamar' => $booking->kamar->nomor_kamar ?? 'N/A'
                    ]
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Error',
                'error' => $e->getMessage(),
                'data' => null
            ], 404);
        }
    }
}
