<div class="animate-fade-in">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-base font-extrabold text-ink-900">پرسش و پاسخ درباره این کالا</h2>
        @auth
            <button type="button" data-modal-open="ask-question" class="btn-outline btn-sm">
                <x-icon name="question" class="h-4 w-4" />
                ثبت پرسش جدید
            </button>
        @else
            <a href="{{ route('login') }}" class="btn-outline btn-sm">برای ثبت پرسش وارد شوید</a>
        @endauth
    </div>

    @forelse($product->questions as $question)
        <article class="mb-4 rounded-card border border-ink-100 p-4 animate-fade-up">
            <div class="flex items-start gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-ink-100 text-[10px] font-bold text-ink-600">
                    {{ $question->user->initials }}
                </span>
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                        <span class="text-2xs font-bold text-ink-900">{{ $question->user->name }}</span>
                        <span class="text-[11px] text-ink-400">{{ jalali_human($question->created_at) }}</span>
                    </div>
                    <p class="mt-1.5 text-[0.8125rem] leading-7 text-ink-800">{{ $question->body }}</p>
                </div>
            </div>

            @if($question->approvedAnswers->isNotEmpty())
                <div class="mt-3 space-y-3 border-t border-dashed border-ink-100 pt-3 ps-12">
                    @foreach($question->approvedAnswers as $answer)
                        <div class="flex items-start gap-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full {{ $answer->is_staff ? 'bg-brand-50 text-brand-600' : 'bg-ink-100 text-ink-600' }} text-[10px] font-bold">
                                @if($answer->is_staff)
                                    <x-icon name="shield-check" class="h-4 w-4" />
                                @else
                                    {{ $answer->user->initials }}
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="text-2xs font-bold text-ink-900">
                                        {{ $answer->is_staff ? 'پاسخ کارشناس دیجی‌نو' : $answer->user->name }}
                                    </span>
                                    @if($answer->is_staff)<span class="badge-red-soft">کارشناس</span>@endif
                                    <span class="text-[11px] text-ink-400">{{ jalali_human($answer->created_at) }}</span>
                                </div>
                                <p class="mt-1 text-2xs leading-7 text-ink-700">{{ $answer->body }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @auth
                <form action="{{ route('ajax.reviews.answer', $question->id) }}" method="POST" data-ajax-form data-reset data-no-redirect
                      class="mt-3 flex gap-2 ps-12">
                    @csrf
                    <input type="text" name="body" class="field-sm" placeholder="پاسخ خود را بنویسید...">
                    <button type="submit" class="btn-outline btn-sm shrink-0">
                        <span data-submit-text>ارسال</span>
                        <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
                    </button>
                </form>
            @endauth
        </article>
    @empty
        <x-empty-state icon="question" title="هنوز پرسشی ثبت نشده است"
                       message="اگر درباره این کالا سؤالی دارید، بپرسید تا کارشناسان ما یا سایر خریداران پاسخ دهند." />
    @endforelse
</div>

@auth
<x-modal id="ask-question" title="ثبت پرسش درباره این کالا">
    <form action="{{ route('ajax.reviews.ask', $product->slug) }}" method="POST" data-ajax-form data-reset data-close-modal data-no-redirect>
        @csrf
        <label class="label" for="question-body">پرسش شما <span class="text-brand-500">*</span></label>
        <textarea id="question-body" name="body" rows="4" class="field" required
                  placeholder="سؤال خود را واضح و کوتاه بنویسید..."></textarea>
        <p data-error-for="body" class="hidden"></p>
        <p class="help mt-2">پرسش شما پس از بررسی توسط کارشناسان منتشر می‌شود.</p>

        <div class="mt-5 flex justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-text>ثبت پرسش</span>
                <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
            </button>
        </div>
    </form>
</x-modal>
@endauth
