@extends('layouts.admin')

@section('title', 'پیشخوان')
@section('heading', 'پیشخوان مدیریت')
@section('subheading', 'نمای کلی فروش، سفارش‌ها و وضعیت فروشگاه')

@section('content')

{{-- ══════════════ KPI cards ══════════════ --}}
<div class="grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
    @php
        $tones = [
            'success' => 'bg-success-50 text-success-600',
            'brand' => 'bg-brand-50 text-brand-500',
            'info' => 'bg-info-50 text-info-600',
            'warning' => 'bg-warning-50 text-warning-600',
        ];
    @endphp
    @foreach($cards as $card)
        <article class="rounded-card bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover" data-reveal>
            <div class="flex items-start justify-between gap-3">
                <span class="stat-icon {{ $tones[$card['tone']] }}">
                    <x-icon :name="$card['icon']" class="h-6 w-6" />
                </span>

                @if($card['delta'])
                    <span class="flex items-center gap-1 rounded-pill px-2 py-1 text-[10px] font-bold {{ $card['delta']['up'] ? 'bg-success-50 text-success-600' : 'bg-brand-50 text-brand-500' }}">
                        <x-icon :name="$card['delta']['up'] ? 'trend-up' : 'trend-down'" class="h-3.5 w-3.5" />
                        {{ fa_number($card['delta']['value']) }}٪
                    </span>
                @endif
            </div>

            <p class="mt-4 text-2xs text-ink-500">{{ $card['label'] }}</p>
            <p class="mt-1 flex items-baseline gap-1.5">
                <span class="text-xl font-extrabold text-ink-900">{{ fa_number($card['value']) }}</span>
                <span class="text-[11px] text-ink-500">{{ $card['suffix'] }}</span>
            </p>
        </article>
    @endforeach
</div>

