{{--
    Catalog filter sidebar — shared by the shop, category, brand and search
    pages. Every control lives inside [data-filter-form]; changing anything
    triggers an AJAX refresh of the grid (see initCatalog in public/js/app.js).
--}}
@php
    $selectedCategories = (array) request('category', []);
    $selectedBrands = (array) request('brand', []);
    $selectedColors = (array) request('color', []);
@endphp

<form data-filter-form class="space-y-3" onsubmit="return false">

    {{-- keep the search term across filter changes --}}
    @if(request()->filled('q'))
        <input type="hidden" name="q" value="{{ request('q') }}">
    @endif
    @if(request()->boolean('special'))
        <input type="hidden" name="special" value="1">
    @endif

    {{-- ─────────── header ─────────── --}}
    <div class="flex items-center justify-between rounded-card bg-white px-4 py-3 shadow-card">
        <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon name="filter" class="h-5 w-5 text-ink-500" />
            فیلترها
        </h2>
        <button type="button" data-clear-filters class="text-2xs font-bold text-brand-500 transition-colors hover:text-brand-600">
            حذف همه
        </button>
    </div>

    {{-- ─────────── quick toggles ─────────── --}}
    <div class="rounded-card bg-white p-4 shadow-card">
        <div class="space-y-3">
            <label class="flex cursor-pointer items-center justify-between gap-2">
                <span class="text-2xs text-ink-700">فقط کالاهای موجود</span>
                <input type="checkbox" name="available" value="1" class="peer sr-only" {{ request()->boolean('available') ? 'checked' : '' }}>
                <span class="switch peer-checked:bg-success-500"><span></span></span>
            </label>

            <label class="flex cursor-pointer items-center justify-between gap-2">
                <span class="text-2xs text-ink-700">فقط کالاهای تخفیف‌دار</span>
                <input type="checkbox" name="discounted" value="1" class="peer sr-only" {{ request()->boolean('discounted') ? 'checked' : '' }}>
                <span class="switch peer-checked:bg-success-500"><span></span></span>
            </label>

            <label class="flex cursor-pointer items-center justify-between gap-2">
                <span class="text-2xs text-ink-700">ارسال رایگان</span>
                <input type="checkbox" name="free_shipping" value="1" class="peer sr-only" {{ request()->boolean('free_shipping') ? 'checked' : '' }}>
                <span class="switch peer-checked:bg-success-500"><span></span></span>
            </label>

            @if(($facets['pickup_count'] ?? 0) > 0)
                <label class="flex cursor-pointer items-center justify-between gap-2">
                    <span class="text-2xs text-ink-700">تحویل حضوری</span>
                    <input type="checkbox" name="pickup" value="1" class="peer sr-only" {{ request()->boolean('pickup') ? 'checked' : '' }}>
                    <span class="switch peer-checked:bg-success-500"><span></span></span>
                </label>
            @endif
        </div>
    </div>

    {{-- ─────────── categories ─────────── --}}
    @if(!empty($facets['categories']))
        <details class="group rounded-card bg-white shadow-card" open>
            <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                <span class="text-2xs font-bold text-ink-900">دسته‌بندی</span>
                <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
            </summary>
            <div class="scrollbar-none max-h-60 space-y-2.5 overflow-y-auto px-4 pb-4">
                @foreach($facets['categories'] as $facet)
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="checkbox" name="category[]" value="{{ $facet['slug'] }}" class="checkbox"
                               {{ in_array($facet['slug'], $selectedCategories) ? 'checked' : '' }}>
                        <span class="flex-1 truncate text-2xs text-ink-700">{{ $facet['name'] }}</span>
                        <span class="text-[10px] text-ink-400">{{ fa_number($facet['count']) }}</span>
                    </label>
                @endforeach
            </div>
        </details>
    @endif

    {{-- ─────────── brands ─────────── --}}
    @if(!empty($facets['brands']))
        <details class="group rounded-card bg-white shadow-card" open>
            <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                <span class="text-2xs font-bold text-ink-900">برند</span>
                <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
            </summary>
            <div class="px-4 pb-4">
                <div class="relative mb-2.5">
                    <input type="text" placeholder="جستجوی برند..." data-no-auto
                           class="field-sm ps-8"
                           oninput="this.closest('details').querySelectorAll('[data-brand-row]').forEach(r=>{r.hidden=!r.dataset.brandRow.includes(this.value.trim())})">
                    <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.625rem" />
                </div>
                <div class="scrollbar-none max-h-52 space-y-2.5 overflow-y-auto">
                    @foreach($facets['brands'] as $facet)
                        <label class="flex cursor-pointer items-center gap-2.5" data-brand-row="{{ $facet['name'] }}">
                            <input type="checkbox" name="brand[]" value="{{ $facet['slug'] }}" class="checkbox"
                                   {{ in_array($facet['slug'], $selectedBrands) ? 'checked' : '' }}>
                            <span class="flex-1 truncate text-2xs text-ink-700">{{ $facet['name'] }}</span>
                            <span class="text-[10px] text-ink-400">{{ fa_number($facet['count']) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </details>
    @endif

    {{-- ─────────── price ─────────── --}}
    <details class="group rounded-card bg-white shadow-card" open>
        <summary class="flex cursor-pointer list-none items-center justify-between p-4">
            <span class="text-2xs font-bold text-ink-900">محدوده قیمت</span>
            <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
        </summary>
        <div class="px-4 pb-4" data-range data-range-min="{{ $facets['price_min'] ?? 0 }}" data-range-max="{{ $facets['price_max'] ?? 100000000 }}">
            <div class="range-track mb-4">
                <span class="range-fill" data-range-fill></span>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <label class="block">
                    <span class="mb-1 block text-[10px] text-ink-500">از (تومان)</span>
                    <input type="text" name="min_price" data-numeric data-price-input data-range-min class="field-sm text-center"
                           value="{{ request('min_price') ? fa_number(number_format((int) en_number(request('min_price')))) : '' }}"
                           placeholder="{{ fa_number(toman($facets['price_min'] ?? 0)) }}">
                </label>
                <label class="block">
                    <span class="mb-1 block text-[10px] text-ink-500">تا (تومان)</span>
                    <input type="text" name="max_price" data-numeric data-price-input data-range-max class="field-sm text-center"
                           value="{{ request('max_price') ? fa_number(number_format((int) en_number(request('max_price')))) : '' }}"
                           placeholder="{{ fa_number(toman($facets['price_max'] ?? 0)) }}">
                </label>
            </div>
        </div>
    </details>

    {{-- ─────────── colors ─────────── --}}
    @if(!empty($facets['colors']))
        <details class="group rounded-card bg-white shadow-card" open>
            <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                <span class="text-2xs font-bold text-ink-900">رنگ</span>
                <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
            </summary>
            <div class="flex flex-wrap gap-2 px-4 pb-4">
                @foreach($facets['colors'] as $color)
                    <label class="cursor-pointer" title="{{ $color['name'] }}">
                        <input type="checkbox" name="color[]" value="{{ $color['hex'] }}" class="peer sr-only"
                               {{ in_array($color['hex'], $selectedColors) ? 'checked' : '' }}>
                        <span class="grid h-8 w-8 place-items-center rounded-full ring-1 ring-ink-200 transition-all peer-checked:ring-2 peer-checked:ring-brand-500 peer-checked:ring-offset-2">
                            <span class="h-5 w-5 rounded-full" style="background-color: {{ $color['hex'] }}"></span>
                        </span>
                        <span class="sr-only">{{ $color['name'] }}</span>
                    </label>
                @endforeach
            </div>
        </details>
    @endif

    {{-- ─────────── rating ─────────── --}}
    @if(!empty($facets['ratings']))
        <details class="group rounded-card bg-white shadow-card">
            <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                <span class="text-2xs font-bold text-ink-900">امتیاز کاربران</span>
                <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
            </summary>
            <div class="space-y-2.5 px-4 pb-4">
                @foreach($facets['ratings'] as $facet)
                    @continue($facet['count'] === 0)
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="radio" name="rating" value="{{ $facet['value'] }}" class="radio"
                               {{ (string) request('rating') === (string) $facet['value'] ? 'checked' : '' }}>
                        <x-stars :rating="$facet['value']" size="h-3.5 w-3.5" />
                        <span class="flex-1 text-2xs text-ink-600">و بالاتر</span>
                        <span class="text-[10px] text-ink-400">{{ fa_number($facet['count']) }}</span>
                    </label>
                @endforeach
            </div>
        </details>
    @endif

    {{-- ─────────── seller ─────────── --}}
    @if(!empty($facets['sellers']))
        <details class="group rounded-card bg-white shadow-card">
            <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                <span class="text-2xs font-bold text-ink-900">فروشنده</span>
                <x-icon name="chevron-down" class="h-4 w-4 text-ink-400 transition-transform group-open:rotate-180" />
            </summary>
            <div class="space-y-2.5 px-4 pb-4">
                @foreach($facets['sellers'] as $seller)
                    @continue($seller['count'] === 0)
                    <label class="flex cursor-pointer items-center gap-2.5">
                        <input type="radio" name="seller" value="{{ $seller['key'] }}" class="radio"
                               {{ request('seller') === $seller['key'] ? 'checked' : '' }}>
                        <span class="flex-1 text-2xs text-ink-700">{{ $seller['label'] }}</span>
                        <span class="text-[10px] text-ink-400">{{ fa_number($seller['count']) }}</span>
                    </label>
                @endforeach
            </div>
        </details>
    @endif
</form>
