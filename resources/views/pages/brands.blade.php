@extends('layouts.app')

@section('title', 'برندهای فروشگاه دیجی‌نو')
@section('meta_description', 'فهرست برندهای معتبر موجود در فروشگاه اینترنتی دیجی‌نو؛ کالای اصل با ضمانت، از برندهای مورد اعتماد شما.')

@section('content')
    <div class="container">
        <div class="mb-4 rounded-card bg-white p-5 shadow-card">
            <h1 class="text-lg font-extrabold text-ink-900">برندها</h1>
            <p class="mt-1.5 text-2xs text-ink-500">{{ fa_number($brands->count()) }} برند فعال در دیجی‌نو</p>

            <div class="relative mt-4 max-w-sm">
                <input type="text" placeholder="جستجوی برند..." class="field ps-10"
                       oninput="document.querySelectorAll('[data-brand-card]').forEach(c=>{c.hidden=!c.dataset.brandCard.toLowerCase().includes(this.value.trim().toLowerCase())})">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 stagger sm:grid-cols-3 lg:grid-cols-5">
            @foreach($brands as $brand)
                <a href="{{ route('brands.show', $brand->slug) }}" data-brand-card="{{ $brand->name }} {{ $brand->name_en }}"
                   class="group flex flex-col items-center gap-3 rounded-card bg-white p-5 shadow-card transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover">
                    <span class="grid h-16 w-full place-items-center">
                        @if($brand->logo)
                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}"
                                 class="max-h-12 w-auto opacity-75 transition-opacity group-hover:opacity-100" loading="lazy">
                        @else
                            <span class="ltr text-lg font-extrabold text-ink-400 transition-colors group-hover:text-brand-500">{{ $brand->name_en ?: $brand->name }}</span>
                        @endif
                    </span>
                    <span class="text-center">
                        <span class="block text-2xs font-bold text-ink-800">{{ $brand->name }}</span>
                        <span class="mt-0.5 block text-[10px] text-ink-400">{{ fa_number($brand->products_count) }} کالا</span>
                    </span>
                    @if($brand->is_featured)
                        <span class="badge-red-soft">برند ویژه</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endsection
