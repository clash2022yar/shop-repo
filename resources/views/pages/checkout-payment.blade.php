@extends('layouts.app')

@section('title', 'پرداخت سفارش ' . $order->code . ' | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="container max-w-2xl">
    <div class="rounded-card bg-white p-6 shadow-card animate-fade-up lg:p-8">

        <div class="text-center">
            <span class="mx-auto mb-4 grid h-16 w-16 place-items-center rounded-full bg-info-50 text-info-600 animate-pulse-ring">
                <x-icon name="credit-card" class="h-8 w-8" />
            </span>
            <h1 class="text-lg font-extrabold text-ink-900">پرداخت سفارش</h1>
            <p class="mt-2 text-2xs leading-7 text-ink-500">
                سفارش شما با کد <span class="ltr font-bold text-ink-800">{{ fa_number($order->code) }}</span> ثبت شد.
                برای نهایی شدن، مبلغ زیر را پرداخت کنید.
            </p>
        </div>

        <dl class="my-6 space-y-3 rounded-card bg-ink-50 p-5 text-2xs">
            <div class="flex items-center justify-between">
                <dt class="text-ink-600">شماره سفارش</dt>
                <dd class="ltr font-bold text-ink-900">{{ fa_number($order->code) }}</dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="text-ink-600">تعداد کالا</dt>
                <dd class="font-medium text-ink-800">{{ fa_number($order->items_count) }} عدد</dd>
            </div>
            <div class="flex items-center justify-between">
                <dt class="text-ink-600">هزینه ارسال</dt>
                <dd class="font-medium text-ink-800">
                    {{ $order->shipping_cost > 0 ? fa_number(toman($order->shipping_cost)) . ' تومان' : 'رایگان' }}
                </dd>
            </div>
            <div class="divider"></div>
            <div class="flex items-baseline justify-between">
                <dt class="font-bold text-ink-800">مبلغ قابل پرداخت</dt>
                <dd class="text-lg font-extrabold text-ink-900">
                    {{ fa_number(toman($order->grand_total)) }}
                    <span class="text-2xs font-medium text-ink-600">تومان</span>
                </dd>
            </div>
        </dl>

        @if($order->payment_method === 'cod')
            <div class="mb-5 flex items-start gap-3 rounded-card bg-warning-50 p-4 text-2xs leading-7 text-warning-700 ring-1 ring-warning-100">
                <x-icon name="info" class="mt-0.5 h-5 w-5 shrink-0" />
                <p>
                    روش پرداخت شما «پرداخت در محل» است. مبلغ سفارش هنگام تحویل کالا از شما دریافت می‌شود.
                    برای تأیید نهایی سفارش، دکمهٔ زیر را بزنید.
                </p>
            </div>
        @endif

        <form action="{{ route('checkout.pay', $order) }}" method="POST" data-ajax-form data-redirect>
            @csrf
            <button type="submit" class="btn-primary btn-lg w-full">
                <x-icon name="lock" class="h-5 w-5" />
                <span data-submit-text>{{ $order->payment_method === 'cod' ? 'تأیید نهایی سفارش' : 'پرداخت و تکمیل خرید' }}</span>
                <x-icon name="spinner" class="hidden h-5 w-5 animate-spin-slow" data-submit-spinner />
            </button>
        </form>

        <a href="{{ route('account.orders.show', $order) }}" class="btn-ghost mt-3 w-full">
            پرداخت بعداً — مشاهده سفارش
        </a>

        <p class="mt-5 flex items-start gap-1.5 text-[11px] leading-6 text-ink-500">
            <x-icon name="shield-check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-success-500" />
            اطلاعات پرداخت شما رمزنگاری می‌شود و دیجی‌نو هیچ‌گاه اطلاعات کارت بانکی شما را ذخیره نمی‌کند.
        </p>
    </div>
</div>
@endsection
