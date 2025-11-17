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
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function createInvoice(Request $request, $id_booking)
    {
        DB::beginTransaction();
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$clientKey = config('midtrans.client_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

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
                'enabled_payments' => [
                    'other_qris'
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

            // Debug response structure
            \Log::info('Midtrans Snap Response Full:', (array) $snapResponse);

            // Cari URL QRIS
            $qrisUrl = null;

            // Cek di actions
            if (isset($snapResponse->actions) && is_array($snapResponse->actions)) {
                foreach ($snapResponse->actions as $action) {
                    if (isset($action->url)) {
                        $qrisUrl = $action->url;
                        break;
                    }
                }
            }

            // Fallback ke redirect_url
            if (!$qrisUrl && isset($snapResponse->redirect_url)) {
                $qrisUrl = $snapResponse->redirect_url;
            }

            // Jika masih null, gunakan token sebagai fallback
            if (!$qrisUrl && isset($snapResponse->token)) {
                $qrisUrl = "https://app.midtrans.com/snap/v2/vtweb/" . $snapResponse->token;
            }

            // Final fallback
            if (!$qrisUrl) {
                throw new Exception('Tidak dapat menghasilkan URL QRIS dari response Midtrans');
            }

            \Log::info('QRIS URL determined:', ['qris_url' => $qrisUrl]);

            $pembayaran = Pembayaran::create([
                'id_booking' => $id_booking,
                'metode' => 'qris',
                'status_pembayaran' => 'pending',
                'id_transaksi' => $transactionId,
                'link_pembayaran' => $snapResponse->redirect_url,
                'qris_data' => $qrisUrl, 
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Invoice berhasil dibuat',
                'data' => [
                    'id_pembayaran' => $pembayaran->id_pembayaran,
                    'id_transaksi' => $pembayaran->id_transaksi,
                    'qris_url' => $qrisUrl,
                    'expiry_time' => now()->addMinutes(15)->format('Y-m-d H:i:s'),
                    'total_harga' => $booking->total_harga,
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Create invoice error:', ['error' => $e->getMessage()]);
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
            $data = $request->all();

            Log::info('Midtrans Notification Received:', $data);

            $transactionId = $data['order_id'] ?? null;
            $transactionStatus = $data['transaction_status'] ?? null;
            $fraudStatus = $data['fraud_status'] ?? 'accept';
            $grossAmount = $data['gross_amount'] ?? 0;

            if (!$transactionId) {
                return response()->json([
                    'status' => false,
                    'message' => 'order_id missing',
                ], 400);
            }

            $pembayaran = Pembayaran::where('id_transaksi', $transactionId)->first();

            if (!$pembayaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ], 404);
            }

            $booking = $pembayaran->booking;

            DB::beginTransaction();

            $newStatus = match ($transactionStatus) {
                'capture', 'settlement' => 'dibayar',
                'deny', 'cancel', 'expire' => 'gagal',
                default => 'pending',
            };

            $bookingStatus = match ($newStatus) {
                'dibayar' => 'berhasil',
                'gagal' => 'batal',
                default => 'pending',
            };

            $updateData = ['status_pembayaran' => $newStatus];
            if ($newStatus === 'dibayar') {
                $updateData['tanggal_bayar'] = now();
            }

            $pembayaran->update($updateData);
            $booking->update(['status_booking' => $bookingStatus]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'OK',
            ]);

        } catch (Exception $e) {
            Log::error('Midtrans Notification Error:', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
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
                        'tgl_checkin' => $booking->tgl_checkin,
                        'tgl_checkout' => $booking->tgl_checkout,
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
