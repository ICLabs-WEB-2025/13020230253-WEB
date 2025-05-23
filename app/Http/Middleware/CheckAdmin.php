<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Gate::check('admin', $request->user())) {
            return $next($request);
        }
        return redirect()->route('home')->with('error', 'Anda tidak memiliki akses admin.');
    }
}