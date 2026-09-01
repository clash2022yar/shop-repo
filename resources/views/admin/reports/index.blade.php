@extends('layouts.admin')

@section('title', 'گزارش‌ها')
@section('heading', 'گزارش‌های فروش')
@section('subheading', 'از ' . jalali($from) . ' تا ' . jalali($to))

@section('breadcrumb')
    <span class="text-ink-700">گزارش‌ها</span>
@endsection

@section('content')
<div>
    {{-- ═════════ range picker ═════════ --}}
    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3 rounded-card bg-white p-4 shadow-card">
        <div>
            <label class="label" for="rp-from">از تاریخ</label>
            <input id="rp-from" type="date" name="from" value="{{ $from->toDateString() }}" class="field-sm ltr">
        </div>
        <div>
            <label class="label" for="rp-to">تا تاریخ</label>
            <input id="rp-to" type="date" name="to" value="{{ $to->toDateString() }}" class="field-sm ltr">
        </div>
        <button type="submit" class="btn-dark btn-sm">
            <x-icon name="chart" class="h-4 w-4" />
            نمایش گزارش
        </button>

        <div class="ms-auto flex flex-wrap items-center gap-1.5">
            @foreach([7 => '۷ روز اخیر', 30 => '۳۰ روز اخیر', 90 => '۹۰ روز اخیر'] as $days => $label)
                <a href="{{ route('admin.reports.index', ['from' => now()->subDays($days)->toDateString(), 'to' => now()->toDateString()]) }}"
                   class="rounded-pill px-3 py-1.5 text-2xs text-ink-600 transition-colors hover:bg-ink-50">{{ $label }}</a>
            @endforeach
        </div>
    </form>

    {{-- ═════════ summary ═════════ --}}
    <div class="mb-4 grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
        @foreach([
            ['label' => 'درآمد (تومان)', 'value' => fa_number(toman($summary['revenue'])), 'icon' => 'wallet', 'tone' => 'bg-success-50 text-success-600'],
            ['label' => 'سفارش پرداخت‌شده', 'value' => fa_number($summary['orders']), 'icon' => 'receipt', 'tone' => 'bg-brand-50 text-brand-500'],
            ['label' => 'میانگین سبد (تومان)', 'value' => fa_number(toman($summary['avg'])), 'icon' => 'chart', 'tone' => 'bg-info-50 text-info-600'],
            ['label' => 'تعداد اقلام فروخته‌شده', 'value' => fa_number($summary['items']), 'icon' => 'box', 'tone' => 'bg-warning-50 text-warning-600'],
            ['label' => 'تخفیف کدها (تومان)', 'value' => fa_number(toman($summary['discount'])), 'icon' => 'ticket', 'tone' => 'bg-brand-50 text-brand-500'],
            ['label' => 'هزینه ارسال (تومان)', 'value' => fa_number(toman($summary['shipping'])), 'icon' => 'truck', 'tone' => 'bg-ink-100 text-ink-600'],
        ] as $card)
            <div class="rounded-card bg-white p-4 shadow-card" data-reveal>
                <span class="stat-icon {{ $card['tone'] }}"><x-icon :name="$card['icon']" class="h-5 w-5" /></span>
                <p class="mt-3 text-[11px] text-ink-500">{{ $card['label'] }}</p>
                <p class="mt-0.5 truncate text-sm font-extrabold text-ink-900">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ═════════ chart ═════════ --}}
    <section class="mb-4 rounded-card bg-white p-5 shadow-card" data-reveal>
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="trend-up" class="h-5 w-5 text-brand-500" />
                روند درآمد روزانه
            </h2>

            <div class="flex items-center gap-1 rounded-field bg-ink-100 p-0.5">
                <button type="button" data-metric="revenue" data-active
                        class="rounded px-3 py-1.5 text-[11px] text-ink-600 transition-colors data-[active]:bg-white data-[active]:font-bold data-[active]:text-ink-900 data-[active]:shadow-sm">درآمد</button>
                <button type="button" data-metric="orders"
                        class="rounded px-3 py-1.5 text-[11px] text-ink-600 transition-colors data-[active]:bg-white data-[active]:font-bold data-[active]:text-ink-900 data-[active]:shadow-sm">تعداد سفارش</button>
            </div>
        </div>

        <div id="report-chart" data-chart='@json($chart)' data-chart-key="revenue" data-chart-height="260"></div>
    </section>

    <div class="grid gap-4 xl:grid-cols-2">
        {{-- categories --}}
        <section class="overflow-hidden rounded-card bg-white shadow-card" data-reveal>
            <div class="border-b border-ink-100 px-5 py-4">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="grid" class="h-5 w-5 text-brand-500" />
                    فروش بر اساس دسته‌بندی
                </h2>
            </div>

            @php $maxRevenue = max(1, (int) collect($byCategory)->max('revenue')); @endphp

            <ul class="divide-y divide-ink-100">
                @forelse($byCategory as $row)
                    <li class="px-5 py-3.5">
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <span class="truncate text-2xs text-ink-800">{{ $row->name }}</span>
                            <span class="shrink-0 text-2xs font-bold text-ink-900">{{ fa_number(toman((int) $row->revenue)) }}</span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-pill bg-ink-100">
                            <span class="block h-full rounded-pill bg-brand-500 transition-all duration-700"
                                  style="width: {{ (int) $row->revenue / $maxRevenue * 100 }}%"></span>
                        </div>
                        <p class="mt-1 text-[10px] text-ink-400">{{ fa_number((int) $row->units) }} عدد فروخته شده</p>
                    </li>
                @empty
                    <li class="py-10 text-center text-2xs text-ink-500">داده‌ای برای نمایش وجود ندارد.</li>
                @endforelse
            </ul>
        </section>

        {{-- top products --}}
        <section class="overflow-hidden rounded-card bg-white shadow-card" data-reveal>
            <div class="border-b border-ink-100 px-5 py-4">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="trend-up" class="h-5 w-5 text-brand-500" />
                    پرفروش‌ترین کالاها
                </h2>
            </div>

            <ol class="divide-y divide-ink-100">
                @foreach($topProducts as $index => $product)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-ink-100 text-[11px] font-bold text-ink-600">
                            {{ fa_number($index + 1) }}
                        </span>
                        <img src="{{ asset($product->primary_image) }}" alt="" class="h-10 w-10 shrink-0 rounded-lg bg-ink-50 object-contain" loading="lazy">
                        <span class="min-w-0 flex-1">
                            <a href="{{ route('admin.products.edit', $product) }}" class="line-clamp-1 text-2xs text-ink-800 transition-colors hover:text-brand-500">
                                {{ $product->name }}
                            </a>
                            <span class="mt-0.5 block text-[10px] text-ink-400">موجودی: {{ fa_number($product->stock) }}</span>
                        </span>
                        <span class="shrink-0 text-end">
                            <span class="block text-2xs font-bold text-ink-900">{{ fa_number($product->sold_count) }}</span>
                            <span class="block text-[10px] text-ink-400">فروش</span>
                        </span>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- searches --}}
        <section class="overflow-hidden rounded-card bg-white shadow-card xl:col-span-2" data-reveal>
            <div class="border-b border-ink-100 px-5 py-4">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="search" class="h-5 w-5 text-brand-500" />
                    پرجستجوترین عبارت‌ها
                </h2>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>عبارت</th>
                            <th>تعداد جستجو</th>
                            <th>بیشترین نتیجه</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($searches as $search)
                            <tr>
                                <td class="text-2xs text-ink-800">{{ $search->term }}</td>
                                <td class="text-2xs text-ink-600">{{ fa_number($search->hits) }}</td>
                                <td class="text-2xs text-ink-600">{{ fa_number((int) $search->results) }}</td>
                                <td>
                                    @if((int) $search->results === 0)
                                        <span class="badge-red">بدون نتیجه</span>
                                    @else
                                        <span class="badge-green">دارای نتیجه</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-10 text-center text-2xs text-ink-500">هنوز جستجویی ثبت نشده است.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        var chart = document.getElementById('report-chart');

        document.querySelectorAll('[data-metric]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('[data-metric]').forEach(function (b) { b.removeAttribute('data-active'); });
                btn.setAttribute('data-active', '');
                chart.dataset.chartKey = btn.dataset.metric;
                dg.renderChart(chart);
            });
        });
    });
</script>
@endpush
