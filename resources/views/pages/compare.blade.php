@extends('layouts.app')

@section('title', 'مقایسه کالاها | دیجی‌نو')
@section('robots', 'noindex, follow')

@section('content')
<div class="container">
    <div class="mb-4 rounded-card bg-white p-5 shadow-card">
        <h1 class="text-lg font-extrabold text-ink-900">مقایسه کالاها</h1>
        <p class="mt-1.5 text-2xs text-ink-500">
            حداکثر {{ fa_number(config('digino.catalog.max_compare')) }} کالا را کنار هم بگذارید و بر اساس مشخصات فنی انتخاب کنید.
        </p>
    </div>

    @if($products->isEmpty())
        <div class="rounded-card bg-white shadow-card">
            <x-empty-state icon="scale" title="کالایی برای مقایسه انتخاب نشده است"
                           message="از صفحهٔ هر کالا، آیکن مقایسه را بزنید تا آن کالا به این صفحه اضافه شود."
                           :action-url="route('shop.index')" action-label="مشاهده محصولات" />
        </div>
    @else
        <div class="overflow-hidden rounded-card bg-white shadow-card">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[40rem] text-2xs">
                    <thead>
                        <tr class="border-b border-ink-100">
                            <th class="w-40 p-4 text-start align-top text-ink-500">کالا</th>
                            @foreach($products as $product)
                                <th class="p-4 align-top">
                                    <a href="{{ route('products.show', $product->slug) }}" class="group block text-center">
                                        <img src="{{ asset($product->primary_image) }}" alt="{{ $product->name }}"
                                             class="mx-auto h-28 w-28 object-contain transition-transform duration-300 group-hover:scale-105" loading="lazy">
                                        <span class="mt-2 block line-clamp-2 text-2xs font-bold leading-6 text-ink-800 transition-colors group-hover:text-brand-500">
                                            {{ $product->name }}
                                        </span>
                                    </a>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-ink-50 bg-ink-50/50">
                            <th class="p-4 text-start font-medium text-ink-500">قیمت</th>
                            @foreach($products as $product)
                                <td class="p-4 text-center">
                                    <span class="text-sm font-extrabold text-ink-900">{{ fa_number(toman($product->price)) }}</span>
                                    <span class="text-[10px] text-ink-500"> تومان</span>
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-ink-50">
                            <th class="p-4 text-start font-medium text-ink-500">امتیاز کاربران</th>
                            @foreach($products as $product)
                                <td class="p-4">
                                    <x-stars :rating="$product->rating" :count="$product->reviews_count" class="justify-center" />
                                </td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-ink-50 bg-ink-50/50">
                            <th class="p-4 text-start font-medium text-ink-500">برند</th>
                            @foreach($products as $product)
                                <td class="p-4 text-center text-ink-700">{{ $product->brand?->name ?? '—' }}</td>
                            @endforeach
                        </tr>

                        <tr class="border-b border-ink-50">
                            <th class="p-4 text-start font-medium text-ink-500">وضعیت موجودی</th>
                            @foreach($products as $product)
                                <td class="p-4 text-center">
                                    <span class="badge {{ $product->stock > 0 ? 'badge-green' : 'badge-gray' }}">{{ $product->stock_label }}</span>
                                </td>
                            @endforeach
                        </tr>

                        @foreach($rows as $index => $row)
                            <tr class="border-b border-ink-50 {{ $index % 2 === 0 ? 'bg-ink-50/50' : '' }}">
                                <th class="p-4 text-start font-medium text-ink-500">{{ $row }}</th>
                                @foreach($products as $product)
                                    <td class="p-4 text-center leading-6 text-ink-700">
                                        {{ $product->attributes->firstWhere('name', $row)?->value ?? '—' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr>
                            <th class="p-4"></th>
                            @foreach($products as $product)
                                <td class="p-4 text-center">
                                    <button type="button" data-add-to-cart="{{ $product->id }}" class="btn-primary btn-sm w-full"
                                            {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                        <x-icon name="cart" class="h-4 w-4" />
                                        افزودن به سبد
                                    </button>
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
