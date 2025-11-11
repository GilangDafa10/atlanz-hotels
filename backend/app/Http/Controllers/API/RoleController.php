<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; // <-- WAJIB DITAMBAHKAN
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        // Pastikan controller API ini hanya bisa diakses dengan token yang valid
        $this->middleware('auth:sanctum');
    }

    // 1. GET ALL
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'status' => true,
            'message' => 'List all roles',
            'data' => $roles
        ], 200);
    }

    // 2. INSERT ROLE
    public function store(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100'
        ]);

        $role = Role::create([
            'nama_role' => $request->nama_role
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Role berhasil ditambahkan',
            'data' => $role
        ], 201);
    }

    // 3. GET ROLE BY ID
    public function show($id_role)
    {
        $role = Role::find($id_role);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role tidak ditemukan',
                'data' => []
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail role',
            'data' => $role
        ], 200);
    }

    // 4. UPDATE ROLE
    public function update(Request $request, $id_role)
    {
        $role = Role::find($id_role);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role tidak ditemukan',
                'data' => []
            ], 400);
        }

        $role->update([
            'nama_role' => $request->nama_role
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Role berhasil diupdate',
            'data' => $role
        ], 201);
    }

    // 5. DELETE ROLE
    public function destroy($id_role)
    {
        $role = Role::find($id_role);

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role tidak ditemukan',
                'data' => []
            ], 400);
        }

        $role->delete();
        return response()->json([
            'status' => true,
            'message' => 'Role berhasil dihapus',
            'data' => []
        ], 202);
    }
}