{{-- ══════════════ today + pending ══════════════ --}}
<div class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1fr)_22rem]">

    {{-- sales chart --}}
    <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="chart" class="h-5 w-5 text-brand-500" />
                روند فروش
            </h2>

            <div class="flex items-center gap-1 rounded-field bg-ink-100 p-0.5">
                @foreach([7 => '۷ روز', 14 => '۱۴ روز', 30 => '۳۰ روز'] as $days => $label)
                    <button type="button" data-chart-range="{{ $days }}" @if($days === 14) data-active @endif
                            class="rounded px-3 py-1.5 text-[11px] font-medium text-ink-600 transition-colors data-[active]:bg-white data-[active]:font-bold data-[active]:text-ink-900 data-[active]:shadow-sm">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div id="sales-chart" data-chart='@json($chart)' data-chart-key="revenue" data-chart-height="240"></div>

        <div class="mt-4 grid grid-cols-2 gap-3 border-t border-ink-100 pt-4 sm:grid-cols-4">
            @php
                $today = [
                    ['label' => 'سفارش امروز', 'value' => fa_number($todayStats['orders']), 'icon' => 'receipt'],
                    ['label' => 'درآمد امروز (تومان)', 'value' => fa_number(toman($todayStats['revenue'])), 'icon' => 'wallet'],
                    ['label' => 'مشتری جدید امروز', 'value' => fa_number($todayStats['customers']), 'icon' => 'user-plus'],
                    ['label' => 'بازدید کالاها', 'value' => fa_number($todayStats['visits']), 'icon' => 'eye'],
                ];
            @endphp
            @foreach($today as $item)
                <div class="flex items-center gap-2.5">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-ink-100 text-ink-500">
                        <x-icon :name="$item['icon']" class="h-[18px] w-[18px]" />
                    </span>
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-extrabold text-ink-900">{{ $item['value'] }}</span>
                        <span class="block truncate text-[10px] text-ink-500">{{ $item['label'] }}</span>
                    </span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- pending work --}}
    <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
        <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="bell" class="h-5 w-5 text-brand-500" />
            کارهای در انتظار
        </h2>

        @php
            $tasks = [
                ['label' => 'سفارش در انتظار پرداخت', 'value' => $pending['orders'], 'icon' => 'clock', 'tone' => 'bg-warning-50 text-warning-600', 'url' => route('admin.orders.index', ['status' => 'pending'])],
                ['label' => 'سفارش در حال پردازش', 'value' => $pending['processing'], 'icon' => 'box', 'tone' => 'bg-info-50 text-info-600', 'url' => route('admin.orders.index', ['status' => 'processing'])],
                ['label' => 'دیدگاه در انتظار تأیید', 'value' => $pending['reviews'], 'icon' => 'star', 'tone' => 'bg-brand-50 text-brand-500', 'url' => route('admin.reviews.index', ['status' => 'pending'])],
                ['label' => 'تیکت باز', 'value' => $pending['tickets'], 'icon' => 'headset', 'tone' => 'bg-success-50 text-success-600', 'url' => route('admin.tickets.index', ['status' => 'open'])],
                ['label' => 'کالای رو به اتمام', 'value' => $pending['low_stock'], 'icon' => 'alert', 'tone' => 'bg-warning-50 text-warning-600', 'url' => route('admin.inventory.index', ['filter' => 'low'])],
                ['label' => 'کالای ناموجود', 'value' => $pending['out_of_stock'], 'icon' => 'x-circle', 'tone' => 'bg-ink-100 text-ink-600', 'url' => route('admin.inventory.index', ['filter' => 'out'])],
            ];
        @endphp

        <ul class="space-y-1">
            @foreach($tasks as $task)
                <li>
                    <a href="{{ $task['url'] }}" class="flex items-center gap-3 rounded-field p-2.5 transition-colors hover:bg-ink-50">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full {{ $task['tone'] }}">
                            <x-icon :name="$task['icon']" class="h-[18px] w-[18px]" />
                        </span>
                        <span class="flex-1 truncate text-2xs text-ink-700">{{ $task['label'] }}</span>
                        <span class="shrink-0 text-sm font-extrabold text-ink-900">{{ fa_number($task['value']) }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- order status breakdown --}}
        <div class="mt-5 border-t border-ink-100 pt-4">
            <p class="mb-3 text-2xs font-bold text-ink-800">وضعیت سفارش‌ها</p>
            @php $totalOrders = max(1, collect($statusBreakdown)->sum('count')); @endphp
            <div class="mb-3 flex h-2 overflow-hidden rounded-pill bg-ink-100">
                @foreach($statusBreakdown as $status)
                    @continue($status['count'] === 0)
                    <span class="{{ str_replace(['bg-', '50'], ['bg-', '400'], explode(' ', $status['class'])[0]) }} h-full transition-all duration-700"
                          style="width: {{ $status['count'] / $totalOrders * 100 }}%"
                          title="{{ $status['label'] }}: {{ fa_number($status['count']) }}"></span>
                @endforeach
            </div>
            <ul class="grid grid-cols-2 gap-x-3 gap-y-1.5">
                @foreach($statusBreakdown as $status)
                    <li class="flex items-center justify-between gap-2 text-[11px]">
                        <span class="truncate text-ink-600">{{ $status['label'] }}</span>
                        <span class="font-bold text-ink-800">{{ fa_number($status['count']) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
</div>

{{-- ══════════════ recent orders + top products ══════════════ --}}
<div class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1fr)_22rem]">

    <section class="overflow-hidden rounded-card bg-white shadow-card" data-reveal>
        <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="receipt" class="h-5 w-5 text-brand-500" />
                آخرین سفارش‌ها
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="btn-link">
                همه سفارش‌ها
                <x-icon name="chevron-left" class="h-4 w-4" />
            </a>
        </div>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کد سفارش</th>
                        <th>مشتری</th>
                        <th>تاریخ</th>
                        <th>مبلغ</th>
                        <th>وضعیت</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="ltr text-2xs font-bold text-ink-900">{{ fa_number($order->code) }}</td>
                            <td class="text-2xs text-ink-700">{{ $order->user?->name ?? 'مهمان' }}</td>
                            <td class="text-2xs text-ink-500">{{ jalali($order->created_at) }}</td>
                            <td class="text-2xs font-bold text-ink-900">{{ fa_number(toman($order->grand_total)) }}</td>
                            <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-icon h-8 w-8" aria-label="مشاهده سفارش">
                                    <x-icon name="eye" class="h-4 w-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-2xs text-ink-500">هنوز سفارشی ثبت نشده است.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="space-y-4">
        {{-- top products --}}
        <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="trend-up" class="h-5 w-5 text-brand-500" />
                    پرفروش‌ترین کالاها
                </h2>
            </div>
            <ul class="space-y-3">
                @foreach($topProducts as $product)
                    <li>
                        <a href="{{ route('admin.products.edit', $product) }}" class="group flex items-center gap-3">
                            <img src="{{ asset($product->primary_image) }}" alt="" class="h-11 w-11 shrink-0 rounded-lg object-contain" loading="lazy">
                            <span class="min-w-0 flex-1">
                                <span class="line-clamp-1 text-2xs text-ink-800 transition-colors group-hover:text-brand-500">{{ $product->name }}</span>
                                <span class="mt-0.5 block text-[10px] text-ink-400">{{ $product->brand?->name }}</span>
                            </span>
                            <span class="shrink-0 text-end">
                                <span class="block text-2xs font-bold text-ink-900">{{ fa_number($product->sold_count) }}</span>
                                <span class="block text-[10px] text-ink-400">فروش</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- low stock --}}
        <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="warehouse" class="h-5 w-5 text-warning-600" />
                    نیازمند شارژ انبار
                </h2>
                <a href="{{ route('admin.inventory.index') }}" class="btn-link">مدیریت</a>
            </div>
            <ul class="space-y-3">
                @forelse($lowStock as $product)
                    <li class="flex items-center gap-3">
                        <img src="{{ asset($product->primary_image) }}" alt="" class="h-10 w-10 shrink-0 rounded-lg object-contain" loading="lazy">
                        <span class="min-w-0 flex-1">
                            <span class="line-clamp-1 text-2xs text-ink-800">{{ $product->name }}</span>
                            <span class="mt-0.5 block text-[10px] text-ink-400">{{ $product->sku }}</span>
                        </span>
                        <span class="shrink-0 {{ $product->stock > 0 ? 'badge-amber' : 'badge-red' }}">
                            {{ fa_number($product->stock) }} عدد
                        </span>
                    </li>
                @empty
                    <li class="py-4 text-center text-2xs text-ink-500">موجودی همه کالاها مناسب است.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>

