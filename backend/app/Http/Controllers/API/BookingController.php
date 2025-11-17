<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdditionalService;
use App\Models\Booking;
use App\Models\Kamar;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * GET - List semua booking
     * Rules:
     * - Admin (id_role=1): bisa lihat semua booking
     * - User (id_role=2): hanya bisa lihat booking milik sendiri
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            $query = Booking::with([
                'user:id,name,email',
                'kamars:id_kamar,nomor_kamar,id_jenis_kamar',
                'kamars.jenisKamar:id_jenis_kamar,jenis_kasur,harga_permalam',
                'additionalServices:id_service,nama_service,harga_service',
                'pembayaran:id_pembayaran,status_pembayaran'
            ]);

            // Filter berdasarkan role
            if ($user->id_role != 1) { // Bukan admin
                $query->where('id_user', $user->id);
            }

            $bookings = $query->orderBy('tgl_booking', 'desc')->get();

            return response()->json([
                'status' => true,
                'message' => 'Daftar booking berhasil diambil',
                'data' => $bookings
            ], 200);
        } catch (Exception $e) {
            Log::error('Booking Index Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data booking',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * POST - Buat booking baru
     * Rules:
     * - Hanya user yang bisa membuat booking (admin tidak perlu booking)
     * - Minimal 1 kamar harus dipilih
     * - Cek ketersediaan kamar untuk tanggal yang dipilih
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            
            // Hanya user biasa yang bisa booking (role=2)
            if ($user->id_role != 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya user yang dapat melakukan booking'
                ], 403);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'tgl_checkin' => 'required|date|after:today',
                'tgl_checkout' => 'required|date|after:tgl_checkin',
                'kamar_ids' => 'required|array|min:1',
                'kamar_ids.*' => 'exists:kamar,id_kamar',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'exists:additional_services,id_service'
            ], [
                'tgl_checkin.after' => 'Tanggal checkin harus setelah hari ini',
                'tgl_checkout.after' => 'Tanggal checkout harus setelah tanggal checkin',
                'kamar_ids.required' => 'Minimal pilih 1 kamar',
                'kamar_ids.*.exists' => 'Kamar yang dipilih tidak valid'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            // Hitung total malam
            $checkin = Carbon::parse($validated['tgl_checkin']);
            $checkout = Carbon::parse($validated['tgl_checkout']);
            $totalMalam = $checkin->diffInDays($checkout);

            // Cek ketersediaan semua kamar
            $kamarTidakTersedia = [];
            $hargaTotalKamar = 0;
            $kamarDetails = [];
            $dataKamar = [];

            foreach ($validated['kamar_ids'] as $id_kamar) {
                $kamar = Kamar::with('jenisKamar')->findOrFail($id_kamar);
                
                // Cek ketersediaan kamar
                if (!$kamar->isAvailable($checkin, $checkout)) {
                    $kamarTidakTersedia[] = $kamar->nomor_kamar;
                    continue;
                }
                
                $hargaSaatIni = $kamar->jenisKamar->harga_permalam;
                $hargaTotalKamar += $hargaSaatIni;
                
                // Siapkan data untuk pivot table
                $dataKamar[$id_kamar] = ['harga_saat_booking' => $hargaSaatIni];
                
                $kamarDetails[] = [
                    'id' => $kamar->id_kamar,
                    'nomor' => $kamar->nomor_kamar,
                    'harga_permalam' => $hargaSaatIni,
                    'jenis' => $kamar->jenisKamar->jenis_kasur
                ];
            }

            if (!empty($kamarTidakTersedia)) {
                $pesan = "Kamar berikut tidak tersedia untuk tanggal yang dipilih: " . implode(', ', $kamarTidakTersedia);
                return response()->json([
                    'status' => false,
                    'message' => $pesan,
                    'kamar_tidak_tersedia' => $kamarTidakTersedia
                ], 400);
            }

            // Hitung harga additional services
            $hargaTotalServices = 0;
            $serviceDetails = [];
            $dataServices = [];

            if (!empty($validated['service_ids'])) {
                foreach ($validated['service_ids'] as $id_service) {
                    $service = AdditionalService::findOrFail($id_service);
                    $hargaTotalServices += $service->harga_service;
                    $dataServices[$id_service] = ['harga_saat_booking' => $service->harga_service];
                    
                    $serviceDetails[] = [
                        'id' => $service->id_service,
                        'nama' => $service->nama_service,
                        'harga' => $service->harga_service
                    ];
                }
            }

            // Hitung total harga
            $totalHarga = ($hargaTotalKamar * $totalMalam) + $hargaTotalServices;

            // Buat booking utama
            $booking = Booking::create([
                'total_malam' => $totalMalam,
                'total_harga' => $totalHarga,
                'status_booking' => 'pending',
                'tgl_checkin' => $checkin,
                'tgl_checkout' => $checkout,
                'tgl_booking' => now(),
                'id_user' => $user->id
            ]);

            // Attach kamar ke booking
            $booking->kamars()->attach($dataKamar);

            // Attach additional services ke booking
            if (!empty($dataServices)) {
                $booking->additionalServices()->attach($dataServices);
            }

            DB::commit();

            // Load relasi untuk response
            $booking->load([
                'kamars.jenisKamar',
                'additionalServices',
                'pembayaran'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => [
                    'booking' => $booking,
                    'kamar_details' => $kamarDetails,
                    'service_details' => $serviceDetails,
                    'next_step' => 'Silakan lanjutkan ke pembayaran dengan QRIS'
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking Store Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat booking: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * GET - Detail booking berdasarkan ID
     * Rules:
     * - Admin bisa lihat semua booking
     * - User hanya bisa lihat booking milik sendiri
     */
    public function show($id_booking)
    {
        try {
            $user = auth()->user();
            
            $query = Booking::with([
                'user:id,name,email,no_hp',
                'kamars:id_kamar,nomor_kamar,id_jenis_kamar',
                'kamars.jenisKamar:id_jenis_kamar,jenis_kasur,harga_permalam,deskripsi,url_gambar',
                'kamars.jenisKamar.fasilitas:id_fasilitas,nama_fasilitas,icon_fasilitas',
                'additionalServices:id_service,nama_service,deskripsi_service,harga_service,url_gambar',
                'pembayaran:id_pembayaran,metode,status_pembayaran,tanggal_bayar,id_transaksi'
            ]);

            // Filter berdasarkan role
            if ($user->id_role != 1) { // Bukan admin
                $query->where('id_user', $user->id);
            }

            $booking = $query->findOrFail($id_booking);

            return response()->json([
                'status' => true,
                'message' => 'Detail booking berhasil diambil',
                'data' => $booking
            ], 200);
        } catch (Exception $e) {
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking tidak ditemukan atau bukan milik Anda'
                ], 404);
            }
            
            Log::error('Booking Show Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil detail booking',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * PUT - Update booking
     * Rules:
     * - Hanya bisa update jika status masih 'pending'
     * - User hanya bisa update booking milik sendiri
     * - Update yang diizinkan:
     *   * additional_services (tambah/hapus)
     *   * Tanggal checkin/checkout (dengan validasi ulang ketersediaan)
     */
    public function update(Request $request, $id_booking)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            
            // Cari booking dengan filter role
            $query = Booking::where('id_booking', $id_booking);
            
            if ($user->id_role != 1) { // Bukan admin
                $query->where('id_user', $user->id);
            }

            $booking = $query->firstOrFail();
            
            // Hanya bisa update jika status masih pending
            if ($booking->status_booking !== 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking sudah dibayar atau dibatalkan, tidak bisa diupdate'
                ], 400);
            }

            // Validasi input
            $rules = [
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'exists:additional_services,id_service',
                'tgl_checkin' => 'nullable|date|after:today',
                'tgl_checkout' => 'nullable|date|after:tgl_checkin'
            ];
            
            // Jika update tanggal, tambahkan validasi
            $tglCheckinBaru = $request->tgl_checkin ?? $booking->tgl_checkin;
            $tglCheckoutBaru = $request->tgl_checkout ?? $booking->tgl_checkout;
            
            if ($request->tgl_checkin || $request->tgl_checkout) {
                $validator = Validator::make($request->all(), [
                    'tgl_checkin' => 'required|date|after:today',
                    'tgl_checkout' => 'required|date|after:tgl_checkin'
                ], [
                    'tgl_checkin.after' => 'Tanggal checkin harus setelah hari ini',
                    'tgl_checkout.after' => 'Tanggal checkout harus setelah tanggal checkin'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi tanggal gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
            }

            $validated = $request->validate($rules);

            // Update additional services jika ada perubahan
            if (isset($validated['service_ids'])) {
                // Detach semua services lama
                $booking->additionalServices()->detach();
                
                // Attach services baru
                $dataServices = [];
                $hargaTotalServices = 0;
                
                if (!empty($validated['service_ids'])) {
                    foreach ($validated['service_ids'] as $id_service) {
                        $service = AdditionalService::findOrFail($id_service);
                        $hargaTotalServices += $service->harga_service;
                        $dataServices[$id_service] = ['harga_saat_booking' => $service->harga_service];
                    }
                }
                
                if (!empty($dataServices)) {
                    $booking->additionalServices()->attach($dataServices);
                }
            }

            // Update tanggal jika ada perubahan
            $updateTanggal = false;
            $checkinLama = Carbon::parse($booking->tgl_checkin);
            $checkoutLama = Carbon::parse($booking->tgl_checkout);
            $checkinBaru = Carbon::parse($tglCheckinBaru);
            $checkoutBaru = Carbon::parse($tglCheckoutBaru);
            
            if ($checkinBaru != $checkinLama || $checkoutBaru != $checkoutLama) {
                $updateTanggal = true;
                $totalMalamBaru = $checkinBaru->diffInDays($checkoutBaru);
                
                // Cek ulang ketersediaan kamar
                $kamarTidakTersedia = [];
                foreach ($booking->kamars as $kamar) {
                    if (!$kamar->isAvailable($checkinBaru, $checkoutBaru)) {
                        $kamarTidakTersedia[] = $kamar->nomor_kamar;
                    }
                }
                
                if (!empty($kamarTidakTersedia)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Kamar tidak tersedia untuk tanggal baru: ' . implode(', ', $kamarTidakTersedia),
                        'kamar_tidak_tersedia' => $kamarTidakTersedia
                    ], 400);
                }
                
                // Update tanggal dan total malam
                $booking->update([
                    'tgl_checkin' => $checkinBaru,
                    'tgl_checkout' => $checkoutBaru,
                    'total_malam' => $totalMalamBaru
                ]);
            }

            // Hitung ulang total harga jika ada perubahan
            if (isset($validated['service_ids']) || $updateTanggal) {
                $hargaKamar = $booking->kamars->sum('pivot.harga_saat_booking') * $booking->total_malam;
                $hargaServices = $booking->additionalServices->sum('pivot.harga_saat_booking');
                $totalHargaBaru = $hargaKamar + $hargaServices;
                
                $booking->update(['total_harga' => $totalHargaBaru]);
            }

            DB::commit();

            // Load ulang relasi
            $booking->load([
                'kamars.jenisKamar',
                'additionalServices',
                'pembayaran'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Booking berhasil diperbarui',
                'data' => $booking
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking Update Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui booking',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * DELETE - Batalkan booking
     * Rules:
     * - Hanya bisa batalkan jika status masih 'pending'
     * - User hanya bisa batalkan booking milik sendiri
     */
    public function destroy($id_booking)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();
            
            // Cari booking dengan filter role
            $query = Booking::where('id_booking', $id_booking);
            
            if ($user->id_role != 1) { // Bukan admin
                $query->where('id_user', $user->id);
            }

            $booking = $query->firstOrFail();
            
            // Hanya bisa batalkan jika status masih pending
            if ($booking->status_booking !== 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking sudah dibayar atau tidak bisa dibatalkan'
                ], 400);
            }

            // Jika sudah ada pembayaran, batalkan transaksi Midtrans
            if ($booking->pembayaran && $booking->pembayaran->status_pembayaran === 'pending') {
                try {
                    // TODO: Implementasi cancel transaction ke Midtrans API
                    // Contoh: $this->cancelMidtransTransaction($booking->pembayaran->id_transaksi);
                } catch (Exception $e) {
                    Log::warning('Gagal cancel transaksi Midtrans: ' . $e->getMessage());
                }
            }

            // Update status booking
            $booking->update(['status_booking' => 'batal']);

            // Hapus relasi (opsional, tergantung kebijakan)
            // $booking->kamars()->detach();
            // $booking->additionalServices()->detach();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Booking berhasil dibatalkan',
                'data' => null
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking tidak ditemukan atau tidak bisa dibatalkan'
                ], 404);
            }
            
            Log::error('Booking Cancel Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Gagal membatalkan booking',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}