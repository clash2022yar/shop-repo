@extends('layouts.admin')

@section('title', 'کدهای تخفیف')
@section('heading', 'کدهای تخفیف')
@section('subheading', fa_number($stats['active']) . ' کد فعال از مجموع ' . fa_number($stats['total']) . ' کد')

@section('breadcrumb')
    <span class="text-ink-700">کدهای تخفیف</span>
@endsection

@section('content')
<div>
    <div class="mb-4 grid gap-3 stagger sm:grid-cols-3">
        @foreach([
            ['label' => 'کل کدها', 'value' => $stats['total'], 'icon' => 'ticket', 'tone' => 'bg-brand-50 text-brand-500'],
            ['label' => 'کدهای قابل استفاده', 'value' => $stats['active'], 'icon' => 'check-circle', 'tone' => 'bg-success-50 text-success-600'],
            ['label' => 'دفعات استفاده', 'value' => $stats['used'], 'icon' => 'trend-up', 'tone' => 'bg-info-50 text-info-600'],
        ] as $card)
            <div class="flex items-center gap-3 rounded-card bg-white p-4 shadow-card" data-reveal>
                <span class="stat-icon {{ $card['tone'] }}"><x-icon :name="$card['icon']" class="h-6 w-6" /></span>
                <span>
                    <span class="block text-[11px] text-ink-500">{{ $card['label'] }}</span>
                    <span class="block text-lg font-extrabold text-ink-900">{{ fa_number($card['value']) }}</span>
                </span>
            </div>
        @endforeach
    </div>

    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <div class="relative">
                <input type="search" name="q" value="{{ request('q') }}" class="field-sm ltr w-56 ps-9" placeholder="جستجوی کد...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>
            <select name="status" class="field-sm w-40" onchange="this.form.submit()">
                <option value="">همه کدها</option>
                <option value="active" @selected(request('status') === 'active')>قابل استفاده</option>
                <option value="expired" @selected(request('status') === 'expired')>منقضی‌شده</option>
            </select>
        </form>

        <button type="button" class="btn-primary btn-sm" data-modal-open="coupon-form" data-crud-new="coupon-form">
            <x-icon name="plus" class="h-4 w-4" />
            ساخت کد تخفیف
        </button>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کد</th>
                        <th>عنوان</th>
                        <th>مقدار تخفیف</th>
                        <th>حداقل سبد</th>
                        <th>محدوده زمانی</th>
                        <th>استفاده</th>
                        <th>وضعیت</th>
                        <th class="w-32">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr data-coupon-row class="animate-fade-in">
                            <td>
                                <button type="button" data-copy="{{ $coupon->code }}"
                                        class="ltr flex items-center gap-1.5 rounded-field bg-ink-100 px-2.5 py-1 text-2xs font-bold text-ink-900 transition-colors hover:bg-brand-50 hover:text-brand-600"
                                        title="کپی کردن کد">
                                    {{ $coupon->code }}
                                    <x-icon name="copy" class="h-3.5 w-3.5" />
                                </button>
                            </td>

                            <td class="text-2xs text-ink-700">
                                {{ $coupon->title ?: '—' }}
                                @if($coupon->category)
                                    <span class="mt-0.5 block text-[10px] text-ink-400">فقط دسته «{{ $coupon->category->name }}»</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap text-2xs font-bold text-ink-900">
                                @if($coupon->type === 'percent')
                                    {{ fa_number($coupon->value) }}٪
                                    @if($coupon->max_discount)
                                        <span class="mt-0.5 block text-[10px] font-normal text-ink-400">
                                            حداکثر {{ fa_number(toman($coupon->max_discount)) }} تومان
                                        </span>
                                    @endif
                                @else
                                    {{ fa_number(toman($coupon->value)) }} تومان
                                @endif
                            </td>

                            <td class="whitespace-nowrap text-2xs text-ink-600">
                                {{ $coupon->min_order_total ? fa_number(toman($coupon->min_order_total)).' تومان' : '—' }}
                            </td>

                            <td class="whitespace-nowrap text-[11px] text-ink-500">
                                <span class="block">از {{ $coupon->starts_at ? jalali($coupon->starts_at) : 'همیشه' }}</span>
                                <span class="block">تا {{ $coupon->expires_at ? jalali($coupon->expires_at) : 'بدون انقضا' }}</span>
                            </td>

                            <td class="text-2xs text-ink-600">
                                {{ fa_number($coupon->used_count) }}{{ $coupon->usage_limit ? ' / '.fa_number($coupon->usage_limit) : '' }}
                                @if($coupon->usage_limit)
                                    <span class="mt-1 block h-1 w-16 overflow-hidden rounded-pill bg-ink-100">
                                        <span class="block h-full rounded-pill bg-brand-500 transition-all"
                                              style="width: {{ min(100, $coupon->used_count / max(1, $coupon->usage_limit) * 100) }}%"></span>
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($coupon->is_exhausted)
                                    <span class="badge-gray">تمام‌شده</span>
                                @elseif(! $coupon->is_active)
                                    <span class="badge-gray">غیرفعال</span>
                                @elseif($coupon->expires_at && $coupon->expires_at->isPast())
                                    <span class="badge-red">منقضی</span>
                                @else
                                    <span class="badge-green">فعال</span>
                                @endif
                            </td>

                            <td>
                                <div class="flex items-center gap-1">
                                    <button type="button" class="btn-icon h-8 w-8" aria-label="ویرایش"
                                            data-crud-edit="coupon-form" data-crud-url="{{ route('admin.coupons.show', $coupon) }}"
                                            data-crud-title="ویرایش {{ $coupon->code }}">
                                        <x-icon name="edit" class="h-4 w-4" />
                                    </button>
                                    <button type="button" class="btn-icon h-8 w-8" aria-label="فعال یا غیرفعال کردن"
                                            data-action="{{ route('admin.coupons.toggle', $coupon) }}" data-method="POST" data-reload>
                                        <x-icon :name="$coupon->is_active ? 'eye-off' : 'eye'" class="h-4 w-4" />
                                    </button>
                                    <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف"
                                            data-action="{{ route('admin.coupons.destroy', $coupon) }}" data-method="DELETE"
                                            data-remove-row="[data-coupon-row]"
                                            data-confirm="کد «{{ $coupon->code }}» حذف شود؟" data-confirm-title="حذف کد تخفیف" data-confirm-accept="حذف کن">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state icon="ticket" title="کد تخفیفی وجود ندارد"
                                               message="با ساخت کد تخفیف، کمپین فروش تازه‌ای راه بیندازید." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $coupons->links() }}</div>
