@extends('layouts.app')

@section('title', 'تکمیل سفارش | دیجی‌نو')
@section('robots', 'noindex, nofollow')

@section('content')
<div class="container">

    {{-- ═════════ steps ═════════ --}}
    <ol class="mb-5 flex items-center justify-center gap-2 rounded-card bg-white px-4 py-4 shadow-card sm:gap-6">
        @php
            $steps = [
                ['label' => 'سبد خرید', 'icon' => 'cart', 'state' => 'done'],
                ['label' => 'اطلاعات ارسال', 'icon' => 'map-pin', 'state' => 'current'],
                ['label' => 'پرداخت', 'icon' => 'credit-card', 'state' => 'todo'],
            ];
        @endphp
        @foreach($steps as $step)
            <li class="flex items-center gap-2 sm:gap-3">
                <span @class([
                    'grid h-9 w-9 shrink-0 place-items-center rounded-full transition-colors',
                    'bg-success-50 text-success-600' => $step['state'] === 'done',
                    'bg-brand-500 text-white shadow-ring' => $step['state'] === 'current',
                    'bg-ink-100 text-ink-400' => $step['state'] === 'todo',
                ])>
                    @if($step['state'] === 'done')
                        <x-icon name="check" class="h-5 w-5" />
                    @else
                        <x-icon :name="$step['icon']" class="h-[18px] w-[18px]" />
                    @endif
                </span>
                <span @class([
                    'text-2xs',
                    'font-bold text-ink-900' => $step['state'] === 'current',
                    'text-ink-500' => $step['state'] !== 'current',
                ])>{{ $step['label'] }}</span>
            </li>
            @unless($loop->last)
                <li aria-hidden="true" class="h-px w-6 bg-ink-200 sm:w-14"></li>
            @endunless
        @endforeach
    </ol>

    <form action="{{ route('checkout.store') }}" method="POST" data-ajax-form id="checkout-form">
        @csrf

        <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">
            <div class="min-w-0 space-y-4">

                {{-- ═════════ address ═════════ --}}
                <section class="rounded-card bg-white p-5 shadow-card">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                            <x-icon name="map-pin" class="h-5 w-5 text-brand-500" />
                            آدرس تحویل سفارش
                        </h2>
                        <button type="button" data-modal-open="new-address" class="btn-outline btn-sm">
                            <x-icon name="plus" class="h-4 w-4" />
                            افزودن آدرس جدید
                        </button>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2" data-address-list>
                        @forelse($addresses as $address)
                            @include('partials.address-option', ['address' => $address])
                        @empty
                            <div class="sm:col-span-2">
                                <x-empty-state icon="map-pin" title="هنوز آدرسی ثبت نکرده‌اید"
                                               message="برای ادامهٔ فرایند خرید، آدرس تحویل سفارش را وارد کنید.">
                                    <button type="button" data-modal-open="new-address" class="btn-primary mt-4">
                                        <x-icon name="plus" class="h-4 w-4" />
                                        افزودن آدرس
                                    </button>
                                </x-empty-state>
                            </div>
                        @endforelse
                    </div>
                    <p data-error-for="address_id" class="hidden"></p>
                </section>

                {{-- ═════════ shipping ═════════ --}}
                <section class="rounded-card bg-white p-5 shadow-card">
                    <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                        <x-icon name="truck" class="h-5 w-5 text-brand-500" />
                        روش ارسال
                    </h2>

                    <div class="space-y-2.5">
                        @foreach($methods as $method)
                            <label class="flex cursor-pointer items-center gap-3 rounded-card border border-ink-200 p-4 transition-all duration-200 hover:border-brand-300 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40">
                                <input type="radio" name="shipping_method_id" value="{{ $method->id }}" class="radio shrink-0"
                                       {{ $loop->first ? 'checked' : '' }} data-shipping-option>
                                <x-icon :name="$method->icon ?: 'truck'" class="h-6 w-6 shrink-0 text-ink-500" />
                                <span class="min-w-0 flex-1">
                                    <span class="block text-2xs font-bold text-ink-900">{{ $method->name }}</span>
                                    <span class="mt-0.5 block text-[11px] text-ink-500">
                                        {{ $method->description }}
                                        @if($method->estimated_days)
                                            — تحویل حدود {{ fa_number($method->estimated_days) }} روز کاری
                                        @endif
                                    </span>
                                </span>
                                <span class="shrink-0 text-2xs font-bold {{ $method->cost > 0 ? 'text-ink-800' : 'text-success-600' }}">
                                    {{ $method->cost > 0 ? fa_number(toman($method->cost)) . ' تومان' : 'رایگان' }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </section>

                {{-- ═════════ payment ═════════ --}}
                <section class="rounded-card bg-white p-5 shadow-card">
                    <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                        <x-icon name="wallet" class="h-5 w-5 text-brand-500" />
                        روش پرداخت
                    </h2>

                    @php
                        $payments = [
                            ['value' => 'online', 'icon' => 'credit-card', 'label' => 'پرداخت اینترنتی', 'note' => 'پرداخت امن از طریق درگاه بانکی'],
                            ['value' => 'cod', 'icon' => 'box', 'label' => 'پرداخت در محل', 'note' => 'پرداخت هنگام تحویل کالا به مأمور ارسال'],
                        ];
                    @endphp

                    <div class="grid gap-2.5 sm:grid-cols-2">
                        @foreach($payments as $payment)
                            <label class="flex cursor-pointer items-center gap-3 rounded-card border border-ink-200 p-4 transition-all duration-200 hover:border-brand-300 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40">
                                <input type="radio" name="payment_method" value="{{ $payment['value'] }}" class="radio shrink-0" {{ $loop->first ? 'checked' : '' }}>
                                <x-icon :name="$payment['icon']" class="h-6 w-6 shrink-0 text-ink-500" />
                                <span class="min-w-0">
                                    <span class="block text-2xs font-bold text-ink-900">{{ $payment['label'] }}</span>
                                    <span class="mt-0.5 block text-[11px] text-ink-500">{{ $payment['note'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p data-error-for="payment_method" class="hidden"></p>

                    <label class="label mt-5" for="order-note">توضیحات سفارش (اختیاری)</label>
                    <textarea id="order-note" name="note" rows="3" class="field"
                              placeholder="اگر نکته‌ای درباره تحویل سفارش دارید بنویسید..."></textarea>
                    <p data-error-for="note" class="hidden"></p>
                </section>

                {{-- ═════════ items ═════════ --}}
                <section class="overflow-hidden rounded-card bg-white shadow-card">
                    <h2 class="border-b border-ink-100 px-5 py-4 text-sm font-extrabold text-ink-900">
                        کالاهای سفارش ({{ fa_number($items->count()) }} کالا)
                    </h2>
                    <div class="divide-y divide-ink-50">
                        @foreach($items as $item)
                            <div class="flex items-center gap-3 p-4">
                                <img src="{{ asset($item->product->primary_image) }}" alt="{{ $item->product->name }}"
                                     class="h-16 w-16 shrink-0 rounded-lg object-contain" loading="lazy">
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-1 text-2xs text-ink-800">{{ $item->product->name }}</p>
                                    <p class="mt-1 text-[11px] text-ink-500">
                                        {{ fa_number($item->quantity) }} عدد
                                        @if($item->variant?->color_name) — {{ $item->variant->color_name }} @endif
                                    </p>
                                </div>
                                <span class="shrink-0 text-2xs font-bold text-ink-900">
                                    {{ fa_number(toman($item->line_total)) }}
                                    <span class="text-[11px] font-normal text-ink-500">تومان</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            {{-- ═════════ summary ═════════ --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-card bg-white p-5 shadow-card">
                    <h2 class="mb-4 text-sm font-extrabold text-ink-900">صورتحساب</h2>

                    <dl class="space-y-3 text-2xs">
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-600">قیمت کالاها (<span data-sum-items>{{ fa_number($summary['selected_count']) }}</span>)</dt>
                            <dd class="font-medium text-ink-800"><span data-sum-subtotal>{{ fa_number($summary['formatted']['subtotal']) }}</span> تومان</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-ink-600">تخفیف کالاها</dt>
                            <dd class="font-medium text-brand-500"><span data-sum-discount>{{ fa_number($summary['formatted']['product_discount']) }}</span> تومان</dd>
                        </div>
                        <div class="flex items-center justify-between {{ $summary['coupon_discount'] ? '' : 'hidden' }}" data-coupon-row>
                            <dt class="text-ink-600">کد تخفیف <span class="badge-green" data-coupon-code>{{ $summary['coupon_code'] }}</span></dt>
                            <dd class="font-medium text-success-600"><span data-sum-coupon>{{ fa_number($summary['formatted']['coupon_discount']) }}</span> تومان</dd>
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

                    <button type="submit" class="btn-primary btn-lg mt-4 w-full">
                        <span data-submit-text>ثبت نهایی سفارش</span>
                        <x-icon name="spinner" class="hidden h-5 w-5 animate-spin-slow" data-submit-spinner />
                    </button>

                    <ul class="mt-4 space-y-2 border-t border-ink-100 pt-4 text-[11px] leading-6 text-ink-500">
                        <li class="flex items-start gap-1.5">
                            <x-icon name="shield-check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-success-500" />
                            پرداخت شما از طریق درگاه امن بانکی انجام می‌شود.
                        </li>
                        <li class="flex items-start gap-1.5">
                            <x-icon name="rotate-left" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-success-500" />
                            امکان بازگشت کالا تا هفت روز پس از تحویل.
                        </li>
                    </ul>
                </div>
            </aside>
        </div>
    </form>
</div>

{{-- ═════════ new-address modal ═════════ --}}
<x-modal id="new-address" title="افزودن آدرس جدید" size="lg">
    <form action="{{ route('ajax.checkout.address.store') }}" method="POST" data-ajax-form data-close-modal data-reload>
        @csrf
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="a-label">عنوان آدرس</label>
                <input id="a-label" type="text" name="label" class="field" placeholder="مثلاً خانه، محل کار">
                <p data-error-for="label" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="a-receiver">نام و نام خانوادگی گیرنده <span class="text-brand-500">*</span></label>
                <input id="a-receiver" type="text" name="receiver_name" class="field" required value="{{ auth()->user()->name }}">
                <p data-error-for="receiver_name" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="a-mobile">شماره موبایل گیرنده <span class="text-brand-500">*</span></label>
                <input id="a-mobile" type="tel" name="receiver_mobile" class="field ltr" required
                       value="{{ auth()->user()->mobile }}" placeholder="09xxxxxxxxx">
                <p data-error-for="receiver_mobile" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="a-postal">کد پستی</label>
                <input id="a-postal" type="text" name="postal_code" class="field ltr" placeholder="۱۰ رقم">
                <p data-error-for="postal_code" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="a-province">استان <span class="text-brand-500">*</span></label>
                <select id="a-province" name="province" class="field" required
                        onchange="dgFillCities(this.value)">
                    <option value="">انتخاب کنید</option>
                    @foreach(\App\Support\Iran::provinces() as $province)
                        <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                </select>
                <p data-error-for="province" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="a-city">شهر <span class="text-brand-500">*</span></label>
                <select id="a-city" name="city" class="field" required>
                    <option value="">ابتدا استان را انتخاب کنید</option>
                </select>
                <p data-error-for="city" class="hidden"></p>
            </div>
            <div class="sm:col-span-2">
                <label class="label" for="a-line">نشانی دقیق <span class="text-brand-500">*</span></label>
                <textarea id="a-line" name="line" rows="2" class="field" required placeholder="خیابان، کوچه، ..."></textarea>
                <p data-error-for="line" class="hidden"></p>
            </div>
            <div>
                <label class="label" for="a-plate">پلاک</label>
                <input id="a-plate" type="text" name="plate" class="field">
            </div>
            <div>
                <label class="label" for="a-unit">واحد</label>
                <input id="a-unit" type="text" name="unit" class="field">
            </div>
        </div>

        <label class="mt-4 flex cursor-pointer items-center gap-2.5">
            <input type="hidden" name="is_default" value="0">
            <input type="checkbox" name="is_default" value="1" class="checkbox" {{ $addresses->isEmpty() ? 'checked' : '' }}>
            <span class="text-2xs text-ink-700">این آدرس، آدرس پیش‌فرض من باشد</span>
        </label>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-text>ذخیره آدرس</span>
                <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    var DG_CITIES = @json(\App\Support\Iran::map(), JSON_UNESCAPED_UNICODE);

    function dgFillCities(province) {
        var select = document.getElementById('a-city');
        if (!select) return;
        var cities = DG_CITIES[province] || [];
        select.innerHTML = cities.length
            ? cities.map(function (c) { return '<option value="' + c + '">' + c + '</option>'; }).join('')
            : '<option value="">ابتدا استان را انتخاب کنید</option>';
    }

    // Recalculate the invoice when the shipping method changes.
    document.addEventListener('dg:ready', function () {
        document.querySelectorAll('[data-shipping-option]').forEach(function (input) {
            input.addEventListener('change', function () {
                dg.http.post('{{ route('ajax.checkout.shipping') }}', { shipping_method_id: input.value })
                    .then(function (data) { dg.cart.sync(data.summary); })
                    .catch(function (err) { dg.toast(err.message, 'error'); });
            });
        });
    });
</script>
@endpush
