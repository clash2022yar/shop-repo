@extends('layouts.admin')

@section('title', 'انبار')
@section('heading', 'مدیریت موجودی انبار')
@section('subheading', 'کالاهای رو به اتمام را پیش از ناموجود شدن شارژ کنید')

@section('breadcrumb')
    <span class="text-ink-700">انبار</span>
@endsection

@section('content')
<div data-inventory-table>

    <div class="mb-4 grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => 'مجموع اقلام انبار', 'value' => fa_number($stats['total_units']), 'icon' => 'warehouse', 'tone' => 'bg-brand-50 text-brand-500'],
            ['label' => 'ارزش انبار (تومان)', 'value' => fa_number(toman($stats['stock_value'])), 'icon' => 'wallet', 'tone' => 'bg-success-50 text-success-600'],
            ['label' => 'رو به اتمام', 'value' => fa_number($stats['low']), 'icon' => 'alert', 'tone' => 'bg-warning-50 text-warning-600'],
            ['label' => 'ناموجود', 'value' => fa_number($stats['out']), 'icon' => 'x-circle', 'tone' => 'bg-ink-100 text-ink-600'],
        ] as $card)
            <div class="flex items-center gap-3 rounded-card bg-white p-4 shadow-card" data-reveal>
                <span class="stat-icon {{ $card['tone'] }}"><x-icon :name="$card['icon']" class="h-6 w-6" /></span>
                <span class="min-w-0">
                    <span class="block text-[11px] text-ink-500">{{ $card['label'] }}</span>
                    <span class="block truncate text-base font-extrabold text-ink-900">{{ $card['value'] }}</span>
                </span>
            </div>
        @endforeach
    </div>

    <div class="mb-4 rounded-card bg-white p-4 shadow-card">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach(['' => 'همه کالاها', 'low' => 'رو به اتمام', 'out' => 'ناموجود', 'ok' => 'موجودی مناسب'] as $value => $label)
                    <button type="button" data-status-tab="{{ $value }}" data-status-name="filter"
                            @if((string) request('filter') === (string) $value) data-active @endif
                            class="rounded-pill px-3 py-1.5 text-2xs text-ink-600 transition-colors hover:bg-ink-50 data-[active]:bg-brand-50 data-[active]:font-bold data-[active]:text-brand-600">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <a href="{{ route('admin.inventory.movements') }}" class="btn-outline btn-sm">
                <x-icon name="list" class="h-4 w-4" />
                دفتر گردش انبار
            </a>
        </div>

        <form data-admin-filter class="grid gap-2 sm:grid-cols-2" onsubmit="return false">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            <div class="relative">
                <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="جستجوی نام یا کد کالا...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کالا</th>
                        <th>دسته‌بندی</th>
                        <th>برند</th>
                        <th>موجودی</th>
                        <th>وضعیت</th>
                        <th>ارزش (تومان)</th>
                        <th class="w-52">عملیات</th>
                    </tr>
                </thead>
                <tbody data-admin-rows>
                    @include('admin.inventory.partials.rows', ['products' => $products])
                </tbody>
            </table>
        </div>
    </div>

    <div data-admin-pagination>{{ $products->links() }}</div>
</div>

<x-modal id="stock-adjust" title="اصلاح موجودی" size="sm">
    <form data-ajax-form id="stock-form" action="" data-method="POST" data-close-modal>
        <p class="mb-4 rounded-field bg-ink-50 px-4 py-3 text-2xs text-ink-700">
            <span data-stock-name class="font-bold"></span>
            <span class="mt-1 block text-[11px] text-ink-500">موجودی فعلی: <span data-stock-current class="font-bold"></span> عدد</span>
        </p>

        <div class="space-y-4">
            <div>
                <label class="label" for="stock-mode">نوع عملیات</label>
                <select id="stock-mode" name="mode" class="field">
                    <option value="add">افزودن به موجودی</option>
                    <option value="subtract">کاستن از موجودی</option>
                    <option value="set">تعیین موجودی دقیق</option>
                </select>
                <p class="error-text" data-error-for="mode"></p>
            </div>

            <div>
                <label class="label" for="stock-qty">تعداد</label>
                <input id="stock-qty" name="quantity" class="field ltr text-end" data-numeric inputmode="numeric" value="1">
                <p class="error-text" data-error-for="quantity"></p>
            </div>

            <div>
                <label class="label" for="stock-note">توضیح</label>
                <textarea id="stock-note" name="note" rows="2" class="field" placeholder="مثلاً: رسید انبار شماره ۱۲۳"></textarea>
                <p class="error-text" data-error-for="note"></p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ثبت تغییر</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgTable({ root: '[data-inventory-table]', url: '{{ route('admin.inventory.table') }}' });

        var form = document.getElementById('stock-form');
        var currentRow = null;

        document.addEventListener('click', function (e) {
            var btn = e.target.closest('[data-stock-adjust]');
            if (btn) {
                currentRow = btn.closest('[data-stock-row]');
                form.setAttribute('action', btn.dataset.url);
                form.querySelector('[name=quantity]').value = '1';
                form.querySelector('[name=note]').value = '';
                document.querySelector('[data-stock-name]').textContent = btn.dataset.name;
                document.querySelector('[data-stock-current]').textContent = dg.faNumber(btn.dataset.stock);
                dg.modal.open('stock-adjust');
                return;
            }

            var hist = e.target.closest('[data-stock-history]');
            if (hist) {
                dg.http.get(hist.dataset.stockHistory)
                    .then(function (data) { dg.modal.show('گردش انبار', data.html); })
                    .catch(function (err) { dg.toast(err.message, 'error'); });
            }
        });

        form.addEventListener('dg:submitted', function (e) {
            var data = e.detail || {};
            if (currentRow && data.stock !== undefined) {
                var cell = currentRow.querySelector('[data-stock-value]');
                cell.textContent = dg.faNumber(data.stock);
                cell.classList.add('animate-count-flip');
            }
            setTimeout(function () { location.reload(); }, 900);
        });
    });
</script>
@endpush
