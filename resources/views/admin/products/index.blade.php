@extends('layouts.admin')

@section('title', 'محصولات')
@section('heading', 'مدیریت محصولات')
@section('subheading', fa_number($counts['all']) . ' کالا در فروشگاه ثبت شده است')

@section('content')
<div data-bulk-root data-admin-table>

    {{-- ══════════ toolbar ══════════ --}}
    <div class="mb-4 rounded-card bg-white p-4 shadow-card">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                @php
                    $tabs = [
                        '' => ['label' => 'همه', 'count' => $counts['all']],
                        'active' => ['label' => 'منتشر شده', 'count' => $counts['active']],
                        'inactive' => ['label' => 'پیش‌نویس', 'count' => $counts['inactive']],
                        'special' => ['label' => 'فروش ویژه', 'count' => $counts['special']],
                        'out' => ['label' => 'ناموجود', 'count' => $counts['out']],
                    ];
                @endphp
                @foreach($tabs as $value => $meta)
                    <button type="button" data-status-tab="{{ $value }}"
                            @if((string) request('status') === (string) $value) data-active @endif
                            class="flex items-center gap-1.5 rounded-pill px-3 py-1.5 text-2xs text-ink-600 transition-colors hover:bg-ink-50 data-[active]:bg-brand-50 data-[active]:font-bold data-[active]:text-brand-600">
                        {{ $meta['label'] }}
                        <span class="text-[10px] text-ink-400">{{ fa_number($meta['count']) }}</span>
                    </button>
                @endforeach
            </div>

            <a href="{{ route('admin.products.create') }}" class="btn-primary btn-sm">
                <x-icon name="plus" class="h-4 w-4" />
                افزودن کالای جدید
            </a>
        </div>

        <form data-admin-filter class="grid gap-2 sm:grid-cols-2 lg:grid-cols-5" onsubmit="return false">
            <input type="hidden" name="status" value="{{ request('status') }}">

            <div class="relative lg:col-span-2">
                <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="جستجوی نام یا کد کالا...">
                <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
            </div>

            <select name="category_id" class="field-sm">
                <option value="">همه دسته‌بندی‌ها</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="brand_id" class="field-sm">
                <option value="">همه برندها</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>{{ $brand->name }}</option>
                @endforeach
            </select>

            <select name="sort" class="field-sm">
                <option value="newest">جدیدترین</option>
                <option value="bestseller" @selected(request('sort') === 'bestseller')>پرفروش‌ترین</option>
                <option value="cheapest" @selected(request('sort') === 'cheapest')>ارزان‌ترین</option>
                <option value="expensive" @selected(request('sort') === 'expensive')>گران‌ترین</option>
                <option value="rating" @selected(request('sort') === 'rating')>بیشترین امتیاز</option>
            </select>
        </form>
    </div>

    {{-- ══════════ bulk bar ══════════ --}}
    <div data-bulk-bar class="hidden">
      <div class="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-card bg-ink-900 px-4 py-3 text-white animate-fade-down">
        <span class="text-2xs">
            <span data-bulk-count class="font-bold">۰</span> کالا انتخاب شده است
        </span>
        <div class="flex flex-wrap items-center gap-2">
            @foreach([
                'activate' => ['انتشار', 'check-circle'],
                'deactivate' => ['پیش‌نویس', 'eye-off'],
                'special' => ['فروش ویژه', 'percent'],
                'unspecial' => ['حذف از ویژه', 'minus'],
                'delete' => ['حذف', 'trash'],
            ] as $action => $meta)
                <button type="button" data-bulk-action="{{ $action }}"
                        class="flex items-center gap-1.5 rounded-field px-3 py-1.5 text-2xs transition-colors hover:bg-white/10 {{ $action === 'delete' ? 'text-brand-300' : '' }}">
                    <x-icon :name="$meta[1]" class="h-4 w-4" />
                    {{ $meta[0] }}
                </button>
            @endforeach
        </div>
      </div>
    </div>

    {{-- ══════════ table ══════════ --}}
    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="checkbox" data-bulk-all aria-label="انتخاب همه"></th>
                        <th>کالا</th>
                        <th>دسته‌بندی</th>
                        <th>برند</th>
                        <th>قیمت</th>
                        <th>موجودی</th>
                        <th>فروش</th>
                        <th>انتشار</th>
                        <th class="w-40">عملیات</th>
                    </tr>
                </thead>
                <tbody data-admin-rows>
                    @include('admin.products.partials.rows', ['products' => $products])
                </tbody>
            </table>
        </div>
    </div>

    <div data-admin-pagination>{{ $products->links() }}</div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        var root = document.querySelector('[data-admin-table]');
        var form = root.querySelector('[data-admin-filter]');
        var rows = root.querySelector('[data-admin-rows]');
        var pager = root.querySelector('[data-admin-pagination]');

        function load(extra) {
            var params = new URLSearchParams(new FormData(form));
            Object.keys(extra || {}).forEach(function (k) { params.set(k, extra[k]); });

            rows.style.opacity = '.4';

            dg.http.get('{{ route('admin.products.table') }}?' + params.toString())
                .then(function (data) {
                    rows.innerHTML = data.html;
                    pager.innerHTML = data.pagination || '';
                    dg.bindAll(rows);
                    history.replaceState(null, '', '?' + params.toString());
                })
                .catch(function (err) { dg.toast(err.message, 'error'); })
                .finally(function () { rows.style.opacity = ''; });
        }

        form.addEventListener('change', function () { load({ page: 1 }); });
        form.querySelector('[name=q]').addEventListener('input', dg.debounce(function () { load({ page: 1 }); }, 450));

        root.querySelectorAll('[data-status-tab]').forEach(function (tab) {
            tab.addEventListener('click', function () {
                root.querySelectorAll('[data-status-tab]').forEach(function (t) { t.removeAttribute('data-active'); });
                tab.setAttribute('data-active', '');
                form.querySelector('[name=status]').value = tab.dataset.statusTab;
                load({ page: 1 });
            });
        });

        root.addEventListener('click', function (e) {
            var link = e.target.closest('[data-page]');
            if (link) { e.preventDefault(); load({ page: link.dataset.page }); }
        });

        root.querySelectorAll('[data-bulk-action]').forEach(function (btn) {
            btn.addEventListener('click', async function () {
                var ids = Array.from(root.querySelectorAll('[data-bulk-item]:checked')).map(function (c) { return c.value; });
                if (!ids.length) return dg.toast('هیچ کالایی انتخاب نشده است.', 'warning');

                if (btn.dataset.bulkAction === 'delete') {
                    var ok = await dg.modal.confirm(dg.faNumber(ids.length) + ' کالا حذف شود؟ این عمل قابل بازگشت نیست.', {
                        title: 'حذف گروهی', accept: 'حذف کن'
                    });
                    if (!ok) return;
                }

                dg.http.post('{{ route('admin.products.bulk') }}', { action: btn.dataset.bulkAction, ids: ids })
                    .then(function (data) { dg.toast(data.message, 'success'); load({}); })
                    .catch(function (err) { dg.toast(err.message, 'error'); });
            });
        });
    });
</script>
@endpush
