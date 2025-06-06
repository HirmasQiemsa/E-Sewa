<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PetugasFasilitasMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('petugas_fasilitas')->check()) {
            return redirect()->guest(route('login'))->with('error', 'Anda harus login sebagai petugas fasilitas.');
        }

        return $next($request);
    }
}
