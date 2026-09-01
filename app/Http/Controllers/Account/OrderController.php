<?php

namespace App\Http\Controllers\Account;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->string('tab')->toString() ?: 'all';

        $statuses = match ($tab) {
            'open' => [OrderStatus::Pending->value, OrderStatus::Paid->value, OrderStatus::Processing->value],
            'shipped' => [OrderStatus::Shipped->value],
            'delivered' => [OrderStatus::Delivered->value],
            'cancelled' => [OrderStatus::Cancelled->value, OrderStatus::Returned->value],
            default => [],
        };

        $orders = $request->user()->orders()
            ->with('items')
            ->when($statuses, fn ($q) => $q->whereIn('status', $statuses))
            ->search($request->string('q')->toString() ?: null)
            ->paginate(8)->withQueryString();

        $counts = [
            'all' => $request->user()->orders()->count(),
            'open' => $request->user()->orders()->whereIn('status', [
                OrderStatus::Pending->value, OrderStatus::Paid->value, OrderStatus::Processing->value,
            ])->count(),
            'shipped' => $request->user()->orders()->where('status', OrderStatus::Shipped->value)->count(),
            'delivered' => $request->user()->orders()->where('status', OrderStatus::Delivered->value)->count(),
            'cancelled' => $request->user()->orders()->whereIn('status', [
                OrderStatus::Cancelled->value, OrderStatus::Returned->value,
            ])->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'ok' => true,
                'html' => view('account.partials.order-list', compact('orders'))->render(),
            ]);
        }

        return view('account.orders', compact('orders', 'counts', 'tab'));
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['items.product.images', 'statusLogs.user', 'shippingMethod', 'coupon']);

        return view('account.order-details', compact('order'));
    }

    public function invoice(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $order->load('items');

        return view('account.invoice', compact('order'));
    }
}
