<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'دسترسی غیرمجاز - فقط ادمین'], 403);
            }
            abort(403, 'دسترسی غیرمجاز');
        }
        return $next($request);
    }
}
