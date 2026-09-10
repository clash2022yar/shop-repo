@props(['product'])
<div class="bg-white border border-gray-100 rounded-xl p-3 hover:shadow-xl hover:border-gray-200 hover:-translate-y-1 transition-all duration-300 group">
    <div class="relative">
        @if($product->has_discount)<span class="absolute top-0 left-0 bg-[#ef4056] text-white text-[11px] font-bold px-1.5 py-0.5 rounded">%{{ $product->discount_percent }}</span>@endif
        <button onclick="toggleWishlist(this)" class="absolute top-0 right-0 w-7 h-7 bg-white shadow rounded-full flex items-center justify-center text-gray-300 hover:text-[#ef4056] transition"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></button>
        <div class="h-36 flex items-center justify-center bg-gray-50 rounded-lg overflow-hidden">
            <img src="{{ $product->images[0] ?? '/images/products/product-1.jpg' }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-2 group-hover:scale-105 transition duration-500">
        </div>
    </div>
    <a href="{{ route('product.show', $product->slug) }}" class="block mt-3">
        <div class="text-[12.5px] font-medium leading-5 line-clamp-2 min-h-[40px] group-hover:text-[#ef4056] transition">{{ $product->name }}</div>
        <div class="flex items-center gap-1 mt-2 text-amber-400 text-[11px]">★★★★★ <span class="text-gray-400">({{ $product->reviews_count }})</span></div>
        <div class="mt-2 text-left">
            @if($product->has_discount)<div class="text-[11px] text-gray-400 line-through">{{ number_format($product->price) }}</div>@endif
            <div class="font-bold text-sm">{{ number_format($product->final_price) }} تومان</div>
        </div>
    </a>
    <button onclick="addToCart('{{ $product->name }}', {{ $product->final_price }})" data-product-id="{{ $product->id }}" class="mt-3 w-full h-8 bg-[#f5f5f7] hover:bg-[#ef4056] hover:text-white rounded-lg text-xs font-bold transition">افزودن به سبد</button>
</div>