</div>

<x-modal id="coupon-form" title="ساخت کد تخفیف" size="lg">
    <form data-ajax-form id="coupon-form-el" action="{{ route('admin.coupons.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="co-code">کد تخفیف <span class="text-brand-500">*</span></label>
                <div class="flex items-center gap-2">
                    <input id="co-code" name="code" class="field ltr uppercase flex-1" placeholder="DIGINO1404">
                    <button type="button" class="btn-outline btn-sm shrink-0" id="generate-code">
                        <x-icon name="refresh" class="h-4 w-4" />
                        تصادفی
                    </button>
                </div>
                <p class="error-text" data-error-for="code"></p>
            </div>

            <div>
                <label class="label" for="co-title">عنوان کمپین</label>
                <input id="co-title" name="title" class="field" placeholder="جشنواره پاییزه">
                <p class="error-text" data-error-for="title"></p>
            </div>

            <div>
                <label class="label" for="co-type">نوع تخفیف <span class="text-brand-500">*</span></label>
                <select id="co-type" name="type" class="field">
                    <option value="percent">درصدی</option>
                    <option value="fixed">مبلغ ثابت (ریال)</option>
                </select>
                <p class="error-text" data-error-for="type"></p>
            </div>

            <div>
                <label class="label" for="co-value">مقدار <span class="text-brand-500">*</span></label>
                <input id="co-value" name="value" class="field ltr text-end" data-numeric inputmode="numeric" placeholder="10">
                <p class="error-text" data-error-for="value"></p>
            </div>

            <div>
                <label class="label" for="co-max">سقف تخفیف (ریال)</label>
                <input id="co-max" name="max_discount" class="field ltr text-end" data-numeric inputmode="numeric">
                <p class="help">تنها برای تخفیف‌های درصدی کاربرد دارد.</p>
                <p class="error-text" data-error-for="max_discount"></p>
            </div>

            <div>
                <label class="label" for="co-min">حداقل مبلغ سبد (ریال)</label>
                <input id="co-min" name="min_order_total" class="field ltr text-end" data-numeric inputmode="numeric">
                <p class="error-text" data-error-for="min_order_total"></p>
            </div>

            <div>
                <label class="label" for="co-limit">سقف کل استفاده</label>
                <input id="co-limit" name="usage_limit" type="number" min="1" class="field ltr text-end" placeholder="نامحدود">
                <p class="error-text" data-error-for="usage_limit"></p>
            </div>

            <div>
                <label class="label" for="co-user-limit">سقف استفاده هر کاربر</label>
                <input id="co-user-limit" name="per_user_limit" type="number" min="1" class="field ltr text-end" value="1">
                <p class="error-text" data-error-for="per_user_limit"></p>
            </div>

            <div>
                <label class="label" for="co-start">شروع</label>
                <input id="co-start" name="starts_at" type="datetime-local" class="field ltr">
                <p class="error-text" data-error-for="starts_at"></p>
            </div>

            <div>
                <label class="label" for="co-end">انقضا</label>
                <input id="co-end" name="expires_at" type="datetime-local" class="field ltr">
                <p class="error-text" data-error-for="expires_at"></p>
            </div>

            <div>
                <label class="label" for="co-category">محدود به دسته‌بندی</label>
                <select id="co-category" name="category_id" class="field">
                    <option value="">همه دسته‌بندی‌ها</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <p class="error-text" data-error-for="category_id"></p>
            </div>

            <div class="flex items-end pb-2">
                <x-switch name="is_active" :checked="true" label="کد فعال باشد" />
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره کد</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgCrud({
            modal: 'coupon-form',
            form: '#coupon-form-el',
            storeUrl: '{{ route('admin.coupons.store') }}',
            baseUrl: '{{ url('admin/coupons') }}',
            resource: 'coupon',
            createTitle: 'ساخت کد تخفیف',
            fields: ['code', 'title', 'type', 'value', 'max_discount', 'min_order_total',
                     'usage_limit', 'per_user_limit', 'category_id', 'starts_at', 'expires_at'],
            booleans: ['is_active'],
        });

        document.getElementById('generate-code').addEventListener('click', function () {
            var chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            var code = 'DG';
            for (var i = 0; i < 6; i++) code += chars.charAt(Math.floor(Math.random() * chars.length));
            document.getElementById('co-code').value = code;
        });
    });
</script>
@endpush
