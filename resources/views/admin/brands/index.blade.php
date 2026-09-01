@extends('layouts.admin')

@section('title', 'برندها')
@section('heading', 'مدیریت برندها')
@section('subheading', fa_number($brands->total()) . ' برند در فروشگاه ثبت شده است')

@section('breadcrumb')
    <span class="text-ink-700">برندها</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <form method="GET" class="relative w-full max-w-sm">
            <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="جستجوی برند...">
            <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
        </form>

        <button type="button" class="btn-primary btn-sm" data-modal-open="brand-form" data-crud-new="brand-form">
            <x-icon name="plus" class="h-4 w-4" />
            برند جدید
        </button>
    </div>

    <div class="grid gap-3 stagger sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
        @forelse($brands as $brand)
            <article data-brand-row class="group rounded-card bg-white p-4 shadow-card transition-shadow hover:shadow-card-hover" data-reveal>
                <div class="flex items-start gap-3">
                    <span class="grid h-14 w-14 shrink-0 place-items-center overflow-hidden rounded-xl border border-ink-100 bg-white">
                        @if($brand->logo)
                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-full w-full object-contain p-1.5" loading="lazy">
                        @else
                            <span class="text-sm font-extrabold text-ink-400">{{ mb_substr($brand->name, 0, 1) }}</span>
                        @endif
                    </span>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <h3 class="truncate text-2xs font-bold text-ink-900">{{ $brand->name }}</h3>
                            @if($brand->is_featured)<span class="badge-amber">منتخب</span>@endif
                            @unless($brand->is_active)<span class="badge-gray">غیرفعال</span>@endunless
                        </div>
                        <p class="ltr mt-0.5 truncate text-[11px] text-ink-400">{{ $brand->name_en ?: $brand->slug }}</p>
                        <p class="mt-2 text-[11px] text-ink-500">{{ fa_number($brand->products_count) }} کالا</p>
                    </div>
                </div>

                <div class="mt-3 flex items-center gap-1 border-t border-ink-100 pt-3">
                    <a href="{{ route('brands.show', $brand->slug) }}" target="_blank" rel="noopener" class="btn-ghost btn-sm flex-1">
                        <x-icon name="external" class="h-4 w-4" />
                        مشاهده
                    </a>
                    <button type="button" class="btn-ghost btn-sm flex-1"
                            data-crud-edit="brand-form" data-crud-url="{{ route('admin.brands.show', $brand) }}"
                            data-crud-title="ویرایش {{ $brand->name }}">
                        <x-icon name="edit" class="h-4 w-4" />
                        ویرایش
                    </button>
                    <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" aria-label="حذف برند"
                            data-action="{{ route('admin.brands.destroy', $brand) }}" data-method="DELETE"
                            data-remove-row="[data-brand-row]"
                            data-confirm="برند «{{ $brand->name }}» حذف شود؟" data-confirm-title="حذف برند" data-confirm-accept="حذف کن">
                        <x-icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </article>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 2xl:col-span-4">
                <div class="rounded-card bg-white shadow-card">
                    <x-empty-state icon="tag" title="برندی یافت نشد" message="نخستین برند فروشگاه را اضافه کنید." />
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $brands->links() }}</div>
</div>

<x-modal id="brand-form" title="برند جدید">
    <form data-ajax-form id="brand-form-el" action="{{ route('admin.brands.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="b-name">نام برند <span class="text-brand-500">*</span></label>
                <input id="b-name" name="name" class="field" placeholder="سامسونگ">
                <p class="error-text" data-error-for="name"></p>
            </div>

            <div>
                <label class="label" for="b-name-en">نام انگلیسی</label>
                <input id="b-name-en" name="name_en" class="field ltr" placeholder="Samsung">
                <p class="error-text" data-error-for="name_en"></p>
            </div>

            <div>
                <label class="label" for="b-slug">نشانی اینترنتی</label>
                <input id="b-slug" name="slug" class="field ltr" placeholder="samsung">
                <p class="error-text" data-error-for="slug"></p>
            </div>

            <div>
                <label class="label" for="b-logo">مسیر لوگو</label>
                <input id="b-logo" name="logo" class="field ltr" placeholder="images/brands/samsung.svg">
                <p class="error-text" data-error-for="logo"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="b-desc">توضیح</label>
                <textarea id="b-desc" name="description" rows="3" class="field"></textarea>
                <p class="error-text" data-error-for="description"></p>
            </div>

            <div>
                <label class="label" for="b-sort">ترتیب نمایش</label>
                <input id="b-sort" name="sort_order" type="number" min="0" class="field ltr text-end" value="0">
            </div>

            <div class="flex flex-col justify-center gap-3">
                <x-switch name="is_active" :checked="true" label="فعال" />
                <x-switch name="is_featured" label="برند منتخب" />
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره برند</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgCrud({
            modal: 'brand-form',
            form: '#brand-form-el',
            storeUrl: '{{ route('admin.brands.store') }}',
            baseUrl: '{{ url('admin/brands') }}',
            resource: 'brand',
            createTitle: 'برند جدید',
            fields: ['name', 'name_en', 'slug', 'logo', 'description', 'sort_order'],
            booleans: ['is_active', 'is_featured'],
        });
    });
</script>
@endpush
