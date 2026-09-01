@extends('layouts.bare')

@section('title', 'ثبت‌نام در دیجی‌نو')

@section('content')
<div class="rounded-card bg-white p-7 shadow-card animate-fade-up">

    <div class="mb-6 text-center">
        <img src="{{ asset('images/logo/mark.svg') }}" alt="" width="56" height="56" class="mx-auto h-14 w-14">
        <h1 class="mt-4 text-lg font-extrabold text-ink-900">ساخت حساب کاربری</h1>
        <p class="mt-1.5 text-2xs text-ink-500">در کمتر از یک دقیقه عضو دیجی‌نو شوید.</p>
    </div>

    <form action="{{ route('register.store') }}" method="POST" data-ajax-form data-redirect>
        @csrf

        <label class="label" for="name">نام و نام خانوادگی</label>
        <input id="name" type="text" name="name" class="field" required autofocus autocomplete="name"
               placeholder="مثلاً: علی رضایی" value="{{ old('name') }}">
        <p data-error-for="name" class="hidden"></p>

        <label class="label mt-4" for="mobile">شماره موبایل</label>
        <input id="mobile" type="tel" name="mobile" class="field ltr" required autocomplete="tel"
               placeholder="09xxxxxxxxx" value="{{ old('mobile') }}">
        <p data-error-for="mobile" class="hidden"></p>

        <label class="label mt-4" for="email">ایمیل</label>
        <input id="email" type="email" name="email" class="field ltr" required autocomplete="email"
               placeholder="name@example.com" value="{{ old('email') }}">
        <p data-error-for="email" class="hidden"></p>

        <label class="label mt-4" for="reg-password">رمز عبور</label>
        <input id="reg-password" type="password" name="password" class="field" required autocomplete="new-password"
               placeholder="حداقل ۸ نویسه">
        <p class="help mt-1">برای امنیت بیشتر، از ترکیب حروف، عدد و نویسه‌های ویژه استفاده کنید.</p>
        <p data-error-for="password" class="hidden"></p>

        <label class="label mt-4" for="password_confirmation">تکرار رمز عبور</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="field" required
               autocomplete="new-password" placeholder="رمز عبور را دوباره وارد کنید">
        <p data-error-for="password_confirmation" class="hidden"></p>

        <label class="mt-5 flex cursor-pointer items-start gap-2.5">
            <input type="checkbox" name="terms" value="1" class="checkbox mt-0.5">
            <span class="text-2xs leading-6 text-ink-600">
                <a href="{{ route('terms') }}" target="_blank" class="text-brand-500 hover:underline">شرایط استفاده</a>
                و
                <a href="{{ route('privacy') }}" target="_blank" class="text-brand-500 hover:underline">حریم خصوصی</a>
                دیجی‌نو را می‌پذیرم.
            </span>
        </label>
        <p data-error-for="terms" class="hidden"></p>

        <label class="mt-2.5 flex cursor-pointer items-start gap-2.5">
            <input type="checkbox" name="newsletter" value="1" class="checkbox mt-0.5" checked>
            <span class="text-2xs leading-6 text-ink-600">اخبار و تخفیف‌های دیجی‌نو برایم ارسال شود.</span>
        </label>

        <button type="submit" class="btn-primary btn-lg mt-6 w-full">
            <span data-submit-text>ثبت‌نام و ورود</span>
            <x-icon name="spinner" class="hidden h-5 w-5 animate-spin-slow" data-submit-spinner />
        </button>
    </form>

    <p class="mt-6 text-center text-2xs text-ink-500">
        قبلاً ثبت‌نام کرده‌اید؟
        <a href="{{ route('login') }}" class="font-bold text-brand-500 hover:underline">وارد شوید</a>
    </p>
</div>
@endsection
