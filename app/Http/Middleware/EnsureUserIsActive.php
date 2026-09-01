<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * A suspended customer is signed out on their next request.
 */
class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && ! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'حساب کاربری شما غیرفعال شده است. با پشتیبانی تماس بگیرید.',
                ], 403);
            }

            return redirect()->route('login')
                ->with('error', 'حساب کاربری شما غیرفعال شده است. با پشتیبانی تماس بگیرید.');
        }

        return $next($request);
    }
}
