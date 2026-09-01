<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SearchLog;
use App\Support\Jalali;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->date('from') ?? now()->subDays(30);
        $to = $request->date('to') ?? now();

        $paid = Order::paid()->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);

        return view('admin.reports.index', [
            'from' => $from,
            'to' => $to,
            'summary' => [
                'revenue' => (int) (clone $paid)->sum('grand_total'),
                'orders' => (clone $paid)->count(),
                'avg' => (int) round((clone $paid)->avg('grand_total') ?? 0),
                'items' => (int) DB::table('order_items')
                    ->whereIn('order_id', (clone $paid)->pluck('id'))->sum('quantity'),
                'discount' => (int) (clone $paid)->sum('coupon_discount'),
                'shipping' => (int) (clone $paid)->sum('shipping_cost'),
            ],
            'byCategory' => Category::query()
                ->select('categories.name')
                ->selectRaw('COALESCE(SUM(order_items.line_total),0) as revenue')
                ->selectRaw('COALESCE(SUM(order_items.quantity),0) as units')
                ->leftJoin('products', 'products.category_id', '=', 'categories.id')
                ->leftJoin('order_items', 'order_items.product_id', '=', 'products.id')
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('revenue')
                ->limit(10)->get(),
            'topProducts' => Product::withSum('images as x', 'id')
                ->orderByDesc('sold_count')->limit(10)->get(),
            'searches' => SearchLog::query()
                ->selectRaw('term, COUNT(*) as hits, MAX(results_count) as results')
                ->groupBy('term')->orderByDesc('hits')->limit(15)->get(),
            'chart' => $this->series($from, $to),
        ]);
    }

    public function sales(Request $request)
    {
        $from = $request->date('from') ?? now()->subDays(30);
        $to = $request->date('to') ?? now();

        return $this->ok('', ['chart' => $this->series($from, $to)]);
    }

    public function topProducts(Request $request)
    {
        return $this->ok('', [
            'products' => Product::orderByDesc('sold_count')->limit($request->integer('limit', 10))
                ->get(['id', 'name', 'sold_count', 'price', 'stock']),
        ]);
    }

    public function searches()
    {
        return $this->ok('', [
            'terms' => SearchLog::query()
                ->selectRaw('term, COUNT(*) as hits')
                ->groupBy('term')->orderByDesc('hits')->limit(25)->get(),
        ]);
    }

    protected function series($from, $to): array
    {
        $rows = Order::query()
            ->where('payment_status', PaymentStatus::Paid->value)
            ->whereBetween('paid_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->selectRaw('DATE(paid_at) as d, SUM(grand_total) as total, COUNT(*) as c')
            ->groupBy('d')->get()->keyBy('d');

        $series = [];
        $cursor = $from->copy()->startOfDay();

        while ($cursor->lte($to)) {
            $key = $cursor->toDateString();
            $row = $rows[$key] ?? null;

            $series[] = [
                'date' => $key,
                'label' => Jalali::format($cursor, 'j F'),
                'short' => Jalali::format($cursor, 'm/d'),
                'revenue' => (int) ($row->total ?? 0),
                'orders' => (int) ($row->c ?? 0),
            ];

            $cursor->addDay();
        }

        return $series;
    }
}
