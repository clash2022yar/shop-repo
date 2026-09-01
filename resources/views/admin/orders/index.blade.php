@extends('layouts.admin')

@section('title', 'سفارش‌ها')
@section('heading', 'مدیریت سفارش‌ها')
@section('subheading', fa_number($counts['all']) . ' سفارش ثبت شده است')

@section('breadcrumb')
    <span class="text-ink-700">سفارش‌ها</span>
@endsection

@section('content')
<div data-orders-table>

    {{-- revenue strip --}}
    <div class="mb-4 grid gap-3 stagger sm:grid-cols-3">
        @foreach([
            ['label' => 'درآمد کل', 'value' => $revenue['total'], 'icon' => 'wallet', 'tone' => 'bg-success-50 text-success-600'],
            ['label' => 'درآمد امروز', 'value' => $revenue['today'], 'icon' => 'calendar', 'tone' => 'bg-info-50 text-info-600'],
            ['label' => 'در انتظار پرداخت', 'value' => $revenue['unpaid'], 'icon' => 'clock', 'tone' => 'bg-warning-50 text-warning-600'],
        ] as $card)
            <div class="flex items-center gap-3 rounded-card bg-white p-4 shadow-card" data-reveal>
                <span class="stat-icon {{ $card['tone'] }}"><x-icon :name="$card['icon']" class="h-6 w-6" /></span>
                <span>
                    <span class="block text-[11px] text-ink-500">{{ $card['label'] }}</span>
                    <span class="block text-base font-extrabold text-ink-900">
                        {{ fa_number(toman($card['value'])) }}
                        <span class="text-[11px] font-normal text-ink-500">تومان</span>
                    </span>
                </span>
            </div>
        @endforeach
    </div>

    {{-- filters --}}
    <div class="mb-4 rounded-card bg-white p-4 shadow-card">
        <div class="mb-4 flex flex-wrap items-center gap-1.5">
            @php
                $tabs = array_merge(['' => 'همه'], collect(\App\Enums\OrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all());
            @endphp
            @foreach($tabs as $value => $label)
                <button type="button" data-status-tab="{{ $value }}"
                        @if((string) request('status') === (string) $value) data-active @endif
                        class="flex items-center gap-1.5 rounded-pill px-3 py-1.5 text-2xs text-ink-600 transition-colors hover:bg-ink-50 data-[active]:bg-brand-50 data-[active]:font-bold data-[active]:text-brand-600">
                    {{ $label }}
                    <span class="text-[10px] text-ink-400">{{ fa_number($counts[$value] ?? $counts['all']) }}</span>
                </button>
            @endforeach
        </div>

        <form data-admin-filter class="grid gap-2 sm:grid-cols-2 lg:grid-cols-6" onsubmit="return false">
            <input type="hidden" name="status" value="{{ request('status') }}">

            <div class="relative lg:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="کد سفارش، نام یا موبایل مشتری...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>

            <select name="payment_status" class="field-sm">
                <option value="">وضعیت پرداخت</option>
                @foreach(\App\Enums\PaymentStatus::options() as $value => $label)
                    <option value="{{ $value }}" @selected(request('payment_status') === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <input type="date" name="from" value="{{ request('from') }}" class="field-sm ltr" aria-label="از تاریخ">
            <input type="date" name="to" value="{{ request('to') }}" class="field-sm ltr" aria-label="تا تاریخ">

            <select name="sort" class="field-sm">
                <option value="">جدیدترین</option>
                <option value="expensive" @selected(request('sort') === 'expensive')>گران‌ترین</option>
                <option value="cheapest" @selected(request('sort') === 'cheapest')>ارزان‌ترین</option>
            </select>
        </form>
    </div>

    {{-- table --}}
    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کد سفارش</th>
                        <th>مشتری</th>
                        <th>تاریخ ثبت</th>
                        <th>اقلام</th>
                        <th>مبلغ کل</th>
                        <th>پرداخت</th>
                        <th>وضعیت</th>
                        <th class="w-32">عملیات</th>
                    </tr>
                </thead>
                <tbody data-admin-rows>
                    @include('admin.orders.partials.rows', ['orders' => $orders])
                </tbody>
            </table>
        </div>
    </div>

    <div data-admin-pagination>{{ $orders->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgTable({ root: '[data-orders-table]', url: '{{ route('admin.orders.table') }}' });
    });
</script>
@endpush
