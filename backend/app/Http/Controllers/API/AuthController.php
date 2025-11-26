<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TemporaryUser;
use App\Otp\UserRegistrationOtp;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use SadiqSalau\LaravelOtp\OtpBroker;
use SadiqSalau\LaravelOtp\Facades\Otp;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function register(UserStoreRequest $request)
    {
        // Simpan data sementara ke temporary_users
        $tempUser = TemporaryUser::updateOrCreate(
            ['email' => $request->email],
            [
                'name'     => $request->name,
                'password' => Hash::make($request->password),
                'no_hp'    => $request->no_hp,
            ]
        );

        // Buat instance OTP class (untuk diproses saat verifikasi)
        $otpClass = new UserRegistrationOtp($tempUser);

        // Kirim OTP via package -> WAJIB sertakan notifiable (di sini: route mail ke email user)
        $result = Otp::identifier($tempUser->email)
            ->send($otpClass, Notification::route('mail', $tempUser->email));

        // Kembalikan hasil package (biasanya ['status' => Otp::OTP_SENT])
        return response()->json($result, 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string'
        ]);

        // Pastikan identifier yang dipakai sama persis dengan waktu mengirim OTP (email)
        $otpAttempt = Otp::identifier($request->email)->attempt($request->code);

        // Periksa hasil
        if (!isset($otpAttempt['status'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unknown OTP response'
            ], 500);
        }

        if ($otpAttempt['status'] === Otp::OTP_EMPTY) {
            return response()->json([
                'success' => false,
                'message' => __('otp.empty') ?? 'No OTP found for this identifier'
            ], 404);
        }

        if ($otpAttempt['status'] === Otp::OTP_MISMATCHED) {
            return response()->json([
                'success' => false,
                'message' => __('otp.mismatched') ?? 'OTP code mismatched'
            ], 400);
        }

        if ($otpAttempt['status'] === Otp::OTP_PROCESSED) {
            // package mengembalikan hasil process() di key 'result'
            $createdUser = $otpAttempt['result'] ?? null;

            if (!$createdUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP processed but no result returned'
                ], 500);
            }

            // Buat token Sanctum
            $token = $createdUser->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => __('otp.processed') ?? 'OTP processed and user created',
                'data' => [
                    'user'  => $createdUser,
                    'token' => $token
                ]
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unhandled OTP status: ' . $otpAttempt['status']
        ], 500);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // cari temporary user
        $tempUser = TemporaryUser::where('email', $request->email)->first();

        if (!$tempUser) {
            return response()->json([
                'success' => false,
                'message' => 'Temporary user not found. Please register again.'
            ], 404);
        }

        // kirim ulang OTP
        $result = Otp::identifier($tempUser->email)
            ->send(new UserRegistrationOtp($tempUser), Notification::route('mail', $tempUser->email));

        if ($result['status'] !== Otp::OTP_SENT) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP.'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP has been resent to your email.'
        ]);
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
            ], 401);
        }

        $expiresAt = Carbon::now()->addHour();

        $tokenResult = $user->createToken('api-token');
        $token = $tokenResult->plainTextToken;

        $user->tokens()
            ->where('id', explode('|', $token)[0])
            ->update(['expires_at' => $expiresAt]);

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => ['token' => $token]
        ]);
    }

    public function update(UserStoreRequest $request, $id)
    {
        $validated = $request->validated();

        if (auth()->user()->id !== (int) $id) {
            return response()->json(['status' => false, 'message' => 'Forbidden'], 403);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }

        $user->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'User updated',
            'data'    => new UserResource($user)
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'status'  => true,
            'message' => 'Detail user login',
            'data'    => new UserResource($user)
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil'
        ]);
    }
}
