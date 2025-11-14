<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\JenisKamarController;
use App\Http\Controllers\API\KamarController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tidak Perlu Login)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| ROUTES UNTUK USER YANG SUDAH LOGIN (UMUM)
|--------------------------------------------------------------------------
| Semua role yang sudah login (Admin & User) bisa mengakses route di sini.
*/
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {

    // 🔹 Route umum (akses untuk semua yang login)
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | 🔒 ROUTES KHUSUS ADMIN
    |--------------------------------------------------------------------------
    | Hanya user dengan id_role = 1 yang bisa mengakses route di sini.
    */
    Route::middleware(['check.role:1'])->group(function () {
        // ROLE
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id_role}', [RoleController::class, 'show']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{id_role}', [RoleController::class, 'update']);
        Route::delete('/roles/{id_role}', [RoleController::class, 'destroy']);

        // JENIS KAMAR
        Route::post('/jenis-kamar', [JenisKamarController::class, 'store']);
        Route::put('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'update']);
        Route::delete('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'destroy']);

        // KAMAR
        Route::post('/kamar', [KamarController::class, 'store']);
        Route::put('/kamar/{id_kamar}', [KamarController::class, 'update']);
        Route::delete('/kamar/{id_kamar}', [KamarController::class, 'destroy']);
    });

    /*
    |--------------------------------------------------------------------------
    | 👤 ROUTES KHUSUS USER
    |--------------------------------------------------------------------------
    | Hanya user dengan id_role = 2 yang bisa mengakses route di sini.
    */
    Route::middleware(['check.role:2'])->group(function () {
        // JENIS KAMAR
        Route::get('/jenis-kamar', [JenisKamarController::class, 'index']);
        Route::get('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'show']);
        
        // KAMAR
        Route::get('/kamar', [KamarController::class, 'index']);
        Route::get('/kamar/{id_kamar}', [KamarController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | 🌐 ROUTES UNTUK GUEST
    |--------------------------------------------------------------------------
    | Guest di sini bisa diartikan user tanpa role tertentu atau id_role = null.
    | Bisa kamu ubah logikanya nanti sesuai kebutuhan (misal check.role:null).
    */
    Route::middleware(['check.role:null'])->group(function () {
        // === Tambahkan route khusus GUEST di sini ===
        // Contoh:
        // Route::get('/info', [PublicInfoController::class, 'index']);
    });
});
