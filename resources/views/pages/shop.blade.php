@extends('layouts.app')

@section('title', $title . ' | دیجی‌نو')
@section('meta_description', $subtitle ?? 'خرید ' . $title . ' با بهترین قیمت و ضمانت اصالت کالا از فروشگاه اینترنتی دیجی‌نو.')

@section('content')
    <div class="container">
        <div class="mb-4 rounded-card bg-white p-5 shadow-card" data-reveal>
            <h1 class="text-lg font-extrabold text-ink-900">{{ $title }}</h1>
            @if(!empty($subtitle))
                <p class="mt-1.5 max-w-3xl text-2xs leading-7 text-ink-500">{{ $subtitle }}</p>
            @endif

            @isset($brand)
                <div class="mt-4 flex items-center gap-3 border-t border-ink-100 pt-4">
                    @if($brand->logo)
                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-12 w-auto" loading="lazy">
                    @endif
                    <div>
                        <p class="text-sm font-bold text-ink-800">{{ $brand->name }}</p>
                        <p class="ltr text-2xs text-ink-400">{{ $brand->name_en }}</p>
                    </div>
                </div>
            @endisset
        </div>
    </div>

    @include('partials.catalog')
@endsection
