{{--
    Digino — "bare" layout used by authentication screens.
    Per the project brief these pages carry the header but NO footer.
--}}
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#EF394E">

    <title>@yield('title', 'ورود به دیجی‌نو')</title>
    <meta name="description" content="@yield('meta_description', 'ورود و ثبت‌نام در فروشگاه اینترنتی دیجی‌نو')">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="یارمحمدی">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preload" href="{{ asset('fonts/vazirmatn-arabic-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('digino.version', '1.0') }}">

    @stack('head')
</head>

<body class="flex min-h-screen flex-col bg-ink-50 antialiased">
    <div id="dg-progress" class="pointer-events-none fixed inset-x-0 top-0 z-[120] h-0.5 origin-right scale-x-0 bg-brand-500 transition-transform duration-300"></div>

    {{-- Simplified header: the shared one would be noisy on an auth screen, so
         we reuse the same markup language without search / cart clutter. --}}
    <header class="sticky top-0 z-50 border-b border-ink-200 bg-white" data-header>
        <div class="container">
            <div class="flex h-16 items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo/mark.svg') }}" alt="دیجی‌نو" width="40" height="40" class="h-10 w-10">
                    <span class="leading-none">
                        <span class="block text-xl font-extrabold text-brand-500">دیجی‌نو</span>
                        <span class="mt-1 block text-2xs text-ink-400">{{ digino('site_tagline', 'خرید هوشمند') }}</span>
                    </span>
                </a>

                <a href="{{ route('home') }}" class="btn-ghost btn-sm">
                    <x-icon name="arrow-right" class="h-4 w-4" />
                    بازگشت به فروشگاه
                </a>
            </div>
        </div>
    </header>

    <main class="flex flex-1 items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            @include('partials.flash')
            @yield('content')
        </div>
    </main>

    @include('partials.toast-host')
    @include('partials.modal-host')

    <p class="pb-6 text-center text-2xs text-ink-400">
        © {{ fa_number(jalali(now(), 'Y')) }} دیجی‌نو — طراحی و توسعه توسط
        <span class="font-bold text-ink-600">یارمحمدی</span>
    </p>

    <script src="{{ asset('js/app.js') }}?v={{ config('digino.version', '1.0') }}" defer></script>
    @stack('scripts')
</body>
</html>
