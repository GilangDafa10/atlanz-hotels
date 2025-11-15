<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\JenisKamarController;
use App\Http\Controllers\API\KamarController;
use App\Http\Controllers\Api\AdditionalServiceController;
use App\Http\Controllers\Api\PembayaranController;

// PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Midtrans
Route::post('/midtrans-notification', [PembayaranController::class, 'notificationHandler']);

// PROTECTED ROUTES (login required)
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {
    // 🔹 Route untuk SEMUA role yang login (Admin & User)
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔹 Route READ-ONLY (bisa diakses Admin & User)
    Route::get('/jenis-kamar', [JenisKamarController::class, 'index']);
    Route::get('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'show']);
    Route::get('/kamar', [KamarController::class, 'index']);
    Route::get('/kamar/{id_kamar}', [KamarController::class, 'show']);
    Route::get('/additional-service', [AdditionalServiceController::class, 'index']);
    Route::get('/additional-service/{id_service}', [AdditionalServiceController::class, 'show']);

    // Midtrans
    Route::post('/booking/{id_booking}/invoice', [PembayaranController::class, 'createInvoice']);
    Route::get('/pembayaran/{id_pembayaran}/status', [PembayaranController::class, 'checkStatus']);

    // 🔒 Routes KHUSUS ADMIN (id_role = 1)
    Route::middleware(['check.role:1'])->group(function () {
        // Role Management
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

        // Additional Service (Admin-only actions)
        Route::post('/additional-service', [AdditionalServiceController::class, 'store']);
        Route::put('/additional-service/{id_service}', [AdditionalServiceController::class, 'update']);
        Route::delete('/additional-service/{id_service}', [AdditionalServiceController::class, 'destroy']);
        

    });
});