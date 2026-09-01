<div class="animate-fade-in">
    <div class="grid gap-6 lg:grid-cols-[18rem_minmax(0,1fr)]">

        {{-- summary --}}
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <div class="rounded-card border border-ink-100 p-5 text-center">
                <p class="text-3xl font-extrabold text-ink-900">{{ fa_number(number_format($product->rating, 1)) }}</p>
                <x-stars :rating="$product->rating" size="h-5 w-5" class="mt-2 justify-center" />
                <p class="mt-2 text-2xs text-ink-500">از مجموع {{ fa_number($product->reviews_count) }} دیدگاه</p>

                <div class="mt-5 space-y-2">
                    @foreach($histogram as $star => $count)
                        @php $percent = $product->reviews_count ? round($count / $product->reviews_count * 100) : 0; @endphp
                        <div class="flex items-center gap-2">
                            <span class="w-8 shrink-0 text-start text-[11px] text-ink-500">{{ fa_number($star) }} ستاره</span>
                            <span class="h-1.5 flex-1 overflow-hidden rounded-pill bg-ink-100">
                                <span class="block h-full rounded-pill bg-star transition-all duration-700" style="width: {{ $percent }}%"></span>
                            </span>
                            <span class="w-8 shrink-0 text-end text-[11px] text-ink-400">{{ fa_number($count) }}</span>
                        </div>
                    @endforeach
                </div>

                @auth
                    <button type="button" data-modal-open="write-review" class="btn-primary mt-5 w-full">
                        <x-icon name="edit" class="h-4 w-4" />
                        ثبت دیدگاه
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn-outline mt-5 w-full">
                        برای ثبت دیدگاه وارد شوید
                    </a>
                @endauth
            </div>
        </aside>

        {{-- list --}}
        <div class="min-w-0">
            <div class="mb-2 flex items-center justify-between">
                <h2 class="text-base font-extrabold text-ink-900">دیدگاه خریداران</h2>
            </div>

            @include('partials.review-list', ['reviews' => $product->approvedReviews->sortByDesc('created_at')])
        </div>
    </div>
</div>

{{-- review modal --}}
@auth
<x-modal id="write-review" title="ثبت دیدگاه درباره این کالا" size="lg">
    <form action="{{ route('ajax.reviews.store', $product->slug) }}" method="POST" data-ajax-form data-reset data-close-modal data-no-redirect>
        @csrf

        <div class="mb-5 text-center">
            <p class="mb-2 text-2xs text-ink-600">امتیاز شما به این کالا</p>
            <div class="inline-flex flex-row-reverse items-center gap-1" data-rating-picker>
                <input type="hidden" name="rating" value="">
                @for($i = 5; $i >= 1; $i--)
                    <button type="button" data-rating-value="{{ $i }}" class="text-ink-300 transition-transform hover:scale-110"
                            aria-label="{{ fa_number($i) }} ستاره">
                        <x-icon name="star" class="h-8 w-8" />
                    </button>
                @endfor
            </div>
            <p class="mt-1.5 h-4 text-2xs font-bold text-brand-500" data-rating-label></p>
            <p data-error-for="rating" class="hidden"></p>
        </div>

        <label class="label" for="review-title">عنوان دیدگاه</label>
        <input id="review-title" type="text" name="title" class="field" placeholder="مثلاً: کیفیت ساخت عالی">
        <p data-error-for="title" class="hidden"></p>

        <label class="label mt-4" for="review-body">متن دیدگاه <span class="text-brand-500">*</span></label>
        <textarea id="review-body" name="body" rows="5" class="field" required
                  placeholder="تجربه خود را از استفاده این کالا بنویسید..."></textarea>
        <p data-error-for="body" class="hidden"></p>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div data-repeater>
                <p class="label">نقاط قوت</p>
                <div data-repeater-list class="space-y-2">
                    <div data-repeater-row class="flex gap-2">
                        <input type="text" name="pros[]" class="field-sm" placeholder="یک نکته مثبت">
                        <button type="button" data-repeater-remove class="btn-icon h-9 w-9 shrink-0"><x-icon name="close" class="h-4 w-4" /></button>
                    </div>
                </div>
                <template data-repeater-template>
                    <div data-repeater-row class="flex gap-2">
                        <input type="text" name="pros[]" class="field-sm" placeholder="یک نکته مثبت">
                        <button type="button" data-repeater-remove class="btn-icon h-9 w-9 shrink-0"><x-icon name="close" class="h-4 w-4" /></button>
                    </div>
                </template>
                <button type="button" data-repeater-add class="btn-link mt-2 text-2xs">
                    <x-icon name="plus" class="h-3.5 w-3.5" /> افزودن نقطه قوت
                </button>
            </div>

            <div data-repeater>
                <p class="label">نقاط ضعف</p>
                <div data-repeater-list class="space-y-2">
                    <div data-repeater-row class="flex gap-2">
                        <input type="text" name="cons[]" class="field-sm" placeholder="یک نکته منفی">
                        <button type="button" data-repeater-remove class="btn-icon h-9 w-9 shrink-0"><x-icon name="close" class="h-4 w-4" /></button>
                    </div>
                </div>
                <template data-repeater-template>
                    <div data-repeater-row class="flex gap-2">
                        <input type="text" name="cons[]" class="field-sm" placeholder="یک نکته منفی">
                        <button type="button" data-repeater-remove class="btn-icon h-9 w-9 shrink-0"><x-icon name="close" class="h-4 w-4" /></button>
                    </div>
                </template>
                <button type="button" data-repeater-add class="btn-link mt-2 text-2xs">
                    <x-icon name="plus" class="h-3.5 w-3.5" /> افزودن نقطه ضعف
                </button>
            </div>
        </div>

        <label class="mt-5 flex cursor-pointer items-center gap-2.5">
            <input type="hidden" name="recommends" value="0">
            <input type="checkbox" name="recommends" value="1" class="checkbox" checked>
            <span class="text-2xs text-ink-700">خرید این کالا را به دیگران پیشنهاد می‌کنم</span>
        </label>

        <div class="mt-6 flex justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-text>ثبت دیدگاه</span>
                <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
            </button>
        </div>
    </form>
</x-modal>
@endauth
