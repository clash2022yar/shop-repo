@extends('layouts.admin')

@section('title', 'دیدگاه‌ها')
@section('heading', 'مدیریت دیدگاه‌ها')
@section('subheading', fa_number($counts['pending']) . ' دیدگاه در انتظار بررسی')

@section('breadcrumb')
    <span class="text-ink-700">دیدگاه‌ها</span>
@endsection

@section('content')
<div>
    <div class="mb-4 rounded-card bg-white p-4 shadow-card">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach(['' => ['همه', $counts['all']], 'pending' => ['در انتظار', $counts['pending']], 'approved' => ['منتشر شده', $counts['approved']], 'rejected' => ['رد شده', $counts['rejected']]] as $value => $meta)
                    <a href="{{ route('admin.reviews.index', array_filter(['status' => $value, 'q' => request('q')])) }}"
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

            <a href="{{ route('admin.questions.index') }}" class="btn-outline btn-sm">
                <x-icon name="help" class="h-4 w-4" />
                پرسش و پاسخ‌ها
            </a>
        </div>

        <form method="GET" class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
            <input type="hidden" name="status" value="{{ request('status') }}">
            <div class="relative lg:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="جستجو در متن دیدگاه‌ها...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>
            <select name="rating" class="field-sm" onchange="this.form.submit()">
                <option value="">همه امتیازها</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" @selected(request('rating') == $i)>{{ fa_number($i) }} ستاره</option>
                @endfor
            </select>
            <button type="submit" class="btn-dark btn-sm">اعمال فیلتر</button>
        </form>
    </div>

    <div class="grid gap-3 stagger lg:grid-cols-2">
        @forelse($reviews as $review)
            <article data-review-row class="rounded-card bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover" data-reveal>
                <div class="mb-3 flex items-start justify-between gap-3">
                    <div class="flex min-w-0 items-center gap-2.5">
                        <img src="{{ asset($review->product?->primary_image ?: 'images/placeholder-product.svg') }}" alt=""
                             class="h-11 w-11 shrink-0 rounded-lg bg-ink-50 object-contain" loading="lazy">
                        <div class="min-w-0">
                            <p class="line-clamp-1 text-2xs font-bold text-ink-900">{{ $review->product?->name }}</p>
                            <p class="mt-0.5 text-[10px] text-ink-400">
                                {{ $review->user?->name ?? 'کاربر مهمان' }} — {{ jalali($review->created_at) }}
                            </p>
                        </div>
                    </div>

                    <span class="badge {{ $review->status->badgeClass() }}">{{ $review->status->label() }}</span>
                </div>

                <div class="mb-2 flex items-center gap-2">
                    <x-stars :rating="$review->rating" />
                    @if($review->is_buyer)<span class="badge-green">خریدار</span>@endif
                </div>

                @if($review->title)<p class="mb-1 text-2xs font-bold text-ink-800">{{ $review->title }}</p>@endif
                <p class="line-clamp-3 text-[11px] leading-7 text-ink-600">{{ $review->body }}</p>

                <div class="mt-4 flex flex-wrap items-center gap-1.5 border-t border-ink-100 pt-3">
                    <button type="button" class="btn-ghost btn-sm" data-review-open="{{ route('admin.reviews.show', $review) }}">
                        <x-icon name="eye" class="h-4 w-4" />
                        مشاهده کامل
                    </button>

                    @if($review->status->value !== 'approved')
                        <button type="button" class="btn-ghost btn-sm text-success-600"
                                data-action="{{ route('admin.reviews.approve', $review) }}" data-method="POST" data-reload>
                            <x-icon name="check" class="h-4 w-4" />
                            تأیید
                        </button>
                    @endif

                    @if($review->status->value !== 'rejected')
                        <button type="button" class="btn-ghost btn-sm"
                                data-action="{{ route('admin.reviews.reject', $review) }}" data-method="POST" data-reload>
                            <x-icon name="x-circle" class="h-4 w-4" />
                            رد
                        </button>
                    @endif

                    <button type="button" class="btn-ghost btn-sm ms-auto text-brand-500"
                            data-action="{{ route('admin.reviews.destroy', $review) }}" data-method="DELETE"
                            data-remove-row="[data-review-row]"
                            data-confirm="این دیدگاه حذف شود؟" data-confirm-title="حذف دیدگاه" data-confirm-accept="حذف کن">
                        <x-icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </article>
        @empty
            <div class="lg:col-span-2">
                <div class="rounded-card bg-white shadow-card">
                    <x-empty-state icon="star" title="دیدگاهی یافت نشد" message="با تغییر فیلترها دوباره تلاش کنید." />
                </div>
            </div>
        @endforelse
    </div>

    <div>{{ $reviews->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        document.querySelectorAll('[data-review-open]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                dg.http.get(btn.dataset.reviewOpen)
                    .then(function (data) { dg.modal.show('جزئیات دیدگاه', data.html); })
                    .catch(function (err) { dg.toast(err.message, 'error'); });
            });
        });
    });
</script>
@endpush