{{-- ══════════════ activity ══════════════ --}}
<section class="mt-4 rounded-card bg-white p-5 shadow-card" data-reveal>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="clock" class="h-5 w-5 text-brand-500" />
            فعالیت‌های اخیر
        </h2>
        <button type="button" id="refresh-activity" class="btn-ghost btn-sm">
            <x-icon name="refresh" class="h-4 w-4" />
            به‌روزرسانی
        </button>
    </div>

    <div id="activity-list">
        @include('admin.partials.activity-list', ['activity' => $activity])
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        var chartEl = document.getElementById('sales-chart');

        document.querySelectorAll('[data-chart-range]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('[data-chart-range]').forEach(function (b) { b.removeAttribute('data-active'); });
                btn.setAttribute('data-active', '');

                dg.http.get('{{ route('admin.dashboard.chart') }}?days=' + btn.dataset.chartRange)
                    .then(function (data) {
                        chartEl.dataset.chart = JSON.stringify(data.chart);
                        dg.renderChart(chartEl);
                    })
                    .catch(function (err) { dg.toast(err.message, 'error'); });
            });
        });

        document.getElementById('refresh-activity').addEventListener('click', function () {
            dg.http.get('{{ route('admin.dashboard.activity') }}')
                .then(function (data) {
                    document.getElementById('activity-list').innerHTML = data.html;
                    dg.toast('فهرست فعالیت‌ها به‌روزرسانی شد.', 'success', 2000);
                })
                .catch(function (err) { dg.toast(err.message, 'error'); });
        });
    });
</script>
@endpush
