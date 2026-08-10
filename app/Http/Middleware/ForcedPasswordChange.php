<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcedPasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        // Temporarily disabled — no redirect loop
        return $next($request);
    }
}
