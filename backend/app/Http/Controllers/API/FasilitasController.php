<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FasilitasStoreRequest;
use App\Http\Resources\FasilitasResource;
use App\Models\Fasilitas;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitas = Fasilitas::all();

        if ($fasilitas->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Belum ada data fasilitas',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Daftar fasilitas berhasil diambil',
            'data' => FasilitasResource::collection($fasilitas)
        ], 200);
    }

    public function store(FasilitasStoreRequest $request)
    {
        $fasilitas = Fasilitas::create($request->validated());

        if (!$fasilitas) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan fasilitas',
                'data' => []
            ], 500);
        }

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan fasilitas',
            'data' => new FasilitasResource($fasilitas)
        ], 201);
    }

    public function show($id_fasilitas)
    {
        $fasilitas = Fasilitas::where('id_fasilitas', $id_fasilitas)->first();

        if (!$fasilitas) {
            return response()->json([
                'status' => false,
                'message' => 'Fasilitas tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail fasilitas berhasil diambil',
            'data' => new FasilitasResource($fasilitas)
        ], 200);
    }

    public function update(FasilitasStoreRequest $request, $id_fasilitas)
    {
        $fasilitas = Fasilitas::where('id_fasilitas', $id_fasilitas)->first();

        if (!$fasilitas) {
            return response()->json([
                'status' => false,
                'message' => 'Fasilitas tidak ditemukan',
                'data' => []
            ], 404);
        }

        $fasilitas->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Fasilitas berhasil diperbarui',
            'data' => new FasilitasResource($fasilitas)
        ], 200);
    }

    public function destroy($id_fasilitas)
    {
        $fasilitas = Fasilitas::where('id_fasilitas', $id_fasilitas)->first();

        if (!$fasilitas) {
            return response()->json([
                'status' => false,
                'message' => 'Fasilitas tidak ditemukan',
                'data' => []
            ], 404);
        }

        $fasilitas->delete();

        return response()->json([
            'status' => true,
            'message' => 'Fasilitas berhasil dihapus',
            'data' => []
        ], 202);
    }
}
