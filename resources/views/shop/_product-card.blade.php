<article class="product-card">
 <button class="wish" data-wishlist="{{ route('wishlist.toggle',$product) }}" aria-label="افزودن به علاقه‌مندی"><x-icon name="heart"/></button>@if($product->discount_percent)<span class="discount">٪{{ $product->discount_percent }}</span>@endif
 <a href="{{ route('product',$product) }}"><img class="product-img" src="/{{ $product->image }}" alt="{{ $product->name }}" loading="lazy"><span class="product-brand">{{ $product->brand }}</span><h3>{{ $product->name }}</h3></a>
 <div class="rating"><x-icon name="star"/> {{ number_format($product->rating,1) }} <span>({{ $product->reviews_count }})</span></div>
 <div class="price"><div><strong>{{ number_format($product->price) }} <small>تومان</small></strong>@if($product->old_price)<del>{{ number_format($product->old_price) }}</del>@endif</div><button class="card-add" data-add-cart="{{ route('cart.add',$product) }}" aria-label="افزودن به سبد"><x-icon name="plus"/></button></div>
</article>