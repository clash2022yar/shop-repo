@extends('layouts.app')

@section('title', 'نتیجه سفارش ' . $order->code . ' | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
@php $paid = $order->payment_status->value === 'paid'; @endphp

<div class="container max-w-2xl">
    <div class="rounded-card bg-white p-6 text-center shadow-card animate-fade-up lg:p-10">

        <span class="mx-auto mb-5 grid h-20 w-20 place-items-center rounded-full {{ $paid ? 'bg-success-50 text-success-600' : 'bg-warning-50 text-warning-600' }} animate-bounce-in">
            <x-icon :name="$paid ? 'check-circle' : 'clock'" class="h-10 w-10" />
        </span>

        <h1 class="text-lg font-extrabold text-ink-900">
            {{ $paid ? 'سفارش شما با موفقیت ثبت شد' : 'سفارش شما در انتظار پرداخت است' }}
        </h1>

        <p class="mx-auto mt-2.5 max-w-md text-2xs leading-7 text-ink-500">
            @if($paid)
                از خرید شما سپاسگزاریم. جزئیات سفارش به حساب کاربری شما اضافه شد و
                وضعیت آن را می‌توانید از بخش «سفارش‌های من» پیگیری کنید.
            @else
                سفارش شما ثبت شده اما هنوز پرداخت نشده است. تا زمان پرداخت، کالاها برای شما رزرو نمی‌شوند.
            @endif
        </p>

        <dl class="my-6 grid gap-3 rounded-card bg-ink-50 p-5 text-2xs sm:grid-cols-2">
            <div class="flex items-center justify-between sm:flex-col sm:items-start sm:gap-1">
                <dt class="text-ink-500">کد رهگیری سفارش</dt>
                <dd class="ltr font-extrabold text-ink-900">{{ fa_number($order->code) }}</dd>
            </div>
            <div class="flex items-center justify-between sm:flex-col sm:items-start sm:gap-1">
                <dt class="text-ink-500">تاریخ ثبت</dt>
                <dd class="font-bold text-ink-900">{{ jalali($order->created_at, 'Y/m/d — H:i') }}</dd>
            </div>
            <div class="flex items-center justify-between sm:flex-col sm:items-start sm:gap-1">
                <dt class="text-ink-500">مبلغ</dt>
                <dd class="font-bold text-ink-900">{{ fa_number(toman($order->grand_total)) }} تومان</dd>
            </div>
            <div class="flex items-center justify-between sm:flex-col sm:items-start sm:gap-1">
                <dt class="text-ink-500">وضعیت</dt>
                <dd><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></dd>
            </div>
            @if($order->transaction_ref)
                <div class="flex items-center justify-between sm:col-span-2 sm:flex-col sm:items-start sm:gap-1">
                    <dt class="text-ink-500">کد پیگیری پرداخت</dt>
                    <dd class="ltr font-bold text-ink-900">{{ $order->transaction_ref }}</dd>
                </div>
            @endif
        </dl>

        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('account.orders.show', $order) }}" class="btn-primary flex-1">
                <x-icon name="receipt" class="h-5 w-5" />
                مشاهده جزئیات سفارش
            </a>
            @if(! $paid && $order->is_payable)
                <a href="{{ route('checkout.payment', $order) }}" class="btn-dark flex-1">
                    <x-icon name="credit-card" class="h-5 w-5" />
                    پرداخت سفارش
                </a>
            @endif
            <a href="{{ route('shop.index') }}" class="btn-ghost flex-1">ادامه خرید</a>
        </div>
    </div>
</div>
@endsection
