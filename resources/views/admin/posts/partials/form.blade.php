@php
    /** @var \App\Models\Post|null $post */
    $post = $post ?? null;
    $editing = (bool) $post;
@endphp

<form data-ajax-form
      action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      data-method="{{ $editing ? 'PUT' : 'POST' }}"
      class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_20rem] xl:items-start">

    <div class="space-y-4">
        <section class="rounded-card bg-white p-5 shadow-card">
            <div class="grid gap-4">
                <div>
                    <label class="label" for="po-title">عنوان مطلب <span class="text-brand-500">*</span></label>
                    <input id="po-title" name="title" class="field" value="{{ old('title', $post?->title) }}"
                           placeholder="راهنمای خرید گوشی مناسب عکاسی">
                    <p class="error-text" data-error-for="title"></p>
                </div>

                <div>
                    <label class="label" for="po-slug">نشانی اینترنتی</label>
                    <input id="po-slug" name="slug" class="field ltr" value="{{ old('slug', $post?->slug) }}" placeholder="خودکار از روی عنوان">
                    <p class="error-text" data-error-for="slug"></p>
                </div>

                <div>
                    <label class="label" for="po-excerpt">چکیده</label>
                    <textarea id="po-excerpt" name="excerpt" rows="3" class="field"
                              placeholder="در چند جمله بگویید خواننده چه چیزی یاد می‌گیرد.">{{ old('excerpt', $post?->excerpt) }}</textarea>
                    <p class="error-text" data-error-for="excerpt"></p>
                </div>

                <div>
                    <label class="label" for="po-body">متن مطلب</label>
                    <textarea id="po-body" name="body" rows="18" class="field leading-8">{{ old('body', $post?->body) }}</textarea>
                    <p class="help">هر خط خالی یک پاراگراف تازه می‌سازد.</p>
                    <p class="error-text" data-error-for="body"></p>
                </div>
            </div>
        </section>
    </div>

    <aside class="space-y-4 xl:sticky xl:top-24">
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">انتشار</h2>

            <x-switch name="is_published" :checked="old('is_published', $post?->is_published ?? true)" label="منتشر شود" />

            <div class="mt-4">
                <label class="label" for="po-date">زمان انتشار</label>
                <input id="po-date" name="published_at" type="datetime-local" class="field-sm ltr"
                       value="{{ old('published_at', $post?->published_at?->format('Y-m-d\TH:i')) }}">
                <p class="error-text" data-error-for="published_at"></p>
            </div>

            <div class="mt-4">
                <label class="label" for="po-read">زمان مطالعه (دقیقه)</label>
                <input id="po-read" name="read_minutes" type="number" min="1" max="120" class="field-sm ltr text-end"
                       value="{{ old('read_minutes', $post?->read_minutes ?? 5) }}">
                <p class="error-text" data-error-for="read_minutes"></p>
            </div>

            <div class="mt-5 flex flex-col gap-2 border-t border-ink-100 pt-4">
                <button type="submit" class="btn-primary w-full">
                    <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                    <span data-submit-text>{{ $editing ? 'ذخیره تغییرات' : 'انتشار مطلب' }}</span>
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn-ghost w-full">انصراف</a>
            </div>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">دسته و تصویر</h2>

            <div class="grid gap-4">
                <div>
                    <label class="label" for="po-category">دسته‌بندی</label>
                    <select id="po-category" name="category_id" class="field-sm">
                        <option value="">بدون دسته‌بندی</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $post?->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="error-text" data-error-for="category_id"></p>
                </div>

                <div>
                    <label class="label" for="po-cover">تصویر شاخص</label>
                    <input id="po-cover" name="cover" class="field-sm ltr" value="{{ old('cover', $post?->cover) }}"
                           placeholder="images/blog/guide.jpg">
                    <p class="error-text" data-error-for="cover"></p>
                </div>

                <img src="{{ $post?->cover ? asset($post->cover) : asset('images/placeholder-product.svg') }}" alt=""
                     class="aspect-[16/9] w-full rounded-field border border-ink-200 bg-ink-50 object-cover"
                     onerror="this.src='{{ asset('images/placeholder-product.svg') }}'">
            </div>
        </section>

        @if($editing)
            <section class="rounded-card bg-white p-5 shadow-card">
                <h2 class="mb-4 text-sm font-extrabold text-ink-900">آمار</h2>
                <dl class="space-y-2.5 text-2xs">
                    <div class="flex items-center justify-between"><dt class="text-ink-500">بازدید</dt><dd class="font-bold text-ink-800">{{ fa_number($post->views_count) }}</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-ink-500">نویسنده</dt><dd class="font-bold text-ink-800">{{ $post->author?->name ?? '—' }}</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-ink-500">آخرین ویرایش</dt><dd class="font-bold text-ink-800">{{ jalali($post->updated_at) }}</dd></div>
                </dl>

                <div class="mt-4 flex flex-col gap-2 border-t border-ink-100 pt-4">
                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" rel="noopener" class="btn-outline btn-sm w-full">
                        <x-icon name="external" class="h-4 w-4" />
                        مشاهده در وبلاگ
                    </a>
                    <button type="button" class="btn-outline btn-sm w-full text-brand-500 hover:bg-brand-50"
                            data-action="{{ route('admin.posts.destroy', $post) }}" data-method="DELETE"
                            data-redirect="{{ route('admin.posts.index') }}"
                            data-confirm="مطلب «{{ $post->title }}» حذف شود؟" data-confirm-title="حذف مطلب" data-confirm-accept="حذف کن">
                        <x-icon name="trash" class="h-4 w-4" />
                        حذف مطلب
                    </button>
                </div>
            </section>
        @endif
    </aside>
</form>
