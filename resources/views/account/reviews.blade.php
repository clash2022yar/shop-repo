@extends('layouts.account')

@section('title', 'دیدگاه‌های من')
@section('heading', 'دیدگاه‌های من')
@section('subheading', 'نظرهایی که دربارهٔ کالاهای دیجی‌نو ثبت کرده‌اید')

@section('content')
<div class="space-y-3">
    @forelse($reviews as $review)
        <article class="rounded-card bg-white p-4 shadow-card animate-fade-up">
            <div class="flex items-start gap-4">
                @if($review->product)
                    <a href="{{ route('products.show', $review->product->slug) }}" class="shrink-0">
                        <img src="{{ asset($review->product->primary_image) }}" alt="{{ $review->product->name }}"
                             class="h-20 w-20 rounded-lg object-contain" loading="lazy">
                    </a>
                @endif

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <a href="{{ route('products.show', $review->product->slug) }}"
                           class="line-clamp-1 text-2xs font-bold text-ink-900 transition-colors hover:text-brand-500">
                            {{ $review->product->name }}
                        </a>

                        @php
                            $tone = match($review->status?->value ?? $review->status) {
                                'approved' => 'badge-green', 'rejected' => 'badge-red', default => 'badge-amber',
                            };
                            $label = match($review->status?->value ?? $review->status) {
                                'approved' => 'منتشر شده', 'rejected' => 'تأیید نشده', default => 'در انتظار بررسی',
                            };
                        @endphp
                        <span class="{{ $tone }}">{{ $label }}</span>
                    </div>

                    <div class="mt-1.5 flex items-center gap-3">
                        <x-stars :rating="$review->rating" size="h-3.5 w-3.5" />
                        <span class="text-[11px] text-ink-400">{{ jalali($review->created_at) }}</span>
                    </div>

                    @if($review->title)
                        <p class="mt-2 text-2xs font-bold text-ink-800">{{ $review->title }}</p>
                    @endif
                    <p class="mt-1 line-clamp-3 text-[11px] leading-6 text-ink-600">{{ $review->body }}</p>

                    <div class="mt-2 flex items-center gap-4 text-[11px] text-ink-400">
                        <span class="flex items-center gap-1"><x-icon name="thumb-up" class="h-3.5 w-3.5" /> {{ fa_number($review->likes) }}</span>
                        <span class="flex items-center gap-1"><x-icon name="thumb-down" class="h-3.5 w-3.5" /> {{ fa_number($review->dislikes) }}</span>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="rounded-card bg-white shadow-card">
            <x-empty-state icon="chat" title="هنوز دیدگاهی ثبت نکرده‌اید"
                           message="با ثبت دیدگاه دربارهٔ کالاهایی که خریده‌اید، به سایر خریداران کمک کنید."
                           :action-url="route('account.orders.index')" action-label="مشاهده سفارش‌ها" />
        </div>
    @endforelse
</div>

{{ $reviews->links() }}
@endsection
