@extends('layouts.app')

@section('title', 'سبد خرید | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="container" data-cart-page>

    @if($items->isEmpty())
        <div class="rounded-card bg-white shadow-card">
            <x-empty-state icon="cart" title="سبد خرید شما خالی است"
                           message="می‌توانید برای مشاهده محصولات بیشتر به صفحهٔ فروشگاه بروید و کالای مورد نظرتان را انتخاب کنید."
                           :action-url="route('shop.index')" action-label="بازگشت به فروشگاه" />
        </div>

        @if($suggestions->isNotEmpty())
            <section class="mt-5 section" data-reveal>
                <x-section-header title="پیشنهاد ما برای شما" icon="trend-up" :more-url="route('shop.index')" />
                <x-rail>
                    @foreach($suggestions as $product)
                        <x-product-card :product="$product" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                    @endforeach
                </x-rail>
            </section>
        @endif
    @else

        @if($removed > 0)
            <div class="mb-4 flex items-start gap-3 rounded-card bg-warning-50 px-4 py-3 text-sm ring-1 ring-warning-100">
                <x-icon name="alert" class="mt-0.5 h-5 w-5 shrink-0 text-warning-600" />
                <p class="text-2xs leading-6 text-warning-700">
                    {{ fa_number($removed) }} کالا به دلیل اتمام موجودی یا غیرفعال شدن، از سبد خرید شما حذف شد.
                </p>
            </div>
        @endif

        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">

            {{-- ═════════ lines ═════════ --}}
            <div class="min-w-0">
                <div class="overflow-hidden rounded-card bg-white shadow-card">

                    {{-- select-all bar --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-ink-100 px-4 py-3">
                        <label class="flex cursor-pointer items-center gap-2.5">
                            <input type="checkbox" class="checkbox" data-cart-select-all
                                   {{ $items->every(fn($i) => $i->is_selected) ? 'checked' : '' }}>
                            <span class="text-2xs font-bold text-ink-800">
                                انتخاب همه (<span data-sum-lines>{{ fa_number($items->count()) }}</span> کالا)
                            </span>
                        </label>

                        <div class="flex items-center gap-2">
                            <button type="button" data-cart-remove-selected
                                    class="flex items-center gap-1.5 text-2xs text-ink-500 transition-colors hover:text-brand-500">
                                <x-icon name="trash" class="h-4 w-4" />
                                حذف موارد انتخاب‌شده
                            </button>
                        </div>
                    </div>

                    <div data-cart-lines>
                        @include('partials.cart-lines', ['items' => $items])
                    </div>
                </div>

                {{-- free-shipping progress --}}
                <div class="mt-3 flex items-center gap-2.5 rounded-card bg-white px-4 py-3 text-2xs text-ink-600 shadow-card">
                    <x-icon name="truck" class="h-5 w-5 shrink-0 text-ink-400" />
                    <span data-free-shipping>
                        @if($summary['free_shipping'])
                            <span class="font-bold text-success-600">سفارش شما شامل ارسال رایگان است.</span>
                        @elseif($summary['free_shipping_remaining'] > 0)
                            تنها <span class="font-bold text-brand-500">{{ fa_number(toman($summary['free_shipping_remaining'])) }} تومان</span>
                            تا ارسال رایگان باقی مانده است.
                        @endif
                    </span>
                </div>

                <a href="{{ route('shop.index') }}" class="btn-ghost mt-3">
                    <x-icon name="arrow-right" class="h-4 w-4" />
                    ادامه خرید
                </a>
            </div>

            {{-- ═════════ summary ═════════ --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-card bg-white p-5 shadow-card">
                    <h2 class="mb-4 text-sm font-extrabold text-ink-900">خلاصه سفارش</h2>

                    <dl class="space-y-3 text-2xs">
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-600">قیمت کالاها (<span data-sum-items>{{ fa_number($summary['selected_count']) }}</span>)</dt>
                            <dd class="font-medium text-ink-800"><span data-sum-subtotal>{{ fa_number($summary['formatted']['subtotal']) }}</span> تومان</dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-ink-600">تخفیف کالاها</dt>
                            <dd class="font-medium text-brand-500">
                                <span data-sum-discount>{{ fa_number($summary['formatted']['product_discount']) }}</span> تومان
                            </dd>
                        </div>

                        <div class="flex items-center justify-between {{ $summary['coupon_discount'] ? '' : 'hidden' }}" data-coupon-row>
                            <dt class="flex items-center gap-1.5 text-ink-600">
                                کد تخفیف
                                <span class="badge-green" data-coupon-code>{{ $summary['coupon_code'] }}</span>
                            </dt>
                            <dd class="font-medium text-success-600">
                                <span data-sum-coupon>{{ fa_number($summary['formatted']['coupon_discount']) }}</span> تومان
                            </dd>
                        </div>

                        <div class="flex items-center justify-between">
                            <dt class="text-ink-600">هزینه ارسال</dt>
                            <dd class="font-medium text-ink-800"><span data-sum-shipping>{{ fa_number($summary['formatted']['shipping_cost']) }}</span></dd>
                        </div>
                    </dl>

                    <div class="my-4 divider"></div>

                    <div class="flex items-baseline justify-between">
                        <span class="text-2xs font-bold text-ink-800">مبلغ قابل پرداخت</span>
                        <span class="text-base font-extrabold text-ink-900">
                            <span data-sum-total>{{ fa_number($summary['formatted']['grand_total']) }}</span>
                            <span class="text-2xs font-medium text-ink-600">تومان</span>
                        </span>
                    </div>

                    {{-- coupon --}}
                    <div class="mt-4">
                        <div class="{{ $summary['coupon_code'] ? 'hidden' : '' }}" data-coupon-form>
                            <form data-ajax-form data-coupon-submit action="{{ route('ajax.cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="code" class="field-sm ltr text-center" placeholder="کد تخفیف">
                                <button type="submit" class="btn-outline btn-sm shrink-0">اعمال</button>
                            </form>
                        </div>

                        <div class="{{ $summary['coupon_code'] ? '' : 'hidden' }} flex items-center justify-between rounded-field bg-success-50 px-3 py-2" data-coupon-applied>
                            <span class="flex items-center gap-1.5 text-2xs text-success-700">
                                <x-icon name="check-circle" class="h-4 w-4" />
                                کد <span class="ltr font-bold" data-coupon-code>{{ $summary['coupon_code'] }}</span> اعمال شد
                            </span>
                            <button type="button" data-coupon-remove class="text-success-700 transition-opacity hover:opacity-70" aria-label="حذف کد تخفیف">
                                <x-icon name="close" class="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    <a href="{{ route('checkout.index') }}" data-checkout-btn
                       class="btn-primary btn-lg mt-4 w-full {{ $summary['selected_count'] < 1 ? 'pointer-events-none opacity-50' : '' }}">
                        ثبت سفارش
                        <x-icon name="arrow-left" class="h-5 w-5" />
                    </a>

                    <p class="mt-3 flex items-start gap-1.5 text-[11px] leading-6 text-ink-500">
                        <x-icon name="info" class="mt-0.5 h-3.5 w-3.5 shrink-0" />
                        کالاهای موجود در سبد خرید شما رزرو نشده‌اند؛ برای ثبت نهایی، سفارش را تکمیل کنید.
                    </p>
                </div>
            </aside>
        </div>

        @if($suggestions->isNotEmpty())
            <section class="mt-5 section" data-reveal>
                <x-section-header title="شاید این کالاها را هم بپسندید" icon="gift" />
                <x-rail>
                    @foreach($suggestions as $product)
                        <x-product-card :product="$product" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                    @endforeach
                </x-rail>
            </section>
        @endif
    @endif
</div>
@endsection
