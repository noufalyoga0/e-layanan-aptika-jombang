<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcedPasswordChange
{
    // Route yang boleh diakses meski must_change_password = true
    private const ALLOWED_ROUTES = [
        'password.change.form',
        'password.change.update',
        'logout',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::check() &&
            Auth::user()->must_change_password &&
            !in_array($request->route()?->getName(), self::ALLOWED_ROUTES)
        ) {
            return redirect()->route('password.change.form')
                ->with('warning', 'Anda harus mengganti password sebelum melanjutkan.');
        }

        return $next($request);
    }
}
