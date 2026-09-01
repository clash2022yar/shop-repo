@extends('layouts.bare')

@section('title', 'تغییر رمز عبور | دیجی‌نو')

@section('content')
<div class="rounded-card bg-white p-7 shadow-card animate-fade-up">

    <div class="mb-6 text-center">
        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-brand-50 text-brand-500">
            <x-icon name="lock" class="h-7 w-7" />
        </span>
        <h1 class="mt-4 text-lg font-extrabold text-ink-900">تعیین رمز عبور جدید</h1>
        <p class="mt-1.5 text-2xs text-ink-500">رمز عبور تازه‌ای برای حساب خود انتخاب کنید.</p>
    </div>

    <form action="{{ route('password.update') }}" method="POST" data-ajax-form data-redirect>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <label class="label" for="rp-email">ایمیل</label>
        <input id="rp-email" type="email" name="email" class="field ltr" required value="{{ $email }}">
        <p data-error-for="email" class="hidden"></p>

        <label class="label mt-4" for="rp-password">رمز عبور جدید</label>
        <input id="rp-password" type="password" name="password" class="field" required autocomplete="new-password">
        <p data-error-for="password" class="hidden"></p>

        <label class="label mt-4" for="rp-password-confirm">تکرار رمز عبور جدید</label>
        <input id="rp-password-confirm" type="password" name="password_confirmation" class="field" required autocomplete="new-password">
        <p data-error-for="password_confirmation" class="hidden"></p>

        <button type="submit" class="btn-primary btn-lg mt-6 w-full">
            <span data-submit-text>تغییر رمز عبور</span>
            <x-icon name="spinner" class="hidden h-5 w-5 animate-spin-slow" data-submit-spinner />
        </button>
    </form>
</div>
@endsection
