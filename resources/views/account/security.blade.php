@extends('layouts.account')

@section('title', 'امنیت حساب')
@section('heading', 'امنیت و رمز عبور')
@section('subheading', 'رمز عبور خود را تغییر دهید و نشست‌های فعال حساب را ببینید.')

@section('content')
<div class="space-y-4">

    <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
        <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="key" class="h-5 w-5 text-brand-500" />
            تغییر رمز عبور
        </h2>

        <form action="{{ route('ajax.account.password.update') }}" method="POST" data-ajax-form data-method="PUT" data-reset data-no-redirect
              class="max-w-md">
            @csrf

            <label class="label" for="s-current">رمز عبور فعلی <span class="text-brand-500">*</span></label>
            <input id="s-current" type="password" name="current_password" class="field" required autocomplete="current-password">
            <p data-error-for="current_password" class="hidden"></p>

            <label class="label mt-4" for="s-password">رمز عبور جدید <span class="text-brand-500">*</span></label>
            <input id="s-password" type="password" name="password" class="field" required autocomplete="new-password">
            <p class="help mt-1">حداقل ۸ نویسه، ترکیبی از حرف و عدد.</p>
            <p data-error-for="password" class="hidden"></p>

            <label class="label mt-4" for="s-confirm">تکرار رمز عبور جدید <span class="text-brand-500">*</span></label>
            <input id="s-confirm" type="password" name="password_confirmation" class="field" required autocomplete="new-password">
            <p data-error-for="password_confirmation" class="hidden"></p>

            <button type="submit" class="btn-primary mt-5">
                <span data-submit-text>تغییر رمز عبور</span>
                <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
            </button>
        </form>
    </section>

    <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
        <h2 class="mb-1 flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="shield" class="h-5 w-5 text-brand-500" />
            نشست‌های فعال
        </h2>
        <p class="mb-4 text-2xs text-ink-500">دستگاه‌هایی که اخیراً با حساب شما وارد شده‌اند.</p>

        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>دستگاه / مرورگر</th>
                        <th>نشانی IP</th>
                        <th>آخرین فعالیت</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td class="max-w-xs truncate text-2xs text-ink-700">{{ $session->user_agent ?: 'نامشخص' }}</td>
                            <td class="ltr text-2xs text-ink-600">{{ $session->ip_address }}</td>
                            <td class="text-2xs text-ink-600">{{ jalali_human(\Carbon\Carbon::createFromTimestamp($session->last_activity)) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-2xs text-ink-500">نشست فعالی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
        <h2 class="mb-3 flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="info" class="h-5 w-5 text-info-500" />
            توصیه‌های امنیتی
        </h2>
        <ul class="space-y-2.5 text-2xs leading-6 text-ink-600">
            <li class="flex items-start gap-2">
                <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0 text-success-500" />
                رمز عبور خود را با هیچ‌کس، حتی کارشناسان پشتیبانی، در میان نگذارید.
            </li>
            <li class="flex items-start gap-2">
                <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0 text-success-500" />
                از رمز عبور یکسان در چند سایت استفاده نکنید.
            </li>
            <li class="flex items-start gap-2">
                <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0 text-success-500" />
                دیجی‌نو هرگز از طریق پیامک یا تماس، رمز عبور یا اطلاعات کارت بانکی شما را درخواست نمی‌کند.
            </li>
        </ul>
    </section>
</div>
@endsection
