<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalServiceStoreRequest;
use App\Http\Resources\AdditionalServiceResource;
use App\Models\AdditionalService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdditionalServiceController extends Controller
{
    public function index()
    {
        try {
            $service = AdditionalService::all();

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

    public function store(AdditionalServiceStoreRequest $request)
    {
        $data = $request->validated();
        
        DB::beginTransaction();

        try {
            $service = new AdditionalService();
            $service->nama_service = $data['nama_service'];
            $service->deskripsi_service = $data['deskripsi_service'];
            $service->harga_service = $data['harga_service'];
            $service->url_gambar = $data['url_gambar'];

            $service->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menambahkan Additional Service',
                'data' => new AdditionalServiceResource($service)
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'false',
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }   
    }
}
