{{-- Off-canvas navigation for small screens + bottom tab bar. --}}
<div id="dg-mobile-nav" class="fixed inset-0 z-[80] hidden lg:hidden" role="dialog" aria-modal="true" aria-label="منوی موبایل">
    <div class="modal-backdrop" data-mobile-nav-close></div>

    <aside class="absolute inset-y-0 start-0 flex w-[85vw] max-w-xs flex-col bg-white shadow-pop" data-mobile-nav-panel>
        <div class="flex items-center justify-between border-b border-ink-100 p-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/logo/mark.svg') }}" alt="دیجی‌نو" class="h-8 w-8">
                <span class="text-lg font-extrabold text-brand-500">دیجی‌نو</span>
            </a>
            <button type="button" class="btn-icon h-8 w-8" data-mobile-nav-close aria-label="بستن منو">
                <x-icon name="close" class="h-5 w-5" />
            </button>
        </div>

        @auth
            <a href="{{ route('account.dashboard') }}" class="flex items-center gap-3 border-b border-ink-100 bg-ink-50 p-4">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-brand-100 text-sm font-bold text-brand-600">
                    {{ auth()->user()->initials }}
                </span>
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-bold text-ink-900">{{ auth()->user()->name }}</span>
                    <span class="block truncate text-2xs text-ink-500">مشاهده حساب کاربری</span>
                </span>
                <x-icon name="chevron-left" class="h-4 w-4 text-ink-400" />
            </a>
        @else
            <div class="border-b border-ink-100 p-4">
                <a href="{{ route('login') }}" class="btn-primary w-full">
                    <x-icon name="user" class="h-5 w-5" /> ورود / ثبت‌نام
                </a>
            </div>
        @endauth

        <nav class="flex-1 overflow-y-auto p-2">
            <p class="px-3 pb-1 pt-3 text-2xs font-bold text-ink-400">دسته‌بندی کالاها</p>
            <ul>
                @foreach($menuCategories ?? [] as $category)
                    <li class="border-b border-ink-50 last:border-0">
                        @if($category->children->isNotEmpty())
                            <details class="group">
                                <summary class="flex cursor-pointer list-none items-center justify-between gap-2 px-3 py-3 text-sm text-ink-700">
                                    <span class="flex items-center gap-2.5">
                                        <x-icon :name="$category->icon ?: 'box'" class="h-5 w-5 text-ink-400" />
                                        {{ $category->name }}
                                    </span>
                                    <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
                                </summary>
                                <ul class="bg-ink-50/70 pb-1">
                                    <li>
                                        <a href="{{ route('categories.show', $category->slug) }}" class="block px-11 py-2 text-2xs font-bold text-brand-500">
                                            مشاهده همه {{ $category->name }}
                                        </a>
                                    </li>
                                    @foreach($category->children as $child)
                                        <li>
                                            <a href="{{ route('categories.show', $child->slug) }}" class="block px-11 py-2 text-2xs text-ink-600">
                                                {{ $child->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </details>
                        @else
                            <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center gap-2.5 px-3 py-3 text-sm text-ink-700">
                                <x-icon :name="$category->icon ?: 'box'" class="h-5 w-5 text-ink-400" />
                                {{ $category->name }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>

            <p class="px-3 pb-1 pt-4 text-2xs font-bold text-ink-400">دسترسی سریع</p>
            <ul class="pb-4">
                <li><a href="{{ route('shop.special') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm font-bold text-brand-500"><x-icon name="percent" class="h-5 w-5" /> فروش ویژه</a></li>
                <li><a href="{{ route('brands.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-ink-700"><x-icon name="tag" class="h-5 w-5 text-ink-400" /> برندها</a></li>
                <li><a href="{{ route('blog.index') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-ink-700"><x-icon name="newspaper" class="h-5 w-5 text-ink-400" /> مجله دیجی‌نو</a></li>
                <li><a href="{{ route('about') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-ink-700"><x-icon name="info" class="h-5 w-5 text-ink-400" /> درباره ما</a></li>
                <li><a href="{{ route('contact') }}" class="flex items-center gap-2.5 px-3 py-2.5 text-sm text-ink-700"><x-icon name="headset" class="h-5 w-5 text-ink-400" /> تماس با ما</a></li>
                @auth
                    <li>
                        <form action="{{ route('logout') }}" method="POST" data-ajax-form data-redirect>
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2.5 px-3 py-2.5 text-sm text-brand-600">
                                <x-icon name="logout" class="h-5 w-5" /> خروج
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>
    </aside>
</div>

{{-- Bottom tab bar (mobile only) --}}
<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-ink-200 bg-white/95 backdrop-blur lg:hidden" aria-label="ناوبری پایین">
    <ul class="grid grid-cols-5">
        @php
            $tabs = [
                ['url' => route('home'), 'icon' => 'store', 'label' => 'خانه', 'active' => request()->routeIs('home')],
                ['url' => route('categories.index'), 'icon' => 'grid', 'label' => 'دسته‌ها', 'active' => request()->routeIs('categories.*')],
                ['url' => route('cart.index'), 'icon' => 'cart', 'label' => 'سبد خرید', 'active' => request()->routeIs('cart.*'), 'badge' => true],
                ['url' => auth()->check() ? route('account.wishlist') : route('login'), 'icon' => 'heart', 'label' => 'علاقه‌مندی', 'active' => request()->routeIs('account.wishlist')],
                ['url' => auth()->check() ? route('account.dashboard') : route('login'), 'icon' => 'user', 'label' => 'حساب من', 'active' => request()->routeIs('account.*')],
            ];
        @endphp
        @foreach($tabs as $tab)
            <li>
                <a href="{{ $tab['url'] }}"
                   class="relative flex flex-col items-center gap-1 py-2 text-[10px] font-medium transition-colors {{ $tab['active'] ? 'text-brand-500' : 'text-ink-500' }}">
                    <span class="relative">
                        <x-icon :name="$tab['icon']" class="h-5 w-5" />
                        @if($tab['badge'] ?? false)
                            <span data-cart-count
                                  class="absolute -top-1.5 -end-2 grid h-4 min-w-4 place-items-center rounded-full bg-brand-500 px-1 text-[9px] font-bold text-white {{ ($cartSummary['count'] ?? 0) > 0 ? '' : 'scale-0' }}">
                                {{ fa_number($cartSummary['count'] ?? 0) }}
                            </span>
                        @endif
                    </span>
                    {{ $tab['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
<div class="h-14 lg:hidden" aria-hidden="true"></div>
