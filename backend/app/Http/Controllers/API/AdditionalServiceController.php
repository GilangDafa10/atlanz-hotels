<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalServiceStoreRequest;
use App\Http\Resources\AdditionalServiceResource;
use App\Models\AdditionalService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

            if ($request->hasFile('url_gambar')) {
                $file = $request->file('url_gambar');
                $path = $file->store('additional-services', 'public');
                $service->url_gambar = $path;
            }

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

    public function show($id)
    {
        try {
            $service = AdditionalService::find($id);

            if (!$service) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => null
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Mendapatkan Data',
                'data' => new AdditionalServiceResource($service)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage(),
                'data' => null
            ], 200);
        }
    }

    public function update(AdditionalServiceStoreRequest $request, $id)
    {
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $service = AdditionalService::find($id);
            
            if (!$service) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => null
                ], 404);
            }

            $service->nama_service = $data['nama_service'];
            $service->deskripsi_service = $data['deskripsi_service'];
            $service->harga_service = $data['harga_service'];

            if ($request->hasFile('url_gambar')) {
                if ($service->url_gambar && Storage::disk('public')->exists($service->url_gambar)) {
                    Storage::disk('public')->delete($service->url_gambar);
                }

                $file = $request->file('url_gambar');
                $path = $file->store('additional-services', 'public');
                $service->url_gambar = $path;
            }

            $service->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Melakukan Update',
                'data' => new AdditionalServiceResource($service)
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage(),
                'data' => null
            ], 200);
        }
    }

    public function destroy($id)
    {
        try {
            $service = AdditionalService::find($id);
            
            if (!$service) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan',
                    'data' => null
                ], 404);
            }

            DB::beginTransaction();

            $service->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Menghapus Data',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Terjadi Kesalahan',
                'error' => $e->getMessage(),
                'data' => null
            ], 200);
        }

    }
}
