<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AddUserDetails
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            view()->share('user_nama_lengkap', Auth::user()->nama_lengkap ?? 'Tidak Ada Nama');
        }
        return $next($request);
    }
}

