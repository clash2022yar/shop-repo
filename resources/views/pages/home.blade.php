@extends('layouts.app')

@section('title', digino('site_name', 'دیجی‌نو') . ' | فروشگاه اینترنتی کالای دیجیتال')
@section('meta_description', 'خرید آنلاین موبایل، لپ‌تاپ، لوازم جانبی و کالای دیجیتال با بهترین قیمت، ضمانت اصالت کالا و ارسال سریع از فروشگاه اینترنتی دیجی‌نو.')

@section('hero')
    {{-- ═══════════════════ hero carousel ═══════════════════ --}}
    <section class="container pt-4" aria-label="پیشنهادهای ویژه">
        <div class="relative overflow-hidden rounded-card shadow-card" data-carousel data-carousel-interval="6500">
            <div class="flex" data-carousel-track>
                @forelse($heroBanners as $banner)
                    <a href="{{ $banner->link ?: route('shop.index') }}" data-carousel-slide
                       class="relative block w-full shrink-0 overflow-hidden"
                       style="background-color: {{ $banner->bg_color ?: '#F1F3F6' }}">
                        <picture>
                            @if($banner->mobile_image)
                                <source media="(max-width: 640px)" srcset="{{ asset($banner->mobile_image) }}">
                            @endif
                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}"
                                 class="h-44 w-full object-cover sm:h-64 lg:h-[22rem]"
                                 loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                                 width="1320" height="352">
                        </picture>

                        @if($banner->title || $banner->cta_label)
                            <div class="absolute inset-y-0 start-0 flex max-w-md flex-col justify-center gap-2 bg-gradient-to-l from-black/55 to-transparent p-6 lg:p-12">
                                <h2 class="text-lg font-extrabold leading-8 text-white drop-shadow lg:text-3xl lg:leading-[3rem]">
                                    {{ $banner->title }}
                                </h2>
                                @if($banner->subtitle)
                                    <p class="text-2xs text-white/85 lg:text-sm">{{ $banner->subtitle }}</p>
                                @endif
                                @if($banner->cta_label)
                                    <span class="mt-2 inline-flex w-fit items-center gap-1.5 rounded-pill bg-white px-4 py-2 text-2xs font-bold text-ink-900 transition-transform duration-300 hover:scale-105">
                                        {{ $banner->cta_label }}
                                        <x-icon name="chevron-left" class="h-4 w-4" />
                                    </span>
                                @endif
                            </div>
                        @endif
                    </a>
                @empty
                    <a href="{{ route('shop.index') }}" data-carousel-slide
                       class="flex h-44 w-full shrink-0 items-center justify-center bg-gradient-to-l from-brand-500 to-brand-700 sm:h-64 lg:h-[22rem]">
                        <span class="text-center text-white">
                            <span class="block text-xl font-extrabold lg:text-3xl">به دیجی‌نو خوش آمدید</span>
                            <span class="mt-2 block text-2xs opacity-90 lg:text-sm">هزاران کالای اصل، با بهترین قیمت</span>
                        </span>
                    </a>
                @endforelse
            </div>

            @if($heroBanners->count() > 1)
                <button type="button" data-carousel-prev aria-label="اسلاید قبلی"
                        class="absolute top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/85 text-ink-700 shadow-card backdrop-blur transition-all hover:bg-white hover:text-brand-500"
                        style="inset-inline-end:.75rem">
                    <x-icon name="chevron-right" class="h-5 w-5" />
                </button>
                <button type="button" data-carousel-next aria-label="اسلاید بعدی"
                        class="absolute top-1/2 grid h-10 w-10 -translate-y-1/2 place-items-center rounded-full bg-white/85 text-ink-700 shadow-card backdrop-blur transition-all hover:bg-white hover:text-brand-500"
                        style="inset-inline-start:.75rem">
                    <x-icon name="chevron-left" class="h-5 w-5" />
                </button>

                <div class="absolute bottom-3 start-1/2 flex -translate-x-1/2 items-center gap-1.5">
                    @foreach($heroBanners as $i => $banner)
                        <button type="button" data-carousel-dot aria-label="اسلاید {{ fa_number($i + 1) }}"
                                class="h-1.5 w-4 rounded-pill bg-white/50 transition-all duration-300 data-[active]:w-7 data-[active]:bg-white"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection

