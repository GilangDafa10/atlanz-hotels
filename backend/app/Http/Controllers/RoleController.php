<?php

namespace App\Http\Controllers;

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
            'status' => 200,
            'message' => 'List all roles',
            'data' => $roles
        ]);
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
            'status' => 201,
            'message' => 'Role berhasil ditambahkan',
            'data' => $role
        ]);
    }

    // 3. GET ROLE BY ID
    public function show($id_role)
    {
        $role = Role::find($id_role);

        if (!$role) {
            return response()->json([
                'status' => 404,
                'message' => 'Role tidak ditemukan',
                'data' => []
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Detail role',
            'data' => $role
        ]);
    }

    // 4. UPDATE ROLE
    public function update(Request $request, $id_role)
    {
        $role = Role::find($id_role);

        if (!$role) {
            return response()->json([
                'status' => 404,
                'message' => 'Role tidak ditemukan',
                'data' => []
            ]);
        }

        $role->update([
            'nama_role' => $request->nama_role
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Role berhasil diupdate',
            'data' => $role
        ]);
    }

    // 5. DELETE ROLE
    public function destroy($id_role)
    {
        $role = Role::find($id_role);

        if (!$role) {
            return response()->json([
                'status' => 404,
                'message' => 'Role tidak ditemukan',
                'data' => []
            ]);
        }

        $role->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Role berhasil dihapus',
            'data' => []
        ]);
    }
}
