@extends('layouts.admin')

@section('title', 'سفارش ' . $order->code)
@section('heading', 'سفارش ' . fa_number($order->code))
@section('subheading', 'ثبت شده در ' . jalali($order->created_at, 'j F Y — H:i'))

@section('breadcrumb')
    <a href="{{ route('admin.orders.index') }}" class="link-muted">سفارش‌ها</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="ltr text-ink-700">{{ fa_number($order->code) }}</span>
@endsection

@section('content')
<div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_22rem] xl:items-start">

    {{-- ═══════════ main ═══════════ --}}
    <div class="space-y-4">

        {{-- status header --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge {{ $order->status->badgeClass() }}" data-order-badge>{{ $order->status->label() }}</span>
                    <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span>
                    @if($order->tracking_code)
                        <span class="badge-blue">کد رهگیری: <span class="ltr">{{ fa_number($order->tracking_code) }}</span></span>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" rel="noopener" class="btn-outline btn-sm">
                        <x-icon name="printer" class="h-4 w-4" />
                        چاپ فاکتور
                    </a>
                    @if($order->status->allowedTransitions())
                        <button type="button" class="btn-primary btn-sm" data-modal-open="order-status">
                            <x-icon name="refresh" class="h-4 w-4" />
                            تغییر وضعیت
                        </button>
                    @endif
                </div>
            </div>

            {{-- progress --}}
            @php $step = $order->status->step(); @endphp
            @if($step > 0)
                <ol class="mt-6 flex items-center">
                    @foreach(['ثبت سفارش', 'پرداخت و تأیید', 'آماده‌سازی و ارسال', 'تحویل به مشتری'] as $i => $label)
                        @php $index = $i + 1; @endphp
                        <li class="flex flex-1 items-center {{ $loop->last ? 'flex-none' : '' }}">
                            <span class="flex flex-col items-center gap-2">
                                <span class="grid h-9 w-9 place-items-center rounded-full border-2 transition-colors
                                    {{ $index <= $step ? 'border-success-500 bg-success-500 text-white' : 'border-ink-200 bg-white text-ink-300' }}">
                                    @if($index < $step)
                                        <x-icon name="check" class="h-4 w-4" />
                                    @else
                                        <span class="text-2xs font-bold">{{ fa_number($index) }}</span>
                                    @endif
                                </span>
                                <span class="whitespace-nowrap text-[10px] {{ $index <= $step ? 'font-bold text-ink-800' : 'text-ink-400' }}">{{ $label }}</span>
                            </span>
                            @unless($loop->last)
                                <span class="mx-2 mb-5 h-0.5 flex-1 rounded-pill {{ $index < $step ? 'bg-success-500' : 'bg-ink-200' }}"></span>
                            @endunless
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="mt-4 flex items-center gap-2 rounded-field bg-brand-50 px-4 py-3 text-2xs text-brand-600">
                    <x-icon name="alert" class="h-5 w-5 shrink-0" />
                    این سفارش {{ $order->status->label() }} است.
                </p>
            @endif
        </section>

        {{-- items --}}
        <section class="overflow-hidden rounded-card bg-white shadow-card">
            <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="box" class="h-5 w-5 text-brand-500" />
                    کالاهای سفارش
                </h2>
                <span class="text-2xs text-ink-500">{{ fa_number($order->items->count()) }} ردیف</span>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>کالا</th>
                            <th>قیمت واحد</th>
                            <th>تعداد</th>
                            <th>جمع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset($item->image ?: 'images/placeholder-product.svg') }}" alt=""
                                             class="h-12 w-12 shrink-0 rounded-lg bg-ink-50 object-contain" loading="lazy">
                                        <div class="min-w-0">
                                            @if($item->product)
                                                <a href="{{ route('products.show', $item->product->slug) }}" target="_blank" rel="noopener"
                                                   class="line-clamp-2 max-w-sm text-2xs text-ink-800 transition-colors hover:text-brand-500">{{ $item->name }}</a>
                                            @else
                                                <span class="line-clamp-2 max-w-sm text-2xs text-ink-800">{{ $item->name }}</span>
                                            @endif
                                            @if($item->variant_title)
                                                <span class="mt-0.5 block text-[10px] text-ink-400">{{ $item->variant_title }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-2xs text-ink-600">{{ fa_number(toman($item->unit_price)) }}</td>
                                <td class="text-2xs text-ink-600">{{ fa_number($item->quantity) }}</td>
                                <td class="whitespace-nowrap text-2xs font-bold text-ink-900">{{ fa_number(toman($item->line_total)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- totals --}}
            <div class="border-t border-ink-100 bg-ink-50 px-5 py-4">
                <dl class="ms-auto max-w-sm space-y-2 text-2xs">
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-ink-500">جمع کالاها</dt>
                        <dd class="text-ink-800">{{ fa_number(toman($order->items_total)) }} تومان</dd>
                    </div>
                    @if($order->discount_total)
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-ink-500">تخفیف کالاها</dt>
                            <dd class="text-success-600">{{ fa_number(toman($order->discount_total)) }}−</dd>
                        </div>
                    @endif
                    @if($order->coupon_discount)
                        <div class="flex items-center justify-between gap-4">
                            <dt class="text-ink-500">کد تخفیف {{ $order->coupon?->code ? '('.$order->coupon->code.')' : '' }}</dt>
                            <dd class="text-success-600">{{ fa_number(toman($order->coupon_discount)) }}−</dd>
                        </div>
                    @endif
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-ink-500">هزینه ارسال {{ $order->shippingMethod?->name ? '('.$order->shippingMethod->name.')' : '' }}</dt>
                        <dd class="text-ink-800">
                            {{ $order->shipping_cost > 0 ? fa_number(toman($order->shipping_cost)).' تومان' : 'رایگان' }}
                        </dd>
                    </div>
                    <div class="flex items-center justify-between gap-4 border-t border-ink-200 pt-2 text-sm">
                        <dt class="font-bold text-ink-900">مبلغ قابل پرداخت</dt>
                        <dd class="font-extrabold text-ink-900">{{ fa_number(toman($order->grand_total)) }} تومان</dd>
                    </div>
                </dl>
            </div>
        </section>

        {{-- notes --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="note" class="h-5 w-5 text-brand-500" />
                یادداشت‌ها
            </h2>

            @if($order->customer_note)
                <div class="mb-4 rounded-field bg-info-50 px-4 py-3">
                    <p class="mb-1 text-[11px] font-bold text-info-600">یادداشت مشتری</p>
                    <p class="text-2xs leading-7 text-ink-700">{{ $order->customer_note }}</p>
                </div>
            @endif

            <form data-ajax-form action="{{ route('admin.orders.note', $order) }}" data-method="POST">
                <label class="label" for="admin-note">یادداشت داخلی (فقط برای همکاران)</label>
                <textarea id="admin-note" name="admin_note" rows="3" class="field"
                          placeholder="مثلاً: مشتری خواسته پیش از ارسال تماس گرفته شود.">{{ $order->admin_note }}</textarea>
                <div class="mt-3 flex justify-end">
                    <button type="submit" class="btn-outline btn-sm">
                        <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-ink-300 border-t-ink-600"></span>
                        <span data-submit-text>ذخیره یادداشت</span>
                    </button>
                </div>
            </form>
        </section>
    </div>

    {{-- ═══════════ sidebar ═══════════ --}}
    <aside class="space-y-4">

        {{-- customer --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">مشتری</h2>

            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-brand-50 text-2xs font-bold text-brand-500">
                    {{ $order->user?->initials ?? '؟' }}
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-2xs font-bold text-ink-900">{{ $order->user?->name ?? $order->receiver_name }}</span>
                    <span class="ltr block text-[11px] text-ink-500">{{ fa_number($order->user?->mobile ?? $order->receiver_mobile) }}</span>
                </span>
            </div>

            @if($order->user)
                <a href="{{ route('admin.customers.show', $order->user) }}" class="btn-outline btn-sm mt-4 w-full">
                    <x-icon name="user" class="h-4 w-4" />
                    پرونده مشتری
                </a>
            @endif
        </section>

        {{-- shipping --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">اطلاعات ارسال</h2>

            <dl class="space-y-3 text-2xs">
                <div>
                    <dt class="mb-1 text-ink-500">گیرنده</dt>
                    <dd class="text-ink-800">{{ $order->receiver_name }} — <span class="ltr">{{ fa_number($order->receiver_mobile) }}</span></dd>
                </div>
                <div>
                    <dt class="mb-1 text-ink-500">نشانی</dt>
                    <dd class="leading-7 text-ink-800">{{ $order->full_address }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-ink-500">کد پستی</dt>
                    <dd class="ltr text-ink-800">{{ fa_number($order->postal_code) }}</dd>
                </div>
                <div>
                    <dt class="mb-1 text-ink-500">روش ارسال</dt>
                    <dd class="text-ink-800">{{ $order->shippingMethod?->name ?? 'تعیین نشده' }}</dd>
                </div>
            </dl>

            <form data-ajax-form action="{{ route('admin.orders.tracking', $order) }}" data-method="POST" class="mt-4 border-t border-ink-100 pt-4">
                <label class="label" for="tracking">کد رهگیری مرسوله</label>
                <div class="flex items-center gap-2">
                    <input id="tracking" name="tracking_code" class="field-sm ltr flex-1" data-numeric
                           value="{{ $order->tracking_code }}" placeholder="۲۴ رقمی پست">
                    <button type="submit" class="btn-dark btn-sm shrink-0">
                        <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                        <span data-submit-text>ثبت</span>
                    </button>
                </div>
                <p class="error-text" data-error-for="tracking_code"></p>
            </form>
        </section>

        {{-- payment --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">پرداخت</h2>
            <dl class="space-y-2.5 text-2xs">
                @php
                    $rows = [
                        'روش پرداخت' => ['online' => 'پرداخت اینترنتی', 'cod' => 'پرداخت در محل', 'wallet' => 'کیف پول'][$order->payment_method] ?? '—',
                        'وضعیت' => $order->payment_status->label(),
                        'شناسه تراکنش' => $order->transaction_ref ? fa_number($order->transaction_ref) : '—',
                        'زمان پرداخت' => $order->paid_at ? jalali($order->paid_at, 'j F Y — H:i') : '—',
                    ];
                @endphp
                @foreach($rows as $label => $value)
                    <div class="flex items-start justify-between gap-3">
                        <dt class="shrink-0 text-ink-500">{{ $label }}</dt>
                        <dd class="ltr text-end font-medium text-ink-800">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        {{-- timeline --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">تاریخچه وضعیت</h2>
            <div data-order-timeline>
                @include('admin.orders.partials.timeline', ['order' => $order])
            </div>
        </section>
    </aside>
</div>

{{-- ═══════════ status modal ═══════════ --}}
<x-modal id="order-status" title="تغییر وضعیت سفارش" size="sm">
    <form data-ajax-form action="{{ route('admin.orders.status', $order) }}" data-method="POST"
          data-close-modal="order-status" id="order-status-form">
        <div class="space-y-4">
            <div>
                <label class="label" for="new-status">وضعیت جدید</label>
                <select id="new-status" name="status" class="field">
                    @foreach($order->status->allowedTransitions() as $target)
                        <option value="{{ $target->value }}">{{ $target->label() }}</option>
                    @endforeach
                </select>
                <p class="error-text" data-error-for="status"></p>
            </div>

            <div>
                <label class="label" for="status-note">توضیح (اختیاری)</label>
                <textarea id="status-note" name="note" rows="3" class="field" placeholder="این توضیح در تاریخچه سفارش ثبت می‌شود."></textarea>
                <p class="error-text" data-error-for="note"></p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ثبت وضعیت</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        document.getElementById('order-status-form').addEventListener('dg:submitted', function (e) {
            var data = e.detail || {};
            var badge = document.querySelector('[data-order-badge]');

            if (badge && data.label) {
                badge.textContent = data.label;
                badge.className = 'badge ' + data.badge;
                badge.classList.add('animate-bounce-in');
            }

            if (data.html) document.querySelector('[data-order-timeline]').innerHTML = data.html;

            setTimeout(function () { location.reload(); }, 1200);
        });
    });
</script>
@endpush
