<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\JenisKamarController;
use App\Http\Controllers\API\KamarController;

// ===== AUTH PUBLIC =====
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ===== ROUTE YANG HARUS LOGIN =====
Route::middleware(['auth:sanctum', 'check.token.expiration'])->group(function () {

    // Auth - user yang login
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // ROLE
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles', [RoleController::class, 'store']);
    Route::get('/roles/{id_role}', [RoleController::class, 'show']);
    Route::put('/roles/{id_role}', [RoleController::class, 'update']);
    Route::delete('/roles/{id_role}', [RoleController::class, 'destroy']);

    // JENIS KAMAR
    Route::get('/jenis-kamar', [JenisKamarController::class, 'index']);
    Route::post('/jenis-kamar', [JenisKamarController::class, 'store']);
    Route::get('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'show']);
    Route::put('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'update']);
    Route::delete('/jenis-kamar/{id_jenis_kamar}', [JenisKamarController::class, 'destroy']);

    // KAMAR (PERBAIKAN DI BAGIAN INI)
    Route::get('/kamar', [KamarController::class, 'index']);
    Route::post('/kamar', [KamarController::class, 'store']);
    Route::get('/kamar/{id_kamar}', [KamarController::class, 'show']);
    Route::put('/kamar/{id_kamar}', [KamarController::class, 'update']);
    Route::delete('/kamar/{id_kamar}', [KamarController::class, 'destroy']);
});
