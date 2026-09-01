@extends('layouts.account')

@section('title', 'پیشخوان')
@section('heading', 'سلام ' . auth()->user()->name . '، خوش آمدید!')
@section('subheading', 'از این بخش می‌توانید سفارش‌ها، اطلاعات حساب و علاقه‌مندی‌های خود را مدیریت کنید.')

@section('content')

    {{-- ══════════ stat cards ══════════ --}}
    @php
        $cards = [
            ['label' => 'سفارش‌های من', 'value' => $stats['orders'], 'icon' => 'box', 'tone' => 'bg-brand-50 text-brand-500', 'url' => route('account.orders.index')],
            ['label' => 'سفارش‌های جاری', 'value' => $stats['open_orders'], 'icon' => 'truck', 'tone' => 'bg-info-50 text-info-600', 'url' => route('account.orders.index', ['tab' => 'open'])],
            ['label' => 'علاقه‌مندی‌ها', 'value' => $stats['wishlist'], 'icon' => 'heart', 'tone' => 'bg-warning-50 text-warning-600', 'url' => route('account.wishlist')],
            ['label' => 'آدرس‌های من', 'value' => $stats['addresses'], 'icon' => 'map-pin', 'tone' => 'bg-success-50 text-success-600', 'url' => route('account.addresses')],
        ];
    @endphp

    <div class="mb-4 grid grid-cols-2 gap-3 stagger lg:grid-cols-4">
        @foreach($cards as $card)
            <a href="{{ $card['url'] }}" class="stat-card group" data-reveal>
                <span class="stat-icon {{ $card['tone'] }} transition-transform duration-300 group-hover:-translate-y-1">
                    <x-icon :name="$card['icon']" class="h-6 w-6" />
                </span>
                <span class="min-w-0">
                    <span class="block text-xl font-extrabold text-ink-900" data-count-to="{{ $card['value'] }}">۰</span>
                    <span class="mt-0.5 block truncate text-2xs text-ink-500">{{ $card['label'] }}</span>
                </span>
            </a>
        @endforeach
    </div>

    {{-- ══════════ profile completeness ══════════ --}}
    @php
        $user = auth()->user();
        $checks = [
            'نام و نام خانوادگی' => filled($user->name),
            'ایمیل' => filled($user->email),
            'شماره موبایل' => filled($user->mobile),
            'آدرس تحویل' => $stats['addresses'] > 0,
            'تاریخ تولد' => filled($user->birth_date ?? null),
        ];
        $done = count(array_filter($checks));
        $percent = (int) round($done / count($checks) * 100);
    @endphp

    @if($percent < 100)
        <section class="mb-4 rounded-card bg-white p-5 shadow-card" data-reveal>
            <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                <h2 class="text-sm font-extrabold text-ink-900">تکمیل اطلاعات حساب</h2>
                <span class="text-2xs font-bold text-brand-500">{{ fa_number($percent) }}٪ کامل شده</span>
            </div>

            <div class="h-2 overflow-hidden rounded-pill bg-ink-100">
                <div class="h-full rounded-pill bg-gradient-to-l from-brand-400 to-brand-600 transition-all duration-1000"
                     style="width: {{ $percent }}%"></div>
            </div>

            <ul class="mt-4 flex flex-wrap gap-x-5 gap-y-2">
                @foreach($checks as $label => $isDone)
                    <li class="flex items-center gap-1.5 text-2xs {{ $isDone ? 'text-success-600' : 'text-ink-500' }}">
                        <x-icon :name="$isDone ? 'check-circle' : 'x-circle'" class="h-4 w-4" />
                        {{ $label }}
                    </li>
                @endforeach
            </ul>

            <a href="{{ route('account.profile') }}" class="btn-link mt-3">
                تکمیل اطلاعات
                <x-icon name="chevron-left" class="h-4 w-4" />
            </a>
        </section>
    @endif

    {{-- ══════════ recent orders ══════════ --}}
    <section class="mb-4 overflow-hidden rounded-card bg-white shadow-card" data-reveal>
        <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="receipt" class="h-5 w-5 text-brand-500" />
                آخرین سفارش‌ها
            </h2>
            <a href="{{ route('account.orders.index') }}" class="btn-link">
                مشاهده همه
                <x-icon name="chevron-left" class="h-4 w-4" />
            </a>
        </div>

        @forelse($recentOrders as $order)
            <a href="{{ route('account.orders.show', $order) }}"
               class="flex flex-wrap items-center gap-4 border-b border-ink-50 p-4 transition-colors last:border-0 hover:bg-ink-50/60">

                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full {{ $order->status->badgeClass() }}">
                    <x-icon :name="$order->status->icon()" class="h-5 w-5" />
                </span>

                <span class="min-w-0 flex-1">
                    <span class="flex flex-wrap items-center gap-2">
                        <span class="ltr text-2xs font-bold text-ink-900">{{ fa_number($order->code) }}</span>
                        <span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span>
                    </span>
                    <span class="mt-1 block text-[11px] text-ink-500">
                        {{ jalali($order->created_at) }} — {{ fa_number($order->items_count) }} کالا
                    </span>
                </span>

                {{-- product thumbnails --}}
                <span class="hidden items-center sm:flex">
                    @foreach($order->items->take(3) as $item)
                        <img src="{{ asset($item->image ?: 'images/placeholder-product.svg') }}" alt=""
                             class="-ms-3 h-10 w-10 rounded-full border-2 border-white bg-white object-contain first:ms-0" loading="lazy">
                    @endforeach
                    @if($order->items->count() > 3)
                        <span class="-ms-3 grid h-10 w-10 place-items-center rounded-full border-2 border-white bg-ink-100 text-[10px] font-bold text-ink-600">
                            +{{ fa_number($order->items->count() - 3) }}
                        </span>
                    @endif
                </span>

                <span class="shrink-0 text-end">
                    <span class="block text-2xs font-extrabold text-ink-900">{{ fa_number(toman($order->grand_total)) }}</span>
                    <span class="text-[10px] text-ink-500">تومان</span>
                </span>

                <x-icon name="chevron-left" class="h-4 w-4 shrink-0 text-ink-300" />
            </a>
        @empty
            <x-empty-state icon="box" title="هنوز سفارشی ثبت نکرده‌اید"
                           message="اولین خرید خود را از دیجی‌نو انجام دهید؛ هزاران کالا در انتظار شماست."
                           :action-url="route('shop.index')" action-label="شروع خرید" />
        @endforelse
    </section>

    {{-- ══════════ recently viewed ══════════ --}}
    @if($recentlyViewed->isNotEmpty())
        <section class="mb-4 section" data-reveal>
            <x-section-header title="بازدیدهای اخیر شما" icon="clock" :more-url="route('account.recently-viewed')" />
            <x-rail>
                @foreach($recentlyViewed as $product)
                    <x-product-card :product="$product" :compact="true" class="w-[10.5rem] shrink-0 sm:w-[12rem]" />
                @endforeach
            </x-rail>
        </section>
    @endif

    {{-- ══════════ support strip ══════════ --}}
    <section class="grid gap-3 sm:grid-cols-2" data-reveal>
        <a href="{{ route('account.tickets.create') }}"
           class="group flex items-center gap-4 rounded-card bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-info-50 text-info-600 transition-transform duration-300 group-hover:-translate-y-1">
                <x-icon name="headset" class="h-6 w-6" />
            </span>
            <span class="min-w-0">
                <span class="block text-2xs font-bold text-ink-900">نیاز به کمک دارید؟</span>
                <span class="mt-0.5 block text-[11px] leading-6 text-ink-500">با ثبت تیکت، کارشناسان ما در کوتاه‌ترین زمان پاسخ می‌دهند.</span>
            </span>
        </a>

        <a href="{{ route('faq') }}"
           class="group flex items-center gap-4 rounded-card bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover">
            <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-warning-50 text-warning-600 transition-transform duration-300 group-hover:-translate-y-1">
                <x-icon name="question" class="h-6 w-6" />
            </span>
            <span class="min-w-0">
                <span class="block text-2xs font-bold text-ink-900">پرسش‌های متداول</span>
                <span class="mt-0.5 block text-[11px] leading-6 text-ink-500">پاسخ سریع پرتکرارترین سؤال‌ها دربارهٔ خرید، ارسال و بازگشت کالا.</span>
            </span>
        </a>
    </section>
@endsection
