<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $r)
    {
        return view('dashboard.index', ['orders' => $r->user()->orders()->with('items')->latest()->take(4)->get(), 'viewed' => Product::where('is_featured', true)->take(5)->get(), 'wishlist' => $r->user()->wishlistItems()->with('product')->latest()->get()]);
    }

    public function orders(Request $r)
    {
        return view('dashboard.orders', ['orders' => $r->user()->orders()->with('items')->latest()->paginate(10)]);
    }

    public function order(Order $order, Request $r)
    {
        abort_unless($order->user_id === $r->user()->id || $r->user()->is_admin, 403);

        return view('dashboard.order', ['order' => $order->load(['items', 'user'])]);
    }

    public function profile(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'phone' => 'nullable|string|max:20']);
        $r->user()->update($d);

        return response()->json(['message' => 'اطلاعات حساب به‌روزرسانی شد']);
    }
}
