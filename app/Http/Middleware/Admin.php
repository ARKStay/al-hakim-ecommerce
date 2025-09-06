<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Kalau belum login, suruh login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Kalau sudah login tapi bukan admin
        if (Auth::user()->role !== 'admin') {

            return redirect()->route('user.index');
        }

        return $next($request);
    }
}
