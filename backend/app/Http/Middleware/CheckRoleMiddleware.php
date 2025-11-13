<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Usage:
     *  ->middleware('checkRole:1')        // hanya role id 1 (Admin)
     *  ->middleware('checkRole:2')        // hanya role id 2 (User)
     *  ->middleware('checkRole:1,2')      // role 1 OR 2
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user terautentikasi (biasanya auth:sanctum sudah dijalankan sebelum middleware ini)
        $user = $request->user() ?? auth()->user();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated.',
                'data'    => []
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Jika user tidak punya id_role (null), tolak akses
        if (is_null($user->id_role)) {
            return response()->json([
                'status'  => false,
                'message' => 'Role tidak ditemukan untuk user ini.',
                'data'    => []
            ], Response::HTTP_FORBIDDEN);
        }

        // Jika middleware dipanggil tanpa parameter, tolak (atau kamu bisa ubah logika untuk mengizinkan default)
        if (count($roles) === 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Role yang diizinkan tidak didefinisikan pada middleware.',
                'data'    => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // roles bisa berupa string '1,2' jika kernel mengirimkannya sebagai satu parameter; flatten dan split
        $allowed = [];
        foreach ($roles as $r) {
            // split by comma and trim spaces
            $parts = array_map('trim', explode(',', $r));
            $allowed = array_merge($allowed, $parts);
        }

        // Pastikan tipe perbandingan konsisten (string/int) -> cast semua ke string untuk perbandingan
        $allowed = array_map('strval', $allowed);
        $userRole = strval($user->id_role);

        if (!in_array($userRole, $allowed, true)) {
            return response()->json([
                'status'  => false,
                'message' => 'Kamu tidak memiliki izin untuk mengakses resource ini.',
                'data'    => []
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
