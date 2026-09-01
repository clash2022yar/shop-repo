@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title) . ' | دیجی‌نو')
@section('meta_description', $page->meta_description ?: mb_substr(strip_tags($page->body), 0, 160))

@section('content')
<div class="container">
    <article class="section" data-reveal>
        <h1 class="section-title mb-1">{{ $page->title }}</h1>
        <p class="mb-6 text-2xs text-ink-400">آخرین به‌روزرسانی: {{ jalali($page->updated_at) }}</p>

        <div class="space-y-4 text-[0.8125rem] leading-8 text-ink-600">
            @foreach(preg_split('/\n\s*\n/', trim((string) $page->body)) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </article>
</div>
@endsection
