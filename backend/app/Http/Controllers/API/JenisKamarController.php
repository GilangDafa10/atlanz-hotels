<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\JenisKamarRequest;
use App\Http\Resources\JenisKamarResource;
use App\Models\JenisKamar;

class JenisKamarController extends Controller
{
    public function index()
    {
        return JenisKamarResource::collection(JenisKamar::all());
    }

    public function store(JenisKamarRequest $request)
    {
        $jenis = JenisKamar::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => "Berhasil Menambahkan Jenis Kamar",
            'data' => new JenisKamarResource($jenis)
        ], 201);
    }

    public function show($id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status' => true,
                'message' => 'Jenis kamar tidak ditemukan',
                'data' => []
            ], 404);
        }

        return new JenisKamarResource($jenis);
    }

    public function update(JenisKamarRequest $request, $id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data' => []
            ], 404);
        }

        $jenis->update($request->validated());
        return new JenisKamarResource($jenis);
    }

    public function destroy($id_jenis_kamar)
    {
        $jenis = JenisKamar::where('id_jenis_kamar', $id_jenis_kamar)->first();

        if (!$jenis) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis kamar tidak ditemukan',
                'data' => []
            ], 404);
        }

        $jenis->delete();
        return response()->json([
            'status' => true,
            'message' => 'Jenis kamar berhasil dihapus',
            'data'    => []
        ], 202);
    }
}