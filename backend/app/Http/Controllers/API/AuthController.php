<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    // REGISTER
    public function register(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Registrasi berhasil',
            'data'    => new UserResource($user)
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::with('role')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
                'data'    => []
            ], 401);
        }

        // Tentukan durasi token berlaku
        $expiresAt = Carbon::now()->addHour(); // ubah di sini sesuai keinginan kamu

        // Buat token baru
        $tokenResult = $user->createToken('api-token');
        $token = $tokenResult->plainTextToken;

        // Simpan waktu kedaluwarsa di database
        $user->tokens()
            ->where('id', explode('|', $token)[0])
            ->update(['expires_at' => $expiresAt]);

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => ['token' => $token]
        ], 200);
    }

    // 
    public function update(UserStoreRequest $request, $id) 
    {
        $validated = $request->validated();

        if (auth()->user()->id !== (int) $id) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda tidak memiliki izin untuk memperbarui data user ini',
                'data'    => []
            ], 403);
        }

        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User tidak ditemukan',
                'data'    => []
            ], 404);
        }

        $user->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'User berhasil diperbarui',
            'data'    => new UserResource($user)
        ], 200);
    }

    // DETAIL USER LOGIN
    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'status'  => true,
            'message' => 'Detail user login',
            'data'    => new UserResource($user)
        ], 200);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil',
            'data'    => []
        ], 201);
    }
}
