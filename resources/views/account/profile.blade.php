@extends('layouts.account')

@section('title', 'اطلاعات حساب کاربری')
@section('heading', 'اطلاعات حساب کاربری')
@section('subheading', 'اطلاعات شخصی خود را ویرایش و به‌روز نگه دارید.')

@section('content')
<div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_18rem]">

    <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
        <form action="{{ route('ajax.account.profile.update') }}" method="POST" data-ajax-form data-method="PUT" data-no-redirect>
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="label" for="p-name">نام و نام خانوادگی <span class="text-brand-500">*</span></label>
                    <input id="p-name" type="text" name="name" class="field" required value="{{ $user->name }}">
                    <p data-error-for="name" class="hidden"></p>
                </div>

                <div>
                    <label class="label" for="p-mobile">شماره موبایل <span class="text-brand-500">*</span></label>
                    <input id="p-mobile" type="tel" name="mobile" class="field ltr" required value="{{ $user->mobile }}">
                    <p data-error-for="mobile" class="hidden"></p>
                </div>

                <div>
                    <label class="label" for="p-email">ایمیل <span class="text-brand-500">*</span></label>
                    <input id="p-email" type="email" name="email" class="field ltr" required value="{{ $user->email }}">
                    <p data-error-for="email" class="hidden"></p>
                </div>

                <div>
                    <label class="label" for="p-national">کد ملی</label>
                    <input id="p-national" type="text" name="national_code" class="field ltr"
                           value="{{ $user->national_code }}" placeholder="۱۰ رقم">
                    <p class="help mt-1">برای صدور فاکتور رسمی لازم است.</p>
                    <p data-error-for="national_code" class="hidden"></p>
                </div>

                <div>
                    <label class="label" for="p-birthday">تاریخ تولد</label>
                    <input id="p-birthday" type="date" name="birthday" class="field ltr"
                           value="{{ optional($user->birthday)->format('Y-m-d') }}">
                    <p data-error-for="birthday" class="hidden"></p>
                </div>

                <div>
                    <label class="label" for="p-gender">جنسیت</label>
                    <select id="p-gender" name="gender" class="field">
                        <option value="">تعیین نشده</option>
                        <option value="male" @selected($user->gender === 'male')>مرد</option>
                        <option value="female" @selected($user->gender === 'female')>زن</option>
                    </select>
                    <p data-error-for="gender" class="hidden"></p>
                </div>
            </div>

            <div class="mt-5 border-t border-ink-100 pt-5">
                <x-switch name="newsletter" :checked="(bool) $user->newsletter"
                          label="دریافت خبرنامه دیجی‌نو"
                          help="جدیدترین تخفیف‌ها و پیشنهادهای ویژه از طریق ایمیل برای شما ارسال می‌شود." />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="btn-primary">
                    <span data-submit-text>ذخیره تغییرات</span>
                    <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
                </button>
            </div>
        </form>
    </section>

    <aside class="space-y-4">
        <section class="rounded-card bg-white p-5 text-center shadow-card" data-reveal>
            <span class="mx-auto grid h-20 w-20 place-items-center rounded-full bg-brand-50 text-xl font-extrabold text-brand-600 ring-4 ring-brand-100">
                {{ $user->initials }}
            </span>
            <p class="mt-3 text-sm font-bold text-ink-900">{{ $user->name }}</p>
            <p class="ltr mt-1 text-2xs text-ink-500">{{ fa_number($user->mobile) }}</p>
            <p class="mt-3 text-[11px] text-ink-400">
                عضویت از {{ jalali($user->created_at) }}
            </p>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card" data-reveal>
            <h2 class="mb-3 text-sm font-extrabold text-ink-900">دسترسی سریع</h2>
            <div class="space-y-1">
                <a href="{{ route('account.security') }}" class="account-link">
                    <x-icon name="lock" class="h-[18px] w-[18px]" />
                    <span class="flex-1">تغییر رمز عبور</span>
                    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
                </a>
                <a href="{{ route('account.addresses') }}" class="account-link">
                    <x-icon name="map-pin" class="h-[18px] w-[18px]" />
                    <span class="flex-1">مدیریت آدرس‌ها</span>
                    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
                </a>
                <a href="{{ route('account.notifications') }}" class="account-link">
                    <x-icon name="bell" class="h-[18px] w-[18px]" />
                    <span class="flex-1">اعلان‌ها</span>
                    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
                </a>
            </div>
        </section>
    </aside>
</div>
@endsection
