<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    // 1. GET ALL
    public function index()
    {
        $kamar = Kamar::with('jenisKamar')->get();

        return response()->json([
            'status' => true,
            'message' => 'List semua kamar',
            'data' => $kamar
        ], 200);
    }

    // 2. CREATE
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis_kamar' => 'required|exists:jenis_kamar,id_jenis_kamar',
            'nomor_kamar' => 'required|string|max:50'
        ]);

        $kamar = Kamar::create($request->only('id_jenis_kamar', 'nomor_kamar'));

        return response()->json([
            'status' => true,
            'message' => 'Kamar berhasil ditambahkan',
            'data' => $kamar
        ], 201);
    }

    // 3. SHOW
    public function show($id_kamar)
    {
        $kamar = Kamar::with('jenisKamar')->find($id_kamar);

        if (!$kamar) {
            return response()->json([
                'status' => false,
                'message' => 'Kamar tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail kamar',
            'data' => $kamar
        ], 200);
    }

    // 4. UPDATE
    public function update(Request $request, $id_kamar)
    {
        $request->validate([
            'id_jenis_kamar' => 'sometimes|exists:jenis_kamar,id_jenis_kamar',
            'nomor_kamar' => 'sometimes|string|max:50'
        ]);

        $kamar = Kamar::find($id_kamar);

        if (!$kamar) {
            return response()->json([
                'status' => false,
                'message' => 'Kamar tidak ditemukan',
                'data' => []
            ], 404);
        }

        $kamar->update($request->only('id_jenis_kamar', 'nomor_kamar'));

        return response()->json([
            'status' => true,
            'message' => 'Kamar berhasil diupdate',
            'data' => $kamar
        ], 200);
    }

    // 5. DELETE
    public function destroy($id_kamar)
    {
        $kamar = Kamar::find($id_kamar);

        if (!$kamar) {
            return response()->json([
                'status' => false,
                'message' => 'Kamar tidak ditemukan',
                'data' => []
            ], 404);
        }

        $kamar->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kamar berhasil dihapus',
            'data' => []
        ], 200);
    }
}
