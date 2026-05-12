<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (! Auth::user()->isAdmin()) {
            Auth::logout();

            return redirect()->route('admin.login')->with('error', 'Akun Anda tidak memiliki akses admin.');
        }

        return $next($request);
    }
}
