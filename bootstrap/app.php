<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role'             => \App\Http\Middleware\CheckRole::class,
            'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'force.pw.change'  => \App\Http\Middleware\ForcedPasswordChange::class,
        ]);

        // Terapkan forced password change ke semua web route yang auth
        $middleware->appendToGroup('web', \App\Http\Middleware\ForcedPasswordChange::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
