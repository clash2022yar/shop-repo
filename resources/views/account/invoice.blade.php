{{-- Printable invoice — deliberately standalone (no header / footer chrome). --}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاکتور سفارش {{ $order->code }} | دیجی‌نو</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('digino.version', '1.0') }}">
</head>
<body class="bg-ink-50 p-4 antialiased lg:p-8">

<div class="mx-auto max-w-3xl rounded-card bg-white p-6 shadow-card lg:p-10">

    {{-- header --}}
    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-ink-200 pb-5">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo/mark.svg') }}" alt="دیجی‌نو" class="h-12 w-12">
            <div>
                <p class="text-lg font-extrabold text-brand-500">دیجی‌نو</p>
                <p class="mt-0.5 text-2xs text-ink-500">{{ digino('site_tagline', 'خرید هوشمند کالای دیجیتال') }}</p>
            </div>
        </div>

        <div class="text-end">
            <p class="text-sm font-extrabold text-ink-900">فاکتور فروش</p>
            <p class="ltr mt-1 text-2xs text-ink-600">{{ fa_number($order->code) }}</p>
            <p class="mt-0.5 text-2xs text-ink-500">{{ jalali($order->created_at, 'Y/m/d — H:i') }}</p>
        </div>
    </div>

    {{-- parties --}}
    <div class="grid gap-5 border-b border-ink-200 py-5 sm:grid-cols-2">
        <div>
            <p class="mb-2 text-2xs font-bold text-ink-800">فروشنده</p>
            <ul class="space-y-1 text-2xs leading-6 text-ink-600">
                <li>فروشگاه اینترنتی دیجی‌نو</li>
                <li>{{ digino('address', 'تهران، خیابان انقلاب، پلاک ۱۲۳') }}</li>
                <li class="ltr">{{ fa_number(digino('support_phone', '021-12345678')) }}</li>
                <li class="ltr">{{ digino('support_email', 'info@digino.com') }}</li>
            </ul>
        </div>
        <div>
            <p class="mb-2 text-2xs font-bold text-ink-800">خریدار</p>
            <ul class="space-y-1 text-2xs leading-6 text-ink-600">
                <li>{{ $order->receiver_name }}</li>
                <li class="ltr">{{ fa_number($order->receiver_mobile) }}</li>
                <li>{{ $order->full_address }}</li>
                @if($order->postal_code)<li>کد پستی: {{ fa_number($order->postal_code) }}</li>@endif
            </ul>
        </div>
    </div>

    {{-- items --}}
    <table class="my-5 w-full text-2xs">
        <thead>
            <tr class="border-b border-ink-200 text-ink-500">
                <th class="py-2 text-start font-medium">#</th>
                <th class="py-2 text-start font-medium">شرح کالا</th>
                <th class="py-2 text-center font-medium">تعداد</th>
                <th class="py-2 text-end font-medium">قیمت واحد</th>
                <th class="py-2 text-end font-medium">جمع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr class="border-b border-ink-50">
                    <td class="py-3 text-ink-500">{{ fa_number($loop->iteration) }}</td>
                    <td class="py-3 text-ink-800">
                        {{ $item->name }}
                        @if($item->variant_title)
                            <span class="block text-[11px] text-ink-400">{{ $item->variant_title }}</span>
                        @endif
                    </td>
                    <td class="py-3 text-center text-ink-700">{{ fa_number($item->quantity) }}</td>
                    <td class="py-3 text-end text-ink-700">{{ fa_number(toman($item->unit_price)) }}</td>
                    <td class="py-3 text-end font-bold text-ink-900">{{ fa_number(toman($item->line_total)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- totals --}}
    <div class="flex justify-end">
        <dl class="w-full max-w-xs space-y-2.5 text-2xs">
            <div class="flex justify-between">
                <dt class="text-ink-600">جمع کالاها</dt>
                <dd class="text-ink-800">{{ fa_number(toman($order->items_total)) }} تومان</dd>
            </div>
            @if($order->discount_total > 0)
                <div class="flex justify-between">
                    <dt class="text-ink-600">تخفیف</dt>
                    <dd class="text-brand-500">{{ fa_number(toman($order->discount_total)) }} تومان</dd>
                </div>
            @endif
            @if($order->coupon_discount > 0)
                <div class="flex justify-between">
                    <dt class="text-ink-600">کد تخفیف</dt>
                    <dd class="text-success-600">{{ fa_number(toman($order->coupon_discount)) }} تومان</dd>
                </div>
            @endif
            <div class="flex justify-between">
                <dt class="text-ink-600">هزینه ارسال</dt>
                <dd class="text-ink-800">{{ $order->shipping_cost > 0 ? fa_number(toman($order->shipping_cost)) . ' تومان' : 'رایگان' }}</dd>
            </div>
            <div class="border-t border-ink-200 pt-2.5">
                <div class="flex items-baseline justify-between">
                    <dt class="font-bold text-ink-900">مبلغ قابل پرداخت</dt>
                    <dd class="text-base font-extrabold text-ink-900">{{ fa_number(toman($order->grand_total)) }} تومان</dd>
                </div>
            </div>
        </dl>
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-ink-200 pt-5">
        <p class="text-[11px] leading-6 text-ink-500">
            وضعیت پرداخت: <span class="font-bold text-ink-700">{{ $order->payment_status->label() }}</span>
            @if($order->transaction_ref)
                — کد پیگیری: <span class="ltr">{{ $order->transaction_ref }}</span>
            @endif
        </p>
        <p class="text-[11px] text-ink-400">این فاکتور به صورت الکترونیکی صادر شده و نیازی به مهر و امضا ندارد.</p>
    </div>

    <div class="mt-6 flex justify-center gap-2 no-print">
        <button type="button" onclick="window.print()" class="btn-primary">
            <x-icon name="printer" class="h-5 w-5" />
            چاپ فاکتور
        </button>
        <a href="{{ route('account.orders.show', $order) }}" class="btn-ghost">بازگشت به سفارش</a>
    </div>
</div>

<p class="mt-5 text-center text-[11px] text-ink-400 no-print">
    دیجی‌نو — طراحی و توسعه توسط <span class="font-bold text-ink-600">یارمحمدی</span>
</p>
</body>
</html>
