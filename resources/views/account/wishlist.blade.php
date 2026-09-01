@extends('layouts.account')

@section('title', 'علاقه‌مندی‌ها')
@section('heading', 'علاقه‌مندی‌های من')
@section('subheading', 'کالاهایی که برای بررسی یا خرید بعدی ذخیره کرده‌اید')

@section('content')
@if($products->isEmpty())
    <div class="rounded-card bg-white shadow-card">
        <x-empty-state icon="heart" title="لیست علاقه‌مندی‌ها خالی است"
                       message="با زدن آیکن قلب روی هر کالا، آن را برای بررسی بعدی ذخیره کنید."
                       :action-url="route('shop.index')" action-label="مشاهده محصولات" />
    </div>
@else
    <div class="grid grid-cols-2 gap-3 stagger sm:grid-cols-3 xl:grid-cols-4">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>

    {{ $products->links() }}
@endif
@endsection
