@extends('layouts.admin')

@section('title', 'صفحات')
@section('heading', 'صفحات ثابت')
@section('subheading', 'صفحاتی مانند قوانین، حریم خصوصی و راهنمای خرید')

@section('breadcrumb')
    <span class="text-ink-700">صفحات ثابت</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <p class="text-2xs text-ink-500">
            {{ fa_number($pages->count()) }} صفحه ثبت شده است. صفحاتی که «نمایش در فوتر» دارند در پایین سایت فهرست می‌شوند.
        </p>

        <button type="button" class="btn-primary btn-sm" data-modal-open="page-form" data-crud-new="page-form">
            <x-icon name="plus" class="h-4 w-4" />
            صفحه جدید
        </button>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>نشانی</th>
                        <th>ترتیب</th>
                        <th>فوتر</th>
                        <th>وضعیت</th>
                        <th>آخرین ویرایش</th>
                        <th class="w-32">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr data-page-row class="animate-fade-in">
                            <td class="text-2xs font-medium text-ink-900">{{ $page->title }}</td>
                            <td class="ltr text-2xs text-ink-500">/p/{{ $page->slug }}</td>
                            <td class="text-2xs text-ink-600">{{ fa_number($page->sort_order) }}</td>
                            <td>
                                @if($page->in_footer)
                                    <span class="badge-blue">دارد</span>
                                @else
                                    <span class="text-2xs text-ink-400">—</span>
                                @endif
                            </td>
                            <td>
                                @if($page->is_published)
                                    <span class="badge-green">منتشر شده</span>
                                @else
                                    <span class="badge-gray">پیش‌نویس</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap text-2xs text-ink-500">{{ jalali($page->updated_at) }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" rel="noopener" class="btn-icon h-8 w-8" aria-label="مشاهده">
                                        <x-icon name="external" class="h-4 w-4" />
                                    </a>
                                    <button type="button" class="btn-icon h-8 w-8" aria-label="ویرایش"
                                            data-crud-edit="page-form" data-crud-url="{{ route('admin.pages.show', $page) }}"
                                            data-crud-title="ویرایش {{ $page->title }}">
                                        <x-icon name="edit" class="h-4 w-4" />
                                    </button>
                                    <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف"
                                            data-action="{{ route('admin.pages.destroy', $page) }}" data-method="DELETE"
                                            data-remove-row="[data-page-row]"
                                            data-confirm="صفحه «{{ $page->title }}» حذف شود؟" data-confirm-title="حذف صفحه" data-confirm-accept="حذف کن">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="file" title="صفحه‌ای ساخته نشده است"
                                               message="صفحات ثابت مانند «راهنمای خرید» را از اینجا بسازید." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<x-modal id="page-form" title="صفحه جدید" size="xl">
    <form data-ajax-form id="page-form-el" action="{{ route('admin.pages.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="pg-title">عنوان صفحه <span class="text-brand-500">*</span></label>
                <input id="pg-title" name="title" class="field" placeholder="راهنمای خرید">
                <p class="error-text" data-error-for="title"></p>
            </div>

            <div>
                <label class="label" for="pg-slug">نشانی اینترنتی</label>
                <input id="pg-slug" name="slug" class="field ltr" placeholder="buying-guide">
                <p class="error-text" data-error-for="slug"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="pg-body">متن صفحه</label>
                <textarea id="pg-body" name="body" rows="14" class="field leading-8"></textarea>
                <p class="help">هر خط خالی یک پاراگراف تازه می‌سازد.</p>
                <p class="error-text" data-error-for="body"></p>
            </div>

            <div>
                <label class="label" for="pg-sort">ترتیب نمایش</label>
                <input id="pg-sort" name="sort_order" type="number" min="0" class="field ltr text-end" value="0">
                <p class="error-text" data-error-for="sort_order"></p>
            </div>

            <div class="flex flex-col justify-center gap-3">
                <x-switch name="is_published" :checked="true" label="منتشر شود" />
                <x-switch name="in_footer" label="نمایش در فوتر" />
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره صفحه</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgCrud({
            modal: 'page-form',
            form: '#page-form-el',
            storeUrl: '{{ route('admin.pages.store') }}',
            baseUrl: '{{ url('admin/pages') }}',
            resource: 'page',
            createTitle: 'صفحه جدید',
            fields: ['title', 'slug', 'body', 'sort_order'],
            booleans: ['is_published', 'in_footer'],
        });
    });
</script>
@endpush
