<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards every /admin route. Anyone who is not a manager or admin is turned
 * away with a 403 rather than being silently redirected, so misconfigured
 * accounts are easy to spot.
 */
class HandleAdminArea
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403, 'شما به پنل مدیریت دسترسی ندارید.');
            }

            abort(403, 'شما به پنل مدیریت دسترسی ندارید.');
        }

        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'حساب کاربری شما غیرفعال شده است.');
        }

        // Make the admin layout aware of who is signed in.
        view()->share('adminUser', $user);

        return $next($request);
    }
}
