{{--
    Shared admin sidebar navigation. Single source of truth — included by
    layouts/admin.blade.php for both the desktop rail and the mobile drawer.
--}}
@php
    $nav = [
        [
            'title' => null,
            'items' => [
                ['route' => 'admin.dashboard', 'icon' => 'dashboard', 'label' => 'پیشخوان', 'match' => 'admin.dashboard'],
            ],
        ],
        [
            'title' => 'فروشگاه',
            'items' => [
                ['route' => 'admin.products.index',   'icon' => 'box',       'label' => 'محصولات',      'match' => 'admin.products.*'],
                ['route' => 'admin.categories.index', 'icon' => 'layers',    'label' => 'دسته‌بندی‌ها',  'match' => 'admin.categories.*'],
                ['route' => 'admin.brands.index',     'icon' => 'tag',       'label' => 'برندها',       'match' => 'admin.brands.*'],
                ['route' => 'admin.inventory.index',  'icon' => 'warehouse', 'label' => 'انبار و موجودی','match' => 'admin.inventory.*', 'badge' => $adminBadges['low_stock'] ?? null, 'badgeTone' => 'amber'],
            ],
        ],
        [
            'title' => 'فروش',
            'items' => [
                ['route' => 'admin.orders.index',    'icon' => 'receipt', 'label' => 'سفارش‌ها', 'match' => 'admin.orders.*', 'badge' => $adminBadges['new_orders'] ?? null],
                ['route' => 'admin.customers.index', 'icon' => 'users',   'label' => 'مشتریان',  'match' => 'admin.customers.*'],
                ['route' => 'admin.coupons.index',   'icon' => 'ticket',  'label' => 'کدهای تخفیف', 'match' => 'admin.coupons.*'],
                ['route' => 'admin.shipping.index',  'icon' => 'truck',   'label' => 'روش‌های ارسال', 'match' => 'admin.shipping.*'],
            ],
        ],
        [
            'title' => 'تعامل کاربران',
            'items' => [
                ['route' => 'admin.reviews.index',   'icon' => 'star',     'label' => 'دیدگاه‌ها',   'match' => 'admin.reviews.*',   'badge' => $adminBadges['pending_reviews'] ?? null],
                ['route' => 'admin.questions.index', 'icon' => 'question', 'label' => 'پرسش و پاسخ', 'match' => 'admin.questions.*', 'badge' => $adminBadges['pending_questions'] ?? null],
                ['route' => 'admin.tickets.index',   'icon' => 'headset',  'label' => 'تیکت‌ها',     'match' => 'admin.tickets.*',   'badge' => $adminBadges['open_tickets'] ?? null],
            ],
        ],
        [
            'title' => 'محتوا',
            'items' => [
                ['route' => 'admin.banners.index', 'icon' => 'image',     'label' => 'بنرها',        'match' => 'admin.banners.*'],
                ['route' => 'admin.posts.index',   'icon' => 'newspaper', 'label' => 'مجله',         'match' => 'admin.posts.*'],
                ['route' => 'admin.pages.index',   'icon' => 'file',      'label' => 'صفحات ثابت',   'match' => 'admin.pages.*'],
            ],
        ],
        [
            'title' => 'سیستم',
            'items' => [
                ['route' => 'admin.reports.index',  'icon' => 'chart',    'label' => 'گزارش‌ها', 'match' => 'admin.reports.*'],
                ['route' => 'admin.staff.index',    'icon' => 'user-plus','label' => 'کاربران پنل', 'match' => 'admin.staff.*', 'gate' => 'manage-staff'],
                ['route' => 'admin.settings.index', 'icon' => 'settings', 'label' => 'تنظیمات', 'match' => 'admin.settings.*'],
            ],
        ],
    ];

    $tones = ['red' => 'badge-red', 'amber' => 'badge-amber', 'blue' => 'badge-blue'];
@endphp

<nav class="flex h-full flex-col" aria-label="منوی مدیریت">

    <div class="flex h-16 shrink-0 items-center gap-2.5 border-b border-white/10 px-5">
        <img src="{{ asset('images/logo/mark.svg') }}" alt="" width="32" height="32" class="h-8 w-8">
        <span class="leading-none">
            <span class="block text-base font-extrabold text-white">دیجی‌نو</span>
            <span class="mt-1 block text-[10px] text-ink-400">پنل مدیریت</span>
        </span>
    </div>

    <div class="scrollbar-none flex-1 overflow-y-auto px-3 py-3">
        @foreach($nav as $group)
            @if($group['title'])
                <p class="px-3 pb-1.5 pt-3 text-[10px] font-bold uppercase tracking-wide text-ink-500">{{ $group['title'] }}</p>
            @endif
            <ul class="space-y-0.5">
                @foreach($group['items'] as $item)
                    @continue(isset($item['gate']) && ! Gate::allows($item['gate']))
                    @php $isActive = request()->routeIs($item['match']); @endphp
                    <li>
                        <a href="{{ route($item['route']) }}" class="admin-nav-link" @if($isActive) data-active aria-current="page" @endif>
                            <x-icon :name="$item['icon']" class="h-[18px] w-[18px] shrink-0" />
                            <span class="flex-1 truncate">{{ $item['label'] }}</span>
                            @if(!empty($item['badge']))
                                <span class="{{ $tones[$item['badgeTone'] ?? 'red'] }} shrink-0">{{ fa_number($item['badge']) }}</span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        @endforeach
    </div>

    <div class="shrink-0 border-t border-white/10 p-3">
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-nav-link">
            <x-icon name="store" class="h-[18px] w-[18px] shrink-0" />
            <span class="flex-1">مشاهده فروشگاه</span>
            <x-icon name="external" class="h-3.5 w-3.5 shrink-0 opacity-60" />
        </a>
        <form action="{{ route('logout') }}" method="POST" data-ajax-form data-redirect>
            @csrf
            <button type="submit" class="admin-nav-link w-full text-brand-300 hover:text-brand-200">
                <x-icon name="logout" class="h-[18px] w-[18px] shrink-0" />
                <span class="flex-1 text-start">خروج</span>
            </button>
        </form>
    </div>
</nav>
