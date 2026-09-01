@extends('layouts.app')

@section('title', 'مجله دیجی‌نو')
@section('meta_description', 'راهنمای خرید، بررسی کالا و اخبار دنیای فناوری در مجله دیجی‌نو.')

@section('content')
<div class="container">
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-5 shadow-card">
        <div>
            <h1 class="text-lg font-extrabold text-ink-900">مجله دیجی‌نو</h1>
            <p class="mt-1.5 text-2xs text-ink-500">راهنمای خرید، بررسی کالا و تازه‌های دنیای فناوری</p>
        </div>

        <form action="{{ route('blog.index') }}" method="GET" class="relative w-full max-w-xs">
            <input type="search" name="q" value="{{ request('q') }}" class="field ps-10" placeholder="جستجو در مقاله‌ها...">
            <x-icon name="search" class="pointer-events-none absolute top-1/2 h-5 w-5 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
        </form>
    </div>

    @if($posts->isEmpty())
        <div class="rounded-card bg-white shadow-card">
            <x-empty-state icon="newspaper" title="مطلبی پیدا نشد"
                           message="عبارت دیگری را جستجو کنید یا به فهرست کامل مقاله‌ها بازگردید."
                           :action-url="route('blog.index')" action-label="همه مقاله‌ها" />
        </div>
    @else
        <div class="grid gap-4 stagger sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
                <article class="group overflow-hidden rounded-card bg-white shadow-card transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover" data-reveal>
                    <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden">
                        <img src="{{ asset($post->cover ?: 'images/placeholder-product.svg') }}" alt="{{ $post->title }}"
                             class="h-44 w-full object-cover transition-transform duration-700 group-hover:scale-105"
                             loading="lazy" width="420" height="176">
                    </a>
                    <div class="p-4">
                        <h2 class="line-clamp-2 text-sm font-bold leading-7 text-ink-900">
                            <a href="{{ route('blog.show', $post->slug) }}" class="transition-colors hover:text-brand-500">{{ $post->title }}</a>
                        </h2>
                        <p class="mt-2 line-clamp-3 text-2xs leading-6 text-ink-500">{{ $post->excerpt }}</p>
                        <div class="mt-3 flex items-center justify-between border-t border-ink-100 pt-3 text-[11px] text-ink-400">
                            <span class="flex items-center gap-1"><x-icon name="user" class="h-3.5 w-3.5" /> {{ $post->author?->name ?? 'تیم دیجی‌نو' }}</span>
                            <span class="flex items-center gap-1"><x-icon name="clock" class="h-3.5 w-3.5" /> {{ fa_number($post->read_minutes ?: 5) }} دقیقه</span>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{ $posts->links() }}
    @endif
</div>
@endsection
