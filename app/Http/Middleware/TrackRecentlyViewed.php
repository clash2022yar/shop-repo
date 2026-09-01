<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\RecentlyViewed;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Records product page visits so the dashboard and product page can show a
 * genuine "recently viewed" rail rather than a random selection.
 */
class TrackRecentlyViewed
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $product = $request->route('product');

        if ($request->isMethod('GET')
            && $product instanceof Product
            && $response->getStatusCode() === 200
        ) {
            RecentlyViewed::updateOrCreate(
                array_filter([
                    'product_id' => $product->id,
                    'user_id' => $request->user()?->id,
                    'session_id' => $request->user() ? null : $request->session()->getId(),
                ], fn ($v) => $v !== null),
                ['viewed_at' => now()]
            );

            // Keep the history bounded.
            $keep = config('digino.catalog.recently_viewed_limit');

            $stale = RecentlyViewed::query()
                ->when($request->user(), fn ($q) => $q->where('user_id', $request->user()->id),
                    fn ($q) => $q->where('session_id', $request->session()->getId()))
                ->orderByDesc('viewed_at')
                ->skip($keep)->take(50)->pluck('id');

            if ($stale->isNotEmpty()) {
                RecentlyViewed::whereIn('id', $stale)->delete();
            }
        }

        return $response;
    }
}
