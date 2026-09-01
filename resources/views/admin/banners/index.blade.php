@extends('layouts.admin')

@section('title', 'بنرها')
@section('heading', 'بنرها و اسلایدرها')
@section('subheading', fa_number($activeCount) . ' بنر فعال از مجموع ' . fa_number($total) . ' بنر')

@section('breadcrumb')
    <span class="text-ink-700">بنرها</span>
@endsection

@section('content')
<div>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-card bg-white p-4 shadow-card">
        <div class="flex flex-wrap items-center gap-1.5">
            <a href="{{ route('admin.banners.index') }}"
               @class([
                   'rounded-pill px-3 py-1.5 text-2xs transition-colors',
                   'bg-brand-50 font-bold text-brand-600' => ! request('position'),
                   'text-ink-600 hover:bg-ink-50' => (bool) request('position'),
               ])>همه جایگاه‌ها</a>

            @foreach($positions as $key => $label)
                <a href="{{ route('admin.banners.index', ['position' => $key]) }}"
                   @class([
                       'rounded-pill px-3 py-1.5 text-2xs transition-colors',
                       'bg-brand-50 font-bold text-brand-600' => request('position') === $key,
                       'text-ink-600 hover:bg-ink-50' => request('position') !== $key,
                   ])>{{ $label }}</a>
            @endforeach
        </div>

        <button type="button" class="btn-primary btn-sm" data-modal-open="banner-form" data-crud-new="banner-form">
            <x-icon name="plus" class="h-4 w-4" />
            بنر جدید
        </button>
    </div>

    @forelse($banners as $position => $group)
        <section class="mb-5">
            <h2 class="mb-3 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="image" class="h-5 w-5 text-brand-500" />
                {{ $positions[$position] ?? $position }}
                <span class="text-[11px] font-normal text-ink-400">{{ fa_number($group->count()) }} بنر</span>
            </h2>

            <div class="grid gap-3 stagger sm:grid-cols-2 xl:grid-cols-3">
                @foreach($group as $banner)
                    <article data-banner-row class="overflow-hidden rounded-card bg-white shadow-card transition-shadow hover:shadow-card-hover" data-reveal>
                        <div class="relative aspect-[16/7] bg-ink-100" style="{{ $banner->bg_color ? 'background:'.$banner->bg_color : '' }}">
                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="h-full w-full object-cover" loading="lazy"
                                 onerror="this.src='{{ asset('images/placeholder-product.svg') }}';this.classList.add('object-contain','p-6')">

                            <span class="absolute top-2 flex items-center gap-1" style="inset-inline-start:.5rem">
                                @if($banner->is_active)
                                    <span class="badge-green">فعال</span>
                                @else
                                    <span class="badge-gray">غیرفعال</span>
                                @endif
                                @if($banner->ends_at && $banner->ends_at->isPast())
                                    <span class="badge-red">پایان‌یافته</span>
                                @endif
                            </span>
                        </div>

                        <div class="p-4">
                            <p class="truncate text-2xs font-bold text-ink-900">{{ $banner->title ?: 'بدون عنوان' }}</p>
                            @if($banner->subtitle)
                                <p class="mt-0.5 truncate text-[11px] text-ink-500">{{ $banner->subtitle }}</p>
                            @endif
                            @if($banner->link)
                                <p class="ltr mt-1.5 truncate text-[10px] text-info-600">{{ $banner->link }}</p>
                            @endif

                            <div class="mt-3 flex items-center gap-1 border-t border-ink-100 pt-3">
                                <button type="button" class="btn-ghost btn-sm flex-1"
                                        data-crud-edit="banner-form" data-crud-url="{{ route('admin.banners.show', $banner) }}"
                                        data-crud-title="ویرایش بنر">
                                    <x-icon name="edit" class="h-4 w-4" />
                                    ویرایش
                                </button>
                                <button type="button" class="btn-ghost btn-sm flex-1"
                                        data-action="{{ route('admin.banners.toggle', $banner) }}" data-method="POST" data-reload>
                                    <x-icon :name="$banner->is_active ? 'eye-off' : 'eye'" class="h-4 w-4" />
                                    {{ $banner->is_active ? 'غیرفعال' : 'فعال' }}
                                </button>
                                <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" aria-label="حذف بنر"
                                        data-action="{{ route('admin.banners.destroy', $banner) }}" data-method="DELETE"
                                        data-remove-row="[data-banner-row]"
                                        data-confirm="این بنر حذف شود؟" data-confirm-title="حذف بنر" data-confirm-accept="حذف کن">
                                    <x-icon name="trash" class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @empty
        <div class="rounded-card bg-white shadow-card">
            <x-empty-state icon="image" title="بنری ثبت نشده است"
                           message="با ساخت بنر، صفحه اصلی فروشگاه را جذاب‌تر کنید." />
        </div>
    @endforelse
