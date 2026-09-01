@extends('layouts.admin')

@section('title', 'روش‌های ارسال')
@section('heading', 'روش‌های ارسال')
@section('subheading', 'هزینه و زمان تحویل هر روش را مدیریت کنید')

@section('breadcrumb')
    <span class="text-ink-700">روش‌های ارسال</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <p class="text-2xs text-ink-500">
            {{ fa_number($methods->count()) }} روش ارسال تعریف شده است. سفارش‌های بالای
            {{ fa_number(toman(config('digino.checkout.free_shipping_from'))) }} تومان به‌صورت پیش‌فرض رایگان ارسال می‌شوند.
        </p>

        <button type="button" class="btn-primary btn-sm" data-modal-open="shipping-form" data-crud-new="shipping-form">
            <x-icon name="plus" class="h-4 w-4" />
            روش ارسال جدید
        </button>
    </div>

    <div class="grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-3">
        @forelse($methods as $method)
            <article data-shipping-row class="rounded-card bg-white p-5 shadow-card transition-shadow hover:shadow-card-hover" data-reveal>
                <div class="flex items-start gap-3">
                    <span class="stat-icon bg-info-50 text-info-600">
                        <x-icon :name="$method->icon ?: 'truck'" class="h-6 w-6" />
                    </span>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="truncate text-2xs font-bold text-ink-900">{{ $method->name }}</h3>
                            @if($method->is_active)
                                <span class="badge-green">فعال</span>
                            @else
                                <span class="badge-gray">غیرفعال</span>
                            @endif
                        </div>
                        @if($method->description)
                            <p class="mt-1 line-clamp-2 text-[11px] leading-6 text-ink-500">{{ $method->description }}</p>
                        @endif
                    </div>
                </div>

                <dl class="mt-4 grid grid-cols-3 gap-2 rounded-field bg-ink-50 p-3 text-center">
                    <div>
                        <dt class="text-[10px] text-ink-500">هزینه</dt>
                        <dd class="mt-0.5 text-2xs font-bold text-ink-900">
                            {{ $method->cost > 0 ? fa_number(toman($method->cost)) : 'رایگان' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[10px] text-ink-500">رایگان از</dt>
                        <dd class="mt-0.5 text-2xs font-bold text-ink-900">
                            {{ $method->free_from ? fa_number(toman($method->free_from)) : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[10px] text-ink-500">زمان تحویل</dt>
                        <dd class="mt-0.5 text-2xs font-bold text-ink-900">
                            {{ $method->estimated_days ? fa_number($method->estimated_days).' روز' : 'همان روز' }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-3 flex items-center gap-1 border-t border-ink-100 pt-3">
                    <button type="button" class="btn-ghost btn-sm flex-1"
                            data-crud-edit="shipping-form" data-crud-url="{{ route('admin.shipping.show', $method) }}"
                            data-crud-title="ویرایش {{ $method->name }}">
                        <x-icon name="edit" class="h-4 w-4" />
                        ویرایش
                    </button>
                    <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" aria-label="حذف روش ارسال"
                            data-action="{{ route('admin.shipping.destroy', $method) }}" data-method="DELETE"
                            data-remove-row="[data-shipping-row]"
                            data-confirm="روش «{{ $method->name }}» حذف شود؟" data-confirm-title="حذف روش ارسال" data-confirm-accept="حذف کن">
                        <x-icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </article>
        @empty
            <div class="sm:col-span-2 xl:col-span-3">
                <div class="rounded-card bg-white shadow-card">
                    <x-empty-state icon="truck" title="روش ارسالی تعریف نشده است"
                                   message="بدون روش ارسال، مشتری در تسویه‌حساب گزینه‌ای برای انتخاب ندارد." />
                </div>
            </div>
        @endforelse
    </div>
</div>

<x-modal id="shipping-form" title="روش ارسال جدید">
    <form data-ajax-form id="shipping-form-el" action="{{ route('admin.shipping.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="sh-name">نام روش <span class="text-brand-500">*</span></label>
                <input id="sh-name" name="name" class="field" placeholder="پست پیشتاز">
                <p class="error-text" data-error-for="name"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="sh-desc">توضیح</label>
                <input id="sh-desc" name="description" class="field" placeholder="تحویل ۲ تا ۴ روز کاری در سراسر کشور">
                <p class="error-text" data-error-for="description"></p>
            </div>

            <div>
                <label class="label" for="sh-cost">هزینه (ریال) <span class="text-brand-500">*</span></label>
                <input id="sh-cost" name="cost" class="field ltr text-end" data-numeric inputmode="numeric" value="0">
                <p class="error-text" data-error-for="cost"></p>
            </div>

            <div>
                <label class="label" for="sh-free">رایگان از مبلغ (ریال)</label>
                <input id="sh-free" name="free_from" class="field ltr text-end" data-numeric inputmode="numeric">
                <p class="error-text" data-error-for="free_from"></p>
            </div>

            <div>
                <label class="label" for="sh-days">زمان تحویل (روز)</label>
                <input id="sh-days" name="estimated_days" type="number" min="0" max="60" class="field ltr text-end" value="2">
                <p class="error-text" data-error-for="estimated_days"></p>
            </div>

            <div>
                <label class="label" for="sh-icon">نام آیکون</label>
                <input id="sh-icon" name="icon" class="field ltr" placeholder="truck">
                <p class="error-text" data-error-for="icon"></p>
            </div>

            <div>
                <label class="label" for="sh-sort">ترتیب نمایش</label>
                <input id="sh-sort" name="sort_order" type="number" min="0" class="field ltr text-end" value="0">
            </div>

            <div class="flex items-end pb-2">
                <x-switch name="is_active" :checked="true" label="فعال باشد" />
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره روش ارسال</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgCrud({
            modal: 'shipping-form',
            form: '#shipping-form-el',
            storeUrl: '{{ route('admin.shipping.store') }}',
            baseUrl: '{{ url('admin/shipping') }}',
            resource: 'method',
            createTitle: 'روش ارسال جدید',
            fields: ['name', 'description', 'cost', 'free_from', 'estimated_days', 'icon', 'sort_order'],
            booleans: ['is_active'],
        });
    });
</script>
@endpush