@section('content')

    {{-- ═══════════════════ trust badges ═══════════════════ --}}
    <section class="container mt-5" aria-label="مزایای خرید از دیجی‌نو">
        <div class="grid grid-cols-2 gap-3 rounded-card bg-white p-4 shadow-card lg:grid-cols-4" data-reveal>
            @php
                $trust = [
                    ['icon' => 'truck', 'title' => 'ارسال سریع', 'text' => 'تحویل در سریع‌ترین زمان ممکن'],
                    ['icon' => 'shield-check', 'title' => 'ضمانت اصالت', 'text' => 'تضمین اصل بودن تمام کالاها'],
                    ['icon' => 'rotate-left', 'title' => 'هفت روز مهلت بازگشت', 'text' => 'بازگشت کالا بدون قید و شرط'],
                    ['icon' => 'headset', 'title' => 'پشتیبانی ۲۴ ساعته', 'text' => 'همیشه در کنار شما هستیم'],
                ];
            @endphp
            @foreach($trust as $item)
                <div class="group flex items-center gap-3 rounded-field p-2 transition-colors hover:bg-ink-50">
                    <span class="icon-tile transition-transform duration-300 group-hover:-translate-y-1">
                        <x-icon :name="$item['icon']" class="h-6 w-6" />
                    </span>
                    <span class="min-w-0">
                        <span class="block text-2xs font-bold text-ink-800">{{ $item['title'] }}</span>
                        <span class="mt-0.5 block truncate text-[11px] text-ink-500">{{ $item['text'] }}</span>
                    </span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════ category circles ═══════════════════ --}}
    @if($popularCategories->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-categories">
            <div class="rounded-card bg-white p-5 shadow-card" data-reveal>
                <h2 id="home-categories" class="mb-5 text-center text-base font-extrabold text-ink-900">
                    دسته‌بندی‌های پرطرفدار
                </h2>

                <div class="grid grid-cols-3 gap-4 stagger sm:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8">
                    @foreach($popularCategories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}"
                           class="group flex flex-col items-center gap-2.5 text-center">
                            <span class="grid h-20 w-20 place-items-center overflow-hidden rounded-full bg-ink-50 ring-1 ring-ink-100 transition-all duration-300 group-hover:-translate-y-1.5 group-hover:bg-brand-50 group-hover:ring-brand-200">
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy">
                                @else
                                    <x-icon :name="$category->icon ?: 'box'" class="h-9 w-9 text-ink-400 transition-colors group-hover:text-brand-500" />
                                @endif
                            </span>
                            <span class="text-2xs font-medium leading-5 text-ink-700 transition-colors group-hover:text-brand-500">
                                {{ $category->name }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ special offers ═══════════════════ --}}
    @if($specialOffers->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-special">
            <div class="overflow-hidden rounded-card bg-gradient-to-l from-brand-600 to-brand-500 p-4 shadow-card" data-reveal>
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                    <h2 id="home-special" class="flex items-center gap-2.5 text-base font-extrabold text-white">
                        <x-icon name="percent" class="h-6 w-6" />
                        پیشنهاد شگفت‌انگیز
                    </h2>

                    <div class="flex items-center gap-3">
                        {{-- offers reset at midnight --}}
                        <div class="ltr flex items-center gap-1 rounded-pill bg-white/15 px-3 py-1.5 text-white backdrop-blur"
                             data-countdown="{{ now()->endOfDay()->toIso8601String() }}" aria-label="زمان باقی‌مانده تا پایان پیشنهاد">
                            <span class="grid h-6 w-7 place-items-center rounded bg-white/20 text-2xs font-bold" data-cd-seconds>۰۰</span>
                            <span class="text-2xs">:</span>
                            <span class="grid h-6 w-7 place-items-center rounded bg-white/20 text-2xs font-bold" data-cd-minutes>۰۰</span>
                            <span class="text-2xs">:</span>
                            <span class="grid h-6 w-7 place-items-center rounded bg-white/20 text-2xs font-bold" data-cd-hours>۰۰</span>
                        </div>

                        <a href="{{ route('shop.special') }}" class="hidden items-center gap-1 rounded-pill bg-white px-4 py-1.5 text-2xs font-bold text-brand-600 transition-transform hover:scale-105 sm:inline-flex">
                            مشاهده همه
                            <x-icon name="chevron-left" class="h-4 w-4" />
                        </a>
                    </div>
                </div>

                <x-rail>
                    @foreach($specialOffers as $product)
                        <x-product-card :product="$product" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                    @endforeach

                    <a href="{{ route('shop.special') }}"
                       class="flex w-[11.5rem] shrink-0 flex-col items-center justify-center gap-3 rounded-card bg-white/10 text-white transition-colors hover:bg-white/20 sm:w-[13rem]">
                        <x-icon name="arrow-left" class="h-8 w-8" />
                        <span class="text-2xs font-bold">مشاهده همه پیشنهادها</span>
                    </a>
                </x-rail>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ promo banners ═══════════════════ --}}
    @if($promoBanners->isNotEmpty())
        <section class="container mt-6" aria-label="بنرهای تبلیغاتی">
            <div class="grid gap-4 sm:grid-cols-2" data-reveal>
                @foreach($promoBanners->take(2) as $banner)
                    <a href="{{ $banner->link ?: route('shop.index') }}"
                       class="group relative overflow-hidden rounded-card shadow-card">
                        <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}"
                             class="h-36 w-full object-cover transition-transform duration-700 group-hover:scale-105 lg:h-48"
                             loading="lazy" width="640" height="192">
                        @if($banner->title)
                            <span class="absolute inset-y-0 start-0 flex flex-col justify-center gap-1 bg-gradient-to-l from-black/45 to-transparent p-5">
                                <span class="text-sm font-extrabold text-white lg:text-lg">{{ $banner->title }}</span>
                                @if($banner->subtitle)
                                    <span class="text-2xs text-white/85">{{ $banner->subtitle }}</span>
                                @endif
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ═══════════════════ best sellers ═══════════════════ --}}
    @if($bestSellers->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-best">
            <div class="section" data-reveal>
                <x-section-header title="پرفروش‌ترین کالاها" icon="trend-up" :more-url="route('shop.index', ['sort' => 'bestselling'])" />
                <x-rail>
                    @foreach($bestSellers as $product)
                        <x-product-card :product="$product" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                    @endforeach
                </x-rail>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ strip banner ═══════════════════ --}}
    @if($stripBanner)
        <section class="container mt-6" aria-label="بنر تبلیغاتی">
            <a href="{{ $stripBanner->link ?: route('shop.index') }}" class="group block overflow-hidden rounded-card shadow-card" data-reveal>
                <img src="{{ asset($stripBanner->image) }}" alt="{{ $stripBanner->title }}"
                     class="h-24 w-full object-cover transition-transform duration-700 group-hover:scale-[1.03] lg:h-32"
                     loading="lazy" width="1320" height="128">
            </a>
        </section>
    @endif

    {{-- ═══════════════════ new arrivals ═══════════════════ --}}
    @if($newArrivals->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-new">
            <div class="section" data-reveal>
                <x-section-header title="جدیدترین کالاها" icon="gift" :more-url="route('shop.index', ['sort' => 'newest'])" />
                <x-rail>
                    @foreach($newArrivals as $product)
                        <x-product-card :product="$product" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                    @endforeach
                </x-rail>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ biggest discounts ═══════════════════ --}}
    @if($mostDiscounted->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-discount">
            <div class="section" data-reveal>
                <x-section-header title="بیشترین تخفیف‌ها" icon="tag" :more-url="route('shop.index', ['sort' => 'discount'])" />
                <x-rail>
                    @foreach($mostDiscounted as $product)
                        <x-product-card :product="$product" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                    @endforeach
                </x-rail>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ brands ═══════════════════ --}}
    @if($featuredBrands->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-brands">
            <div class="section" data-reveal>
                <x-section-header title="خرید بر اساس برند" icon="tag" :more-url="route('brands.index')" more-label="همه برندها" />

                <div class="grid grid-cols-3 gap-3 stagger sm:grid-cols-4 lg:grid-cols-6">
                    @foreach($featuredBrands as $brand)
                        <a href="{{ route('brands.show', $brand->slug) }}"
                           class="group flex h-20 items-center justify-center rounded-card border border-ink-100 bg-white p-3 transition-all duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-card-hover">
                            @if($brand->logo)
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}"
                                     class="max-h-10 w-auto opacity-70 transition-opacity duration-300 group-hover:opacity-100" loading="lazy">
                            @else
                                <span class="ltr text-sm font-extrabold text-ink-500 transition-colors group-hover:text-brand-500">
                                    {{ $brand->name_en ?: $brand->name }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ magazine ═══════════════════ --}}
    @if($posts->isNotEmpty())
        <section class="container mt-6" aria-labelledby="home-blog">
            <div class="section" data-reveal>
                <x-section-header title="مجله دیجی‌نو" icon="newspaper" :more-url="route('blog.index')" more-label="همه مطالب" />

                <div class="grid gap-4 stagger sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts as $post)
                        <article class="group overflow-hidden rounded-card border border-ink-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden">
                                <img src="{{ asset($post->cover ?: 'images/placeholder-product.svg') }}" alt="{{ $post->title }}"
                                     class="h-40 w-full object-cover transition-transform duration-700 group-hover:scale-105"
                                     loading="lazy" width="420" height="160">
                            </a>
                            <div class="p-4">
                                <h3 class="line-clamp-2 text-sm font-bold leading-7 text-ink-900">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="transition-colors hover:text-brand-500">{{ $post->title }}</a>
                                </h3>
                                <p class="mt-2 line-clamp-2 text-2xs leading-6 text-ink-500">{{ $post->excerpt }}</p>
                                <div class="mt-3 flex items-center gap-4 text-[11px] text-ink-400">
                                    <span class="flex items-center gap-1"><x-icon name="clock" class="h-3.5 w-3.5" /> {{ fa_number($post->read_minutes ?: 5) }} دقیقه مطالعه</span>
                                    <span class="flex items-center gap-1"><x-icon name="calendar" class="h-3.5 w-3.5" /> {{ jalali($post->published_at) }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ═══════════════════ about strip ═══════════════════ --}}
    <section class="container mt-6" aria-labelledby="home-about">
        <div class="section" data-reveal>
            <h2 id="home-about" class="section-title mb-3">فروشگاه اینترنتی دیجی‌نو، تجربه‌ای مطمئن از خرید آنلاین</h2>
            <div class="space-y-3 text-[0.8125rem] leading-8 text-ink-600">
                <p>
                    دیجی‌نو با هدف ساده‌تر کردن خرید کالای دیجیتال راه‌اندازی شده است؛ جایی که می‌توانید
                    هزاران کالا از برندهای معتبر داخلی و بین‌المللی را با قیمت شفاف، مقایسه دقیق مشخصات فنی و
                    دیدگاه‌های واقعی خریداران بررسی کنید و با خیال راحت انتخاب کنید.
                </p>
                <p>
                    تمام کالاهای موجود در دیجی‌نو دارای ضمانت اصالت هستند و پیش از ارسال، از نظر سلامت فیزیکی
                    بررسی می‌شوند. اگر پس از دریافت سفارش نظرتان تغییر کرد، تا هفت روز فرصت دارید کالا را
                    بدون نیاز به توضیح اضافه بازگردانید.
                </p>
            </div>
            <a href="{{ route('about') }}" class="btn-link mt-3">
                بیشتر درباره دیجی‌نو بخوانید
                <x-icon name="chevron-left" class="h-4 w-4" />
            </a>
        </div>
    </section>
@endsection
