<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenisKamarStoreRequest;
use App\Http\Resources\JenisKamarResource;
use App\Models\JenisKamar;

class JenisKamarController extends Controller
{
    /**
     * GET - Ambil semua data jenis kamar
     */
    public function index()
    {
        $jenisKamar = JenisKamar::all();

        if ($jenisKamar->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Belum ada data jenis kamar',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Daftar jenis kamar berhasil diambil',
            'data'    => JenisKamarResource::collection($jenisKamar)
        ], 200);
    }

    /**
     * POST - Tambah data jenis kamar baru
     */
    public function store(JenisKamarStoreRequest $request)
    {
        $jenis = JenisKamar::create($request->validated());

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan jenis kamar',
                'data'    => []
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Berhasil menambahkan jenis kamar',
            'data'    => new JenisKamarResource($jenis)
        ], 201);
    }

    /**
     * GET - Ambil detail jenis kamar berdasarkan ID
     */
    public function show($id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail jenis kamar berhasil diambil',
            'data'    => new JenisKamarResource($jenis)
        ], 200);
    }

    /**
     * PUT - Update data jenis kamar berdasarkan ID
     */
    public function update(JenisKamarStoreRequest $request, $id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $jenis->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Jenis kamar berhasil diperbarui',
            'data'    => new JenisKamarResource($jenis)
        ], 200);
    }

    /**
     * DELETE - Hapus jenis kamar berdasarkan ID
     */
    public function destroy($id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status'  => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $jenis->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Jenis kamar berhasil dihapus',
            'data'    => []
        ], 202);
    }
}
