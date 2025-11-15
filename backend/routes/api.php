<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\JenisKamarController;
use App\Http\Controllers\API\KamarController;
<<<<<<< Updated upstream

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
=======
use App\Http\Controllers\Api\AdditionalServiceController;
use App\Http\Controllers\API\FasilitasController;
use App\Http\Controllers\API\FasilitasJenisKamarController;

// ================================
// PUBLIC ROUTES
// ================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// ================================
// PROTECTED ROUTES (Login Required)
// ================================
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {

    // ============================================
    // ROUTE UNTUK SEMUA ROLE (Admin & User)
    // ============================================
>>>>>>> Stashed changes
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile/{id}', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

<<<<<<< Updated upstream
    /*
    |--------------------------------------------------------------------------
    | 🔒 ROUTES KHUSUS ADMIN
    |--------------------------------------------------------------------------
    | Hanya user dengan id_role = 1 yang bisa mengakses route di sini.
    */
    Route::middleware(['check.role:1'])->group(function () {
        // ROLE
=======
    // ========== READ ONLY (Admin & User) ==========
    Route::get('/jenis-kamar', [JenisKamarController::class, 'index']);
    Route::get('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'show']);

    Route::get('/kamar', [KamarController::class, 'index']);
    Route::get('/kamar/{id_kamar}', [KamarController::class, 'show']);

    Route::get('/additional-service', [AdditionalServiceController::class, 'index']);
    Route::get('/additional-service/{id_service}', [AdditionalServiceController::class, 'show']);

    // ========== READ-ONLY Fasilitas & FasilitasJenisKamar ==========
    // USER (ROLE 2) dan ADMIN bisa mengakses READ-ONLY
    Route::get('/fasilitas', [FasilitasController::class, 'index']);
    Route::get('/fasilitas/{id_fasilitas}', [FasilitasController::class, 'show']);

    Route::get('/fasilitas-jenis-kamar', [FasilitasJenisKamarController::class, 'index']);
    Route::get('/fasilitas-jenis-kamar/{id}', [FasilitasJenisKamarController::class, 'show']);


    // ================================
    // ADMIN ONLY (id_role = 1)
    // ================================
    Route::middleware(['check.role:1'])->group(function () {

        // ROLE CRUD
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id_role}', [RoleController::class, 'show']);
>>>>>>> Stashed changes
        Route::post('/roles', [RoleController::class, 'store']);
        Route::put('/roles/{id_role}', [RoleController::class, 'update']);
        Route::delete('/roles/{id_role}', [RoleController::class, 'destroy']);

<<<<<<< Updated upstream
        // JENIS KAMAR
=======
        // ===== Jenis Kamar CRUD =====
>>>>>>> Stashed changes
        Route::post('/jenis-kamar', [JenisKamarController::class, 'store']);
        Route::put('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'update']);
        Route::delete('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'destroy']);

<<<<<<< Updated upstream
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
        // ROLE
        Route::get('/roles', [RoleController::class, 'index']);
        Route::get('/roles/{id_role}', [RoleController::class, 'show']);

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
=======
        // ===== Kamar CRUD =====
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

        // ===== FASILITAS JENIS KAMAR CRUD (ADMIN) =====
        Route::post('/fasilitas-jenis-kamar', [FasilitasJenisKamarController::class, 'store']);
        Route::put('/fasilitas-jenis-kamar/{id}', [FasilitasJenisKamarController::class, 'update']);
        Route::delete('/fasilitas-jenis-kamar/{id}', [FasilitasJenisKamarController::class, 'destroy']);
    });
>>>>>>> Stashed changes
});
