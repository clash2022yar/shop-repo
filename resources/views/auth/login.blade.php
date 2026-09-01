@extends('layouts.bare')

@section('title', 'ورود به دیجی‌نو')

@section('content')
<div class="rounded-card bg-white p-7 shadow-card animate-fade-up">

    <div class="mb-6 text-center">
        <img src="{{ asset('images/logo/mark.svg') }}" alt="" width="56" height="56" class="mx-auto h-14 w-14">
        <h1 class="mt-4 text-lg font-extrabold text-ink-900">ورود به حساب کاربری</h1>
        <p class="mt-1.5 text-2xs text-ink-500">برای ادامه، شماره موبایل یا ایمیل خود را وارد کنید.</p>
    </div>

    <form action="{{ route('login.store') }}" method="POST" data-ajax-form data-redirect>
        @csrf
        @if(request()->filled('intended'))
            <input type="hidden" name="intended" value="{{ request('intended') }}">
        @endif

        <label class="label" for="identifier">شماره موبایل یا ایمیل</label>
        <div class="relative">
            <input id="identifier" type="text" name="identifier" class="field ps-10" required autofocus
                   autocomplete="username" placeholder="09xxxxxxxxx یا name@example.com"
                   value="{{ old('identifier') }}">
            <x-icon name="user" class="pointer-events-none absolute top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
        </div>
        <p data-error-for="identifier" class="hidden"></p>

        <div class="mt-4 flex items-center justify-between">
            <label class="label mb-0" for="password">رمز عبور</label>
            <a href="{{ route('password.request') }}" class="text-[11px] text-brand-500 transition-colors hover:text-brand-600">
                رمز عبور را فراموش کرده‌اید؟
            </a>
        </div>
        <div class="relative mt-1.5">
            <input id="password" type="password" name="password" class="field ps-10 pe-10" required
                   autocomplete="current-password" placeholder="رمز عبور خود را وارد کنید">
            <x-icon name="lock" class="pointer-events-none absolute top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            <button type="button" class="absolute top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center text-ink-400 transition-colors hover:text-ink-700"
                    style="inset-inline-end:.375rem" aria-label="نمایش رمز عبور"
                    onclick="var i=document.getElementById('password');i.type=i.type==='password'?'text':'password';this.querySelectorAll('svg').forEach(function(s){s.classList.toggle('hidden')})">
                <x-icon name="eye" class="h-5 w-5" />
                <x-icon name="eye-off" class="hidden h-5 w-5" />
            </button>
        </div>
        <p data-error-for="password" class="hidden"></p>

        <label class="mt-4 flex cursor-pointer items-center gap-2.5">
            <input type="checkbox" name="remember" value="1" class="checkbox">
            <span class="text-2xs text-ink-600">مرا به خاطر بسپار</span>
        </label>

        <button type="submit" class="btn-primary btn-lg mt-6 w-full">
            <span data-submit-text>ورود به حساب</span>
            <x-icon name="spinner" class="hidden h-5 w-5 animate-spin-slow" data-submit-spinner />
        </button>
    </form>

    <div class="my-6 flex items-center gap-3">
        <span class="h-px flex-1 bg-ink-200"></span>
        <span class="text-[11px] text-ink-400">حساب کاربری ندارید؟</span>
        <span class="h-px flex-1 bg-ink-200"></span>
    </div>

    <a href="{{ route('register') }}" class="btn-outline w-full">
        <x-icon name="user-plus" class="h-5 w-5" />
        ساخت حساب کاربری جدید
    </a>

    <p class="mt-6 text-center text-[11px] leading-6 text-ink-400">
        ورود شما به معنای پذیرش
        <a href="{{ route('terms') }}" class="text-ink-600 underline-offset-2 hover:underline">شرایط استفاده</a>
        و
        <a href="{{ route('privacy') }}" class="text-ink-600 underline-offset-2 hover:underline">حریم خصوصی</a>
        دیجی‌نو است.
    </p>
</div>
@endsection
