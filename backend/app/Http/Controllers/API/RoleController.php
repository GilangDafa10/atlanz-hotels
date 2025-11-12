<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Resources\RoleResource;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * GET - Ambil semua data role
     */
    public function index()
    {
        $roles = Role::all();

        if ($roles->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Belum ada data role',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Daftar semua role berhasil diambil',
            'data'    => RoleResource::collection($roles)
        ], 200);
    }

    /**
     * POST - Tambah role baru
     */
    public function store(RoleStoreRequest $request)
    {
        $validated = $request->validated();

        $role = Role::create($validated);

        if (!$role) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan role',
                'data'    => []
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Role berhasil ditambahkan',
            'data'    => new RoleResource($role)
        ], 201);
    }

    /**
     * GET - Ambil detail role berdasarkan ID
     */
    public function show($id_role)
    {
        $role = Role::where('id_role', $id_role)->first();

        if (!$role) {
            return response()->json([
                'status'  => false,
                'message' => 'Role tidak ditemukan',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail role berhasil diambil',
            'data'    => new RoleResource($role)
        ], 200);
    }

    /**
     * PUT - Update data role berdasarkan ID
     */
    public function update(RoleStoreRequest $request, $id_role)
    {
        $validated = $request->validated();

        $role = Role::where('id_role', $id_role)->first();

        if (!$role) {
            return response()->json([
                'status'  => false,
                'message' => 'Role tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $updated = $role->update($validated);

        if (!$updated) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui role',
                'data'    => []
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Role berhasil diperbarui',
            'data'    => new RoleResource($role)
        ], 201);
    }

    /**
     * DELETE - Hapus role berdasarkan ID
     */
    public function destroy($id_role)
    {
        $role = Role::where('id_role', $id_role)->first();

        if (!$role) {
            return response()->json([
                'status'  => false,
                'message' => 'Role tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $role->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Role berhasil dihapus',
            'data'    => []
        ], 202);
    }
}
