<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role === $role) {
            if ($role === 'agent' && !Auth::user()->is_approved) {
                return redirect()->route('home')->with('error', 'Akun agen Anda belum disetujui.');
            }
            return $next($request);
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
}