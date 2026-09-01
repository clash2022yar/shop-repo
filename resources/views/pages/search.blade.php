@extends('layouts.app')

@section('title', $title . ' | دیجی‌نو')
@section('robots', 'noindex, follow')

@section('content')
    <div class="container">
        <div class="mb-4 rounded-card bg-white p-5 shadow-card" data-reveal>
            <h1 class="flex flex-wrap items-center gap-2 text-lg font-extrabold text-ink-900">
                <x-icon name="search" class="h-5 w-5 text-ink-400" />
                @if($term !== '')
                    نتایج جستجو برای «<span class="text-brand-500">{{ $term }}</span>»
                @else
                    جستجو در دیجی‌نو
                @endif
            </h1>

            <p class="mt-1.5 text-2xs text-ink-500">
                {{ fa_number($products->total()) }} کالا یافت شد
            </p>

            {{-- trending searches --}}
            @if($products->total() === 0 && $trending->isNotEmpty())
                <div class="mt-4 border-t border-ink-100 pt-4">
                    <p class="mb-2.5 text-2xs font-bold text-ink-700">جستجوهای پرطرفدار:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($trending as $item)
                            <a href="{{ route('search', ['q' => $item->term]) }}"
                               class="inline-flex items-center gap-1.5 rounded-pill bg-ink-100 px-3 py-1.5 text-2xs text-ink-700 transition-colors hover:bg-brand-50 hover:text-brand-600">
                                <x-icon name="trend-up" class="h-3.5 w-3.5" />
                                {{ $item->term }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('partials.catalog')
@endsection
