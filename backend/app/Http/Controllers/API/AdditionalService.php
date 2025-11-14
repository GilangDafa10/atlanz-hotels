<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdditionalServiceResource;
use App\Models\AdditionalService as ModelsAdditionalService;
use Exception;
use Illuminate\Http\Request;

class AdditionalService extends Controller
{
    public function index()
    {
        try {
            $service = ModelsAdditionalService::all();

             return response()->json([
                'status' => true,
                'message' => 'Berhasil Mendapatkan Semua Data Additional Service',
                'data' => AdditionalServiceResource::collection($service)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
