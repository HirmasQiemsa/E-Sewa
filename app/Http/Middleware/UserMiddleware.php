<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::guard('web')->check()) {
            return redirect('/login');
        }

        if (Auth::guard('web')->user()->role !== $role) {
            abort(403, 'Akses Ditolak!');
        }

        return $next($request);
    }
}

