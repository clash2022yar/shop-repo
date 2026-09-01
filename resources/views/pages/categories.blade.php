@extends('layouts.app')

@section('title', 'همه دسته‌بندی‌های کالا | دیجی‌نو')
@section('meta_description', 'فهرست کامل دسته‌بندی‌های فروشگاه اینترنتی دیجی‌نو؛ از موبایل و لپ‌تاپ تا لوازم خانگی و کالای ورزشی.')

@section('content')
    <div class="container">
        <div class="mb-4 rounded-card bg-white p-5 shadow-card">
            <h1 class="text-lg font-extrabold text-ink-900">دسته‌بندی کالاها</h1>
            <p class="mt-1.5 text-2xs text-ink-500">برای مشاهده کالاها، دسته‌بندی مورد نظر خود را انتخاب کنید.</p>
        </div>

        <div class="grid gap-4 stagger md:grid-cols-2 xl:grid-cols-3">
            @foreach($categories as $category)
                <section class="rounded-card bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover" data-reveal>
                    <a href="{{ route('categories.show', $category->slug) }}" class="group mb-4 flex items-center gap-3">
                        <span class="icon-tile shrink-0 transition-transform duration-300 group-hover:-translate-y-1">
                            <x-icon :name="$category->icon ?: 'box'" class="h-6 w-6" />
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-extrabold text-ink-900 transition-colors group-hover:text-brand-500">{{ $category->name }}</span>
                            <span class="mt-0.5 block text-[11px] text-ink-400">{{ fa_number($category->children->count()) }} زیر‌دسته</span>
                        </span>
                        <x-icon name="chevron-left" class="h-4 w-4 shrink-0 text-ink-300 transition-transform group-hover:-translate-x-1" />
                    </a>

                    @if($category->children->isNotEmpty())
                        <ul class="grid grid-cols-2 gap-x-3 gap-y-2 border-t border-ink-100 pt-4">
                            @foreach($category->children as $child)
                                <li>
                                    <a href="{{ route('categories.show', $child->slug) }}"
                                       class="flex items-center justify-between gap-1 text-2xs text-ink-600 transition-colors hover:text-brand-500">
                                        <span class="truncate">{{ $child->name }}</span>
                                        <span class="shrink-0 text-[10px] text-ink-400">{{ fa_number($child->products_count) }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @endforeach
        </div>
    </div>
@endsection
