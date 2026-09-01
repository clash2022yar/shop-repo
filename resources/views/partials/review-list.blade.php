@forelse($reviews as $review)
    <article class="border-b border-ink-100 py-5 last:border-0 animate-fade-up">
        <div class="flex items-start gap-3">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-ink-100 text-2xs font-bold text-ink-600">
                {{ $review->user->initials }}
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                    <span class="text-sm font-bold text-ink-900">{{ $review->user->name }}</span>
                    @if($review->is_buyer)
                        <span class="badge-green"><x-icon name="check-circle" class="h-3 w-3" /> خریدار</span>
                    @endif
                    <span class="text-2xs text-ink-400">{{ jalali_human($review->created_at) }}</span>
                </div>

                <div class="mt-1.5 flex items-center gap-2">
                    <x-stars :rating="$review->rating" size="h-3.5 w-3.5" />
                    @if($review->recommends)
                        <span class="text-2xs font-bold text-success-600">این کالا را پیشنهاد می‌کنم</span>
                    @else
                        <span class="text-2xs font-bold text-brand-500">این کالا را پیشنهاد نمی‌کنم</span>
                    @endif
                </div>

                @if($review->title)
                    <h4 class="mt-2.5 text-sm font-bold text-ink-900">{{ $review->title }}</h4>
                @endif

                <p class="mt-2 whitespace-pre-line text-[0.8125rem] leading-7 text-ink-700">{{ $review->body }}</p>

                @if(filled($review->pros) || filled($review->cons))
                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                        @if(filled($review->pros))
                            <ul class="space-y-1 rounded-field bg-success-50/60 p-3">
                                @foreach($review->pros as $pro)
                                    <li class="flex items-start gap-1.5 text-2xs text-success-700">
                                        <x-icon name="plus" class="mt-0.5 h-3 w-3 shrink-0" /> {{ $pro }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if(filled($review->cons))
                            <ul class="space-y-1 rounded-field bg-brand-50/60 p-3">
                                @foreach($review->cons as $con)
                                    <li class="flex items-start gap-1.5 text-2xs text-brand-700">
                                        <x-icon name="minus" class="mt-0.5 h-3 w-3 shrink-0" /> {{ $con }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                <div class="mt-3 flex items-center gap-4 text-2xs text-ink-500">
                    <span>آیا این دیدگاه مفید بود؟</span>
                    <button type="button" data-review-vote="{{ $review->id }}" data-vote-type="like"
                            class="flex items-center gap-1 transition-colors hover:text-success-600">
                        <x-icon name="thumb-up" class="h-4 w-4" />
                        <span data-vote-likes="{{ $review->id }}">{{ fa_number($review->likes) }}</span>
                    </button>
                    <button type="button" data-review-vote="{{ $review->id }}" data-vote-type="dislike"
                            class="flex items-center gap-1 transition-colors hover:text-brand-500">
                        <x-icon name="thumb-down" class="h-4 w-4" />
                        <span data-vote-dislikes="{{ $review->id }}">{{ fa_number($review->dislikes) }}</span>
                    </button>
                </div>
            </div>
        </div>
    </article>
@empty
    <x-empty-state icon="chat" title="هنوز دیدگاهی ثبت نشده است"
                   message="اولین نفری باشید که تجربهٔ خود را از این کالا با دیگران به اشتراک می‌گذارد." />
@endforelse
