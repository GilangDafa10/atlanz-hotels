<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // GET ALL
    public function index()
    {
        return response()->json([
            'status' => 200,
            'message' => 'List semua kamar',
            'data' => Kamar::with('jenisKamar')->get()
        ]);
    }

    // INSERT
    public function store(Request $request)
    {
        $request->validate([
            'nomor_kamar' => 'required|string',
            'id_jenis_kamar' => 'required|exists:jenis_kamar,id_jenis_kamar',
        ]);

        $kamar = Kamar::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Kamar berhasil ditambahkan',
            'data' => $kamar
        ]);
    }

    // GET BY ID
    public function show($id)
    {
        $kamar = Kamar::with('jenisKamar')->find($id);

        if (!$kamar) {
            return response()->json([
                'status' => 404,
                'message' => 'Kamar tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Detail kamar',
            'data' => $kamar
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $kamar = Kamar::find($id);

        if (!$kamar) {
            return response()->json([
                'status' => 404,
                'message' => 'Kamar tidak ditemukan'
            ]);
        }

        $kamar->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Kamar berhasil diupdate',
            'data' => $kamar
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $kamar = Kamar::find($id);

        if (!$kamar) {
            return response()->json([
                'status' => 404,
                'message' => 'Kamar tidak ditemukan'
            ]);
        }

        $kamar->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Kamar berhasil dihapus'
        ]);
    }
}
