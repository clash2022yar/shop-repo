@extends('layouts.app')

@section('title', $post->title . ' | مجله دیجی‌نو')
@section('meta_description', $post->excerpt)
@section('og_type', 'article')
@section('og_image', asset($post->cover ?: 'images/logo/mark.svg'))

@section('content')
<div class="container">
    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_18rem]">

        <article class="section" data-reveal>
            @if($post->cover)
                <img src="{{ asset($post->cover) }}" alt="{{ $post->title }}"
                     class="mb-5 h-56 w-full rounded-card object-cover lg:h-80" width="880" height="320">
            @endif

            <h1 class="text-lg font-extrabold leading-9 text-ink-900 lg:text-xl lg:leading-10">{{ $post->title }}</h1>

            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 border-b border-ink-100 pb-4 text-[11px] text-ink-400">
                <span class="flex items-center gap-1.5"><x-icon name="user" class="h-4 w-4" /> {{ $post->author?->name ?? 'تیم دیجی‌نو' }}</span>
                <span class="flex items-center gap-1.5"><x-icon name="calendar" class="h-4 w-4" /> {{ jalali($post->published_at) }}</span>
                <span class="flex items-center gap-1.5"><x-icon name="clock" class="h-4 w-4" /> {{ fa_number($post->read_minutes ?: 5) }} دقیقه مطالعه</span>
                <span class="flex items-center gap-1.5"><x-icon name="eye" class="h-4 w-4" /> {{ fa_number($post->views_count) }} بازدید</span>
            </div>

            @if($post->excerpt)
                <p class="mt-5 rounded-card bg-ink-50 p-4 text-[0.8125rem] font-medium leading-8 text-ink-700">{{ $post->excerpt }}</p>
            @endif

            <div class="mt-5 space-y-4 text-[0.8125rem] leading-8 text-ink-700">
                @foreach(preg_split('/\n\s*\n/', trim((string) $post->body)) as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-2 border-t border-ink-100 pt-5">
                <span class="text-2xs text-ink-500">اشتراک‌گذاری:</span>
                <button type="button" data-copy="{{ url()->current() }}" class="btn-ghost btn-sm">
                    <x-icon name="link" class="h-4 w-4" />
                    کپی نشانی
                </button>
            </div>
        </article>

        <aside class="space-y-4">
            @if($related->isNotEmpty())
                <section class="section" data-reveal>
                    <h2 class="section-title mb-4">مطالب مرتبط</h2>
                    <div class="space-y-3">
                        @foreach($related as $item)
                            <a href="{{ route('blog.show', $item->slug) }}" class="group flex gap-3">
                                <img src="{{ asset($item->cover ?: 'images/placeholder-product.svg') }}" alt=""
                                     class="h-16 w-20 shrink-0 rounded-lg object-cover" loading="lazy">
                                <span class="min-w-0">
                                    <span class="line-clamp-2 text-2xs font-medium leading-6 text-ink-800 transition-colors group-hover:text-brand-500">
                                        {{ $item->title }}
                                    </span>
                                    <span class="mt-1 block text-[10px] text-ink-400">{{ jalali($item->published_at) }}</span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="section" data-reveal>
                <h2 class="section-title mb-2">عضویت در خبرنامه</h2>
                <p class="mb-3 text-2xs leading-6 text-ink-500">تازه‌ترین مقاله‌ها و تخفیف‌ها را در ایمیل خود دریافت کنید.</p>
                <form action="{{ route('ajax.newsletter') }}" method="POST" data-ajax-form data-reset data-no-redirect>
                    @csrf
                    <input type="email" name="email" class="field ltr" required placeholder="name@example.com">
                    <p data-error-for="email" class="hidden"></p>
                    <button type="submit" class="btn-primary mt-2.5 w-full">
                        <span data-submit-text>عضویت</span>
                        <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
                    </button>
                </form>
            </section>
        </aside>
    </div>
</div>
@endsection
