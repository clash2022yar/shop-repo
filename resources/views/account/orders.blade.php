@extends('layouts.account')

@section('title', 'سفارش‌های من')
@section('heading', 'سفارش‌های من')
@section('subheading', 'همه سفارش‌های ثبت‌شده و وضعیت لحظه‌ای آن‌ها')

@section('content')
    @php
        $tabs = [
            'all' => ['label' => 'همه سفارش‌ها', 'icon' => 'list'],
            'open' => ['label' => 'جاری', 'icon' => 'clock'],
            'shipped' => ['label' => 'ارسال شده', 'icon' => 'truck'],
            'delivered' => ['label' => 'تحویل شده', 'icon' => 'check-circle'],
            'cancelled' => ['label' => 'لغو/مرجوع', 'icon' => 'x-circle'],
        ];
    @endphp

    <div class="mb-4 rounded-card bg-white shadow-card">
        <div class="scrollbar-none overflow-x-auto border-b border-ink-100">
            <div class="flex min-w-max">
                @foreach($tabs as $key => $meta)
                    <a href="{{ route('account.orders.index', ['tab' => $key]) }}"
                       class="tab" aria-selected="{{ $tab === $key ? 'true' : 'false' }}">
                        <x-icon :name="$meta['icon']" class="h-4 w-4" />
                        {{ $meta['label'] }}
                        <span class="badge-gray">{{ fa_number($counts[$key]) }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <form action="{{ route('account.orders.index') }}" method="GET" class="flex gap-2 p-4">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="relative flex-1">
                <input type="search" name="q" value="{{ request('q') }}" class="field ps-10"
                       placeholder="جستجو بر اساس کد سفارش یا نام کالا...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>
            <button type="submit" class="btn-outline shrink-0">جستجو</button>
        </form>
    </div>

    <div class="space-y-4">
        @include('account.partials.order-list', ['orders' => $orders])
    </div>
@endsection
