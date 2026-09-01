{{-- The product grid, re-rendered on every AJAX filter change. --}}
@if($products->isEmpty())
    <x-empty-state icon="search" title="کالایی با این مشخصات پیدا نشد"
                   message="فیلترها را تغییر دهید یا عبارت دیگری را جستجو کنید."
                   :action-url="route('shop.index')" action-label="مشاهده همه محصولات" />
@else
    <div class="grid grid-cols-2 gap-3 stagger sm:grid-cols-3 xl:grid-cols-4">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
@endif
