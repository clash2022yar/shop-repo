<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\HandleAdminArea;
use App\Http\Middleware\ShareCartState;
use App\Http\Middleware\TrackRecentlyViewed;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->prefix('ajax')
                ->name('ajax.')
                ->group(base_path('routes/ajax.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Middleware that runs on every web request.
        $middleware->web(append: [
            ShareCartState::class,
            TrackRecentlyViewed::class,
        ]);

        // Route middleware aliases.
        $middleware->alias([
            'admin' => HandleAdminArea::class,
            'active' => EnsureUserIsActive::class,
        ]);

        // Redirect guests to the Digino login screen.
        $middleware->redirectGuestsTo(fn (Request $request) => $request->expectsJson() ? null : route('login'));
        $middleware->redirectUsersTo('/panel');

        // AJAX requests are the primary interaction model of Digino, so we
        // never want the CSRF token to silently expire behind the scenes.
        $middleware->validateCsrfTokens(except: []);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Every AJAX endpoint answers with a predictable JSON envelope.
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('ajax/*') || $request->expectsJson()
        );
    })
    ->create();
