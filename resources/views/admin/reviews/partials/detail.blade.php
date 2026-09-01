<article class="space-y-5">
    <div class="flex items-center gap-3 rounded-field bg-ink-50 p-3">
        <img src="{{ asset($review->product?->primary_image ?: 'images/placeholder-product.svg') }}" alt=""
             class="h-14 w-14 shrink-0 rounded-lg bg-white object-contain">
        <div class="min-w-0">
            <p class="line-clamp-2 text-2xs font-bold text-ink-900">{{ $review->product?->name }}</p>
            <p class="ltr mt-1 text-[10px] text-ink-400">{{ $review->product?->sku }}</p>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-50 text-[11px] font-bold text-brand-500">
                {{ $review->user?->initials ?? '؟' }}
            </span>
            <span>
                <span class="block text-2xs font-bold text-ink-800">{{ $review->user?->name ?? 'کاربر مهمان' }}</span>
                <span class="block text-[10px] text-ink-400">{{ jalali($review->created_at, 'j F Y — H:i') }}</span>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <x-stars :rating="$review->rating" show-value />
            @if($review->is_buyer)<span class="badge-green">خریدار</span>@endif
        </div>
    </div>

    @if($review->title)
        <h4 class="text-sm font-bold text-ink-900">{{ $review->title }}</h4>
    @endif

    <p class="whitespace-pre-line text-2xs leading-8 text-ink-700">{{ $review->body }}</p>

    @if(filled($review->pros) || filled($review->cons))
        <div class="grid gap-4 sm:grid-cols-2">
            @if(filled($review->pros))
                <div>
                    <p class="mb-2 flex items-center gap-1.5 text-[11px] font-bold text-success-600">
                        <x-icon name="plus" class="h-4 w-4" /> نقاط قوت
                    </p>
                    <ul class="space-y-1.5">
                        @foreach($review->pros as $item)
                            <li class="flex gap-2 text-[11px] text-ink-600">
                                <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-success-500"></span>{{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(filled($review->cons))
                <div>
                    <p class="mb-2 flex items-center gap-1.5 text-[11px] font-bold text-brand-500">
                        <x-icon name="minus" class="h-4 w-4" /> نقاط ضعف
                    </p>
                    <ul class="space-y-1.5">
                        @foreach($review->cons as $item)
                            <li class="flex gap-2 text-[11px] text-ink-600">
                                <span class="mt-1.5 h-1 w-1 shrink-0 rounded-full bg-brand-500"></span>{{ $item }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-4 border-t border-ink-100 pt-4 text-[11px] text-ink-500">
        <span class="flex items-center gap-1.5"><x-icon name="thumb-up" class="h-4 w-4" /> {{ fa_number($review->likes) }} مفید</span>
        <span class="flex items-center gap-1.5"><x-icon name="thumb-down" class="h-4 w-4" /> {{ fa_number($review->dislikes) }} غیرمفید</span>
        <span class="flex items-center gap-1.5">
            <x-icon :name="$review->recommends ? 'check-circle' : 'x-circle'" class="h-4 w-4" />
            {{ $review->recommends ? 'خرید را پیشنهاد می‌کند' : 'خرید را پیشنهاد نمی‌کند' }}
        </span>
    </div>

    @if($review->reject_reason)
        <p class="rounded-field bg-brand-50 px-4 py-3 text-[11px] leading-6 text-brand-600">
            دلیل رد شدن: {{ $review->reject_reason }}
        </p>
    @endif

    <div class="flex flex-wrap items-center gap-2 border-t border-ink-100 pt-4">
        <button type="button" class="btn-success btn-sm"
                data-action="{{ route('admin.reviews.approve', $review) }}" data-method="POST" data-reload>
            <x-icon name="check" class="h-4 w-4" />
            تأیید و انتشار
        </button>

        <button type="button" class="btn-outline btn-sm"
                data-action="{{ route('admin.reviews.reject', $review) }}" data-method="POST" data-reload>
            <x-icon name="x-circle" class="h-4 w-4" />
            رد کردن
        </button>

        <button type="button" class="btn-ghost btn-sm text-brand-500"
                data-action="{{ route('admin.reviews.destroy', $review) }}" data-method="DELETE" data-reload
                data-confirm="این دیدگاه برای همیشه حذف شود؟" data-confirm-title="حذف دیدگاه" data-confirm-accept="حذف کن">
            <x-icon name="trash" class="h-4 w-4" />
            حذف
        </button>
    </div>
</article>
