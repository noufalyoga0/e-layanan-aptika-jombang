<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            $route = match ($user->role) {
                'admin_aptika' => route('verifikasi'),
                'teknisi'      => route('workspace.tech'),
                'kabid'        => route('analytics'),
                default        => route('tickets.index'),
            };
            return redirect($route);
        }

        return $next($request);
    }
}
