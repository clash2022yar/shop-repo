@php $threshold = config('digino.catalog.low_stock_threshold'); @endphp

@forelse($products as $product)
    <tr data-stock-row="{{ $product->id }}" class="animate-fade-in">
        <td>
            <div class="flex items-center gap-3">
                <img src="{{ asset($product->primary_image) }}" alt="" class="h-11 w-11 shrink-0 rounded-lg bg-ink-50 object-contain" loading="lazy">
                <div class="min-w-0">
                    <a href="{{ route('admin.products.edit', $product) }}" class="line-clamp-1 max-w-xs text-2xs text-ink-900 transition-colors hover:text-brand-500">
                        {{ $product->name }}
                    </a>
                    <span class="ltr mt-0.5 block text-[10px] text-ink-400">{{ $product->sku }}</span>
                </div>
            </div>
        </td>

        <td class="text-2xs text-ink-600">{{ $product->category?->name ?? '—' }}</td>
        <td class="text-2xs text-ink-600">{{ $product->brand?->name ?? '—' }}</td>

        <td>
            <span data-stock-value class="text-sm font-extrabold text-ink-900">{{ fa_number($product->stock) }}</span>
            <span class="text-[10px] text-ink-400"> عدد</span>
        </td>

        <td>
            @if($product->stock <= 0)
                <span class="badge-red">ناموجود</span>
            @elseif($product->stock <= $threshold)
                <span class="badge-amber">رو به اتمام</span>
            @else
                <span class="badge-green">موجود</span>
            @endif
        </td>

        <td class="whitespace-nowrap text-2xs text-ink-600">{{ fa_number(toman($product->stock * $product->price)) }}</td>

        <td>
            <div class="flex items-center gap-1">
                <button type="button" class="btn-outline btn-sm"
                        data-stock-adjust="{{ $product->id }}"
                        data-url="{{ route('admin.inventory.adjust', $product) }}"
                        data-name="{{ $product->name }}"
                        data-stock="{{ $product->stock }}">
                    <x-icon name="edit" class="h-4 w-4" />
                    اصلاح موجودی
                </button>
                <button type="button" class="btn-icon h-8 w-8" aria-label="گردش انبار"
                        data-stock-history="{{ route('admin.inventory.history', $product) }}">
                    <x-icon name="clock" class="h-4 w-4" />
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">
            <x-empty-state icon="warehouse" title="کالایی یافت نشد" message="فیلترها را تغییر دهید." />
        </td>
    </tr>
@endforelse
