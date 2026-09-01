@extends('layouts.account')

@section('title', 'تراکنش‌ها')
@section('heading', 'کیف پول و تراکنش‌ها')
@section('subheading', 'سابقهٔ پرداخت‌های موفق شما در دیجی‌نو')

@section('content')
<div class="mb-4 grid gap-3 sm:grid-cols-3">
    <div class="stat-card" data-reveal>
        <span class="stat-icon bg-success-50 text-success-600"><x-icon name="wallet" class="h-6 w-6" /></span>
        <span>
            <span class="block text-lg font-extrabold text-ink-900" data-count-to="{{ toman($totalPaid) }}">۰</span>
            <span class="mt-0.5 block text-2xs text-ink-500">مجموع پرداختی (تومان)</span>
        </span>
    </div>
    <div class="stat-card" data-reveal>
        <span class="stat-icon bg-info-50 text-info-600"><x-icon name="receipt" class="h-6 w-6" /></span>
        <span>
            <span class="block text-lg font-extrabold text-ink-900" data-count-to="{{ $orders->total() }}">۰</span>
            <span class="mt-0.5 block text-2xs text-ink-500">تراکنش موفق</span>
        </span>
    </div>
    <div class="stat-card" data-reveal>
        <span class="stat-icon bg-brand-50 text-brand-500"><x-icon name="credit-card" class="h-6 w-6" /></span>
        <span>
            <span class="block text-lg font-extrabold text-ink-900">
                {{ $orders->total() ? fa_number(toman((int) round($totalPaid / max(1, $orders->total())))) : '۰' }}
            </span>
            <span class="mt-0.5 block text-2xs text-ink-500">میانگین هر خرید (تومان)</span>
        </span>
    </div>
</div>

<div class="overflow-hidden rounded-card bg-white shadow-card">
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>کد سفارش</th>
                    <th>تاریخ پرداخت</th>
                    <th>روش پرداخت</th>
                    <th>کد پیگیری</th>
                    <th>مبلغ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="ltr text-2xs font-bold text-ink-900">{{ fa_number($order->code) }}</td>
                        <td class="text-2xs text-ink-600">{{ jalali($order->paid_at ?: $order->created_at, 'Y/m/d — H:i') }}</td>
                        <td class="text-2xs text-ink-600">
                            {{ match($order->payment_method) { 'online' => 'پرداخت اینترنتی', 'cod' => 'پرداخت در محل', 'wallet' => 'کیف پول', default => '—' } }}
                        </td>
                        <td class="ltr text-2xs text-ink-500">{{ $order->transaction_ref ?: '—' }}</td>
                        <td class="text-2xs font-bold text-ink-900">{{ fa_number(toman($order->grand_total)) }} تومان</td>
                        <td>
                            <a href="{{ route('account.orders.show', $order) }}" class="btn-icon h-8 w-8" aria-label="مشاهده سفارش">
                                <x-icon name="chevron-left" class="h-4 w-4" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <x-empty-state icon="wallet" title="تراکنشی ثبت نشده است"
                                           message="پس از اولین پرداخت موفق، سابقهٔ تراکنش‌ها اینجا نمایش داده می‌شود." />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $orders->links() }}
@endsection
