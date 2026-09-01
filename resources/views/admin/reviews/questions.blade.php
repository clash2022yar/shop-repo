@extends('layouts.admin')

@section('title', 'پرسش و پاسخ')
@section('heading', 'پرسش‌های مشتریان')
@section('subheading', fa_number($counts['pending']) . ' پرسش بدون پاسخ')

@section('breadcrumb')
    <a href="{{ route('admin.reviews.index') }}" class="link-muted">دیدگاه‌ها</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="text-ink-700">پرسش و پاسخ</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center gap-1.5 rounded-card bg-white p-4 shadow-card">
        @foreach(['' => ['همه', $counts['all']], 'pending' => ['در انتظار', $counts['pending']], 'approved' => ['تأیید شده', $counts['approved']]] as $value => $meta)
            <a href="{{ route('admin.questions.index', array_filter(['status' => $value])) }}"
               @class([
                   'flex items-center gap-1.5 rounded-pill px-3 py-1.5 text-2xs transition-colors',
                   'bg-brand-50 font-bold text-brand-600' => (string) request('status') === (string) $value,
                   'text-ink-600 hover:bg-ink-50' => (string) request('status') !== (string) $value,
               ])>
                {{ $meta[0] }}
                <span class="text-[10px] text-ink-400">{{ fa_number($meta[1]) }}</span>
            </a>
        @endforeach
    </div>

    <div class="space-y-3 stagger">
        @forelse($questions as $question)
            <article data-question-row class="rounded-card bg-white p-5 shadow-card" data-reveal>
                <div class="mb-3 flex flex-wrap items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-2.5">
                        <img src="{{ asset($question->product?->primary_image ?: 'images/placeholder-product.svg') }}" alt=""
                             class="h-11 w-11 shrink-0 rounded-lg bg-ink-50 object-contain" loading="lazy">
                        <div class="min-w-0">
                            <p class="line-clamp-1 text-2xs font-bold text-ink-900">{{ $question->product?->name }}</p>
                            <p class="mt-0.5 text-[10px] text-ink-400">
                                {{ $question->user?->name ?? 'کاربر مهمان' }} — {{ jalali_human($question->created_at) }}
                            </p>
                        </div>
                    </div>

                    <span class="{{ $question->status === 'approved' ? 'badge-green' : 'badge-amber' }}">
                        {{ $question->status === 'approved' ? 'تأیید شده' : 'در انتظار' }}
                    </span>
                </div>

                <p class="rounded-field bg-ink-50 px-4 py-3 text-2xs leading-7 text-ink-800">{{ $question->body }}</p>

                @if($question->answers->isNotEmpty())
                    <ul class="mt-3 space-y-2" style="padding-inline-start:1.5rem">
                        @foreach($question->answers as $answer)
                            <li class="rounded-field border p-3 {{ $answer->is_staff ? 'border-success-200 bg-success-50' : 'border-ink-200' }}">
                                <p class="mb-1 flex items-center gap-1.5 text-[11px] font-bold {{ $answer->is_staff ? 'text-success-600' : 'text-ink-700' }}">
                                    @if($answer->is_staff)<x-icon name="badge-check" class="h-4 w-4" />@endif
                                    {{ $answer->is_staff ? 'کارشناس دیجی‌نو' : ($answer->user?->name ?? 'کاربر') }}
                                </p>
                                <p class="text-[11px] leading-7 text-ink-700">{{ $answer->body }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <div class="mt-4 border-t border-ink-100 pt-4">
                    <form data-ajax-form action="{{ route('admin.questions.answer', $question) }}" data-method="POST" data-reload
                          class="flex flex-col gap-2 sm:flex-row sm:items-start">
                        <div class="flex-1">
                            <textarea name="body" rows="2" class="field-sm" placeholder="پاسخ کارشناس دیجی‌نو..."></textarea>
                            <p class="error-text" data-error-for="body"></p>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <button type="submit" class="btn-primary btn-sm">
                                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                                <span data-submit-text>ثبت پاسخ</span>
                            </button>
                            @if($question->status !== 'approved')
                                <button type="button" class="btn-outline btn-sm"
                                        data-action="{{ route('admin.questions.approve', $question) }}" data-method="POST" data-reload>
                                    <x-icon name="check" class="h-4 w-4" />
                                    تأیید
                                </button>
                            @endif
                            <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" aria-label="حذف پرسش"
                                    data-action="{{ route('admin.questions.destroy', $question) }}" data-method="DELETE"
                                    data-remove-row="[data-question-row]"
                                    data-confirm="این پرسش حذف شود؟" data-confirm-title="حذف پرسش" data-confirm-accept="حذف کن">
                                <x-icon name="trash" class="h-4 w-4" />
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-card bg-white shadow-card">
                <x-empty-state icon="help" title="پرسشی ثبت نشده است" message="پرسش‌های مشتریان در صفحه کالاها اینجا نمایش داده می‌شوند." />
            </div>
        @endforelse
    </div>

    <div>{{ $questions->links() }}</div>
</div>
@endsection
