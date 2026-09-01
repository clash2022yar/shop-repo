@extends('layouts.admin')

@section('title', $customer->name)
@section('heading', 'پرونده مشتری')
@section('subheading', $customer->name)

@section('breadcrumb')
    <a href="{{ route('admin.customers.index') }}" class="link-muted">مشتریان</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="text-ink-700">{{ $customer->name }}</span>
@endsection

@section('content')
<div class="grid gap-4 xl:grid-cols-[22rem_minmax(0,1fr)] xl:items-start">

    {{-- ═══════════ profile ═══════════ --}}
    <aside class="space-y-4 xl:sticky xl:top-24">
        <section class="rounded-card bg-white p-5 text-center shadow-card" data-reveal>
            <span class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-brand-50 text-xl font-extrabold text-brand-500">
                {{ $customer->initials }}
            </span>

            <h2 class="mt-3 text-base font-extrabold text-ink-900">{{ $customer->name }}</h2>
            <p class="ltr mt-1 text-2xs text-ink-500">{{ $customer->email }}</p>

            <div class="mt-3 flex items-center justify-center gap-2">
                @if($customer->is_active)
                    <span class="badge-green">حساب فعال</span>
                @else
                    <span class="badge-gray">حساب غیرفعال</span>
                @endif
                <span class="badge-blue">{{ $customer->role->label() }}</span>
            </div>

            <div class="mt-5 flex items-center gap-2">
                <button type="button" class="btn-outline btn-sm flex-1"
                        data-action="{{ route('admin.customers.toggle', $customer) }}" data-method="POST" data-reload>
                    <x-icon :name="$customer->is_active ? 'lock' : 'unlock'" class="h-4 w-4" />
                    {{ $customer->is_active ? 'غیرفعال کردن' : 'فعال کردن' }}
                </button>
                <a href="mailto:{{ $customer->email }}" class="btn-outline btn-sm flex-1">
                    <x-icon name="mail" class="h-4 w-4" />
                    ایمیل
                </a>
            </div>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
            <h3 class="mb-4 text-sm font-extrabold text-ink-900">اطلاعات حساب</h3>
            <dl class="space-y-3 text-2xs">
                @php
                    $genders = ['male' => 'آقا', 'female' => 'خانم'];
                    $info = [
                        'موبایل' => fa_number($customer->mobile),
                        'کد ملی' => $customer->national_code ? fa_number($customer->national_code) : '—',
                        'تاریخ تولد' => $customer->birthday ? jalali($customer->birthday) : '—',
                        'جنسیت' => $genders[$customer->gender] ?? '—',
                        'تاریخ عضویت' => jalali($customer->created_at),
                        'خبرنامه' => $customer->newsletter ? 'عضو' : 'عضو نیست',
                    ];
                @endphp
                @foreach($info as $label => $value)
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-ink-500">{{ $label }}</dt>
                        <dd class="ltr font-medium text-ink-800">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
            <h3 class="mb-4 text-sm font-extrabold text-ink-900">نشانی‌ها</h3>
            <ul class="space-y-3">
                @forelse($customer->addresses as $address)
                    <li class="rounded-field border border-ink-200 p-3">
                        <p class="mb-1 flex items-center gap-2 text-2xs font-bold text-ink-800">
                            {{ $address->label ?: 'نشانی' }}
                            @if($address->is_default)<span class="badge-red-soft">پیش‌فرض</span>@endif
                        </p>
                        <p class="text-[11px] leading-6 text-ink-600">{{ $address->full }}</p>
                        <p class="ltr mt-1 text-[10px] text-ink-400">{{ fa_number($address->postal_code) }}</p>
                    </li>
                @empty
                    <li class="py-3 text-center text-2xs text-ink-500">نشانی ثبت نشده است.</li>
                @endforelse
            </ul>
        </section>
    </aside>

    {{-- ═══════════ activity ═══════════ --}}
    <div class="space-y-4">
        <div class="grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
            @foreach([
                ['label' => 'تعداد سفارش', 'value' => fa_number($totals['orders']), 'icon' => 'receipt', 'tone' => 'bg-brand-50 text-brand-500'],
                ['label' => 'مجموع خرید (تومان)', 'value' => fa_number(toman($totals['paid'])), 'icon' => 'wallet', 'tone' => 'bg-success-50 text-success-600'],
                ['label' => 'میانگین سبد (تومان)', 'value' => fa_number(toman($totals['avg'])), 'icon' => 'chart', 'tone' => 'bg-info-50 text-info-600'],
                ['label' => 'آخرین سفارش', 'value' => $totals['last'] ? jalali($totals['last']) : '—', 'icon' => 'calendar', 'tone' => 'bg-warning-50 text-warning-600'],
            ] as $card)
                <div class="rounded-card bg-white p-4 shadow-card" data-reveal>
                    <span class="stat-icon {{ $card['tone'] }}"><x-icon :name="$card['icon']" class="h-5 w-5" /></span>
                    <p class="mt-3 text-[11px] text-ink-500">{{ $card['label'] }}</p>
                    <p class="mt-0.5 text-sm font-extrabold text-ink-900">{{ $card['value'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- orders --}}
        <section class="overflow-hidden rounded-card bg-white shadow-card">
            <div class="border-b border-ink-100 px-5 py-4">
                <h3 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="receipt" class="h-5 w-5 text-brand-500" />
                    سفارش‌های مشتری
                </h3>
            </div>

            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>کد سفارش</th>
                            <th>تاریخ</th>
                            <th>اقلام</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="ltr text-2xs font-bold text-ink-900">{{ fa_number($order->code) }}</td>
                                <td class="whitespace-nowrap text-2xs text-ink-500">{{ jalali($order->created_at) }}</td>
                                <td class="text-2xs text-ink-600">{{ fa_number($order->items->count()) }}</td>
                                <td class="whitespace-nowrap text-2xs font-bold text-ink-900">{{ fa_number(toman($order->grand_total)) }}</td>
                                <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-icon h-8 w-8" aria-label="مشاهده سفارش">
                                        <x-icon name="eye" class="h-4 w-4" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-10 text-center text-2xs text-ink-500">این مشتری هنوز خریدی نکرده است.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5">{{ $orders->links() }}</div>
        </section>

        {{-- reviews --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h3 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="star" class="h-5 w-5 text-brand-500" />
                دیدگاه‌های ثبت‌شده
            </h3>

            <ul class="space-y-3">
                @forelse($customer->reviews as $review)
                    <li class="rounded-field border border-ink-200 p-4">
                        <div class="mb-2 flex flex-wrap items-center justify-between gap-2">
                            <span class="line-clamp-1 text-2xs font-bold text-ink-800">{{ $review->product?->name }}</span>
                            <x-stars :rating="$review->rating" />
                        </div>
                        @if($review->title)<p class="mb-1 text-2xs text-ink-800">{{ $review->title }}</p>@endif
                        <p class="text-[11px] leading-7 text-ink-600">{{ $review->body }}</p>
                        <p class="mt-2 text-[10px] text-ink-400">{{ jalali($review->created_at) }}</p>
                    </li>
                @empty
                    <li class="py-4 text-center text-2xs text-ink-500">دیدگاهی ثبت نکرده است.</li>
                @endforelse
            </ul>
        </section>
    </div>
</div>
@endsection
