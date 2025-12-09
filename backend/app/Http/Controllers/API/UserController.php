<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserRoleUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Tidak ada data user',
                'data'    => []
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Daftar semua user berhasil diambil',
            'data'    => UserResource::collection($users)
        ], 200);
    }

    public function updateRole(UserRoleUpdateRequest $request, $id_user)
    {
        $validated = $request->validated();
        $user = User::where('id', $id_user)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $updated = $user->update($validated);

        if (!$updated) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui role user',
                'data'    => []
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Role berhasil diperbarui',
            'data'    => new UserResource($user)
        ], 201);
    }

    public function update(UserUpdateRequest $request, $id_user)
    {
        $validated = $request->validated();
        $user = User::where('id', $id_user)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $updated = $user->update($validated);

        if (!$updated) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memperbarui role user',
                'data'    => []
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Role berhasil diperbarui',
            'data'    => new UserResource($user)
        ], 201);
    }
}
