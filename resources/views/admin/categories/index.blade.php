@extends('layouts.admin')

@section('title', 'دسته‌بندی‌ها')
@section('heading', 'دسته‌بندی کالاها')
@section('subheading', fa_number($total) . ' دسته‌بندی ثبت شده است')

@section('content')
<div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_22rem] xl:items-start">

    {{-- ═════════ tree ═════════ --}}
    <section class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="flex items-center justify-between border-b border-ink-100 px-5 py-4">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="grid" class="h-5 w-5 text-brand-500" />
                ساختار درختی
            </h2>
            <button type="button" class="btn-primary btn-sm" data-modal-open="category-form" data-crud-new="category-form">
                <x-icon name="plus" class="h-4 w-4" />
                دسته‌بندی جدید
            </button>
        </div>

        <div class="divide-y divide-ink-100">
            @forelse($categories as $category)
                <div class="animate-fade-in" data-category-row="{{ $category->id }}">
                    <div class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition-colors hover:bg-ink-50">
                        @if($category->children->isNotEmpty())
                            <button type="button" class="btn-icon h-7 w-7" data-tree-toggle aria-label="باز و بسته کردن زیرشاخه‌ها">
                                <x-icon name="chevron-down" class="h-4 w-4 transition-transform" />
                            </button>
                        @else
                            <span class="h-7 w-7"></span>
                        @endif

                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-brand-50 text-brand-500">
                            <x-icon :name="$category->icon ?: 'grid'" class="h-5 w-5" />
                        </span>

                        <span class="min-w-0 flex-1">
                            <span class="flex flex-wrap items-center gap-2">
                                <span class="text-2xs font-bold text-ink-900">{{ $category->name }}</span>
                                @unless($category->is_active)<span class="badge-gray">غیرفعال</span>@endunless
                                @if($category->show_in_menu)<span class="badge-blue">در منو</span>@endif
                            </span>
                            <span class="ltr mt-0.5 block text-[10px] text-ink-400">{{ $category->slug }}</span>
                        </span>

                        <span class="shrink-0 text-2xs text-ink-500">{{ fa_number($category->products_count) }} کالا</span>

                        <span class="flex shrink-0 items-center gap-1">
                            <a href="{{ route('categories.show', $category->slug) }}" target="_blank" rel="noopener" class="btn-icon h-8 w-8" aria-label="مشاهده">
                                <x-icon name="external" class="h-4 w-4" />
                            </a>
                            <button type="button" class="btn-icon h-8 w-8" aria-label="ویرایش"
                                    data-crud-edit="category-form" data-crud-url="{{ route('admin.categories.show', $category) }}" data-crud-title="ویرایش {{ $category->name }}">
                                <x-icon name="edit" class="h-4 w-4" />
                            </button>
                            <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف"
                                    data-action="{{ route('admin.categories.destroy', $category) }}" data-method="DELETE"
                                    data-remove-row="[data-category-row]"
                                    data-confirm="دسته‌بندی «{{ $category->name }}» حذف شود؟" data-confirm-title="حذف دسته‌بندی" data-confirm-accept="حذف کن">
                                <x-icon name="trash" class="h-4 w-4" />
                            </button>
                        </span>
                    </div>

                    @if($category->children->isNotEmpty())
                        <div data-tree-children class="border-t border-dashed border-ink-100 bg-ink-50/60">
                            @foreach($category->children as $child)
                                <div class="flex flex-wrap items-center gap-3 py-2.5 pe-5 transition-colors hover:bg-white"
                                     style="padding-inline-start:4.5rem" data-category-row="{{ $child->id }}">
                                    <span class="min-w-0 flex-1">
                                        <span class="flex flex-wrap items-center gap-2">
                                            <span class="text-2xs text-ink-800">{{ $child->name }}</span>
                                            @unless($child->is_active)<span class="badge-gray">غیرفعال</span>@endunless
                                        </span>
                                        <span class="ltr mt-0.5 block text-[10px] text-ink-400">{{ $child->slug }}</span>
                                    </span>

                                    <span class="shrink-0 text-2xs text-ink-500">{{ fa_number($child->products_count) }} کالا</span>

                                    <span class="flex shrink-0 items-center gap-1">
                                        <button type="button" class="btn-icon h-8 w-8" aria-label="ویرایش"
                                                data-crud-edit="category-form" data-crud-url="{{ route('admin.categories.show', $child) }}" data-crud-title="ویرایش {{ $child->name }}">
                                            <x-icon name="edit" class="h-4 w-4" />
                                        </button>
                                        <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف"
                                                data-action="{{ route('admin.categories.destroy', $child) }}" data-method="DELETE"
                                                data-remove-row="[data-category-row]"
                                                data-confirm="دسته‌بندی «{{ $child->name }}» حذف شود؟" data-confirm-title="حذف دسته‌بندی" data-confirm-accept="حذف کن">
                                            <x-icon name="trash" class="h-4 w-4" />
                                        </button>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <x-empty-state icon="grid" title="هنوز دسته‌بندی‌ای ندارید"
                               message="با ساخت نخستین دسته‌بندی، ساختار فروشگاه را شکل دهید." />
            @endforelse
        </div>
    </section>

    {{-- ═════════ guide ═════════ --}}
    <aside class="space-y-4 xl:sticky xl:top-24">
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-3 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="info" class="h-5 w-5 text-info-600" />
                راهنمای دسته‌بندی
            </h2>
            <ul class="space-y-2.5 text-2xs leading-6 text-ink-600">
                <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" />دسته‌بندی‌های سطح اول در منوی اصلی فروشگاه دیده می‌شوند.</li>
                <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" />ترتیب نمایش با فیلد «ترتیب» کنترل می‌شود؛ عدد کوچک‌تر بالاتر.</li>
                <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" />دسته‌بندی دارای کالا یا زیرشاخه حذف نمی‌شود.</li>
                <li class="flex gap-2"><x-icon name="check" class="mt-1 h-4 w-4 shrink-0 text-success-600" />نام آیکون باید یکی از آیکون‌های داخلی دیجی‌نو باشد؛ مثلاً <span class="ltr font-bold">mobile</span>.</li>
            </ul>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-3 text-sm font-extrabold text-ink-900">آیکون‌های پرکاربرد</h2>
            <div class="grid grid-cols-6 gap-2">
                @foreach(['mobile','laptop','headphone','camera','watch','shirt','home','beauty','book','toy','car','tools','grid','gift','food','sport'] as $icon)
                    <button type="button" data-copy="{{ $icon }}" title="{{ $icon }}"
                            class="grid aspect-square place-items-center rounded-field border border-ink-200 text-ink-500 transition-colors hover:border-brand-300 hover:bg-brand-50 hover:text-brand-500">
                        <x-icon :name="$icon" class="h-5 w-5" />
                    </button>
                @endforeach
            </div>
            <p class="mt-3 text-[10px] text-ink-400">برای کپی کردن نام آیکون روی آن کلیک کنید.</p>
        </section>
    </aside>
