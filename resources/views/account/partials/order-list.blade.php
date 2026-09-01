@forelse($orders as $order)
    <article class="overflow-hidden rounded-card bg-white shadow-card transition-shadow hover:shadow-card-hover animate-fade-up">

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink-100 bg-ink-50/60 px-4 py-3">
            <div class="flex flex-wrap items-center gap-x-5 gap-y-1.5 text-2xs">
                <span class="flex items-center gap-1.5">
                    <span class="text-ink-500">کد سفارش:</span>
                    <span class="ltr font-bold text-ink-900">{{ fa_number($order->code) }}</span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="text-ink-500">تاریخ:</span>
                    <span class="font-medium text-ink-800">{{ jalali($order->created_at) }}</span>
                </span>
            </div>
            <span class="badge {{ $order->status->badgeClass() }}">
                <x-icon :name="$order->status->icon()" class="h-3.5 w-3.5" />
                {{ $order->status->label() }}
            </span>
        </div>

        <div class="flex flex-wrap items-center gap-4 p-4">
            <div class="flex flex-1 flex-wrap items-center gap-2">
                @foreach($order->items->take(4) as $item)
                    <img src="{{ asset($item->image ?: 'images/placeholder-product.svg') }}" alt="{{ $item->name }}"
                         title="{{ $item->name }}"
                         class="h-16 w-16 rounded-lg border border-ink-100 bg-white object-contain p-1" loading="lazy">
                @endforeach
                @if($order->items->count() > 4)
                    <span class="grid h-16 w-16 place-items-center rounded-lg bg-ink-100 text-2xs font-bold text-ink-600">
                        +{{ fa_number($order->items->count() - 4) }}
                    </span>
                @endif
            </div>

            <div class="text-end">
                <p class="text-[11px] text-ink-500">مبلغ سفارش</p>
                <p class="mt-0.5 text-sm font-extrabold text-ink-900">
                    {{ fa_number(toman($order->grand_total)) }}
                    <span class="text-[11px] font-medium text-ink-600">تومان</span>
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 border-t border-ink-100 px-4 py-3">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('account.orders.show', $order) }}" class="btn-outline btn-sm">
                    <x-icon name="eye" class="h-4 w-4" />
                    جزئیات سفارش
                </a>
                <a href="{{ route('account.orders.invoice', $order) }}" target="_blank" class="btn-ghost btn-sm">
                    <x-icon name="printer" class="h-4 w-4" />
                    فاکتور
                </a>
            </div>

            <div class="flex items-center gap-2">
                @if($order->is_payable)
                    <a href="{{ route('checkout.payment', $order) }}" class="btn-primary btn-sm">
                        <x-icon name="credit-card" class="h-4 w-4" />
                        پرداخت
                    </a>
                @endif
                @if($order->is_cancellable)
                    <button type="button" class="btn-ghost btn-sm text-brand-500"
                            data-action="{{ route('ajax.account.orders.cancel', $order) }}"
                            data-method="POST" data-reload
                            data-confirm="سفارش {{ fa_number($order->code) }} لغو شود؟ در صورت پرداخت، مبلغ به حساب شما بازگردانده می‌شود."
                            data-confirm-title="لغو سفارش" data-confirm-accept="لغو سفارش">
                        <x-icon name="x-circle" class="h-4 w-4" />
                        لغو سفارش
                    </button>
                @endif
            </div>
        </div>
    </article>
@empty
    <x-empty-state icon="box" title="سفارشی در این بخش وجود ندارد"
                   message="با تکمیل اولین خرید خود، سفارش‌هایتان اینجا نمایش داده می‌شود."
                   :action-url="route('shop.index')" action-label="مشاهده محصولات"
                   class="rounded-card bg-white shadow-card" />
@endforelse

@if($orders instanceof \Illuminate\Contracts\Pagination\Paginator || $orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $orders->links() }}
@endif
