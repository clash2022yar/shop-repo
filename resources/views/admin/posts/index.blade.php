@extends('layouts.admin')

@section('title', 'وبلاگ')
@section('heading', 'مطالب وبلاگ')
@section('subheading', fa_number($counts['published']) . ' مطلب منتشر شده و ' . fa_number($counts['draft']) . ' پیش‌نویس')

@section('breadcrumb')
    <span class="text-ink-700">وبلاگ</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <form method="GET" class="relative w-full max-w-sm">
            <input type="search" name="q" value="{{ request('q') }}" class="field-sm ps-9" placeholder="جستجوی عنوان مطلب...">
            <x-icon name="search" class="pointer-events-none absolute top-1/2 h-4 w-4 -translate-y-1/2 text-ink-400" style="inset-inline-start:.75rem" />
        </form>

        <a href="{{ route('admin.posts.create') }}" class="btn-primary btn-sm">
            <x-icon name="plus" class="h-4 w-4" />
            نوشتن مطلب
        </a>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>نویسنده</th>
                        <th>تاریخ انتشار</th>
                        <th>بازدید</th>
                        <th>وضعیت</th>
                        <th class="w-32">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr data-post-row class="animate-fade-in">
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $post->cover ? asset($post->cover) : asset('images/placeholder-product.svg') }}" alt=""
                                         class="h-11 w-16 shrink-0 rounded-lg bg-ink-50 object-cover" loading="lazy"
                                         onerror="this.src='{{ asset('images/placeholder-product.svg') }}'">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="line-clamp-2 max-w-sm text-2xs font-medium text-ink-900 transition-colors hover:text-brand-500">
                                        {{ $post->title }}
                                    </a>
                                </div>
                            </td>
                            <td class="text-2xs text-ink-600">{{ $post->author?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap text-2xs text-ink-500">{{ jalali($post->published_at) }}</td>
                            <td class="text-2xs text-ink-600">{{ fa_number($post->views_count) }}</td>
                            <td>
                                @if($post->is_published)
                                    <span class="badge-green">منتشر شده</span>
                                @else
                                    <span class="badge-gray">پیش‌نویس</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="btn-icon h-8 w-8" aria-label="مشاهده">
                                        <x-icon name="external" class="h-4 w-4" />
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn-icon h-8 w-8" aria-label="ویرایش">
                                        <x-icon name="edit" class="h-4 w-4" />
                                    </a>
                                    <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف"
                                            data-action="{{ route('admin.posts.destroy', $post) }}" data-method="DELETE"
                                            data-remove-row="[data-post-row]"
                                            data-confirm="مطلب «{{ $post->title }}» حذف شود؟" data-confirm-title="حذف مطلب" data-confirm-accept="حذف کن">
                                        <x-icon name="trash" class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-empty-state icon="book" title="مطلبی وجود ندارد"
                                               message="نخستین مطلب وبلاگ دیجی‌نو را بنویسید."
                                               :action-url="route('admin.posts.create')" action-label="نوشتن مطلب" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $posts->links() }}</div>
</div>
@endsection
