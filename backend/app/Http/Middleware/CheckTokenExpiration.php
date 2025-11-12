<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenExpiration
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->user()?->currentAccessToken();

        if ($token && $token->expires_at && now()->greaterThan($token->expires_at)) {
            // Hapus token yang sudah expired
            $token->delete();

            return response()->json([
                'status' => false,
                'message' => 'Token sudah kadaluarsa, silakan login ulang.',
                'data' => []
            ], 401);
        }

        return $next($request);
    }
}
