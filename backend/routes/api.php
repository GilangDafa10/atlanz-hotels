<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\JenisKamarController;
use App\Http\Controllers\KamarController;
use Illuminate\Http\Request;

// Ambil user login (butuh token)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Detail user login
Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json([
        'status' => 200,
        'message' => 'Detail user',
        'data' => $request->user()
    ]);
});

// Role CRUD (Semua butuh Login/token)
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/roles', [RoleController::class, 'index']);            // Get All
    Route::post('/roles', [RoleController::class, 'store']);           // Insert
    Route::get('/roles/{id_role}', [RoleController::class, 'show']);   // Get by ID
    Route::put('/roles/{id_role}', [RoleController::class, 'update']); // Update
    Route::delete('/roles/{id_role}', [RoleController::class, 'destroy']); // Delete

});

Route::middleware('auth:sanctum')->group(function () {

    // JENIS KAMAR
    Route::get('/jenis-kamar', [JenisKamarController::class, 'index']);
    Route::post('/jenis-kamar', [JenisKamarController::class, 'store']);
    Route::get('/jenis-kamar/{id}', [JenisKamarController::class, 'show']);
    Route::put('/jenis-kamar/{id}', [JenisKamarController::class, 'update']);
    Route::delete('/jenis-kamar/{id}', [JenisKamarController::class, 'destroy']);

    // KAMAR
    Route::get('/kamar', [KamarController::class, 'index']);
    Route::post('/kamar', [KamarController::class, 'store']);
    Route::get('/kamar/{id}', [KamarController::class, 'show']);
    Route::put('/kamar/{id}', [KamarController::class, 'update']);
    Route::delete('/kamar/{id}', [KamarController::class, 'destroy']);
});