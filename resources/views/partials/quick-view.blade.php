{{-- Quick-view modal body --}}
<div class="grid gap-6 md:grid-cols-2">
    <div>
        <div class="mb-3 aspect-square overflow-hidden rounded-card bg-ink-50">
            <img src="{{ asset($product->primary_image) }}" alt="{{ $product->name }}"
                 class="h-full w-full object-contain" id="qv-main">
        </div>
        @if($product->images->count() > 1)
            <div class="flex gap-2 overflow-x-auto scrollbar-none">
                @foreach($product->images->take(5) as $image)
                    <button type="button" onclick="document.getElementById('qv-main').src='{{ asset($image->path) }}'"
                            class="h-14 w-14 shrink-0 overflow-hidden rounded-lg border border-ink-200 p-1 transition-all hover:border-brand-400">
                        <img src="{{ asset($image->path) }}" alt="" class="h-full w-full object-contain">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    <div class="flex flex-col">
        @if($product->brand)
            <span class="text-2xs text-ink-400">{{ $product->brand->name }}</span>
        @endif
        <h3 class="mt-1 text-lg font-extrabold leading-8 text-ink-900">{{ $product->name }}</h3>
        @if($product->subtitle)
            <p class="mt-1 text-2xs leading-6 text-ink-500">{{ $product->subtitle }}</p>
        @endif

        <div class="mt-3 flex items-center gap-3">
            <x-stars :rating="$product->rating" :count="$product->reviews_count" />
            <span class="badge {{ $product->stock > 0 ? 'badge-green' : 'badge-gray' }}">{{ $product->stock_label }}</span>
        </div>

        @if($product->attributes->isNotEmpty())
            <ul class="mt-4 space-y-1.5 text-2xs">
                @foreach($product->attributes->take(4) as $attr)
                    <li class="flex items-start gap-2 text-ink-600">
                        <x-icon name="check" class="mt-0.5 h-3.5 w-3.5 shrink-0 text-success-500" />
                        <span><span class="text-ink-500">{{ $attr->name }}:</span> {{ $attr->value }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-auto pt-5">
            <x-price :amount="$product->price" :old="$product->has_discount ? $product->compare_at_price : null"
                     :discount="$product->discount_percent" size="lg" align="start" />

            <div class="mt-4 flex gap-2">
                <button type="button" data-add-to-cart="{{ $product->id }}" class="btn-primary flex-1"
                        {{ $product->stock <= 0 ? 'disabled' : '' }}>
                    <x-icon name="cart" class="h-5 w-5" />
                    {{ $product->stock > 0 ? 'افزودن به سبد خرید' : 'ناموجود' }}
                </button>
                <a href="{{ route('products.show', $product->slug) }}" class="btn-ghost">
                    جزئیات کامل
                    <x-icon name="chevron-left" class="h-4 w-4" />
                </a>
            </div>
        </div>
    </div>
</div>
