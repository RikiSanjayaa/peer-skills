<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspension
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->suspended_until && Auth::user()->suspended_until->isFuture()) {
            
            // Kalau dia mencoba akses halaman selain 'suspended' atau 'logout', paksa ke suspended
            if (!$request->routeIs('suspended.*') && !$request->routeIs('logout')) {
                return redirect()->route('suspended.show');
            }
        }

        return $next($request);
    }
}