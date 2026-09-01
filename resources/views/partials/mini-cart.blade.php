{{-- Header cart dropdown, rendered by ajax.cart.mini --}}
@if($items->isEmpty())
    <div class="px-5 py-10 text-center">
        <span class="mx-auto mb-3 grid h-14 w-14 place-items-center rounded-full bg-ink-100 text-ink-400">
            <x-icon name="cart" class="h-7 w-7" />
        </span>
        <p class="text-sm font-bold text-ink-700">سبد خرید شما خالی است</p>
        <p class="mt-1 text-2xs text-ink-500">هنوز کالایی انتخاب نکرده‌اید.</p>
        <a href="{{ route('shop.index') }}" class="btn-outline mt-4 w-full">شروع خرید</a>
    </div>
@else
    <div class="max-h-80 divide-y divide-ink-100 overflow-y-auto">
        @foreach($items as $item)
            <div class="flex gap-3 p-3 transition-colors hover:bg-ink-50">
                <a href="{{ route('products.show', $item->product->slug) }}" class="shrink-0">
                    <img src="{{ asset($item->product->primary_image) }}" alt="{{ $item->product->name }}"
                         class="h-16 w-16 rounded-lg object-contain" loading="lazy">
                </a>
                <div class="min-w-0 flex-1">
                    <a href="{{ route('products.show', $item->product->slug) }}"
                       class="line-clamp-2 text-2xs leading-5 text-ink-800 transition-colors hover:text-brand-500">
                        {{ $item->product->name }}
                    </a>
                    @if($item->variant)
                        <p class="mt-0.5 text-[10px] text-ink-400">{{ $item->variant->title }}</p>
                    @endif
                    <div class="mt-1 flex items-center justify-between">
                        <span class="text-[10px] text-ink-500">{{ fa_number($item->quantity) }} عدد</span>
                        <span class="text-2xs font-bold text-ink-900">{{ fa_number(toman($item->line_total)) }} تومان</span>
                    </div>
                </div>
                <button type="button" class="btn-icon h-7 w-7 shrink-0 self-start hover:text-brand-500"
                        data-cart-remove="{{ $item->id }}" aria-label="حذف {{ $item->product->name }}">
                    <x-icon name="trash" class="h-4 w-4" />
                </button>
            </div>
        @endforeach
    </div>

    <div class="border-t border-ink-100 bg-ink-50 p-4">
        <div class="mb-3 flex items-center justify-between text-sm">
            <span class="text-ink-600">مبلغ قابل پرداخت</span>
            <span class="font-extrabold text-ink-900">
                {{ fa_number($summary['formatted']['grand_total']) }}
                <span class="text-2xs font-medium text-ink-600">تومان</span>
            </span>
        </div>
        <a href="{{ route('cart.index') }}" class="btn-primary w-full">مشاهده سبد خرید</a>
    </div>
@endif
