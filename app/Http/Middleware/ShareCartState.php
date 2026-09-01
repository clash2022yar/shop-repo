<?php

namespace App\Http\Middleware;

use App\Services\CartService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * Every page paints the header cart badge, so the summary is shared once here
 * instead of being fetched again in each controller.
 */
class ShareCartState
{
    public function __construct(protected CartService $cart) {}

    public function handle(Request $request, Closure $next): Response
    {
        View::share('cartSummary', $this->cart->summary());
        View::share('cartItems', $this->cart->items());

        return $next($request);
    }
}
