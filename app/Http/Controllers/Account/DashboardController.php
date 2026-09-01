<?php

namespace App\Http\Controllers\Account;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecentlyViewed;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return view('account.dashboard', [
            'recentOrders' => $user->orders()->with('items')->limit(4)->get(),
            'stats' => [
                'orders' => $user->orders()->count(),
                'open_orders' => $user->orders()->whereIn('status', [
                    OrderStatus::Pending->value, OrderStatus::Paid->value,
                    OrderStatus::Processing->value, OrderStatus::Shipped->value,
                ])->count(),
                'wishlist' => $user->wishlists()->count(),
                'addresses' => $user->addresses()->count(),
                'tickets' => $user->tickets()->count(),
            ],
            'recentlyViewed' => $this->recentProducts($request),
        ]);
    }

    public function recentlyViewed(Request $request)
    {
        return view('account.recently-viewed', [
            'products' => $this->recentProducts($request, 24),
        ]);
    }

    protected function recentProducts(Request $request, int $limit = 12)
    {
        $ids = RecentlyViewed::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('viewed_at')
            ->limit($limit)
            ->pluck('product_id');

        if ($ids->isEmpty()) {
            return collect();
        }

        return Product::active()->whereIn('id', $ids)
            ->with(['brand', 'images'])
            ->get()
            ->sortBy(fn ($p) => $ids->search($p->id))
            ->values();
    }
}
