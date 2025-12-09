<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\JenisKamarController;
use App\Http\Controllers\API\KamarController;
use App\Http\Controllers\API\AdditionalServiceController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\FasilitasController;
use App\Http\Controllers\API\PembayaranController;
use App\Http\Controllers\API\SocialAuthController;
use App\Http\Controllers\API\UserController;

// ================================
// PUBLIC ROUTES
// ================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback']);

// Midtrans
Route::post('/midtrans-notification', [PembayaranController::class, 'notificationHandler']);

Route::get('/jenis-kamar', [JenisKamarController::class, 'index']);
Route::get('/additional-service', [AdditionalServiceController::class, 'index']);
Route::get('/fasilitas', [FasilitasController::class, 'index']);

// PROTECTED ROUTES (login required)
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {

    // ============================================
    // ROUTE UNTUK SEMUA ROLE (Admin & User)
    // ============================================
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile/{id}', [UserController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ========== READ ONLY (Admin & User) ==========
    Route::get('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'show']);

    Route::get('/kamar', [KamarController::class, 'index']);
    Route::get('/kamar/{id_kamar}', [KamarController::class, 'show']);

    Route::get('/additional-service/{id_service}', [AdditionalServiceController::class, 'show']);

    // Midtrans
    Route::post('/booking/{id_booking}/invoice', [PembayaranController::class, 'createInvoice']);
    Route::get('/pembayaran/{id_pembayaran}/status', [PembayaranController::class, 'checkStatus']);

    // booking
    Route::post('/booking', [BookingController::class, 'store']);
    Route::get('/booking', [BookingController::class, 'index']);
    Route::get('/booking/{id_booking}', [BookingController::class, 'show']);
    Route::get('/booking/batal/{id_booking}', [BookingController::class, 'destroy']);

    // ========== READ-ONLY Fasilitas & FasilitasJenisKamar ==========
    // USER (ROLE 2) dan ADMIN bisa mengakses READ-ONLY
    Route::get('/fasilitas/{id_fasilitas}', [FasilitasController::class, 'show']);

    // ================================
    // ADMIN ONLY (id_role = 1)
    // ================================
    Route::middleware(['check.role:1'])->group(function () {

        // USER
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{id_user}', [UserController::class, 'updateRole']);

        // ROLE CRUD
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id_role}', [RoleController::class, 'show']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{id_role}', [RoleController::class, 'update']);
        Route::delete('/roles/{id_role}', [RoleController::class, 'destroy']);

        // Jenis Kamar (CRUD lengkap)
        Route::post('/jenis-kamar', [JenisKamarController::class, 'store']);
        Route::put('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'update']);
        Route::delete('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'destroy']);

        // Kamar (CRUD lengkap)
        Route::post('/kamar', [KamarController::class, 'store']);
        Route::put('/kamar/{id_kamar}', [KamarController::class, 'update']);
        Route::delete('/kamar/{id_kamar}', [KamarController::class, 'destroy']);

        // ===== Additional Service CRUD =====
        Route::post('/additional-service', [AdditionalServiceController::class, 'store']);
        Route::put('/additional-service/{id_service}', [AdditionalServiceController::class, 'update']);
        Route::delete('/additional-service/{id_service}', [AdditionalServiceController::class, 'destroy']);

        // ===== FASILITAS CRUD (ADMIN) =====
        Route::post('/fasilitas', [FasilitasController::class, 'store']);
        Route::put('/fasilitas/{id_fasilitas}', [FasilitasController::class, 'update']);
        Route::delete('/fasilitas/{id_fasilitas}', [FasilitasController::class, 'destroy']);

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
});
