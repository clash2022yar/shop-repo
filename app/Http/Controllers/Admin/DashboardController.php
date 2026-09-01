<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\Ticket;
use App\Models\User;
use App\Support\Jalali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $monthAgo = now()->subDays(30);
        $prevMonth = now()->subDays(60);

        $revenue = (int) Order::paid()->sum('grand_total');
        $revenueMonth = (int) Order::paid()->where('paid_at', '>=', $monthAgo)->sum('grand_total');
        $revenuePrev = (int) Order::paid()->whereBetween('paid_at', [$prevMonth, $monthAgo])->sum('grand_total');

        return view('admin.dashboard', [
            'cards' => [
                [
                    'key' => 'revenue',
                    'label' => 'درآمد کل',
                    'value' => toman($revenue),
                    'suffix' => 'تومان',
                    'icon' => 'wallet',
                    'tone' => 'success',
                    'delta' => $this->delta($revenueMonth, $revenuePrev),
                ],
                [
                    'key' => 'orders',
                    'label' => 'سفارش‌ها',
                    'value' => number_format(Order::count()),
                    'suffix' => 'سفارش',
                    'icon' => 'shopping-bag',
                    'tone' => 'brand',
                    'delta' => $this->delta(
                        Order::where('created_at', '>=', $monthAgo)->count(),
                        Order::whereBetween('created_at', [$prevMonth, $monthAgo])->count()
                    ),
                ],
                [
                    'key' => 'customers',
                    'label' => 'مشتریان',
                    'value' => number_format(User::customers()->count()),
                    'suffix' => 'کاربر',
                    'icon' => 'users',
                    'tone' => 'info',
                    'delta' => $this->delta(
                        User::customers()->where('created_at', '>=', $monthAgo)->count(),
                        User::customers()->whereBetween('created_at', [$prevMonth, $monthAgo])->count()
                    ),
                ],
                [
                    'key' => 'products',
                    'label' => 'کالاها',
                    'value' => number_format(Product::count()),
                    'suffix' => 'کالا',
                    'icon' => 'box',
                    'tone' => 'warning',
                    'delta' => null,
                ],
            ],

            'todayStats' => [
                'orders' => Order::where('created_at', '>=', $today)->count(),
                'revenue' => (int) Order::paid()->where('paid_at', '>=', $today)->sum('grand_total'),
                'customers' => User::customers()->where('created_at', '>=', $today)->count(),
                'visits' => (int) Product::where('updated_at', '>=', $today)->sum('views_count'),
            ],

            'pending' => [
                'orders' => Order::where('status', OrderStatus::Pending->value)->count(),
                'processing' => Order::where('status', OrderStatus::Processing->value)->count(),
                'reviews' => Review::pending()->count(),
                'tickets' => Ticket::where('status', 'open')->count(),
                'low_stock' => Product::whereBetween('stock', [1, config('digino.catalog.low_stock_threshold')])->count(),
                'out_of_stock' => Product::where('stock', 0)->count(),
            ],

            'recentOrders' => Order::with('user')->latest()->limit(8)->get(),

            'topProducts' => Product::with('brand')->orderByDesc('sold_count')->limit(6)->get(),

            'lowStock' => Product::with('brand')
                ->where('stock', '<=', config('digino.catalog.low_stock_threshold'))
                ->orderBy('stock')->limit(6)->get(),

            'statusBreakdown' => collect(OrderStatus::cases())->map(fn ($s) => [
                'label' => $s->label(),
                'value' => $s->value,
                'count' => Order::where('status', $s->value)->count(),
                'class' => $s->badgeClass(),
            ])->all(),

            'chart' => $this->salesSeries(14),
            'activity' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ]);
    }

    public function chart(Request $request)
    {
        return $this->ok('', ['chart' => $this->salesSeries($request->integer('days', 14))]);
    }

    public function activity()
    {
        return $this->ok('', [
            'html' => view('admin.partials.activity-list', [
                'activity' => ActivityLog::with('user')->latest()->limit(15)->get(),
            ])->render(),
        ]);
    }

    /** Daily revenue + order count for the dashboard chart. */
    protected function salesSeries(int $days = 14): array
    {
        $days = max(7, min(90, $days));

        $rows = Order::query()
            ->where('payment_status', PaymentStatus::Paid->value)
            ->where('paid_at', '>=', now()->subDays($days)->startOfDay())
            ->selectRaw('DATE(paid_at) as d, SUM(grand_total) as total, COUNT(*) as orders')
            ->groupBy('d')
            ->pluck('total', 'd');

        $orderRows = Order::query()
            ->where('created_at', '>=', now()->subDays($days)->startOfDay())
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->groupBy('d')
            ->pluck('c', 'd');

        $series = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->toDateString();

            $series[] = [
                'date' => $key,
                'label' => Jalali::format($date, 'j F'),
                'short' => Jalali::format($date, 'm/d'),
                'revenue' => (int) ($rows[$key] ?? 0),
                'orders' => (int) ($orderRows[$key] ?? 0),
            ];
        }

        return $series;
    }

    protected function delta(int|float $current, int|float $previous): ?array
    {
        if ($previous <= 0) {
            return $current > 0 ? ['value' => 100, 'up' => true] : null;
        }

        $change = (int) round((($current - $previous) / $previous) * 100);

        return ['value' => abs($change), 'up' => $change >= 0];
    }
}
