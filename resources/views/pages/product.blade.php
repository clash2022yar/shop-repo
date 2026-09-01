@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name) . ' | خرید و قیمت از دیجی‌نو')
@section('meta_description', $product->meta_description ?: ($product->subtitle ?: mb_substr(strip_tags($product->short_description ?? ''), 0, 160)))
@section('og_type', 'product')
@section('og_image', asset($product->primary_image))

@push('head')
    {{-- Structured data helps search engines show price & rating. --}}
    <script type="application/ld+json">
        @json([
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'image' => [asset($product->primary_image)],
            'description' => strip_tags((string) $product->short_description),
            'sku' => $product->sku,
            'brand' => ['@type' => 'Brand', 'name' => $product->brand?->name],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'IRR',
                'price' => $product->price,
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
            'aggregateRating' => $product->reviews_count > 0 ? [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->rating,
                'reviewCount' => $product->reviews_count,
            ] : null,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    </script>
@endpush

@section('content')
<div class="container" data-product-page data-product-slug="{{ $product->slug }}">

    <div class="rounded-card bg-white p-4 shadow-card lg:p-6">
        <div class="grid gap-6 lg:grid-cols-[22rem_minmax(0,1fr)] xl:grid-cols-[26rem_minmax(0,1fr)_17rem]">

            {{-- ══════════════ gallery ══════════════ --}}
            <div class="lg:sticky lg:top-24 lg:self-start">
                <div class="relative">
                    {{-- action rail --}}
                    <div class="absolute top-2 z-10 flex flex-col gap-1.5" style="inset-inline-start:.5rem">
                        <button type="button" data-wishlist-toggle="{{ $product->id }}"
                                data-active="{{ $isWished ?? false ? '1' : '0' }}"
                                class="wish-btn static" aria-label="افزودن به علاقه‌مندی‌ها">
                            <x-icon name="heart" class="h-5 w-5" />
                        </button>
                        <button type="button" data-copy="{{ url()->current() }}"
                                class="grid h-9 w-9 place-items-center rounded-full bg-white text-ink-500 shadow-card ring-1 ring-ink-100 transition-colors hover:text-brand-500"
                                aria-label="کپی نشانی محصول">
                            <x-icon name="share" class="h-[18px] w-[18px]" />
                        </button>
                        <a href="{{ route('compare', ['ids' => $product->id]) }}"
                           class="grid h-9 w-9 place-items-center rounded-full bg-white text-ink-500 shadow-card ring-1 ring-ink-100 transition-colors hover:text-brand-500"
                           aria-label="افزودن به مقایسه">
                            <x-icon name="scale" class="h-[18px] w-[18px]" />
                        </a>
                    </div>

                    <div class="aspect-square overflow-hidden rounded-card bg-white">
                        <img src="{{ asset($product->primary_image) }}" alt="{{ $product->name }}"
                             data-gallery-main width="520" height="520" fetchpriority="high"
                             class="h-full w-full object-contain transition-opacity duration-200">
                    </div>

                    @if($product->images->count() > 1)
                        <button type="button" data-gallery-prev aria-label="تصویر قبلی"
                                class="absolute top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink-600 shadow-card transition-colors hover:text-brand-500"
                                style="inset-inline-end:.5rem">
                            <x-icon name="chevron-right" class="h-5 w-5" />
                        </button>
                        <button type="button" data-gallery-next aria-label="تصویر بعدی"
                                class="absolute top-1/2 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full bg-white/90 text-ink-600 shadow-card transition-colors hover:text-brand-500"
                                style="inset-inline-start:.5rem">
                            <x-icon name="chevron-left" class="h-5 w-5" />
                        </button>
                    @endif
                </div>

                {{-- thumbnails --}}
                @if($product->images->count() > 1)
                    @php $thumbs = $product->images->take(6); $overflow = $product->images->count() - 6; @endphp
                    <div class="mt-3 flex items-center gap-2">
                        @foreach($thumbs as $image)
                            <button type="button" data-gallery-thumb="{{ asset($image->path) }}" @if($loop->first) data-active @endif
                                    class="h-14 w-14 shrink-0 overflow-hidden rounded-lg border border-ink-200 bg-white p-1 transition-all duration-200 hover:border-brand-300 data-[active]:border-brand-500 data-[active]:ring-1 data-[active]:ring-brand-200"
                                    aria-label="تصویر {{ fa_number($loop->iteration) }}">
                                <img src="{{ asset($image->path) }}" alt="" class="h-full w-full object-contain" loading="lazy">
                            </button>
                        @endforeach

                        @if($overflow > 0)
                            <span class="grid h-14 w-14 shrink-0 place-items-center rounded-lg bg-ink-100 text-2xs font-bold text-ink-600">
                                +{{ fa_number($overflow) }}
                            </span>
                        @endif
                    </div>
                @endif

                {{-- key specs strip --}}
                @php $keySpecs = $product->attributes->where('is_key', true)->take(4); @endphp
                @if($keySpecs->isNotEmpty())
                    <div class="mt-4 grid grid-cols-4 gap-1 rounded-card bg-ink-50 p-3">
                        @foreach($keySpecs as $spec)
                            <div class="flex flex-col items-center gap-1.5 text-center">
                                <x-icon :name="$spec->icon ?? 'info'" class="h-5 w-5 text-ink-500" />
                                <span class="text-[10px] text-ink-400">{{ $spec->name }}</span>
                                <span class="line-clamp-1 text-[11px] font-bold text-ink-700">{{ $spec->value }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ══════════════ info ══════════════ --}}
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    @if($product->brand)
                        <a href="{{ route('brands.show', $product->brand->slug) }}" class="text-2xs text-ink-500 transition-colors hover:text-brand-500">
                            {{ $product->brand->name }}
                        </a>
                        <span class="text-ink-300">|</span>
                    @endif
                    @if($product->category)
                        <a href="{{ route('categories.show', $product->category->slug) }}" class="text-2xs text-ink-500 transition-colors hover:text-brand-500">
                            {{ $product->category->name }}
                        </a>
                    @endif
                    @if($product->is_special)
                        <span class="badge-red">فروش ویژه</span>
                    @endif
                </div>

                <h1 class="mt-2 text-lg font-extrabold leading-8 text-ink-900 lg:text-xl lg:leading-9">{{ $product->name }}</h1>

                @if($product->name_en)
                    <p class="ltr mt-1 text-2xs text-ink-400">{{ $product->name_en }}</p>
                @endif

                <div class="mt-3 flex flex-wrap items-center gap-4 border-b border-ink-100 pb-4">
                    <a href="#reviews" data-tab-jump="reviews" class="flex items-center gap-2">
                        <x-stars :rating="$product->rating" :show-value="true" />
                        <span class="text-2xs text-ink-500">({{ fa_number($product->reviews_count) }} دیدگاه)</span>
                    </a>
                    <span class="flex items-center gap-1 text-2xs text-ink-500">
                        <x-icon name="chat" class="h-4 w-4 text-ink-400" />
                        {{ fa_number($product->questions_count ?? 0) }} پرسش
                    </span>
                    <span class="flex items-center gap-1 text-2xs text-ink-500">
                        <x-icon name="eye" class="h-4 w-4 text-ink-400" />
                        {{ fa_number($product->views_count ?? 0) }} بازدید
                    </span>
                    <span class="ltr text-2xs text-ink-400">کد کالا: {{ fa_number($product->sku) }}</span>
                </div>

                {{-- variants --}}
                <input type="hidden" data-variant-id value="{{ $product->variants->first()->id ?? '' }}">

                @php $colorVariants = $product->variants->whereNotNull('color_hex'); @endphp
                @if($colorVariants->isNotEmpty())
                    <div class="mt-4">
                        <p class="mb-2.5 text-2xs text-ink-600">
                            رنگ: <span class="font-bold text-ink-900" data-variant-label="color">{{ $colorVariants->first()->color_name }}</span>
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($colorVariants as $variant)
                                <button type="button" data-variant-option="{{ $variant->id }}" data-variant-group="color"
                                        data-variant-label="{{ $variant->color_name }}" @if($loop->first) data-active @endif
                                        class="group flex items-center gap-2 rounded-pill border border-ink-200 px-3 py-1.5 transition-all duration-200 hover:border-brand-300 data-[active]:border-brand-500 data-[active]:bg-brand-50 {{ $variant->stock <= 0 ? 'opacity-40' : '' }}"
                                        {{ $variant->stock <= 0 ? 'disabled' : '' }}
                                        title="{{ $variant->color_name }}">
                                    <span class="h-4 w-4 rounded-full ring-1 ring-ink-200" style="background-color: {{ $variant->color_hex }}"></span>
                                    <span class="text-2xs text-ink-700">{{ $variant->color_name }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php $optionVariants = $product->variants->whereNull('color_hex')->whereNotNull('option_value'); @endphp
                @if($optionVariants->isNotEmpty())
                    <div class="mt-4">
                        <p class="mb-2.5 text-2xs text-ink-600">
                            {{ $optionVariants->first()->option_name }}:
                            <span class="font-bold text-ink-900" data-variant-label="option">{{ $optionVariants->first()->option_value }}</span>
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($optionVariants as $variant)
                                <button type="button" data-variant-option="{{ $variant->id }}" data-variant-group="option"
                                        data-variant-label="{{ $variant->option_value }}"
                                        class="rounded-field border border-ink-200 px-4 py-2 text-2xs text-ink-700 transition-all duration-200 hover:border-brand-300 data-[active]:border-brand-500 data-[active]:bg-brand-50 data-[active]:font-bold data-[active]:text-brand-600 {{ $variant->stock <= 0 ? 'opacity-40' : '' }}"
                                        {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $variant->option_value }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- highlights --}}
                @if(filled($product->highlights))
                    <div class="mt-5 rounded-card bg-ink-50 p-4">
                        <p class="mb-2.5 text-2xs font-bold text-ink-800">ویژگی‌های شاخص</p>
                        <ul class="grid gap-2 sm:grid-cols-2">
                            @foreach($product->highlights as $highlight)
                                <li class="flex items-start gap-2 text-2xs leading-6 text-ink-600">
                                    <x-icon name="check-circle" class="mt-0.5 h-4 w-4 shrink-0 text-success-500" />
                                    {{ $highlight }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- key specs table --}}
                @php $mainSpecs = $product->attributes->where('is_key', true); @endphp
                @if($mainSpecs->isNotEmpty())
                    <div class="mt-5">
                        <p class="mb-2.5 text-2xs font-bold text-ink-800">مشخصات کلیدی</p>
                        <dl class="grid gap-x-6 gap-y-2 sm:grid-cols-2">
                            @foreach($mainSpecs as $spec)
                                <div class="flex items-baseline gap-2 border-b border-dashed border-ink-100 pb-2">
                                    <dt class="shrink-0 text-2xs text-ink-500">{{ $spec->name }}</dt>
                                    <span class="flex-1 border-b border-dotted border-ink-200"></span>
                                    <dd class="text-2xs font-medium text-ink-800">{{ $spec->value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        <button type="button" data-tab-jump="specs" class="btn-link mt-3">
                            مشاهده همه مشخصات فنی
                            <x-icon name="chevron-left" class="h-4 w-4" />
                        </button>
                    </div>
                @endif
            </div>

            {{-- ══════════════ buy box ══════════════ --}}
            <aside class="xl:sticky xl:top-24 xl:self-start">
                <div class="rounded-card border border-ink-100 bg-white p-4 shadow-card">

                    <div class="mb-3 flex items-center gap-2 text-2xs">
                        <x-icon name="store" class="h-5 w-5 text-ink-400" />
                        <span class="text-ink-500">فروشنده:</span>
                        <span class="font-bold text-ink-800">{{ $product->is_digino_seller ? 'دیجی‌نو' : 'فروشنده منتخب' }}</span>
                    </div>

                    <ul class="mb-4 space-y-2.5 border-y border-ink-100 py-3.5">
                        @if($product->warranty)
                            <li class="flex items-center gap-2 text-2xs text-ink-600">
                                <x-icon name="shield-check" class="h-[18px] w-[18px] shrink-0 text-ink-400" />
                                {{ $product->warranty }}
                            </li>
                        @endif
                        <li class="flex items-center gap-2 text-2xs text-ink-600">
                            <x-icon name="rotate-left" class="h-[18px] w-[18px] shrink-0 text-ink-400" />
                            هفت روز ضمانت بازگشت کالا
                        </li>
                        @if($product->free_shipping)
                            <li class="flex items-center gap-2 text-2xs text-success-600">
                                <x-icon name="truck" class="h-[18px] w-[18px] shrink-0" />
                                ارسال رایگان این کالا
                            </li>
                        @endif
                        @if($product->has_pickup)
                            <li class="flex items-center gap-2 text-2xs text-ink-600">
                                <x-icon name="store" class="h-[18px] w-[18px] shrink-0 text-ink-400" />
                                امکان تحویل حضوری
                            </li>
                        @endif
                    </ul>

                    {{-- stock --}}
                    <div class="mb-3">
                        <span data-product-stock class="{{ $product->stock > 0 ? 'badge-green' : 'badge-gray' }}">
                            {{ $product->stock_label }}
                        </span>
                    </div>

                    {{-- price --}}
                    <div class="mb-4">
                        @if($product->has_discount)
                            <div class="mb-1 flex items-center justify-between">
                                <span class="chip-discount">{{ fa_number($product->discount_percent) }}٪ تخفیف</span>
                                <span class="price-old">{{ fa_number(toman($product->compare_at_price)) }}</span>
                            </div>
                        @endif

                        <div class="flex items-baseline justify-end gap-1.5">
                            <span data-product-price class="text-2xl font-extrabold text-ink-900">{{ fa_number(toman($product->price)) }}</span>
                            <span class="text-2xs font-medium text-ink-600">تومان</span>
                        </div>

                        @if($product->has_discount)
                            <p class="mt-1 text-end text-[11px] text-success-600">
                                {{ fa_number(toman($product->savings)) }} تومان سود شما از این خرید
                            </p>
                        @endif

                        <p class="mt-2 flex items-center justify-end gap-1 text-[11px] text-ink-500">
                            <x-icon name="credit-card" class="h-3.5 w-3.5" />
                            اقساط از ماهانه <span data-product-installment class="font-bold text-ink-700">{{ fa_number(toman($product->installment)) }}</span> تومان
                        </p>
                    </div>

                    {{-- qty + CTA --}}
                    @if($product->stock > 0)
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <label for="qty" class="text-2xs text-ink-600">تعداد</label>
                            <select id="qty" data-qty-input class="field-sm w-24">
                                @for($i = 1; $i <= min($product->stock, $product->max_per_order ?: config('digino.cart.max_qty_per_item')); $i++)
                                    <option value="{{ $i }}">{{ fa_number($i) }}</option>
                                @endfor
                            </select>
                        </div>
                    @endif

                    <button type="button" data-product-add data-add-to-cart="{{ $product->id }}" data-use-variant
                            class="btn-primary btn-lg w-full" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <x-icon name="cart" class="h-5 w-5" />
                        {{ $product->stock > 0 ? 'افزودن به سبد خرید' : 'ناموجود' }}
                    </button>

                    @if($product->stock <= 0)
                        <p class="mt-2.5 text-center text-[11px] leading-6 text-ink-500">
                            این کالا در حال حاضر موجود نیست. می‌توانید آن را به علاقه‌مندی‌ها اضافه کنید تا موجودی را پیگیری کنیم.
                        </p>
                    @endif
                </div>
            </aside>
        </div>
    </div>

    {{-- ══════════════ tabs ══════════════ --}}
    <div class="mt-5 rounded-card bg-white shadow-card" data-tabs id="product-tabs">
        <div class="scrollbar-none overflow-x-auto border-b border-ink-100" role="tablist" aria-label="اطلاعات محصول">
            <div class="flex min-w-max">
                @php
                    $tabs = [
                        ['id' => 'description', 'label' => 'معرفی محصول', 'icon' => 'info'],
                        ['id' => 'specs', 'label' => 'مشخصات فنی', 'icon' => 'list'],
                        ['id' => 'reviews', 'label' => 'نظرات کاربران', 'icon' => 'chat', 'count' => $product->reviews_count],
                        ['id' => 'questions', 'label' => 'پرسش و پاسخ', 'icon' => 'question', 'count' => $product->questions_count ?? 0],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    <button type="button" role="tab" id="tab-{{ $tab['id'] }}" aria-controls="panel-{{ $tab['id'] }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}" class="tab"
                            data-tab-hash="{{ $tab['id'] }}"
                            @unless($loop->first) data-tab-url="{{ route('ajax.catalog.tab', [$product->slug, $tab['id']]) }}" @endunless>
                        <x-icon :name="$tab['icon']" class="h-4 w-4" />
                        {{ $tab['label'] }}
                        @if(!empty($tab['count']))
                            <span class="badge-gray">{{ fa_number($tab['count']) }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <div class="p-5">
            <div id="panel-description" role="tabpanel" aria-labelledby="tab-description">
                @include('partials.product-tab-description', ['product' => $product])
            </div>
            <div id="panel-specs" role="tabpanel" aria-labelledby="tab-specs" class="hidden"></div>
            <div id="panel-reviews" role="tabpanel" aria-labelledby="tab-reviews" class="hidden"></div>
            <div id="panel-questions" role="tabpanel" aria-labelledby="tab-questions" class="hidden"></div>
        </div>
    </div>

    {{-- ══════════════ related ══════════════ --}}
    @if($related->isNotEmpty())
        <section class="mt-5 section" data-reveal>
            <x-section-header title="کالاهای مشابه" icon="layers"
                              :more-url="route('categories.show', $product->category->slug)" />
            <x-rail>
                @foreach($related as $item)
                    <x-product-card :product="$item" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                @endforeach
            </x-rail>
        </section>
    @endif

    @if($sameBrand->isNotEmpty() && $product->brand)
        <section class="mt-5 section" data-reveal>
            <x-section-header :title="'سایر محصولات ' . $product->brand->name" icon="tag"
                              :more-url="route('brands.show', $product->brand->slug)" />
            <x-rail>
                @foreach($sameBrand as $item)
                    <x-product-card :product="$item" class="w-[11.5rem] shrink-0 sm:w-[13rem]" />
                @endforeach
            </x-rail>
        </section>
    @endif
</div>

{{-- ══════════════ sticky mobile buy bar ══════════════ --}}
<div data-sticky-bar
     class="fixed inset-x-0 bottom-14 z-40 translate-y-full border-t border-ink-200 bg-white p-3 opacity-0 shadow-pop transition-all duration-300 ease-out-soft lg:bottom-0">
    <div class="container flex items-center gap-3">
        <img src="{{ asset($product->primary_image) }}" alt="" class="hidden h-11 w-11 rounded object-contain sm:block">
        <div class="min-w-0 flex-1">
            <p class="line-clamp-1 text-2xs font-bold text-ink-800">{{ $product->name }}</p>
            <p class="mt-0.5 text-2xs text-ink-700">
                <span class="font-extrabold">{{ fa_number(toman($product->price)) }}</span>
                <span class="text-[11px] text-ink-500">تومان</span>
            </p>
        </div>
        <button type="button" data-add-to-cart="{{ $product->id }}" data-use-variant class="btn-primary shrink-0"
                {{ $product->stock <= 0 ? 'disabled' : '' }}>
            <x-icon name="cart" class="h-5 w-5" />
            <span class="hidden sm:inline">افزودن به سبد</span>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // "See all specs" / "jump to reviews" links activate the matching tab.
    document.addEventListener('dg:ready', function () {
        document.querySelectorAll('[data-tab-jump]').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                var tab = document.getElementById('tab-' + el.dataset.tabJump);
                if (!tab) return;
                tab.click();
                document.getElementById('product-tabs').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    });
</script>
@endpush
