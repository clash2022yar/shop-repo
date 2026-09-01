@extends('layouts.account')

@section('title', 'سفارش ' . $order->code)
@section('heading', 'جزئیات سفارش ' . fa_number($order->code))
@section('subheading', 'ثبت‌شده در ' . jalali($order->created_at, 'Y/m/d — H:i'))

@section('actions')
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('account.orders.invoice', $order) }}" target="_blank" class="btn-outline btn-sm">
            <x-icon name="printer" class="h-4 w-4" />
            چاپ فاکتور
        </a>
        <a href="{{ route('account.orders.index') }}" class="btn-ghost btn-sm">
            <x-icon name="arrow-right" class="h-4 w-4" />
            بازگشت
        </a>
    </div>
@endsection

@section('content')

    {{-- ══════════ tracking timeline ══════════ --}}
    @php
        $steps = [
            ['label' => 'ثبت سفارش', 'icon' => 'receipt', 'step' => 1],
            ['label' => 'پردازش و بسته‌بندی', 'icon' => 'box', 'step' => 2],
            ['label' => 'ارسال', 'icon' => 'truck', 'step' => 3],
            ['label' => 'تحویل', 'icon' => 'check-circle', 'step' => 4],
        ];
        $current = $order->status->step();
    @endphp

    <section class="mb-4 rounded-card bg-white p-5 shadow-card" data-reveal>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-sm font-extrabold text-ink-900">وضعیت سفارش</h2>
            <span class="badge {{ $order->status->badgeClass() }}">
                <x-icon :name="$order->status->icon()" class="h-3.5 w-3.5" />
                {{ $order->status->label() }}
            </span>
        </div>

        @if($current > 0)
            <ol class="relative flex justify-between">
                <span class="absolute inset-x-5 top-5 -z-0 h-0.5 bg-ink-100" aria-hidden="true"></span>
                <span class="absolute start-5 top-5 -z-0 h-0.5 bg-success-500 transition-all duration-1000"
                      style="width: calc({{ max(0, ($current - 1) / 3 * 100) }}% - 1.25rem)" aria-hidden="true"></span>

                @foreach($steps as $step)
                    @php $state = $current > $step['step'] ? 'done' : ($current === $step['step'] ? 'current' : 'todo'); @endphp
                    <li class="relative z-10 flex w-20 flex-col items-center gap-2 text-center">
                        <span @class([
                            'grid h-10 w-10 place-items-center rounded-full ring-4 ring-white transition-colors',
                            'bg-success-500 text-white' => $state === 'done',
                            'bg-brand-500 text-white animate-pulse-ring' => $state === 'current',
                            'bg-ink-100 text-ink-400' => $state === 'todo',
                        ])>
                            <x-icon :name="$state === 'done' ? 'check' : $step['icon']" class="h-5 w-5" />
                        </span>
                        <span class="text-[11px] leading-5 {{ $state === 'todo' ? 'text-ink-400' : 'font-bold text-ink-800' }}">
                            {{ $step['label'] }}
                        </span>
                    </li>
                @endforeach
            </ol>
        @else
            <div class="flex items-start gap-3 rounded-card bg-brand-50 p-4 text-2xs leading-7 text-brand-700">
                <x-icon name="info" class="mt-0.5 h-5 w-5 shrink-0" />
                <p>
                    این سفارش {{ $order->status->label() }} است.
                    @if($order->cancelled_at) تاریخ: {{ jalali($order->cancelled_at, 'Y/m/d — H:i') }} @endif
                </p>
            </div>
        @endif

        @if($order->tracking_code)
            <div class="mt-4 flex flex-wrap items-center gap-2 rounded-field bg-info-50 px-4 py-3 text-2xs text-info-700">
                <x-icon name="truck" class="h-4 w-4" />
                کد رهگیری مرسوله:
                <span class="ltr font-bold">{{ fa_number($order->tracking_code) }}</span>
                <button type="button" data-copy="{{ $order->tracking_code }}" class="btn-icon h-7 w-7" aria-label="کپی کد رهگیری">
                    <x-icon name="copy" class="h-4 w-4" />
                </button>
            </div>
        @endif
    </section>

    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_18rem]">
        <div class="min-w-0 space-y-4">

            {{-- ══════════ items ══════════ --}}
            <section class="overflow-hidden rounded-card bg-white shadow-card" data-reveal>
                <h2 class="border-b border-ink-100 px-5 py-4 text-sm font-extrabold text-ink-900">
                    کالاهای سفارش ({{ fa_number($order->items_count) }} عدد)
                </h2>
                <div class="divide-y divide-ink-50">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-4">
                            @if($item->product)
                                <a href="{{ route('products.show', $item->product->slug) }}" class="shrink-0">
                                    <img src="{{ asset($item->image ?: $item->product->primary_image) }}" alt="{{ $item->name }}"
                                         class="h-20 w-20 rounded-lg object-contain" loading="lazy">
                                </a>
                            @else
                                <img src="{{ asset($item->image ?: 'images/placeholder-product.svg') }}" alt="{{ $item->name }}"
                                     class="h-20 w-20 shrink-0 rounded-lg object-contain" loading="lazy">
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-2xs leading-6 text-ink-800">{{ $item->name }}</p>
                                @if($item->variant_title)
                                    <p class="mt-1 text-[11px] text-ink-500">{{ $item->variant_title }}</p>
                                @endif
                                <p class="mt-1 text-[11px] text-ink-500">
                                    {{ fa_number($item->quantity) }} × {{ fa_number(toman($item->unit_price)) }} تومان
                                </p>

                                @if($item->product && $order->status->value === 'delivered')
                                    <a href="{{ route('products.show', $item->product->slug) }}#reviews" class="btn-link mt-1.5 text-[11px]">
                                        <x-icon name="star" class="h-3.5 w-3.5" />
                                        ثبت دیدگاه درباره این کالا
                                    </a>
                                @endif
                            </div>

                            <span class="shrink-0 text-end text-2xs font-bold text-ink-900">
                                {{ fa_number(toman($item->line_total)) }}
                                <span class="block text-[10px] font-normal text-ink-500">تومان</span>
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- ══════════ history ══════════ --}}
            @if($order->statusLogs->isNotEmpty())
                <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
                    <h2 class="mb-4 text-sm font-extrabold text-ink-900">تاریخچه سفارش</h2>
                    <ol class="relative space-y-4 border-s border-ink-100 ps-5">
                        @foreach($order->statusLogs->sortByDesc('created_at') as $log)
                            <li class="relative">
                                <span class="absolute -start-[1.6rem] top-1 grid h-4 w-4 place-items-center rounded-full bg-white">
                                    <span class="h-2 w-2 rounded-full bg-brand-500"></span>
                                </span>
                                <p class="text-2xs font-bold text-ink-800">{{ $log->label ?? $log->to_status }}</p>
                                <p class="mt-0.5 text-[11px] text-ink-400">{{ jalali($log->created_at, 'Y/m/d — H:i') }}</p>
                                @if($log->note)
                                    <p class="mt-1 text-[11px] leading-6 text-ink-600">{{ $log->note }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </section>
            @endif
        </div>

        {{-- ══════════ side ══════════ --}}
        <aside class="space-y-4">
            <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
                <h2 class="mb-3 text-sm font-extrabold text-ink-900">اطلاعات تحویل</h2>
                <dl class="space-y-2.5 text-2xs">
                    <div class="flex gap-2">
                        <dt class="shrink-0 text-ink-500">گیرنده:</dt>
                        <dd class="font-medium text-ink-800">{{ $order->receiver_name }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="shrink-0 text-ink-500">موبایل:</dt>
                        <dd class="ltr font-medium text-ink-800">{{ fa_number($order->receiver_mobile) }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="shrink-0 text-ink-500">نشانی:</dt>
                        <dd class="leading-6 text-ink-800">{{ $order->full_address }}</dd>
                    </div>
                    @if($order->postal_code)
                        <div class="flex gap-2">
                            <dt class="shrink-0 text-ink-500">کد پستی:</dt>
                            <dd class="ltr font-medium text-ink-800">{{ fa_number($order->postal_code) }}</dd>
                        </div>
                    @endif
                    @if($order->shippingMethod)
                        <div class="flex gap-2">
                            <dt class="shrink-0 text-ink-500">روش ارسال:</dt>
                            <dd class="font-medium text-ink-800">{{ $order->shippingMethod->name }}</dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
                <h2 class="mb-3 text-sm font-extrabold text-ink-900">صورتحساب</h2>
                <dl class="space-y-2.5 text-2xs">
                    <div class="flex justify-between">
                        <dt class="text-ink-600">قیمت کالاها</dt>
                        <dd class="text-ink-800">{{ fa_number(toman($order->items_total)) }} تومان</dd>
                    </div>
                    @if($order->discount_total > 0)
                        <div class="flex justify-between">
                            <dt class="text-ink-600">تخفیف کالاها</dt>
                            <dd class="text-brand-500">{{ fa_number(toman($order->discount_total)) }} تومان</dd>
                        </div>
                    @endif
                    @if($order->coupon_discount > 0)
                        <div class="flex justify-between">
                            <dt class="text-ink-600">کد تخفیف {{ $order->coupon?->code }}</dt>
                            <dd class="text-success-600">{{ fa_number(toman($order->coupon_discount)) }} تومان</dd>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <dt class="text-ink-600">هزینه ارسال</dt>
                        <dd class="text-ink-800">{{ $order->shipping_cost > 0 ? fa_number(toman($order->shipping_cost)) . ' تومان' : 'رایگان' }}</dd>
                    </div>
                    <div class="divider"></div>
                    <div class="flex items-baseline justify-between">
                        <dt class="font-bold text-ink-800">مبلغ کل</dt>
                        <dd class="text-sm font-extrabold text-ink-900">{{ fa_number(toman($order->grand_total)) }} تومان</dd>
                    </div>
                    <div class="flex justify-between pt-1">
                        <dt class="text-ink-600">وضعیت پرداخت</dt>
                        <dd><span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span></dd>
                    </div>
                </dl>

                @if($order->is_payable)
                    <a href="{{ route('checkout.payment', $order) }}" class="btn-primary mt-4 w-full">
                        <x-icon name="credit-card" class="h-4 w-4" />
                        پرداخت سفارش
                    </a>
                @endif

                @if($order->is_cancellable)
                    <button type="button" class="btn-ghost mt-2 w-full text-brand-500"
                            data-action="{{ route('ajax.account.orders.cancel', $order) }}"
                            data-method="POST" data-reload
                            data-confirm="آیا از لغو این سفارش مطمئن هستید؟"
                            data-confirm-title="لغو سفارش" data-confirm-accept="لغو سفارش">
                        <x-icon name="x-circle" class="h-4 w-4" />
                        لغو سفارش
                    </button>
                @endif
            </section>

            @if($order->customer_note)
                <section class="rounded-card bg-white p-5 shadow-card">
                    <h2 class="mb-2 text-sm font-extrabold text-ink-900">یادداشت شما</h2>
                    <p class="text-2xs leading-7 text-ink-600">{{ $order->customer_note }}</p>
                </section>
            @endif
        </aside>
    </div>
@endsection