</div>

{{-- ═════════ modal ═════════ --}}
<x-modal id="category-form" title="دسته‌بندی" size="lg">
    <form data-ajax-form action="{{ route('admin.categories.store') }}" data-method="POST" data-reload id="category-form-el">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="c-name">نام دسته‌بندی <span class="text-brand-500">*</span></label>
                <input id="c-name" name="name" class="field" placeholder="مثال: گوشی موبایل">
                <p class="error-text" data-error-for="name"></p>
            </div>

            <div>
                <label class="label" for="c-slug">نشانی اینترنتی</label>
                <input id="c-slug" name="slug" class="field ltr" placeholder="mobile-phone">
                <p class="error-text" data-error-for="slug"></p>
            </div>

            <div>
                <label class="label" for="c-parent">دسته‌بندی والد</label>
                <select id="c-parent" name="parent_id" class="field">
                    <option value="">— دسته‌بندی اصلی —</option>
                    @foreach($flat as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                <p class="error-text" data-error-for="parent_id"></p>
            </div>

            <div>
                <label class="label" for="c-icon">نام آیکون</label>
                <input id="c-icon" name="icon" class="field ltr" placeholder="mobile">
                <p class="error-text" data-error-for="icon"></p>
            </div>

            <div>
                <label class="label" for="c-image">تصویر دسته‌بندی</label>
                <input id="c-image" name="image" class="field ltr" placeholder="images/categories/mobile.jpg">
                <p class="error-text" data-error-for="image"></p>
            </div>

            <div>
                <label class="label" for="c-banner">بنر صفحه دسته‌بندی</label>
                <input id="c-banner" name="banner" class="field ltr" placeholder="images/banners/mobile.jpg">
                <p class="error-text" data-error-for="banner"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="c-desc">توضیح</label>
                <textarea id="c-desc" name="description" rows="3" class="field"></textarea>
                <p class="error-text" data-error-for="description"></p>
            </div>

            <div>
                <label class="label" for="c-sort">ترتیب نمایش</label>
                <input id="c-sort" name="sort_order" type="number" min="0" class="field ltr text-end" value="0">
                <p class="error-text" data-error-for="sort_order"></p>
            </div>

            <div class="flex flex-col justify-center gap-3">
                <x-switch name="is_active" :checked="true" label="فعال" />
                <x-switch name="show_in_menu" :checked="true" label="نمایش در منوی اصلی" />
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="c-meta-title">عنوان متا</label>
                <input id="c-meta-title" name="meta_title" class="field">
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="c-meta-desc">توضیح متا</label>
                <textarea id="c-meta-desc" name="meta_description" rows="2" class="field"></textarea>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره دسته‌بندی</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgCrud({
            modal: 'category-form',
            form: '#category-form-el',
            storeUrl: '{{ route('admin.categories.store') }}',
            baseUrl: '{{ url('admin/categories') }}',
            resource: 'category',
            createTitle: 'دسته‌بندی جدید',
            fields: ['name', 'slug', 'parent_id', 'icon', 'image', 'banner', 'description', 'sort_order', 'meta_title', 'meta_description'],
            booleans: ['is_active', 'show_in_menu'],
        });

        document.querySelectorAll('[data-tree-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var children = btn.closest('[data-category-row]').querySelector('[data-tree-children]');
                children.classList.toggle('hidden');
                btn.firstElementChild.classList.toggle('-rotate-90');
            });
        });
    });
</script>
@endpush
