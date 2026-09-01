@extends('layouts.admin')

@section('title', 'مشتریان')
@section('heading', 'مدیریت مشتریان')
@section('subheading', fa_number($stats['total']) . ' مشتری در دیجی‌نو حساب دارند')

@section('breadcrumb')
    <span class="text-ink-700">مشتریان</span>
@endsection

@section('content')
<div data-customers-table>

    <div class="mb-4 grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'کل مشتریان', 'value' => $stats['total'], 'icon' => 'users', 'tone' => 'bg-brand-50 text-brand-500'],
            ['label' => 'حساب‌های فعال', 'value' => $stats['active'], 'icon' => 'check-circle', 'tone' => 'bg-success-50 text-success-600'],
            ['label' => 'عضو ۳۰ روز اخیر', 'value' => $stats['new_month'], 'icon' => 'user-plus', 'tone' => 'bg-info-50 text-info-600'],
            ['label' => 'دارای سفارش', 'value' => $stats['with_orders'], 'icon' => 'shopping-bag', 'tone' => 'bg-warning-50 text-warning-600'],
        ] as $card)
            <div class="flex items-center gap-3 rounded-card bg-white p-4 shadow-card" data-reveal>
                <span class="stat-icon {{ $card['tone'] }}"><x-icon :name="$card['icon']" class="h-6 w-6" /></span>
                <span>
                    <span class="block text-[11px] text-ink-500">{{ $card['label'] }}</span>
                    <span class="block text-lg font-extrabold text-ink-900" data-count-to="{{ $card['value'] }}">{{ fa_number($card['value']) }}</span>
                </span>
            </div>
        @endforeach
    </div>

    <div class="mb-4 rounded-card bg-white p-4 shadow-card">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach(['' => 'همه', 'active' => 'فعال', 'inactive' => 'غیرفعال', 'buyers' => 'خریدار'] as $value => $label)
                    <button type="button" data-status-tab="{{ $value }}"
                            @if((string) request('status') === (string) $value) data-active @endif
                            class="rounded-pill px-3 py-1.5 text-2xs text-ink-600 transition-colors hover:bg-ink-50 data-[active]:bg-brand-50 data-[active]:font-bold data-[active]:text-brand-600">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <button type="button" class="btn-primary btn-sm" data-modal-open="customer-form" data-crud-new="customer-form">
                <x-icon name="user-plus" class="h-4 w-4" />
                افزودن مشتری
            </button>
        </div>

        <form data-admin-filter class="grid gap-2 sm:grid-cols-2 lg:grid-cols-4" onsubmit="return false">
            <input type="hidden" name="status" value="{{ request('status') }}">

            <div class="relative lg:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="نام، ایمیل یا موبایل مشتری...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>

            <select name="sort" class="field-sm">
                <option value="">تازه‌ترین عضویت</option>
                <option value="orders" @selected(request('sort') === 'orders')>بیشترین سفارش</option>
            </select>
        </form>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>مشتری</th>
                        <th>موبایل</th>
                        <th>تاریخ عضویت</th>
                        <th>سفارش‌ها</th>
                        <th>مجموع خرید</th>
                        <th>وضعیت</th>
                        <th class="w-32">عملیات</th>
                    </tr>
                </thead>
                <tbody data-admin-rows>
                    @include('admin.customers.partials.rows', ['customers' => $customers])
                </tbody>
            </table>
        </div>
    </div>

    <div data-admin-pagination>{{ $customers->links() }}</div>
</div>

<x-modal id="customer-form" title="افزودن مشتری">
    <form data-ajax-form id="customer-form-el" action="{{ route('admin.customers.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="label" for="cu-name">نام و نام خانوادگی <span class="text-brand-500">*</span></label>
                <input id="cu-name" name="name" class="field" placeholder="مثال: زهرا محمدی">
                <p class="error-text" data-error-for="name"></p>
            </div>

            <div>
                <label class="label" for="cu-email">ایمیل <span class="text-brand-500">*</span></label>
                <input id="cu-email" name="email" type="email" class="field ltr" placeholder="name@example.com">
                <p class="error-text" data-error-for="email"></p>
            </div>

            <div>
                <label class="label" for="cu-mobile">موبایل <span class="text-brand-500">*</span></label>
                <input id="cu-mobile" name="mobile" class="field ltr" data-numeric inputmode="numeric" placeholder="09123456789">
                <p class="error-text" data-error-for="mobile"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="cu-password">گذرواژه</label>
                <input id="cu-password" name="password" type="password" class="field ltr" autocomplete="new-password">
                <p class="help">برای ویرایش، در صورت خالی ماندن گذرواژه فعلی حفظ می‌شود.</p>
                <p class="error-text" data-error-for="password"></p>
            </div>

            <div data-loyalty-wrap class="hidden sm:col-span-2">
                <label class="label" for="cu-points">امتیاز باشگاه مشتریان</label>
                <input id="cu-points" name="loyalty_points" type="number" min="0" class="field ltr text-end" value="0">
                <p class="error-text" data-error-for="loyalty_points"></p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره مشتری</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgTable({ root: '[data-customers-table]', url: '{{ route('admin.customers.table') }}' });

        dgCrud({
            modal: 'customer-form',
            form: '#customer-form-el',
            storeUrl: '{{ route('admin.customers.store') }}',
            baseUrl: '{{ url('admin/customers') }}',
            resource: 'customer',
            createTitle: 'افزودن مشتری',
            fields: ['name', 'email', 'mobile', 'loyalty_points'],
            onEdit: function () {
                document.querySelector('[data-loyalty-wrap]').classList.remove('hidden');
            },
        });
    });
</script>
@endpush
