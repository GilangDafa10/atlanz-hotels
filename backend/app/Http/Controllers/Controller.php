<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // trait untuk authorize
use Illuminate\Foundation\Bus\DispatchesJobs;            // trait untuk dispatch jobs
use Illuminate\Foundation\Validation\ValidatesRequests; // trait untuk validasi request
use Illuminate\Routing\Controller as BaseController;    // PENTING: extend base routing controller

/**
 * Base controller aplikasi.
 *
 * Controller ini merupakan parent untuk semua controller aplikasi di app/Http/Controllers.
 * Pastikan class ini extend Illuminate\Routing\Controller agar method seperti
 * ->middleware() tersedia untuk semua controller turunan.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
