@extends('layouts.app')

@section('title', ($category->meta_title ?: $category->name) . ' | خرید اینترنتی از دیجی‌نو')
@section('meta_description', $category->meta_description ?: ('خرید انواع ' . $category->name . ' با بهترین قیمت، ضمانت اصالت کالا و ارسال سریع از فروشگاه اینترنتی دیجی‌نو.'))

@section('content')
    <div class="container">
        {{-- category banner --}}
        @if($category->banner)
            <div class="mb-4 overflow-hidden rounded-card shadow-card" data-reveal>
                <img src="{{ asset($category->banner) }}" alt="{{ $category->name }}"
                     class="h-28 w-full object-cover lg:h-44" width="1320" height="176">
            </div>
        @endif

        <div class="mb-4 rounded-card bg-white p-5 shadow-card" data-reveal>
            <div class="flex items-center gap-3">
                <span class="icon-tile shrink-0">
                    <x-icon :name="$category->icon ?: 'box'" class="h-6 w-6" />
                </span>
                <div class="min-w-0">
                    <h1 class="text-lg font-extrabold text-ink-900">{{ $category->name }}</h1>
                    <p class="mt-0.5 text-2xs text-ink-500">{{ fa_number($products->total()) }} کالا در این دسته‌بندی</p>
                </div>
            </div>

            @if($category->description)
                <p class="mt-3 max-w-3xl text-2xs leading-7 text-ink-500">{{ $category->description }}</p>
            @endif

            {{-- sub-categories --}}
            @if($category->children->isNotEmpty())
                <div class="mt-4 flex gap-3 overflow-x-auto border-t border-ink-100 pt-4 scrollbar-none">
                    @foreach($category->children as $child)
                        <a href="{{ route('categories.show', $child->slug) }}"
                           class="group flex w-24 shrink-0 flex-col items-center gap-2 text-center">
                            <span class="grid h-16 w-16 place-items-center overflow-hidden rounded-full bg-ink-50 ring-1 ring-ink-100 transition-all duration-300 group-hover:-translate-y-1 group-hover:ring-brand-200">
                                @if($child->image)
                                    <img src="{{ asset($child->image) }}" alt="{{ $child->name }}" class="h-full w-full object-cover" loading="lazy">
                                @else
                                    <x-icon :name="$child->icon ?: 'box'" class="h-7 w-7 text-ink-400 transition-colors group-hover:text-brand-500" />
                                @endif
                            </span>
                            <span class="line-clamp-2 text-[11px] leading-5 text-ink-600 transition-colors group-hover:text-brand-500">{{ $child->name }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @include('partials.catalog', ['categorySlug' => $category->slug])
@endsection
