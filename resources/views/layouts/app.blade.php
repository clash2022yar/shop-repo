{{--
    Digino — base storefront layout (header + footer).
    Pages extend this with @section('content').
--}}
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-pt-32">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#EF394E">

    <title>@yield('title', digino('site_name', 'دیجی‌نو') . ' | ' . digino('site_tagline', 'خرید هوشمند کالای دیجیتال'))</title>
    <meta name="description" content="@yield('meta_description', digino('site_description', 'دیجی‌نو، فروشگاه تخصصی محصولات دیجیتال با ضمانت اصالت کالا، بهترین قیمت و ارسال سریع به سراسر کشور.'))">
    <meta name="author" content="یارمحمدی">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="دیجی‌نو">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'دیجی‌نو | خرید هوشمند')">
    <meta property="og:description" content="@yield('meta_description', 'فروشگاه تخصصی کالای دیجیتال')">
    <meta property="og:image" content="@yield('og_image', asset('images/logo/mark.svg'))">
    <meta property="og:locale" content="fa_IR">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('images/logo/mark.svg') }}">
    <link rel="preload" href="{{ asset('fonts/vazirmatn-arabic-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('digino.version', '1.0') }}">

    @stack('head')
</head>

<body class="min-h-screen bg-ink-50 antialiased">
    {{-- Skip link for keyboard users --}}
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:right-3 focus:z-[200] focus:rounded-field focus:bg-brand-500 focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-white">
        رفتن به محتوای اصلی
    </a>

    {{-- Thin progress bar shown during AJAX navigation --}}
    <div id="dg-progress" class="pointer-events-none fixed inset-x-0 top-0 z-[120] h-0.5 origin-right scale-x-0 bg-brand-500 transition-transform duration-300"></div>

    @include('partials.header')

    <main id="main" class="pb-10">
        @hasSection('hero')
            @yield('hero')
        @endif

        <div class="container">
            @include('partials.flash')

            @isset($breadcrumbs)
                <x-breadcrumb :items="$breadcrumbs" />
            @endisset
        </div>

        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Global UI machinery: toasts, modal host, mini-cart, mobile nav --}}
    @include('partials.toast-host')
    @include('partials.modal-host')
    @include('partials.mobile-nav')
    @include('partials.back-to-top')

    <script src="{{ asset('js/app.js') }}?v={{ config('digino.version', '1.0') }}" defer></script>
    @stack('scripts')
</body>
</html>
