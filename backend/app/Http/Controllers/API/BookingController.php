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
                'data' => [
                    'bookings' => $bookings->map(function ($b) {
                        return [
                            'id_booking' => $b->id_booking,
                            'id_user' => $b->id_user,
                            'total_malam' => $b->total_malam,
                            'total_harga' => $b->total_harga,
                            'status_booking' => $b->status_booking,
                            'tgl_checkin' => $b->tgl_checkin,
                            'tgl_checkout' => $b->tgl_checkout,
                            'tgl_booking' => $b->tgl_booking,

                            'kamars' => $b->kamars->map(function ($k) {
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
                            }),

                            'additional_services' => $b->additionalServices->map(function ($s) {
                                return [
                                    'id' => $s->id_service,
                                    'nama' => $s->nama_service,
                                    'harga' => $s->harga_service,
                                ];
                            }),

                            'pembayaran' => $b->pembayaran ? [
                                'status_pembayaran' => $b->pembayaran->status_pembayaran,
                                'metode' => $b->pembayaran->metode,
                                'tanggal_bayar' => $b->pembayaran->tanggal_bayar,
                                'id_transaksi' => $b->pembayaran->id_transaksi
                            ] : null,
                        ];
                    })
                ]
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

            // hanya user biasa yang bisa booking
            if ($user->id_role != 2) {
                return response()->json([
                    'status' => false,
                    'message' => 'Hanya user yang dapat melakukan booking'
                ], 403);
            }

            // Validasi input (B: jenis_kamar + jumlah)
            $validator = Validator::make($request->all(), [
                'tgl_checkin' => 'required|date|after:today',
                'tgl_checkout' => 'required|date|after:tgl_checkin',
                'jenis_kamar_id' => 'required|exists:jenis_kamar,id_jenis_kamar',
                'jumlah' => 'required|integer|min:1',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'exists:additional_service,id_service'
            ], [
                'tgl_checkin.after' => 'Tanggal checkin harus setelah hari ini',
                'tgl_checkout.after' => 'Tanggal checkout harus setelah tanggal checkin',
                'jenis_kamar_id.required' => 'Pilih jenis kamar',
                'jumlah.required' => 'Masukkan jumlah kamar yang ingin dipesan'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            $checkin = Carbon::parse($validated['tgl_checkin']);
            $checkout = Carbon::parse($validated['tgl_checkout']);
            $totalMalam = $checkin->diffInDays($checkout);
            if ($totalMalam <= 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Rentang tanggal harus valid (minimal 1 malam)'
                ], 422);
            }

            $jenisKamarId = $validated['jenis_kamar_id'];
            $jumlah = (int) $validated['jumlah'];

            // Ambil semua kamar dari jenis yang diminta
            $allKamar = Kamar::with('jenisKamar')
                ->where('id_jenis_kamar', $jenisKamarId)
                ->get();

            if ($allKamar->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jenis kamar tidak ditemukan'
                ], 404);
            }

            // Filter kamar yang available
            $available = $allKamar->filter(function ($kamar) use ($checkin, $checkout) {
                return $kamar->isAvailable($checkin, $checkout);
            })->values();

            if ($available->count() < $jumlah) {
                return response()->json([
                    'status' => false,
                    'message' => "Kamar tidak tersedia untuk tanggal yang dipilih. Tersedia: {$available->count()} kamar dari {$jumlah} yang diminta.",
                    'available_count' => $available->count()
                ], 400);
            }

            // Ambil kamar yang akan di-book (ambil $jumlah pertama)
            $selectedKamar = $available->slice(0, $jumlah);

            // Hitung harga kamar (per malam) - gunakan harga jenis kamar
            // Asumsi: semua selected kamar dari 1 jenis sehingga harga sama
            $hargaPerMalam = (int) $selectedKamar->first()->jenisKamar->harga_permalam;
            $hargaTotalKamar = $hargaPerMalam * $jumlah * $totalMalam;

            // Hitung harga services (jika ada)
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

            $totalHarga = $hargaTotalKamar + $hargaTotalServices;

            // Buat booking utama
            $booking = Booking::create([
                'total_malam' => $totalMalam,
                'total_harga' => $totalHarga,
                'status_booking' => 'pending',
                'tgl_checkin' => $checkin,
                'tgl_checkout' => $checkout,
                'tgl_booking' => now(),
                'id_user' => $user->id_user ?? $user->id // menyesuaikan pk user
            ]);

            // Attach kamar ke booking (dengan harga_saat_booking per kamar)
            $dataKamarAttach = [];
            foreach ($selectedKamar as $kamar) {
                $dataKamarAttach[$kamar->id_kamar] = [
                    'harga_saat_booking' => $kamar->jenisKamar->harga_permalam
                ];
            }
            $booking->kamars()->attach($dataKamarAttach);

            // Attach services
            if (!empty($dataServices)) {
                $booking->additionalServices()->attach($dataServices);
            }

            DB::commit();

            // Prepare response sesuai format yang diminta
            $kamarsResp = [];
            foreach ($selectedKamar as $k) {
                $kamarsResp[] = [
                    'id_kamar' => $k->id_kamar,
                    'nomor_kamar' => $k->nomor_kamar,
                    'jenis_kamar' => [
                        'jenis_kasur' => $k->jenisKamar->jenis_kasur,
                        'harga_permalam' => $k->jenisKamar->harga_permalam,
                        'deskripsi' => $k->jenisKamar->deskripsi,
                        'url_gambar' => $k->jenisKamar->url_gambar,
                    ],
                ];
            }

            $responseBooking = [
                'total_malam' => $booking->total_malam,
                'total_harga' => $booking->total_harga,
                'status_booking' => $booking->status_booking,
                'tgl_checkin' => $booking->tgl_checkin,
                'tgl_checkout' => $booking->tgl_checkout,
                'tgl_booking' => $booking->tgl_booking,
                'id_user' => $booking->id_user,
                'id_booking' => $booking->id_booking,
                'kamars' => $kamarsResp,
                'additional_services' => $serviceDetails,
                'pembayaran' => null
            ];

            return response()->json([
                'status' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => [
                    'booking' => $responseBooking
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
                'data' => [
                    'booking' => [
                        'id_booking' => $booking->id_booking,
                        'id_user' => $booking->id_user,
                        'total_malam' => $booking->total_malam,
                        'total_harga' => $booking->total_harga,
                        'status_booking' => $booking->status_booking,
                        'tgl_checkin' => $booking->tgl_checkin,
                        'tgl_checkout' => $booking->tgl_checkout,
                        'tgl_booking' => $booking->tgl_booking,

                        'kamars' => $booking->kamars->map(function ($k) {
                            return [
                                'id_kamar' => $k->id_kamar,
                                'nomor_kamar' => $k->nomor_kamar,
                                'jenis_kamar' => [
                                    'jenis_kasur' => $k->jenisKamar->jenis_kasur,
                                    'harga_permalam' => $k->jenisKamar->harga_permalam,
                                    'deskripsi' => $k->jenisKamar->deskripsi,
                                    'url_gambar' => $k->jenisKamar->url_gambar,
                                ],
                                'fasilitas' => $k->jenisKamar->fasilitas->map(function ($f) {
                                    return [
                                        'id_fasilitas' => $f->id_fasilitas,
                                        'nama_fasilitas' => $f->nama_fasilitas,
                                        'icon_fasilitas' => $f->icon_fasilitas
                                    ];
                                })
                            ];
                        }),

                        'additional_services' => $booking->additionalServices->map(function ($s) {
                            return [
                                'id' => $s->id_service,
                                'nama' => $s->nama_service,
                                'harga' => $s->harga_service
                            ];
                        }),

                        'pembayaran' => $booking->pembayaran ? [
                            'status_pembayaran' => $booking->pembayaran->status_pembayaran,
                            'metode' => $booking->pembayaran->metode,
                            'tanggal_bayar' => $booking->pembayaran->tanggal_bayar,
                            'id_transaksi' => $booking->pembayaran->id_transaksi
                        ] : null
                    ]
                ]
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