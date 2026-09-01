@extends('layouts.account')

@section('title', 'بازدیدهای اخیر')
@section('heading', 'بازدیدهای اخیر')
@section('subheading', 'آخرین کالاهایی که در دیجی‌نو مشاهده کرده‌اید')

@section('content')
@if($products->isEmpty())
    <div class="rounded-card bg-white shadow-card">
        <x-empty-state icon="clock" title="هنوز کالایی مشاهده نکرده‌اید"
                       message="کالاهایی که ببینید اینجا ذخیره می‌شوند تا بعداً راحت پیدایشان کنید."
                       :action-url="route('shop.index')" action-label="مشاهده محصولات" />
    </div>
@else
    <div class="grid grid-cols-2 gap-3 stagger sm:grid-cols-3 xl:grid-cols-4">
        @foreach($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
@endif
@endsection
