@php
    /** @var \App\Models\Product|null $product */
    $product = $product ?? null;
    $editing = (bool) $product;
    $images = $editing ? $product->images->sortBy('sort_order')->pluck('path')->values()->all() : [];
    $attributes = $editing ? $product->attributes->sortBy('sort_order')->values() : collect();
    $variants = $editing ? $product->variants->sortBy('sort_order')->values() : collect();
@endphp

<form data-ajax-form
      action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}"
      data-method="{{ $editing ? 'PUT' : 'POST' }}"
      class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_20rem] xl:items-start">

    {{-- ═══════════════════ main column ═══════════════════ --}}
    <div class="space-y-4">

        {{-- basic info --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="info" class="h-5 w-5 text-brand-500" />
                اطلاعات پایه
            </h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="label" for="p-name">نام کالا <span class="text-brand-500">*</span></label>
                    <input id="p-name" name="name" class="field" value="{{ old('name', $product?->name) }}"
                           placeholder="مثال: گوشی موبایل سامسونگ گلکسی S24 اولترا ظرفیت ۲۵۶ گیگابایت">
                    <p class="error-text" data-error-for="name"></p>
                </div>

                <div>
                    <label class="label" for="p-name-en">نام انگلیسی</label>
                    <input id="p-name-en" name="name_en" class="field ltr" value="{{ old('name_en', $product?->name_en) }}"
                           placeholder="Samsung Galaxy S24 Ultra 256GB">
                    <p class="error-text" data-error-for="name_en"></p>
                </div>

                <div>
                    <label class="label" for="p-slug">نشانی اینترنتی (اختیاری)</label>
                    <input id="p-slug" name="slug" class="field ltr" value="{{ old('slug', $product?->slug) }}"
                           placeholder="به‌صورت خودکار ساخته می‌شود">
                    <p class="help">در صورت خالی بودن از روی نام کالا ساخته می‌شود.</p>
                    <p class="error-text" data-error-for="slug"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="label" for="p-subtitle">زیرعنوان</label>
                    <input id="p-subtitle" name="subtitle" class="field" value="{{ old('subtitle', $product?->subtitle) }}"
                           placeholder="یک جمله کوتاه که زیر نام کالا نمایش داده می‌شود">
                    <p class="error-text" data-error-for="subtitle"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="label" for="p-short">توضیح کوتاه</label>
                    <textarea id="p-short" name="short_description" rows="3" class="field"
                              placeholder="خلاصه‌ای از ویژگی‌های کلیدی کالا">{{ old('short_description', $product?->short_description) }}</textarea>
                    <p class="error-text" data-error-for="short_description"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="label" for="p-desc">توضیح کامل</label>
                    <textarea id="p-desc" name="description" rows="9" class="field leading-8"
                              placeholder="نقد و بررسی کامل کالا...">{{ old('description', $product?->description) }}</textarea>
                    <p class="help">هر خط خالی یک پاراگراف تازه در صفحه کالا می‌سازد.</p>
                    <p class="error-text" data-error-for="description"></p>
                </div>
            </div>
        </section>

        {{-- pricing --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="tag" class="h-5 w-5 text-brand-500" />
                قیمت و موجودی
            </h2>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="label" for="p-price">قیمت فروش (ریال) <span class="text-brand-500">*</span></label>
                    <input id="p-price" name="price" class="field ltr text-end" data-numeric inputmode="numeric"
                           value="{{ old('price', $product?->price) }}" placeholder="0">
                    <p class="error-text" data-error-for="price"></p>
                </div>

                <div>
                    <label class="label" for="p-compare">قیمت پیش از تخفیف</label>
                    <input id="p-compare" name="compare_at_price" class="field ltr text-end" data-numeric inputmode="numeric"
                           value="{{ old('compare_at_price', $product?->compare_at_price) }}" placeholder="0">
                    <p class="error-text" data-error-for="compare_at_price"></p>
                </div>

                <div>
                    <label class="label" for="p-stock">موجودی انبار <span class="text-brand-500">*</span></label>
                    <input id="p-stock" name="stock" class="field ltr text-end" data-numeric inputmode="numeric"
                           value="{{ old('stock', $product?->stock ?? 0) }}" placeholder="0">
                    <p class="error-text" data-error-for="stock"></p>
                </div>

                <div>
                    <label class="label" for="p-max">حداکثر در هر سفارش</label>
                    <input id="p-max" name="max_per_order" type="number" min="1" max="20" class="field ltr text-end"
                           value="{{ old('max_per_order', $product?->max_per_order ?? config('digino.cart.max_qty_per_item')) }}">
                    <p class="error-text" data-error-for="max_per_order"></p>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-3 rounded-field bg-ink-50 p-3">
                <x-icon name="calculator" class="h-5 w-5 shrink-0 text-ink-400" />
                <p class="text-2xs text-ink-600">
                    درصد تخفیف محاسبه‌شده:
                    <span data-discount-preview class="font-bold text-brand-500">۰٪</span>
                    — سود مشتری: <span data-saving-preview class="font-bold text-success-600">۰</span> تومان
                </p>
            </div>
        </section>

        {{-- images --}}
        <section class="rounded-card bg-white p-5 shadow-card" data-repeater="images">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="image" class="h-5 w-5 text-brand-500" />
                    تصاویر کالا
                </h2>
                <button type="button" class="btn-outline btn-sm" data-repeater-add>
                    <x-icon name="plus" class="h-4 w-4" />
                    افزودن تصویر
                </button>
            </div>

            <p class="mb-3 text-2xs leading-6 text-ink-500">
                مسیر فایل تصویر را نسبت به پوشه <span class="ltr font-bold">public/</span> وارد کنید؛ نخستین تصویر به‌عنوان تصویر اصلی کالا استفاده می‌شود.
            </p>

            <div data-repeater-list class="space-y-2">
                @foreach($images ?: [''] as $i => $path)
                    <div data-repeater-row class="flex items-center gap-2 animate-fade-in">
                        <img src="{{ $path ? asset($path) : asset('images/placeholder-product.svg') }}" alt=""
                             class="h-12 w-12 shrink-0 rounded-lg border border-ink-200 bg-ink-50 object-contain"
                             data-image-preview
                             onerror="this.src='{{ asset('images/placeholder-product.svg') }}'">
                        <input type="text" name="images[]" class="field-sm ltr flex-1" value="{{ $path }}"
                               placeholder="images/products/example.jpg"
                               oninput="this.previousElementSibling.src = this.value ? '/' + this.value.replace(/^\//, '') : '{{ asset('images/placeholder-product.svg') }}'">
                        <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" data-repeater-remove aria-label="حذف تصویر">
                            <x-icon name="trash" class="h-4 w-4" />
                        </button>
                    </div>
                @endforeach
            </div>

            <template data-repeater-template>
                <div data-repeater-row class="flex items-center gap-2 animate-fade-in">
                    <img src="{{ asset('images/placeholder-product.svg') }}" alt=""
                         class="h-12 w-12 shrink-0 rounded-lg border border-ink-200 bg-ink-50 object-contain" data-image-preview>
                    <input type="text" name="images[]" class="field-sm ltr flex-1" placeholder="images/products/example.jpg"
                           oninput="this.previousElementSibling.src = this.value ? '/' + this.value.replace(/^\//, '') : '{{ asset('images/placeholder-product.svg') }}'">
                    <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" data-repeater-remove aria-label="حذف تصویر">
                        <x-icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </template>
        </section>

        {{-- attributes --}}
        <section class="rounded-card bg-white p-5 shadow-card" data-repeater="attributes">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="list" class="h-5 w-5 text-brand-500" />
                    مشخصات فنی
                </h2>
                <button type="button" class="btn-outline btn-sm" data-repeater-add>
                    <x-icon name="plus" class="h-4 w-4" />
                    افزودن مشخصه
                </button>
            </div>

            <div data-repeater-list class="space-y-2">
                @forelse($attributes as $i => $attr)
                    <div data-repeater-row class="grid items-center gap-2 rounded-field bg-ink-50 p-2 animate-fade-in sm:grid-cols-[9rem_1fr_1.4fr_auto_auto]">
                        <input type="text" name="attributes[{{ $i }}][group]" class="field-sm" value="{{ $attr->group }}" placeholder="گروه">
                        <input type="text" name="attributes[{{ $i }}][name]" class="field-sm" value="{{ $attr->name }}" placeholder="عنوان">
                        <input type="text" name="attributes[{{ $i }}][value]" class="field-sm" value="{{ $attr->value }}" placeholder="مقدار">
                        <label class="flex items-center gap-1.5 whitespace-nowrap px-1 text-[11px] text-ink-600">
                            <input type="checkbox" class="checkbox" name="attributes[{{ $i }}][is_key]" value="1" @checked($attr->is_key)>
                            کلیدی
                        </label>
                        <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" data-repeater-remove aria-label="حذف مشخصه">
                            <x-icon name="trash" class="h-4 w-4" />
                        </button>
                    </div>
                @empty
                    <p data-repeater-empty class="hidden [&:only-child]:block rounded-field bg-ink-50 py-6 text-center text-2xs text-ink-500">
                        هنوز مشخصه‌ای ثبت نشده است.
                    </p>
                @endforelse
            </div>

            <template data-repeater-template>
                <div data-repeater-row class="grid items-center gap-2 rounded-field bg-ink-50 p-2 animate-fade-in sm:grid-cols-[9rem_1fr_1.4fr_auto_auto]">
                    <input type="text" name="attributes[__INDEX__][group]" class="field-sm" value="مشخصات کلی" placeholder="گروه">
                    <input type="text" name="attributes[__INDEX__][name]" class="field-sm" placeholder="عنوان">
                    <input type="text" name="attributes[__INDEX__][value]" class="field-sm" placeholder="مقدار">
                    <label class="flex items-center gap-1.5 whitespace-nowrap px-1 text-[11px] text-ink-600">
                        <input type="checkbox" class="checkbox" name="attributes[__INDEX__][is_key]" value="1">
                        کلیدی
                    </label>
                    <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" data-repeater-remove aria-label="حذف مشخصه">
                        <x-icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </template>
        </section>

        {{-- variants --}}
        <section class="rounded-card bg-white p-5 shadow-card" data-repeater="variants">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
                    <x-icon name="palette" class="h-5 w-5 text-brand-500" />
                    تنوع‌های کالا
                </h2>
                <button type="button" class="btn-outline btn-sm" data-repeater-add>
                    <x-icon name="plus" class="h-4 w-4" />
                    افزودن تنوع
                </button>
            </div>

            <div data-repeater-list class="space-y-2">
                @forelse($variants as $i => $variant)
                    <div data-repeater-row class="grid items-center gap-2 rounded-field bg-ink-50 p-2 animate-fade-in lg:grid-cols-[1.4fr_1fr_3.5rem_1fr_1fr_auto]">
                        <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant->id }}">
                        <input type="text" name="variants[{{ $i }}][title]" class="field-sm" value="{{ $variant->title }}" placeholder="عنوان تنوع">
                        <input type="text" name="variants[{{ $i }}][color_name]" class="field-sm" value="{{ $variant->color_name }}" placeholder="نام رنگ">
                        <input type="color" name="variants[{{ $i }}][color_hex]" class="h-9 w-full cursor-pointer rounded-field border border-ink-200 bg-white p-1"
                               value="{{ $variant->color_hex ?: '#000000' }}" aria-label="کد رنگ">
                        <input type="text" name="variants[{{ $i }}][price_diff]" class="field-sm ltr text-end" data-numeric value="{{ $variant->price_diff }}" placeholder="اختلاف قیمت">
                        <input type="text" name="variants[{{ $i }}][stock]" class="field-sm ltr text-end" data-numeric value="{{ $variant->stock }}" placeholder="موجودی">
                        <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" data-repeater-remove aria-label="حذف تنوع">
                            <x-icon name="trash" class="h-4 w-4" />
                        </button>
                    </div>
                @empty
                    <p data-repeater-empty class="hidden [&:only-child]:block rounded-field bg-ink-50 py-6 text-center text-2xs text-ink-500">
                        این کالا تنوع رنگ یا اندازه ندارد.
                    </p>
                @endforelse
            </div>

            <template data-repeater-template>
                <div data-repeater-row class="grid items-center gap-2 rounded-field bg-ink-50 p-2 animate-fade-in lg:grid-cols-[1.4fr_1fr_3.5rem_1fr_1fr_auto]">
                    <input type="text" name="variants[__INDEX__][title]" class="field-sm" placeholder="عنوان تنوع">
                    <input type="text" name="variants[__INDEX__][color_name]" class="field-sm" placeholder="نام رنگ">
                    <input type="color" name="variants[__INDEX__][color_hex]" class="h-9 w-full cursor-pointer rounded-field border border-ink-200 bg-white p-1" value="#000000" aria-label="کد رنگ">
                    <input type="text" name="variants[__INDEX__][price_diff]" class="field-sm ltr text-end" data-numeric value="0" placeholder="اختلاف قیمت">
                    <input type="text" name="variants[__INDEX__][stock]" class="field-sm ltr text-end" data-numeric value="0" placeholder="موجودی">
                    <button type="button" class="btn-icon h-9 w-9 hover:text-brand-500" data-repeater-remove aria-label="حذف تنوع">
                        <x-icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </template>
        </section>

        {{-- seo --}}
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 flex items-center gap-2 text-sm font-extrabold text-ink-900">
                <x-icon name="search" class="h-5 w-5 text-brand-500" />
                بهینه‌سازی موتور جستجو
            </h2>

            <div class="grid gap-4">
                <div>
                    <label class="label" for="p-meta-title">عنوان متا</label>
                    <input id="p-meta-title" name="meta_title" class="field" value="{{ old('meta_title', $product?->meta_title) }}">
                    <p class="error-text" data-error-for="meta_title"></p>
                </div>
                <div>
                    <label class="label" for="p-meta-desc">توضیح متا</label>
                    <textarea id="p-meta-desc" name="meta_description" rows="3" class="field">{{ old('meta_description', $product?->meta_description) }}</textarea>
                    <p class="error-text" data-error-for="meta_description"></p>
                </div>
            </div>
        </section>
    </div>

    {{-- ═══════════════════ sidebar ═══════════════════ --}}
    <aside class="space-y-4 xl:sticky xl:top-24">

        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">انتشار</h2>

            <div class="space-y-3">
                <x-switch name="is_active" :checked="old('is_active', $product?->is_active ?? true)" label="نمایش در فروشگاه" help="کالاهای غیرفعال فقط برای مدیران دیده می‌شوند." />
                <x-switch name="is_featured" :checked="old('is_featured', $product?->is_featured ?? false)" label="کالای منتخب" help="در بخش پیشنهاد دیجی‌نو صفحه اصلی نمایش داده می‌شود." />
                <x-switch name="is_special" :checked="old('is_special', $product?->is_special ?? false)" label="فروش ویژه" help="در نوار شگفت‌انگیز صفحه اصلی قرار می‌گیرد." />
            </div>

            <div class="mt-4">
                <label class="label" for="p-special-end">پایان فروش ویژه</label>
                <input id="p-special-end" name="special_ends_at" type="datetime-local" class="field-sm ltr"
                       value="{{ old('special_ends_at', $product?->special_ends_at?->format('Y-m-d\TH:i')) }}">
                <p class="error-text" data-error-for="special_ends_at"></p>
            </div>

            <div class="mt-5 flex flex-col gap-2 border-t border-ink-100 pt-4">
                <button type="submit" class="btn-primary w-full">
                    <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                    <span data-submit-text>{{ $editing ? 'ذخیره تغییرات' : 'ثبت کالا' }}</span>
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn-ghost w-full">انصراف</a>
            </div>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">سازمان‌دهی</h2>

            <div class="space-y-4">
                <div>
                    <label class="label" for="p-category">دسته‌بندی <span class="text-brand-500">*</span></label>
                    <select id="p-category" name="category_id" class="field-sm">
                        <option value="">انتخاب کنید</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                                {{ $category->parent_id ? '— ' : '' }}{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="error-text" data-error-for="category_id"></p>
                </div>

                <div>
                    <label class="label" for="p-brand">برند</label>
                    <select id="p-brand" name="brand_id" class="field-sm">
                        <option value="">بدون برند</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <p class="error-text" data-error-for="brand_id"></p>
                </div>

                <div>
                    <label class="label" for="p-sku">کد کالا</label>
                    <input id="p-sku" name="sku" class="field-sm ltr" value="{{ old('sku', $product?->sku) }}" placeholder="خودکار">
                    <p class="error-text" data-error-for="sku"></p>
                </div>
            </div>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">ارسال و خدمات</h2>

            <div class="space-y-3">
                <x-switch name="free_shipping" :checked="old('free_shipping', $product?->free_shipping ?? false)" label="ارسال رایگان" />
                <x-switch name="has_pickup" :checked="old('has_pickup', $product?->has_pickup ?? false)" label="امکان تحویل حضوری" />
                <x-switch name="is_digino_seller" :checked="old('is_digino_seller', $product?->is_digino_seller ?? true)" label="فروش توسط دیجی‌نو" />
            </div>

            <div class="mt-4 grid gap-4">
                <div>
                    <label class="label" for="p-warranty">گارانتی</label>
                    <input id="p-warranty" name="warranty" class="field-sm" value="{{ old('warranty', $product?->warranty) }}"
                           placeholder="۱۸ ماه گارانتی شرکتی">
                    <p class="error-text" data-error-for="warranty"></p>
                </div>
                <div>
                    <label class="label" for="p-weight">وزن با بسته‌بندی (گرم)</label>
                    <input id="p-weight" name="shipping_weight" type="number" min="0" class="field-sm ltr text-end"
                           value="{{ old('shipping_weight', $product?->shipping_weight) }}">
                    <p class="error-text" data-error-for="shipping_weight"></p>
                </div>
            </div>
        </section>

        @if($editing)
            <section class="rounded-card bg-white p-5 shadow-card">
                <h2 class="mb-4 text-sm font-extrabold text-ink-900">عملیات بیشتر</h2>
                <div class="space-y-2">
                    <a href="{{ route('products.show', $product->slug) }}" target="_blank" rel="noopener" class="btn-outline btn-sm w-full">
                        <x-icon name="external" class="h-4 w-4" />
                        مشاهده در فروشگاه
                    </a>
                    <button type="button" class="btn-outline btn-sm w-full"
                            data-action="{{ route('admin.products.duplicate', $product) }}" data-method="POST">
                        <x-icon name="copy" class="h-4 w-4" />
                        ساخت نسخه کپی
                    </button>
                    <a href="{{ route('admin.inventory.movements') }}" class="btn-outline btn-sm w-full">
                        <x-icon name="warehouse" class="h-4 w-4" />
                        گردش انبار
                    </a>
                    <button type="button" class="btn-outline btn-sm w-full text-brand-500 hover:bg-brand-50"
                            data-action="{{ route('admin.products.destroy', $product) }}" data-method="DELETE"
                            data-redirect="{{ route('admin.products.index') }}"
                            data-confirm="کالای «{{ $product->name }}» برای همیشه حذف شود؟"
                            data-confirm-title="حذف کالا" data-confirm-accept="حذف کن">
                        <x-icon name="trash" class="h-4 w-4" />
                        حذف کالا
                    </button>
                </div>
            </section>

            <section class="rounded-card bg-white p-5 shadow-card">
                <h2 class="mb-4 text-sm font-extrabold text-ink-900">آمار کالا</h2>
                <dl class="space-y-2.5 text-2xs">
                    @foreach([
                        'بازدید' => fa_number($product->views_count),
                        'فروش' => fa_number($product->sold_count),
                        'امتیاز' => fa_number(number_format($product->rating, 1)) . ' از ۵',
                        'دیدگاه‌ها' => fa_number($product->reviews_count),
                        'آخرین ویرایش' => jalali($product->updated_at),
                    ] as $label => $value)
                        <div class="flex items-center justify-between gap-3">
                            <dt class="text-ink-500">{{ $label }}</dt>
                            <dd class="font-bold text-ink-800">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>
        @endif
    </aside>
</form>

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        var price = document.getElementById('p-price');
        var compare = document.getElementById('p-compare');
        var pct = document.querySelector('[data-discount-preview]');
        var save = document.querySelector('[data-saving-preview]');

        function num(el) { return Number(dg.enNumber(String(el.value || '0')).replace(/\D/g, '')) || 0; }

        function update() {
            var p = num(price), c = num(compare);
            var percent = c > p && c > 0 ? Math.round((c - p) / c * 100) : 0;
            pct.textContent = dg.faNumber(percent) + '٪';
            save.textContent = dg.faNumber(Math.max(0, Math.round((c - p) / 10)).toLocaleString('en-US'));
        }

        [price, compare].forEach(function (el) { el.addEventListener('input', update); });
        update();
    });
</script>
@endpush
