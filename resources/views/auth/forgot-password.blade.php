@extends('layouts.bare')

@section('title', 'بازیابی رمز عبور | دیجی‌نو')

@section('content')
<div class="rounded-card bg-white p-7 shadow-card animate-fade-up">

    <div class="mb-6 text-center">
        <span class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-brand-50 text-brand-500">
            <x-icon name="key" class="h-7 w-7" />
        </span>
        <h1 class="mt-4 text-lg font-extrabold text-ink-900">بازیابی رمز عبور</h1>
        <p class="mt-1.5 text-2xs leading-6 text-ink-500">
            ایمیل حساب خود را وارد کنید تا لینک بازیابی رمز عبور برایتان ارسال شود.
        </p>
    </div>

    <form action="{{ route('password.email') }}" method="POST" data-ajax-form data-reset data-no-redirect>
        @csrf
        <label class="label" for="fp-email">ایمیل</label>
        <input id="fp-email" type="email" name="email" class="field ltr" required autofocus
               placeholder="name@example.com">
        <p data-error-for="email" class="hidden"></p>

        <button type="submit" class="btn-primary btn-lg mt-5 w-full">
            <span data-submit-text>ارسال لینک بازیابی</span>
            <x-icon name="spinner" class="hidden h-5 w-5 animate-spin-slow" data-submit-spinner />
        </button>
    </form>

    <a href="{{ route('login') }}" class="btn-ghost mt-3 w-full">
        <x-icon name="arrow-right" class="h-4 w-4" />
        بازگشت به صفحه ورود
    </a>
</div>
@endsection
