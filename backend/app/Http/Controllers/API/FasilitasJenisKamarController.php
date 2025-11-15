<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FasilitasJenisKamarStoreRequest;
use App\Http\Resources\FasilitasJenisKamarResource;
use App\Models\FasilitasJenisKamar;

class FasilitasJenisKamarController extends Controller
{
    public function index()
    {
        $list = FasilitasJenisKamar::with(['fasilitas', 'jenisKamar'])->get();

        if ($list->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Belum ada data fasilitas untuk jenis kamar',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Daftar fasilitas jenis kamar berhasil diambil',
            'data' => FasilitasJenisKamarResource::collection($list)
        ], 200);
    }

    public function store(FasilitasJenisKamarStoreRequest $request)
    {
        $item = FasilitasJenisKamar::create($request->validated());

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan relasi fasilitas-jenis kamar',
                'data' => []
            ], 500);
        }

        $item->load(['fasilitas', 'jenisKamar']);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menambahkan relasi fasilitas-jenis kamar',
            'data' => new FasilitasJenisKamarResource($item)
        ], 201);
    }

    public function show($id)
    {
        $item = FasilitasJenisKamar::with(['fasilitas', 'jenisKamar'])->where('id_fasilitas_jenis_kamar', $id)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Relasi tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail relasi berhasil diambil',
            'data' => new FasilitasJenisKamarResource($item)
        ], 200);
    }

    public function update(FasilitasJenisKamarStoreRequest $request, $id)
    {
        $item = FasilitasJenisKamar::where('id_fasilitas_jenis_kamar', $id)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Relasi tidak ditemukan',
                'data' => []
            ], 404);
        }

        $item->update($request->validated());
        $item->load(['fasilitas', 'jenisKamar']);

        return response()->json([
            'status' => true,
            'message' => 'Relasi berhasil diperbarui',
            'data' => new FasilitasJenisKamarResource($item)
        ], 200);
    }

    public function destroy($id)
    {
        $item = FasilitasJenisKamar::where('id_fasilitas_jenis_kamar', $id)->first();

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Relasi tidak ditemukan',
                'data' => []
            ], 404);
        }

        $item->delete();

        return response()->json([
            'status' => true,
            'message' => 'Relasi berhasil dihapus',
            'data' => []
        ], 202);
    }
}
