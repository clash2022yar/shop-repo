{{--
    Digino — the site header.
    One file, included by every layout. Two rows exactly as in the design:
      row 1: logo · search · account · cart
      row 2: category mega-menu trigger · main nav · special offers link
--}}
<header id="dg-header" class="sticky top-0 z-50 bg-white shadow-header transition-shadow duration-300" data-header>
    {{-- ══════════════════════════ row 1 ══════════════════════════ --}}
    <div class="container">
        <div class="flex items-center gap-3 py-3 lg:gap-6 lg:py-4">

            {{-- mobile: menu button --}}
            <button type="button" class="btn-icon shrink-0 lg:hidden" data-mobile-nav-open aria-label="باز کردن منو">
                <x-icon name="menu" class="h-6 w-6" />
            </button>

            {{-- logo --}}
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-2" aria-label="دیجی‌نو، صفحه اصلی">
                <img src="{{ asset('images/logo/mark.svg') }}" alt="" width="40" height="40"
                     class="h-9 w-9 transition-transform duration-500 ease-bounce2 group-hover:rotate-[-8deg] group-hover:scale-110 lg:h-10 lg:w-10">
                <span class="hidden leading-none sm:block">
                    <span class="block text-xl font-extrabold tracking-tight text-brand-500 lg:text-2xl">دیجی‌نو</span>
                    <span class="mt-0.5 block text-2xs font-medium text-ink-500">{{ digino('site_tagline', 'خرید هوشمند') }}</span>
                </span>
            </a>

            {{-- search --}}
            <div class="relative flex-1" data-search-root>
                <form action="{{ route('search') }}" method="GET" role="search" autocomplete="off"
                      class="relative flex items-center rounded-field bg-ink-100 transition-all duration-300 focus-within:bg-white focus-within:shadow-ring focus-within:ring-1 focus-within:ring-brand-200">
                    <span class="pointer-events-none grid h-11 w-11 shrink-0 place-items-center text-ink-400">
                        <x-icon name="search" class="h-5 w-5" />
                    </span>
                    <input type="search" name="q" value="{{ request('q') }}" data-search-input
                           class="h-11 w-full border-0 bg-transparent pe-3 text-sm text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-0"
                           placeholder="جستجو در محصولات، برندها و دسته‌بندی‌ها..."
                           aria-label="جستجو در دیجی‌نو">
                    <button type="button" data-search-clear
                            class="me-2 hidden h-7 w-7 shrink-0 place-items-center rounded-full text-ink-400 hover:bg-ink-200 hover:text-ink-700"
                            aria-label="پاک کردن جستجو">
                        <x-icon name="close" class="h-4 w-4" />
                    </button>
                </form>

                {{-- autocomplete panel --}}
                <div data-search-panel
                     class="absolute inset-x-0 top-[calc(100%+0.5rem)] z-50 hidden overflow-hidden rounded-card bg-white shadow-pop ring-1 ring-ink-200">
                    <div data-search-results class="max-h-[70vh] overflow-y-auto"></div>
                </div>
            </div>

            {{-- account --}}
            <div class="relative shrink-0" data-dropdown>
                @auth
                    <button type="button" data-dropdown-trigger
                            class="flex items-center gap-2 rounded-field px-2 py-2 text-sm text-ink-700 transition-colors hover:bg-ink-100 lg:px-3">
                        <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-50 text-xs font-bold text-brand-600">
                            {{ auth()->user()->initials }}
                        </span>
                        <span class="hidden max-w-24 truncate font-medium lg:block">{{ auth()->user()->name }}</span>
                        <x-icon name="chevron-down" class="hidden h-4 w-4 text-ink-400 transition-transform duration-300 data-[open]:rotate-180 lg:block" />
                    </button>

                    <div data-dropdown-panel
                         class="absolute end-0 top-[calc(100%+0.5rem)] z-50 hidden w-60 origin-top overflow-hidden rounded-card bg-white shadow-pop ring-1 ring-ink-200">
                        <div class="border-b border-ink-100 bg-ink-50 p-4">
                            <p class="truncate text-sm font-bold text-ink-900">{{ auth()->user()->name }}</p>
                            <p class="mt-0.5 truncate text-2xs text-ink-500 ltr">{{ auth()->user()->mobile ?? auth()->user()->email }}</p>
                        </div>
                        <nav class="p-1.5">
                            @admin
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-field px-3 py-2.5 text-sm font-bold text-brand-600 transition-colors hover:bg-brand-50">
                                    <x-icon name="dashboard" class="h-4.5 w-4.5" /> پنل مدیریت
                                </a>
                                <div class="my-1 h-px bg-ink-100"></div>
                            @endadmin
                            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 rounded-field px-3 py-2.5 text-sm text-ink-700 transition-colors hover:bg-ink-100">
                                <x-icon name="dashboard" class="h-4.5 w-4.5 text-ink-400" /> پیشخوان
                            </a>
                            <a href="{{ route('account.orders.index') }}" class="flex items-center gap-3 rounded-field px-3 py-2.5 text-sm text-ink-700 transition-colors hover:bg-ink-100">
                                <x-icon name="box" class="h-4.5 w-4.5 text-ink-400" /> سفارش‌های من
                            </a>
                            <a href="{{ route('account.wishlist') }}" class="flex items-center gap-3 rounded-field px-3 py-2.5 text-sm text-ink-700 transition-colors hover:bg-ink-100">
                                <x-icon name="heart" class="h-4.5 w-4.5 text-ink-400" /> علاقه‌مندی‌ها
                            </a>
                            <a href="{{ route('account.profile') }}" class="flex items-center gap-3 rounded-field px-3 py-2.5 text-sm text-ink-700 transition-colors hover:bg-ink-100">
                                <x-icon name="user" class="h-4.5 w-4.5 text-ink-400" /> اطلاعات حساب
                            </a>
                            <div class="my-1 h-px bg-ink-100"></div>
                            <form action="{{ route('logout') }}" method="POST" data-ajax-form data-redirect>
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-3 rounded-field px-3 py-2.5 text-sm text-brand-600 transition-colors hover:bg-brand-50">
                                    <x-icon name="logout" class="h-4.5 w-4.5" /> خروج از حساب کاربری
                                </button>
                            </form>
                        </nav>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-2 rounded-field border border-ink-200 px-3 py-2 text-sm font-medium text-ink-700 transition-all duration-200 hover:border-brand-300 hover:bg-brand-50 hover:text-brand-600">
                        <x-icon name="user" class="h-5 w-5" />
                        <span class="hidden whitespace-nowrap sm:block">ورود / ثبت‌نام</span>
                    </a>
                @endauth
            </div>

            <span class="hidden h-8 w-px bg-ink-200 lg:block"></span>

            {{-- cart --}}
            <div class="relative shrink-0" data-dropdown data-mini-cart-root>
                <button type="button" data-dropdown-trigger data-mini-cart-trigger
                        class="relative flex items-center gap-2 rounded-field px-2 py-2 text-sm text-ink-700 transition-colors hover:bg-ink-100 lg:px-3"
                        aria-label="سبد خرید">
                    <span class="relative">
                        <x-icon name="cart" class="h-6 w-6" />
                        <span data-cart-count
                              class="absolute -top-1.5 -end-2 grid h-[18px] min-w-[18px] place-items-center rounded-full bg-brand-500 px-1 text-[10px] font-bold text-white transition-transform duration-300 ease-bounce2 {{ ($cartSummary['count'] ?? 0) > 0 ? '' : 'scale-0' }}">
                            {{ fa_number($cartSummary['count'] ?? 0) }}
                        </span>
                    </span>
                    <span class="hidden whitespace-nowrap font-medium lg:block">سبد خرید</span>
                    <x-icon name="chevron-down" class="hidden h-4 w-4 text-ink-400 transition-transform duration-300 data-[open]:rotate-180 lg:block" />
                </button>

                <div data-dropdown-panel data-mini-cart-panel
                     class="absolute end-0 top-[calc(100%+0.5rem)] z-50 hidden w-[22rem] origin-top overflow-hidden rounded-card bg-white shadow-pop ring-1 ring-ink-200">
                    <div class="grid place-items-center p-8 text-ink-400">
                        <x-icon name="spinner" class="h-6 w-6 animate-spin-slow" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════ row 2 ══════════════════════════ --}}
    <div class="hidden border-t border-ink-100 lg:block">
        <div class="container">
            <nav class="flex items-center gap-6" aria-label="منوی اصلی">

                {{-- mega-menu trigger --}}
                <div class="relative" data-mega-root>
                    <button type="button" data-mega-trigger
                            class="group flex items-center gap-2 py-3 text-[0.8125rem] font-bold text-ink-800 transition-colors hover:text-brand-500">
                        <x-icon name="menu" class="h-5 w-5" />
                        <span>دسته‌بندی کالاها</span>
                        <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform duration-300 group-data-[open]:rotate-180" />
                    </button>

                    @include('partials.mega-menu')
                </div>

                {{-- top level links --}}
                <ul class="flex flex-1 items-center gap-5 overflow-x-auto scrollbar-none">
                    @forelse($menuCategories ?? [] as $category)
                        <li>
                            <a href="{{ route('categories.show', $category->slug) }}"
                               class="nav-link {{ request()->routeIs('categories.show') && request()->route('category')?->slug === $category->slug ? 'text-brand-500' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @empty
                        <li><a href="{{ route('shop.index') }}" class="nav-link">همه محصولات</a></li>
                    @endforelse
                </ul>

                {{-- special offers --}}
                <a href="{{ route('shop.special') }}"
                   class="group flex shrink-0 items-center gap-1.5 py-3 text-[0.8125rem] font-bold text-brand-500 transition-all hover:gap-2.5">
                    <span class="grid h-1.5 w-1.5 place-items-center rounded-full bg-brand-500 animate-pulse-ring"></span>
                    فروش ویژه
                </a>
            </nav>
        </div>
    </div>
</header>
