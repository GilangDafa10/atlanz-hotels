<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ===== REGISTER =====
    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'no_hp'    => 'nullable|string|max:20',
            'id_role'  => 'nullable|exists:roles,id_role', // role opsional
        ]);

        // Simpan user ke database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'no_hp'    => $request->no_hp,
            'id_role'  => $request->id_role,
        ]);

        return response()->json([
            'status'  => 201,
            'message' => 'Registrasi berhasil',
            'data'    => $user
        ], 201);
    }

    // ===== LOGIN =====
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::with('role')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 401,
                'message' => 'Email atau password salah',
                'data' => []
            ], 401);
        }

        // Tentukan ability sesuai role
        $abilities = [$user->role->nama_role ?? 'user'];

        // Buat token
        $token = $user->createToken('api-token', $abilities)->plainTextToken;

        return response()->json([
            'status' => 200,
            'message' => 'Login berhasil',
            'data' => [
                'token' => $token
            ]
        ]);
    }

    // ===== LOGOUT =====
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Logout berhasil',
            'data' => []
        ]);
    }
}
