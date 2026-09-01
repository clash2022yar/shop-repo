{{--
    The product card used across home, shop, category, search, cart suggestions
    and the account dashboard. Matches the card in the reference design:
    wishlist heart (start), discount / special badge (end), image, brand,
    title, rating, price.
--}}
@props(['product', 'compact' => false, 'showRating' => true])

@php
    $url = route('products.show', $product->slug);
    $img = asset($product->primary_image);
    $wished = auth()->check() && auth()->user()->relationLoaded('wishlists')
        ? auth()->user()->wishlists->contains('product_id', $product->id)
        : false;
@endphp

<article {{ $attributes->merge(['class' => 'product-card group']) }} data-product-id="{{ $product->id }}">

    {{-- wishlist --}}
    <button type="button" class="wish-btn" data-wishlist-toggle="{{ $product->id }}"
            data-active="{{ $wished ? '1' : '0' }}"
            aria-label="افزودن {{ $product->name }} به علاقه‌مندی‌ها">
        <x-icon name="heart" class="h-[18px] w-[18px]" />
    </button>

    {{-- badges --}}
    <div class="absolute top-2 z-10 flex flex-col items-end gap-1" style="inset-inline-end:.5rem">
        @if($product->discount_percent > 0)
            <span class="chip-discount">{{ fa_number($product->discount_percent) }}٪</span>
        @endif
        @if($product->is_special)
            <span class="badge-green whitespace-nowrap">فروش ویژه</span>
        @endif
        @if($product->stock <= 0)
            <span class="badge-gray whitespace-nowrap">ناموجود</span>
        @endif
    </div>

    {{-- media --}}
    <a href="{{ $url }}" class="product-card__media" tabindex="-1" aria-hidden="true">
        <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy" decoding="async" width="240" height="240"
             class="{{ $product->stock <= 0 ? 'opacity-50 grayscale' : '' }}">

        {{-- quick view, revealed on hover --}}
        <button type="button" data-quick-view="{{ $product->slug }}"
                class="absolute bottom-2 start-1/2 z-10 -translate-x-1/2 translate-y-4 rounded-pill bg-ink-900/85 px-3 py-1.5 text-2xs font-bold text-white opacity-0 backdrop-blur transition-all duration-300 ease-out-soft group-hover:translate-y-0 group-hover:opacity-100">
            نگاه سریع
        </button>
    </a>

    {{-- body --}}
    <div class="flex flex-1 flex-col">
        @if($product->brand)
            <span class="product-card__brand">{{ $product->brand->name }}</span>
        @endif

        <h3 class="product-card__title">
            <a href="{{ $url }}" class="before:absolute before:inset-0 before:content-['']">{{ $product->name }}</a>
        </h3>

        @if($showRating && $product->reviews_count > 0)
            <x-stars :rating="$product->rating" :count="$product->reviews_count" class="mb-1" />
        @endif

        <div class="product-card__price">
            @if($product->stock > 0)
                <x-price :amount="$product->price"
                         :old="$product->has_discount ? $product->compare_at_price : null"
                         :discount="0" size="base" />
            @else
                <span class="text-xs font-bold text-ink-400">اتمام موجودی</span>
            @endif
        </div>

        @unless($compact)
            <button type="button" data-add-to-cart="{{ $product->id }}"
                    class="relative z-10 mt-3 w-full rounded-field border border-brand-200 bg-brand-50 py-2 text-2xs font-bold text-brand-600 opacity-0 transition-all duration-300 hover:bg-brand-500 hover:text-white group-hover:opacity-100 disabled:cursor-not-allowed disabled:opacity-40 max-lg:opacity-100"
                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                {{ $product->stock > 0 ? 'افزودن به سبد' : 'ناموجود' }}
            </button>
        @endunless
    </div>
</article>