</div>

<x-modal id="banner-form" title="بنر جدید" size="lg">
    <form data-ajax-form id="banner-form-el" action="{{ route('admin.banners.store') }}" data-method="POST" data-reload>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="label" for="ba-title">عنوان</label>
                <input id="ba-title" name="title" class="field" placeholder="جشنواره فروش پاییزه">
                <p class="error-text" data-error-for="title"></p>
            </div>

            <div>
                <label class="label" for="ba-subtitle">زیرعنوان</label>
                <input id="ba-subtitle" name="subtitle" class="field" placeholder="تا ۴۰٪ تخفیف">
                <p class="error-text" data-error-for="subtitle"></p>
            </div>

            <div class="sm:col-span-2">
                <label class="label" for="ba-caption">توضیح کوتاه</label>
                <input id="ba-caption" name="caption" class="field">
                <p class="error-text" data-error-for="caption"></p>
            </div>

            <div>
                <label class="label" for="ba-image">تصویر دسکتاپ <span class="text-brand-500">*</span></label>
                <input id="ba-image" name="image" class="field ltr" placeholder="images/banners/hero-1.jpg">
                <p class="error-text" data-error-for="image"></p>
            </div>

            <div>
                <label class="label" for="ba-mobile">تصویر موبایل</label>
                <input id="ba-mobile" name="mobile_image" class="field ltr" placeholder="images/banners/hero-1-m.jpg">
                <p class="error-text" data-error-for="mobile_image"></p>
            </div>

            <div>
                <label class="label" for="ba-link">لینک مقصد</label>
                <input id="ba-link" name="link" class="field ltr" placeholder="/shop?special=1">
                <p class="error-text" data-error-for="link"></p>
            </div>

            <div>
                <label class="label" for="ba-cta">متن دکمه</label>
                <input id="ba-cta" name="cta_label" class="field" placeholder="مشاهده تخفیف‌ها">
                <p class="error-text" data-error-for="cta_label"></p>
            </div>

            <div>
                <label class="label" for="ba-position">جایگاه نمایش <span class="text-brand-500">*</span></label>
                <select id="ba-position" name="position" class="field">
                    @foreach($positions as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
                <p class="error-text" data-error-for="position"></p>
            </div>

            <div>
                <label class="label" for="ba-color">رنگ پس‌زمینه</label>
                <input id="ba-color" name="bg_color" class="field ltr" placeholder="#FDEBEC">
                <p class="error-text" data-error-for="bg_color"></p>
            </div>

            <div>
                <label class="label" for="ba-start">شروع نمایش</label>
                <input id="ba-start" name="starts_at" type="datetime-local" class="field ltr">
                <p class="error-text" data-error-for="starts_at"></p>
            </div>

            <div>
                <label class="label" for="ba-end">پایان نمایش</label>
                <input id="ba-end" name="ends_at" type="datetime-local" class="field ltr">
                <p class="error-text" data-error-for="ends_at"></p>
            </div>

            <div>
                <label class="label" for="ba-sort">ترتیب</label>
                <input id="ba-sort" name="sort_order" type="number" min="0" class="field ltr text-end" value="0">
            </div>

            <div class="flex items-end pb-2">
                <x-switch name="is_active" :checked="true" label="بنر فعال باشد" />
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <button type="button" class="btn-ghost" data-modal-close>انصراف</button>
            <button type="submit" class="btn-primary">
                <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                <span data-submit-text>ذخیره بنر</span>
            </button>
        </div>
    </form>
</x-modal>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        dgCrud({
            modal: 'banner-form',
            form: '#banner-form-el',
            storeUrl: '{{ route('admin.banners.store') }}',
            baseUrl: '{{ url('admin/banners') }}',
            resource: 'banner',
            createTitle: 'بنر جدید',
            fields: ['title', 'subtitle', 'caption', 'image', 'mobile_image', 'link', 'cta_label',
                     'position', 'bg_color', 'starts_at', 'ends_at', 'sort_order'],
            booleans: ['is_active'],
        });
    });
</script>
@endpush
