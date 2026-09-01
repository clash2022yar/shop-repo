{{-- Shared body for every HTTP error page. --}}
<section class="mx-auto flex max-w-3xl flex-col items-center px-4 py-16 text-center sm:py-24" data-reveal>
        <div class="relative mb-8">
            <span class="absolute inset-0 -z-10 animate-pulse-ring rounded-full bg-brand-100/70"></span>
            <div class="flex h-32 w-32 items-center justify-center rounded-full bg-white shadow-card">
                <x-icon name="{{ $icon ?? 'alert' }}" class="h-14 w-14 text-brand-500" />
            </div>
        </div>

        <p class="animate-bounce-in text-6xl font-black tracking-tight text-ink-900 sm:text-7xl">@fa($code)</p>
        <h1 class="mt-4 text-xl font-extrabold text-ink-900 sm:text-2xl">{{ $heading }}</h1>
        <p class="mt-3 max-w-xl text-sm leading-7 text-ink-500">{{ $message }}</p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                <x-icon name="home" class="h-5 w-5" />
                بازگشت به صفحه اصلی
            </a>
            <a href="{{ route('shop.index') }}" class="btn btn-outline btn-lg">
                <x-icon name="grid" class="h-5 w-5" />
                مشاهده فروشگاه
            </a>
            <a href="{{ route('contact') }}" class="btn btn-ghost btn-lg">
                <x-icon name="headset" class="h-5 w-5" />
                تماس با پشتیبانی
            </a>
        </div>

        <div class="mt-10 w-full rounded-card border border-ink-200 bg-white p-5 text-right shadow-card">
            <p class="mb-3 text-sm font-bold text-ink-900">شاید دنبال یکی از این بخش‌ها بودید</p>
            <div class="grid gap-2 sm:grid-cols-3">
                <a href="{{ route('shop.special') }}" class="page-link">پیشنهاد شگفت‌انگیز</a>
                <a href="{{ route('categories.index') }}" class="page-link">همه دسته‌بندی‌ها</a>
                <a href="{{ route('blog.index') }}" class="page-link">مجله دیجی‌نو</a>
                <a href="{{ route('faq') }}" class="page-link">پرسش‌های متداول</a>
                <a href="{{ route('account.orders.index') }}" class="page-link">پیگیری سفارش</a>
                <a href="{{ route('about') }}" class="page-link">درباره دیجی‌نو</a>
            </div>
        </div>
    </section>
