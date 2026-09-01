<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.orders.index', [
            'orders' => $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString(),
            'counts' => collect(OrderStatus::cases())
                ->mapWithKeys(fn ($s) => [$s->value => Order::where('status', $s->value)->count()])
                ->put('all', Order::count())->all(),
            'revenue' => [
                'total' => (int) Order::paid()->sum('grand_total'),
                'today' => (int) Order::paid()->whereDate('paid_at', today())->sum('grand_total'),
                'unpaid' => (int) Order::where('payment_status', PaymentStatus::Unpaid->value)->sum('grand_total'),
            ],
        ]);
    }

    public function table(Request $request)
    {
        $orders = $this->query($request)->paginate(config('digino.admin.per_page'))->withQueryString();

        return $this->ok('', [
            'html' => view('admin.orders.partials.rows', compact('orders'))->render(),
            'pagination' => $orders->links()->render(),
            'total' => $orders->total(),
        ]);
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', [
            'order' => $order->load(['items.product.images', 'user', 'statusLogs.user', 'shippingMethod', 'coupon']),
        ]);
    }

    public function invoice(Order $order)
    {
        return view('admin.orders.invoice', ['order' => $order->load('items', 'user')]);
    }

    public function updateStatus(Request $request, Order $order, CheckoutService $checkout)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
            'note' => ['nullable', 'string', 'max:500'],
        ], ['status.required' => 'وضعیت جدید را انتخاب کنید.']);

        $target = OrderStatus::from($data['status']);

        if (! in_array($target, $order->status->allowedTransitions(), true)) {
            return $this->fail(
                'تغییر وضعیت از «'.$order->status->label().'» به «'.$target->label().'» مجاز نیست.'
            );
        }

        // Cancelling has to return stock, so it goes through the service.
        if ($target === OrderStatus::Cancelled) {
            $checkout->cancel($order, $data['note'] ?? null);
        } else {
            $order->transitionTo($target, $data['note'] ?? null, $request->user()->id);
        }

        ActivityLog::record('order.status', $order, "سفارش {$order->code} به «{$target->label()}» تغییر یافت.");

        $order->refresh();

        return $this->ok('وضعیت سفارش به‌روزرسانی شد.', [
            'status' => $order->status->value,
            'label' => $order->status->label(),
            'badge' => $order->status->badgeClass(),
            'html' => view('admin.orders.partials.timeline', [
                'order' => $order->load('statusLogs.user'),
            ])->render(),
        ]);
    }

    public function updateTracking(Request $request, Order $order)
    {
        $data = $request->validate([
            'tracking_code' => ['nullable', 'string', 'max:60'],
        ]);

        $order->update(['tracking_code' => en_number($data['tracking_code'] ?? '') ?: null]);

        return $this->ok('کد رهگیری مرسوله ذخیره شد.');
    }

    public function updateNote(Request $request, Order $order)
    {
        $order->update(['admin_note' => $request->input('admin_note')]);

        return $this->ok('یادداشت ذخیره شد.');
    }

    public function destroy(Order $order)
    {
        $code = $order->code;
        $order->delete();

        ActivityLog::record('order.deleted', null, "سفارش {$code} حذف شد.");

        return $this->ok('سفارش حذف شد.');
    }

    protected function query(Request $request)
    {
        return Order::query()
            ->with(['user', 'items'])
            ->search($request->input('q'))
            ->status($request->input('status'))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->input('payment_status')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('to')))
            ->when($request->input('sort') === 'cheapest', fn ($q) => $q->orderBy('grand_total'),
                fn ($q) => $request->input('sort') === 'expensive'
                    ? $q->orderByDesc('grand_total')
                    : $q->latest());
    }
}
