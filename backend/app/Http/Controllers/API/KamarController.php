<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\KamarStoreRequest;
use App\Http\Requests\KamarUpdateRequest;
use App\Http\Resources\KamarResource;
use App\Models\Kamar;

class KamarController extends Controller
{
    /**
     * GET - Ambil semua data kamar
     */
    public function index()
    {
        $kamar = Kamar::with('jenisKamar')->get();

        if ($kamar->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Belum ada data kamar',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Daftar semua kamar berhasil diambil',
            'data'    => KamarResource::collection($kamar)
        ], 200);
    }

    /**
     * POST - Tambah data kamar baru
     */
    public function store(KamarStoreRequest $request)
    {
        $validated = $request->validated();

        $kamar = Kamar::create($validated);

        if (!$kamar) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan kamar',
                'data'    => []
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Kamar berhasil ditambahkan',
            'data'    => new KamarResource($kamar->load('jenisKamar'))
        ], 201);
    }

    /**
     * GET - Ambil detail kamar berdasarkan ID
     */
    public function show($id_kamar)
    {
        $kamar = Kamar::with('jenisKamar')->where('id_kamar', $id_kamar)->first();

        if (!$kamar) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail kamar berhasil diambil',
            'data'    => new KamarResource($kamar)
        ], 200);
    }

    /**
     * PUT - Update data kamar berdasarkan ID
     */
    public function update(KamarUpdateRequest $request, $id_kamar)
    {
        $validated = $request->validated();

        $kamar = Kamar::where('id_kamar', $id_kamar)->first();

        if (!$kamar) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $kamar->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Kamar berhasil diperbarui',
            'data'    => new KamarResource($kamar)
        ], 200);
    }

    /**
     * DELETE - Hapus kamar berdasarkan ID
     */
    public function destroy($id_kamar)
    {
        $kamar = Kamar::where('id_kamar', $id_kamar)->first();

        if (!$kamar) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $kamar->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Kamar berhasil dihapus',
            'data'    => []
        ], 202);
    }
}
