@extends('layouts.account')

@section('title', 'ثبت تیکت جدید')
@section('heading', 'ثبت تیکت جدید')
@section('subheading', 'پرسش یا مشکل خود را شرح دهید؛ در کوتاه‌ترین زمان پاسخ می‌دهیم.')

@section('content')
<div class="rounded-card bg-white p-5 shadow-card" data-reveal>
    <form action="{{ route('ajax.account.tickets.store') }}" method="POST" data-ajax-form data-redirect>
        @csrf

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="t-subject">موضوع تیکت <span class="text-brand-500">*</span></label>
                <input id="t-subject" type="text" name="subject" class="field" required
                       placeholder="مثلاً: پیگیری وضعیت سفارش">
                <p data-error-for="subject" class="hidden"></p>
            </div>

            <div>
                <label class="label" for="t-department">دپارتمان <span class="text-brand-500">*</span></label>
                <select id="t-department" name="department" class="field" required>
                    <option value="support">پشتیبانی عمومی</option>
                    <option value="orders">سفارش‌ها و ارسال</option>
                    <option value="technical">مشکلات فنی سایت</option>
                    <option value="finance">مالی و پرداخت</option>
                </select>
                <p data-error-for="department" class="hidden"></p>
            </div>

            <div>
                <label class="label" for="t-priority">اولویت <span class="text-brand-500">*</span></label>
                <select id="t-priority" name="priority" class="field" required>
                    <option value="low">کم</option>
                    <option value="normal" selected>عادی</option>
                    <option value="high">زیاد</option>
                </select>
                <p data-error-for="priority" class="hidden"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="t-order">سفارش مرتبط (اختیاری)</label>
                <select id="t-order" name="order_id" class="field">
                    <option value="">مرتبط با سفارش خاصی نیست</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}">
                            {{ fa_number($order->code) }} — {{ jalali($order->created_at) }} — {{ fa_number(toman($order->grand_total)) }} تومان
                        </option>
                    @endforeach
                </select>
                <p data-error-for="order_id" class="hidden"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="t-body">متن پیام <span class="text-brand-500">*</span></label>
                <textarea id="t-body" name="body" rows="6" class="field" required
                          placeholder="لطفاً جزئیات را کامل بنویسید تا سریع‌تر بتوانیم کمکتان کنیم..."></textarea>
                <p data-error-for="body" class="hidden"></p>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
            <a href="{{ route('account.tickets.index') }}" class="btn-ghost">انصراف</a>
            <button type="submit" class="btn-primary">
                <span data-submit-text>ارسال تیکت</span>
                <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
            </button>
        </div>
    </form>
</div>
@endsection
