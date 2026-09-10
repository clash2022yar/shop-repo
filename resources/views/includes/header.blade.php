<header class="sticky top-0 z-40 bg-white shadow-sm">
    <!-- Top Bar -->
    <div class="border-b border-gray-100">
        <div class="max-w-[1680px] mx-auto px-4 lg:px-8 h-[68px] flex items-center gap-4">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 shrink-0 group">
                <div class="w-10 h-10 rounded-xl bg-[#ef4056] flex items-center justify-center text-white relative overflow-hidden group-hover:scale-105 transition duration-300">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span class="absolute -top-1 -left-1 w-3 h-3 bg-yellow-400 rounded-full animate-pulse hidden"></span>
                </div>
                <div class="leading-none">
                    <div class="text-[22px] font-extrabold text-[#ef4056] tracking-tight -mb-1">دیجینو</div>
                    <div class="text-[11px] text-gray-500 font-medium tracking-widest">خرید هوشمند</div>
                </div>
            </a>

            <!-- Search -->
            <div class="flex-1 max-w-[720px] mx-2 lg:mx-6 hidden md:block">
                <form action="/search" method="GET" class="relative group">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="جستجو در محصولات، برندها و دسته‌بندی‌ها..." 
                        class="w-full h-11 bg-[#f0f0f1] group-focus-within:bg-white border border-transparent group-focus-within:border-[#ef4056]/30 group-focus-within:shadow-[0_0_0_3px_rgba(239,64,86,0.08)] rounded-lg pr-11 pl-4 text-[13px] placeholder:text-[#81858b] outline-none transition-all duration-300">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-[#a1a3a8] group-focus-within:text-[#ef4056] transition">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>
                </form>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 mr-auto">
                <a href="/login" class="hidden sm:flex items-center gap-2 h-10 px-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition text-[12.5px] font-medium">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>ورود / ثبت‌نام</span>
                </a>
                <span class="hidden sm:block w-[1px] h-6 bg-gray-200 mx-1"></span>
                <a href="/cart" class="relative flex items-center gap-2 h-10 px-3 rounded-lg hover:bg-gray-50 transition group">
                    <span class="relative">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-[#ef4056] text-white text-[10px] font-bold min-w-[18px] h-[18px] rounded-full flex items-center justify-center px-1 shadow-md group-hover:scale-110 transition">0</span>
                    </span>
                    <span class="hidden lg:inline text-[12.5px] font-medium">سبد خرید</span>
                    <svg class="hidden lg:block w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <!-- Mobile search icon -->
                <button onclick="document.getElementById('mobileSearch').classList.toggle('hidden')" class="md:hidden w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
            </div>
        </div>
        <!-- Mobile Search -->
        <div id="mobileSearch" class="hidden md:hidden px-4 pb-3">
            <form action="/search" class="relative">
                <input name="q" placeholder="جستجو..." class="w-full h-10 bg-[#f0f0f1] rounded-lg pr-10 pl-4 text-sm outline-none focus:bg-white focus:border focus:border-[#ef4056]/30">
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </form>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="hidden lg:block border-b border-gray-100 bg-white">
        <div class="max-w-[1680px] mx-auto px-8 h-10 flex items-center gap-6 text-[12.5px]">
            <button id="megaMenuBtn" class="flex items-center gap-2 font-bold text-[#3f4064] hover:text-[#ef4056] transition shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                دسته‌بندی کالاها
            </button>
            <span class="w-[1px] h-5 bg-gray-200"></span>
            <div class="flex items-center gap-5 overflow-x-auto no-scrollbar">
                <a href="/shop?category=mobile" class="whitespace-nowrap hover:text-[#ef4056] transition flex items-center gap-1">کالای دیجیتال</a>
                <a href="/shop?category=mobile" class="whitespace-nowrap hover:text-[#ef4056] transition">موبایل</a>
                <a href="/shop?category=computer" class="whitespace-nowrap hover:text-[#ef4056] transition">لوازم خانگی</a>
                <a href="/shop?category=laptop" class="whitespace-nowrap hover:text-[#ef4056] transition">کامپیوتر و لپتاپ</a>
                <a href="/shop?category=audio" class="whitespace-nowrap hover:text-[#ef4056] transition">صوتی و تصویری</a>
                <a href="/shop?category=smartwatch" class="whitespace-nowrap hover:text-[#ef4056] transition">ساعت و پوشیدنی</a>
                <a href="/shop?category=gaming" class="whitespace-nowrap hover:text-[#ef4056] transition">گیمینگ</a>
                <a href="/shop" class="whitespace-nowrap hover:text-[#ef4056] transition">خودرو و ابزارآلات</a>
                <a href="/shop?on_sale=1" class="whitespace-nowrap text-[#ef4056] font-semibold flex items-center gap-1">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                    فروش ویژه
                </a>
            </div>
            <a href="#" class="mr-auto flex items-center gap-1 text-[#62666d] hover:text-gray-900 whitespace-nowrap">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                سوپرمارکت
            </a>
        </div>
    </nav>

    <!-- Mega Menu (hidden) -->
    <div id="megaMenu" class="hidden absolute top-[108px] right-0 left-0 bg-white shadow-2xl border-t z-30 max-w-[1680px] mx-auto">
        <div class="flex min-h-[380px]">
            <div class="w-[200px] bg-[#f5f5f5] p-3 space-y-1 text-[13px]">
                <a class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-sm text-[#ef4056] font-medium"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 2v5M8 2v5"/></svg> موبایل</a>
                <a class="flex items-center gap-2 p-2 hover:bg-white rounded-lg transition">لپتاپ</a>
                <a class="flex items-center gap-2 p-2 hover:bg-white rounded-lg transition">تبلت</a>
                <a class="flex items-center gap-2 p-2 hover:bg-white rounded-lg transition">ساعت هوشمند</a>
                <a class="flex items-center gap-2 p-2 hover:bg-white rounded-lg transition">هدفون</a>
            </div>
            <div class="flex-1 p-6 grid grid-cols-4 gap-6 text-sm">
                <div><div class="font-bold mb-3 flex items-center gap-2"><span class="w-1 h-4 bg-[#ef4056] rounded-full"></span>برندها</div><div class="space-y-2 text-[#62666d] pr-3"><div>اپل</div><div>سامسونگ</div><div>شیائومی</div><div>هواوی</div></div></div>
                <div><div class="font-bold mb-3 flex items-center gap-2"><span class="w-1 h-4 bg-[#19bfd3] rounded-full"></span>قیمت</div><div class="space-y-2 text-[#62666d] pr-3"><div>تا 10 میلیون</div><div>10 تا 30 میلیون</div><div>بالای 30 میلیون</div></div></div>
            </div>
        </div>
    </div>
</header>
