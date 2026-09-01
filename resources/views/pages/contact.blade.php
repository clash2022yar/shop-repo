@extends('layouts.app')

@section('title', 'تماس با دیجی‌نو')
@section('meta_description', 'راه‌های ارتباطی با فروشگاه اینترنتی دیجی‌نو؛ تلفن، ایمیل، نشانی و فرم تماس مستقیم.')

@section('content')
<div class="container">
    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_20rem]">

        {{-- form --}}
        <section class="section" data-reveal>
            <h1 class="section-title mb-1">تماس با ما</h1>
            <p class="mb-5 text-2xs leading-7 text-ink-500">
                پیام خود را بنویسید؛ کارشناسان ما در ساعات کاری پاسخ می‌دهند.
                اگر دربارهٔ سفارش خاصی سؤال دارید، از بخش
                <a href="{{ route('account.tickets.create') }}" class="text-brand-500 hover:underline">تیکت پشتیبانی</a>
                استفاده کنید تا سریع‌تر بررسی شود.
            </p>

            <form action="{{ route('ajax.contact') }}" method="POST" data-ajax-form data-reset data-no-redirect>
                @csrf
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="label" for="c-name">نام و نام خانوادگی <span class="text-brand-500">*</span></label>
                        <input id="c-name" type="text" name="name" class="field" required
                               value="{{ auth()->user()->name ?? '' }}">
                        <p data-error-for="name" class="hidden"></p>
                    </div>
                    <div>
                        <label class="label" for="c-email">ایمیل <span class="text-brand-500">*</span></label>
                        <input id="c-email" type="email" name="email" class="field ltr" required
                               value="{{ auth()->user()->email ?? '' }}">
                        <p data-error-for="email" class="hidden"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label" for="c-subject">موضوع <span class="text-brand-500">*</span></label>
                        <input id="c-subject" type="text" name="subject" class="field" required>
                        <p data-error-for="subject" class="hidden"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="label" for="c-message">متن پیام <span class="text-brand-500">*</span></label>
                        <textarea id="c-message" name="message" rows="6" class="field" required></textarea>
                        <p data-error-for="message" class="hidden"></p>
                    </div>
                </div>

                <button type="submit" class="btn-primary mt-5">
                    <span data-submit-text>ارسال پیام</span>
                    <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
                </button>
            </form>
        </section>

        {{-- contact details --}}
        <aside class="space-y-4">
            <section class="section" data-reveal>
                <h2 class="section-title mb-4">راه‌های ارتباطی</h2>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <span class="icon-tile shrink-0"><x-icon name="phone" class="h-5 w-5" /></span>
                        <span class="min-w-0">
                            <span class="block text-2xs font-bold text-ink-800">تلفن پشتیبانی</span>
                            <a href="tel:{{ digino('support_phone', '02112345678') }}" class="ltr mt-0.5 block text-2xs text-ink-500 hover:text-brand-500">
                                {{ fa_number(digino('support_phone', '021-12345678')) }}
                            </a>
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="icon-tile shrink-0"><x-icon name="mail" class="h-5 w-5" /></span>
                        <span class="min-w-0">
                            <span class="block text-2xs font-bold text-ink-800">ایمیل</span>
                            <a href="mailto:{{ digino('support_email', 'info@digino.com') }}" class="ltr mt-0.5 block truncate text-2xs text-ink-500 hover:text-brand-500">
                                {{ digino('support_email', 'info@digino.com') }}
                            </a>
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="icon-tile shrink-0"><x-icon name="map-pin" class="h-5 w-5" /></span>
                        <span class="min-w-0">
                            <span class="block text-2xs font-bold text-ink-800">نشانی</span>
                            <span class="mt-0.5 block text-2xs leading-6 text-ink-500">{{ digino('address', 'تهران، خیابان انقلاب، پلاک ۱۲۳') }}</span>
                        </span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="icon-tile shrink-0"><x-icon name="clock" class="h-5 w-5" /></span>
                        <span class="min-w-0">
                            <span class="block text-2xs font-bold text-ink-800">ساعات کاری</span>
                            <span class="mt-0.5 block text-2xs text-ink-500">{{ digino('working_hours', 'شنبه تا پنجشنبه، ۹ تا ۱۸') }}</span>
                        </span>
                    </li>
                </ul>
            </section>

            <section id="careers" class="section scroll-mt-32" data-reveal>
                <h2 class="section-title mb-2">همکاری با ما</h2>
                <p class="text-2xs leading-7 text-ink-500">
                    اگر به کار در یک تیم کوچک و جدی علاقه دارید، رزومهٔ خود را به نشانی
                    <span class="ltr font-bold text-ink-700">{{ digino('support_email', 'info@digino.com') }}</span>
                    بفرستید. همهٔ رزومه‌ها را می‌خوانیم.
                </p>
            </section>
        </aside>
    </div>
</div>
@endsection
