{{--
    Shared catalog body: filter sidebar + toolbar + product grid + pagination.
    Used by pages.shop, pages.category, pages.search and brand listings.

    Expects: $products (paginator), $facets (array), optional $categorySlug.
--}}
@php
    $sorts = [
        '' => 'پرفروش‌ترین',
        'newest' => 'جدیدترین',
        'cheapest' => 'ارزان‌ترین',
        'expensive' => 'گران‌ترین',
        'rating' => 'محبوب‌ترین',
        'discount' => 'بیشترین تخفیف',
    ];

    // Human-readable chips for every active filter.
    $chips = [];
    foreach ((array) request('category', []) as $slug) {
        $name = collect($facets['categories'] ?? [])->firstWhere('slug', $slug)['name'] ?? $slug;
        $chips[] = ['label' => $name, 'key' => "category[]={$slug}"];
    }
    foreach ((array) request('brand', []) as $slug) {
        $name = collect($facets['brands'] ?? [])->firstWhere('slug', $slug)['name'] ?? $slug;
        $chips[] = ['label' => $name, 'key' => "brand[]={$slug}"];
    }
    if (request()->filled('min_price')) {
        $chips[] = ['label' => 'از ' . fa_number(toman((int) en_number(request('min_price')))) . ' تومان', 'key' => 'min_price='];
    }
    if (request()->filled('max_price')) {
        $chips[] = ['label' => 'تا ' . fa_number(toman((int) en_number(request('max_price')))) . ' تومان', 'key' => 'max_price='];
    }
    if (request()->boolean('available')) {
        $chips[] = ['label' => 'فقط موجود', 'key' => 'available=1'];
    }
    if (request()->boolean('discounted')) {
        $chips[] = ['label' => 'تخفیف‌دار', 'key' => 'discounted=1'];
    }
    if (request()->boolean('free_shipping')) {
        $chips[] = ['label' => 'ارسال رایگان', 'key' => 'free_shipping=1'];
    }
    if (request()->filled('rating')) {
        $chips[] = ['label' => 'امتیاز ' . fa_number(request('rating')) . ' به بالا', 'key' => 'rating=' . request('rating')];
    }
@endphp

<div class="container" data-catalog @isset($categorySlug) data-category-slug="{{ $categorySlug }}" @endisset>
    <div class="grid gap-5 lg:grid-cols-[16.5rem_minmax(0,1fr)]">

        {{-- ═══════════ sidebar ═══════════ --}}
        <aside class="lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:self-start lg:overflow-y-auto lg:pb-4 scrollbar-none">
            {{-- mobile: filters live in a modal-style drawer --}}
            <details class="group rounded-card bg-white shadow-card lg:hidden">
                <summary class="flex cursor-pointer list-none items-center justify-between p-4">
                    <span class="flex items-center gap-2 text-sm font-bold text-ink-900">
                        <x-icon name="filter" class="h-5 w-5 text-ink-500" />
                        فیلترها
                        @if(count($chips))<span class="badge-red">{{ fa_number(count($chips)) }}</span>@endif
                    </span>
                    <x-icon name="chevron-down" class="h-5 w-5 text-ink-400 transition-transform group-open:rotate-180" />
                </summary>
                <div class="border-t border-ink-100 p-3">
                    @include('partials.catalog-filters')
                </div>
            </details>

            <div class="hidden lg:block">
                @include('partials.catalog-filters')
            </div>
        </aside>

        {{-- ═══════════ results ═══════════ --}}
        <div class="min-w-0">

            {{-- toolbar --}}
            <div class="mb-3 rounded-card bg-white px-4 py-3 shadow-card">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2 overflow-x-auto scrollbar-none">
                        <span class="hidden shrink-0 items-center gap-1.5 text-2xs font-bold text-ink-600 sm:flex">
                            <x-icon name="sort" class="h-4 w-4" />
                            مرتب‌سازی:
                        </span>

                        {{-- desktop: sort pills --}}
                        <div class="hidden items-center gap-1 md:flex">
                            @foreach($sorts as $value => $label)
                                <button type="button" data-page="1"
                                        onclick="document.querySelector('[data-sort-select]').value='{{ $value }}';document.querySelector('[data-sort-select]').dispatchEvent(new Event('change',{bubbles:true}))"
                                        class="whitespace-nowrap rounded-pill px-3 py-1.5 text-2xs font-medium transition-colors {{ (string) request('sort') === (string) $value ? 'bg-brand-50 font-bold text-brand-600' : 'text-ink-600 hover:bg-ink-50' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>

                        {{-- mobile + the real control used by JS --}}
                        <select data-sort-select class="field-sm w-40 md:sr-only md:absolute" aria-label="مرتب‌سازی">
                            @foreach($sorts as $value => $label)
                                <option value="{{ $value }}" @selected((string) request('sort') === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-2xs text-ink-500" data-result-count>
                            {{ fa_number($products->total()) }} کالا
                        </span>

                        <div class="hidden items-center gap-0.5 rounded-field border border-ink-200 p-0.5 sm:flex">
                            <button type="button" data-view-mode="grid" data-active
                                    class="grid h-7 w-7 place-items-center rounded text-ink-500 transition-colors data-[active]:bg-ink-100 data-[active]:text-ink-800"
                                    aria-label="نمایش شبکه‌ای">
                                <x-icon name="grid" class="h-4 w-4" />
                            </button>
                            <button type="button" data-view-mode="list"
                                    class="grid h-7 w-7 place-items-center rounded text-ink-500 transition-colors data-[active]:bg-ink-100 data-[active]:text-ink-800"
                                    aria-label="نمایش فهرستی">
                                <x-icon name="list" class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

                {{-- active filter chips --}}
                @if(count($chips))
                    <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-ink-100 pt-3">
                        @foreach($chips as $chip)
                            <button type="button" data-remove-filter="{{ $chip['key'] }}"
                                    class="inline-flex items-center gap-1.5 rounded-pill bg-ink-100 px-3 py-1 text-2xs text-ink-700 transition-colors hover:bg-brand-50 hover:text-brand-600">
                                {{ $chip['label'] }}
                                <x-icon name="close" class="h-3 w-3" />
                            </button>
                        @endforeach
                        <button type="button" data-clear-filters class="text-2xs font-bold text-brand-500 hover:underline">
                            حذف همه فیلترها
                        </button>
                    </div>
                @endif
            </div>

            {{-- grid --}}
            <div data-product-grid class="transition-opacity duration-200">
                @include('partials.product-grid', ['products' => $products])
            </div>

            {{-- pagination --}}
            <div data-product-pagination>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
