{{--
    Digino — admin panel layout: top bar + fixed sidebar. No footer.
--}}
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-pt-24">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#23262B">

    <title>@yield('title', 'پنل مدیریت') | دیجی‌نو</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="یارمحمدی">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preload" href="{{ asset('fonts/vazirmatn-arabic-wght-normal.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('digino.version', '1.0') }}">

    @stack('head')
</head>

<body class="min-h-screen bg-ink-50 antialiased">
    <div id="dg-progress" class="pointer-events-none fixed inset-x-0 top-0 z-[120] h-0.5 origin-right scale-x-0 bg-brand-500 transition-transform duration-300"></div>

    {{-- ───────────── sidebar (desktop) ───────────── --}}
    <aside class="fixed inset-y-0 start-0 z-40 hidden w-64 bg-ink-900 lg:block no-print">
        @include('partials.admin-sidebar')
    </aside>

    {{-- ───────────── sidebar (mobile drawer) ───────────── --}}
    <div id="dg-admin-nav" class="fixed inset-0 z-[80] hidden lg:hidden" role="dialog" aria-modal="true" aria-label="منوی مدیریت">
        <div class="modal-backdrop" data-mobile-nav-close></div>
        <aside class="absolute inset-y-0 start-0 w-72 bg-ink-900" data-mobile-nav-panel>
            <button type="button" class="absolute end-3 top-4 grid h-8 w-8 place-items-center rounded-full text-ink-400 transition-colors hover:bg-white/10 hover:text-white"
                    data-mobile-nav-close aria-label="بستن منو">
                <x-icon name="close" class="h-5 w-5" />
            </button>
            @include('partials.admin-sidebar')
        </aside>
    </div>

    <div class="lg:ps-64">

        {{-- ───────────── top bar ───────────── --}}
        <header class="sticky top-0 z-30 border-b border-ink-200 bg-white/95 backdrop-blur no-print" data-header>
            <div class="flex h-16 items-center gap-3 px-4 lg:px-6">

                <button type="button" class="btn-icon lg:hidden" data-mobile-nav-open aria-label="باز کردن منو">
                    <x-icon name="menu" class="h-6 w-6" />
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-base font-extrabold text-ink-900">@yield('heading', View::getSection('title', 'پیشخوان'))</h1>
                    @hasSection('subheading')
                        <p class="truncate text-2xs text-ink-500">@yield('subheading')</p>
                    @endif
                </div>

                {{-- global admin search --}}
                <form action="{{ route('admin.products.index') }}" method="GET" class="relative hidden xl:block">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="جستجوی محصول، سفارش یا مشتری..."
                           class="field-sm w-72 ps-9">
                    <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
                </form>

                {{-- notifications --}}
                <div class="relative" data-dropdown>
                    <button type="button" class="btn-icon relative" data-dropdown-trigger aria-label="اعلان‌ها">
                        <x-icon name="bell" class="h-5 w-5" />
                        @if(($adminBadges['new_orders'] ?? 0) + ($adminBadges['pending_reviews'] ?? 0) + ($adminBadges['open_tickets'] ?? 0) > 0)
                            <span class="absolute end-1.5 top-1.5 h-2 w-2 animate-pulse-ring rounded-full bg-brand-500"></span>
                        @endif
                    </button>
                    <div data-dropdown-panel class="absolute end-0 top-full z-50 mt-2 hidden w-72 overflow-hidden rounded-card bg-white shadow-pop ring-1 ring-ink-200">
                        <p class="border-b border-ink-100 px-4 py-3 text-2xs font-bold text-ink-800">کارهای در انتظار شما</p>
                        <ul class="divide-y divide-ink-50">
                            <li>
                                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-3 text-2xs transition-colors hover:bg-ink-50">
                                    <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-50 text-brand-500"><x-icon name="receipt" class="h-4 w-4" /></span>
                                    <span class="flex-1 text-ink-700">سفارش در انتظار بررسی</span>
                                    <span class="font-bold text-ink-900">{{ fa_number($adminBadges['new_orders'] ?? 0) }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-3 text-2xs transition-colors hover:bg-ink-50">
                                    <span class="grid h-8 w-8 place-items-center rounded-full bg-warning-50 text-warning-600"><x-icon name="star" class="h-4 w-4" /></span>
                                    <span class="flex-1 text-ink-700">دیدگاه در انتظار تأیید</span>
                                    <span class="font-bold text-ink-900">{{ fa_number($adminBadges['pending_reviews'] ?? 0) }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tickets.index', ['status' => 'open']) }}" class="flex items-center gap-3 px-4 py-3 text-2xs transition-colors hover:bg-ink-50">
                                    <span class="grid h-8 w-8 place-items-center rounded-full bg-info-50 text-info-600"><x-icon name="headset" class="h-4 w-4" /></span>
                                    <span class="flex-1 text-ink-700">تیکت پاسخ‌داده‌نشده</span>
                                    <span class="font-bold text-ink-900">{{ fa_number($adminBadges['open_tickets'] ?? 0) }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.inventory.index', ['filter' => 'low']) }}" class="flex items-center gap-3 px-4 py-3 text-2xs transition-colors hover:bg-ink-50">
                                    <span class="grid h-8 w-8 place-items-center rounded-full bg-ink-100 text-ink-600"><x-icon name="warehouse" class="h-4 w-4" /></span>
                                    <span class="flex-1 text-ink-700">کالای رو به اتمام</span>
                                    <span class="font-bold text-ink-900">{{ fa_number($adminBadges['low_stock'] ?? 0) }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- admin user --}}
                <div class="relative" data-dropdown>
                    <button type="button" class="flex items-center gap-2 rounded-pill py-1 pe-1 ps-3 transition-colors hover:bg-ink-50" data-dropdown-trigger>
                        <span class="hidden text-2xs font-bold text-ink-800 sm:block">{{ auth()->user()->name }}</span>
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-ink-900 text-[10px] font-bold text-white">
                            {{ auth()->user()->initials }}
                        </span>
                    </button>
                    <div data-dropdown-panel class="absolute end-0 top-full z-50 mt-2 hidden w-56 overflow-hidden rounded-card bg-white py-1.5 shadow-pop ring-1 ring-ink-200">
                        <p class="px-4 py-2 text-2xs text-ink-500">
                            وارد شده به عنوان
                            <span class="block font-bold text-ink-800">{{ auth()->user()->role->label() }}</span>
                        </p>
                        <div class="divider my-1"></div>
                        <a href="{{ route('account.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-2xs text-ink-700 transition-colors hover:bg-ink-50">
                            <x-icon name="user" class="h-4 w-4 text-ink-400" /> حساب کاربری من
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-2xs text-ink-700 transition-colors hover:bg-ink-50">
                            <x-icon name="settings" class="h-4 w-4 text-ink-400" /> تنظیمات فروشگاه
                        </a>
                        <div class="divider my-1"></div>
                        <form action="{{ route('logout') }}" method="POST" data-ajax-form data-redirect>
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-2xs text-brand-600 transition-colors hover:bg-brand-50">
                                <x-icon name="logout" class="h-4 w-4" /> خروج از پنل
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @hasSection('toolbar')
                <div class="border-t border-ink-100 px-4 py-2.5 lg:px-6">@yield('toolbar')</div>
            @endif
        </header>

        {{-- ───────────── content ───────────── --}}
        <main class="p-4 lg:p-6">
            @hasSection('breadcrumb')
                <nav class="mb-4 flex flex-wrap items-center gap-2 text-2xs text-ink-500" aria-label="مسیر">
                    <a href="{{ route('admin.dashboard') }}" class="link-muted">پیشخوان</a>
                    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
                    @yield('breadcrumb')
                </nav>
            @endif

            @include('partials.flash')

            @yield('content')
        </main>

        <p class="px-6 pb-6 text-center text-2xs text-ink-400 no-print">
            پنل مدیریت دیجی‌نو — نسخه {{ fa_number(config('digino.version', '1.0')) }} — توسعه توسط
            <span class="font-bold text-ink-600">یارمحمدی</span>
        </p>
    </div>

    @include('partials.toast-host')
    @include('partials.modal-host')

    <script src="{{ asset('js/app.js') }}?v={{ config('digino.version', '1.0') }}" defer></script>
    <script src="{{ asset('js/admin.js') }}?v={{ config('digino.version', '1.0') }}" defer></script>
    @stack('scripts')
</body>
</html>
