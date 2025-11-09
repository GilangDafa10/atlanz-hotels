<?php

namespace App\Http\Controllers;

use App\Models\JenisKamar;
use Illuminate\Http\Request;

class JenisKamarController extends Controller
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
            'message' => 'List semua jenis kamar',
            'data' => JenisKamar::all()
        ]);
    }

    // INSERT
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kasur' => 'required|string',
            'harga_permalam' => 'required|integer',
            'deskripsi' => 'nullable|string',
            'url_gambar' => 'nullable|string',
        ]);

        $jenis = JenisKamar::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Jenis kamar berhasil ditambahkan',
            'data' => $jenis
        ]);
    }

    // GET BY ID
    public function show($id)
    {
        $jenis = JenisKamar::find($id);

        if (!$jenis) {
            return response()->json([
                'status' => 404,
                'message' => 'Jenis kamar tidak ditemukan'
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Detail jenis kamar',
            'data' => $jenis
        ]);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $jenis = JenisKamar::find($id);

        if (!$jenis) {
            return response()->json([
                'status' => 404,
                'message' => 'Jenis kamar tidak ditemukan'
            ]);
        }

        $jenis->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Jenis kamar berhasil diupdate',
            'data' => $jenis
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        $jenis = JenisKamar::find($id);

        if (!$jenis) {
            return response()->json([
                'status' => 404,
                'message' => 'Jenis kamar tidak ditemukan'
            ]);
        }

        $jenis->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Jenis kamar berhasil dihapus'
        ]);
    }
}
