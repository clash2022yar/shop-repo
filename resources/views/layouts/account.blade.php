{{--
    Digino — user dashboard layout: header + account sidebar. No footer.
--}}
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-pt-32">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#EF394E">

    <title>@yield('title', 'حساب کاربری') | دیجی‌نو</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="یارمحمدی">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preload" href="{{ asset('fonts/vazirmatn-arabic-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('digino.version', '1.0') }}">

    @stack('head')
</head>

<body class="min-h-screen bg-ink-50 antialiased">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:right-3 focus:z-[200] focus:rounded-field focus:bg-brand-500 focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-white">
        رفتن به محتوای اصلی
    </a>

    <div id="dg-progress" class="pointer-events-none fixed inset-x-0 top-0 z-[120] h-0.5 origin-right scale-x-0 bg-brand-500 transition-transform duration-300"></div>

    @include('partials.header')

    <main id="main" class="container py-5">
        <x-breadcrumb :items="array_merge([['label' => 'حساب کاربری', 'url' => route('account.dashboard')]], $breadcrumbs ?? [])" />

        @include('partials.flash')

        <div class="mt-3 grid gap-5 lg:grid-cols-[17rem_minmax(0,1fr)]">

            {{-- sidebar: drawer on mobile, static column on desktop --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                {{-- mobile toggle --}}
                <details class="group rounded-card bg-white shadow-card lg:hidden">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-2 p-4">
                        <span class="flex items-center gap-2.5">
                            <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-50 text-2xs font-bold text-brand-600">
                                {{ auth()->user()->initials }}
                            </span>
                            <span class="text-sm font-bold text-ink-900">منوی حساب کاربری</span>
                        </span>
                        <x-icon name="chevron-down" class="h-5 w-5 text-ink-400 transition-transform group-open:rotate-180" />
                    </summary>
                    <div class="p-2 pt-0">@include('partials.account-sidebar')</div>
                </details>

                <div class="hidden lg:block">@include('partials.account-sidebar')</div>
            </aside>

            {{-- content --}}
            <div class="min-w-0">
                @hasSection('page-header')
                    @yield('page-header')
                @else
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h1 class="text-lg font-extrabold text-ink-900">@yield('heading', View::getSection('title', 'حساب کاربری'))</h1>
                            @hasSection('subheading')
                                <p class="mt-1 text-2xs text-ink-500">@yield('subheading')</p>
                            @endif
                        </div>
                        @yield('actions')
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </main>

    @include('partials.toast-host')
    @include('partials.modal-host')
    @include('partials.mobile-nav')
    @include('partials.back-to-top')

    <script src="{{ asset('js/app.js') }}?v={{ config('digino.version', '1.0') }}" defer></script>
    @stack('scripts')
</body>
</html>
