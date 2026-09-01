{{--
    Shared account sidebar. Included by layouts/account.blade.php only, but kept
    in its own file so it can also be dropped into a mobile drawer.
--}}
@php
    $groups = [
        [
            'title' => null,
            'items' => [
                ['route' => 'account.dashboard', 'icon' => 'dashboard', 'label' => 'پیشخوان'],
            ],
        ],
        [
            'title' => 'خرید و سفارش‌ها',
            'items' => [
                ['route' => 'account.orders.index', 'icon' => 'box', 'label' => 'سفارش‌های من', 'badge' => $sidebarCounts['orders'] ?? null],
                ['route' => 'account.wishlist', 'icon' => 'heart', 'label' => 'علاقه‌مندی‌ها', 'badge' => $sidebarCounts['wishlist'] ?? null],
                ['route' => 'account.recently-viewed', 'icon' => 'clock', 'label' => 'بازدیدهای اخیر'],
                ['route' => 'account.reviews', 'icon' => 'chat', 'label' => 'دیدگاه‌های من', 'badge' => $sidebarCounts['reviews'] ?? null],
            ],
        ],
        [
            'title' => 'حساب کاربری',
            'items' => [
                ['route' => 'account.profile', 'icon' => 'user', 'label' => 'اطلاعات حساب'],
                ['route' => 'account.addresses', 'icon' => 'map-pin', 'label' => 'آدرس‌ها', 'badge' => $sidebarCounts['addresses'] ?? null],
                ['route' => 'account.payments', 'icon' => 'wallet', 'label' => 'کیف پول و تراکنش‌ها'],
                ['route' => 'account.security', 'icon' => 'lock', 'label' => 'امنیت و رمز عبور'],
                ['route' => 'account.notifications', 'icon' => 'bell', 'label' => 'اعلان‌ها', 'badge' => $sidebarCounts['notifications'] ?? null],
            ],
        ],
        [
            'title' => 'پشتیبانی',
            'items' => [
                ['route' => 'account.tickets.index', 'icon' => 'headset', 'label' => 'تیکت‌های پشتیبانی', 'badge' => $sidebarCounts['tickets'] ?? null],
            ],
        ],
    ];
@endphp

<div class="overflow-hidden rounded-card bg-white shadow-card">

    {{-- user card --}}
    <div class="flex items-center gap-3 border-b border-ink-100 p-4">
        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-brand-50 text-sm font-extrabold text-brand-600 ring-2 ring-brand-100">
            {{ auth()->user()->initials }}
        </span>
        <span class="min-w-0 flex-1">
            <span class="block truncate text-sm font-bold text-ink-900">{{ auth()->user()->name }}</span>
            <span class="ltr mt-0.5 block truncate text-2xs text-ink-500">{{ fa_number(auth()->user()->mobile ?? auth()->user()->email) }}</span>
        </span>
        <a href="{{ route('account.profile') }}" class="btn-icon h-8 w-8 shrink-0" aria-label="ویرایش اطلاعات حساب">
            <x-icon name="edit" class="h-4 w-4" />
        </a>
    </div>

    {{-- navigation --}}
    <nav class="p-2" aria-label="منوی حساب کاربری">
        @foreach($groups as $group)
            @if($group['title'])
                <p class="px-3 pb-1 pt-3 text-2xs font-bold text-ink-400">{{ $group['title'] }}</p>
            @endif
            <ul>
                @foreach($group['items'] as $item)
                    @php $isActive = request()->routeIs($item['route']) || request()->routeIs(str_replace('.index', '.*', $item['route'])); @endphp
                    <li>
                        <a href="{{ route($item['route']) }}" class="account-link" @if($isActive) data-active aria-current="page" @endif>
                            <x-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if(!empty($item['badge']))
                                <span class="badge-red-soft">{{ fa_number($item['badge']) }}</span>
                            @endif
                            <x-icon name="chevron-left" class="h-3.5 w-3.5 shrink-0 text-ink-300 transition-transform duration-200 group-hover:-translate-x-0.5" />
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach

        <div class="divider my-2"></div>

        @admin
            <a href="{{ route('admin.dashboard') }}" class="account-link text-info-600">
                <x-icon name="settings" class="h-[18px] w-[18px] shrink-0" />
                <span class="flex-1">پنل مدیریت</span>
                <x-icon name="external" class="h-3.5 w-3.5 shrink-0" />
            </a>
        @endadmin

        <form action="{{ route('logout') }}" method="POST" data-ajax-form data-redirect>
            @csrf
            <button type="submit" class="account-link w-full text-brand-600">
                <x-icon name="logout" class="h-[18px] w-[18px] shrink-0" />
                <span class="flex-1 text-start">خروج از حساب</span>
            </button>
        </form>
    </nav>
</div>

{{-- support box --}}
<div class="mt-4 rounded-card bg-white p-4 shadow-card">
    <div class="flex items-center gap-2.5">
        <span class="grid h-10 w-10 place-items-center rounded-full bg-info-50 text-info-600">
            <x-icon name="headset" class="h-5 w-5" />
        </span>
        <div class="min-w-0">
            <p class="text-2xs font-bold text-ink-800">پشتیبانی دیجی‌نو</p>
            <p class="ltr mt-0.5 truncate text-2xs text-ink-500">{{ fa_number(digino('support_phone', '021-12345678')) }}</p>
        </div>
    </div>
    <a href="{{ route('account.tickets.create') }}" class="btn-outline btn-sm mt-3 w-full">
        <x-icon name="plus" class="h-4 w-4" />
        ثبت تیکت جدید
    </a>
</div>
