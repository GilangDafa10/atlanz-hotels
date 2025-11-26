<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        $url = Socialite::driver($provider)
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'status' => true,
            'message' => 'berhasil melakukan redirect',
            'url'    => $url
        ]);
    }

    public function callback($provider) {
        $social = Socialite::driver($provider)->stateless()->user();

        $user = User::where('email', $social->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $social->getName(),
                'email' => $social->getEmail(),
                'id_role' => 2,
                'password' => Hash::make(uniqid()),
            ]);
        }

        $expiresAt = Carbon::now()->addHour();

        $tokenResult = $user->createToken('api-token');
        $token = $tokenResult->plainTextToken;

        $user->tokens()
            ->where('id', explode('|', $token)[0])
            ->update(['expires_at' => $expiresAt]);

        return response()->json([
            'status'  => true,
            'message' => 'Login sosial berhasil',
            'token'   => $token,
            'user'    => $user
        ]);
    }
}
