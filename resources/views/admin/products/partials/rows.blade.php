@forelse($products as $product)
    <tr data-row-id="{{ $product->id }}" class="animate-fade-in">
        <td class="w-10">
            <input type="checkbox" class="checkbox" data-bulk-item value="{{ $product->id }}"
                   aria-label="انتخاب {{ $product->name }}">
        </td>

        <td>
            <div class="flex items-center gap-3">
                <img src="{{ asset($product->primary_image) }}" alt="" class="h-11 w-11 shrink-0 rounded-lg bg-ink-50 object-contain" loading="lazy">
                <div class="min-w-0">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="line-clamp-1 max-w-xs text-2xs font-medium text-ink-900 transition-colors hover:text-brand-500">
                        {{ $product->name }}
                    </a>
                    <p class="ltr mt-0.5 text-[10px] text-ink-400">{{ $product->sku }}</p>
                </div>
            </div>
        </td>

        <td class="text-2xs text-ink-600">{{ $product->category?->name ?? '—' }}</td>
        <td class="text-2xs text-ink-600">{{ $product->brand?->name ?? '—' }}</td>

        <td>
            <span class="text-2xs font-bold text-ink-900">{{ fa_number(toman($product->price)) }}</span>
            @if($product->has_discount)
                <span class="mt-0.5 block text-[10px] text-brand-500">{{ fa_number($product->discount_percent) }}٪ تخفیف</span>
            @endif
        </td>

        <td>
            @if($product->stock <= 0)
                <span class="badge-red">ناموجود</span>
            @elseif($product->stock <= config('digino.catalog.low_stock_threshold'))
                <span class="badge-amber">{{ fa_number($product->stock) }} عدد</span>
            @else
                <span class="badge-green">{{ fa_number($product->stock) }} عدد</span>
            @endif
        </td>

        <td class="text-2xs text-ink-600">{{ fa_number($product->sold_count) }}</td>

        <td>
            <button type="button" class="switch {{ $product->is_active ? 'bg-success-500' : '' }}"
                    data-action="{{ route('admin.products.toggle', $product) }}" data-method="POST"
                    aria-label="تغییر وضعیت انتشار"
                    onclick="this.classList.toggle('bg-success-500')">
                <span></span>
            </button>
        </td>

        <td>
            <div class="flex items-center gap-1">
                <a href="{{ route('products.show', $product->slug) }}" target="_blank" rel="noopener"
                   class="btn-icon h-8 w-8" aria-label="مشاهده در فروشگاه">
                    <x-icon name="external" class="h-4 w-4" />
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-icon h-8 w-8" aria-label="ویرایش">
                    <x-icon name="edit" class="h-4 w-4" />
                </a>
                <button type="button" class="btn-icon h-8 w-8" aria-label="ساخت نسخه کپی"
                        data-action="{{ route('admin.products.duplicate', $product) }}" data-method="POST">
                    <x-icon name="copy" class="h-4 w-4" />
                </button>
                <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف"
                        data-action="{{ route('admin.products.destroy', $product) }}" data-method="DELETE"
                        data-remove-row="tr"
                        data-confirm="کالای «{{ $product->name }}» حذف شود؟"
                        data-confirm-title="حذف کالا" data-confirm-accept="حذف کن">
                    <x-icon name="trash" class="h-4 w-4" />
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9">
            <x-empty-state icon="box" title="کالایی یافت نشد"
                           message="فیلترها را تغییر دهید یا کالای تازه‌ای اضافه کنید."
                           :action-url="route('admin.products.create')" action-label="افزودن کالا" />
        </td>
    </tr>
@endforelse
