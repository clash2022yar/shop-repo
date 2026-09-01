{{-- A single cart row: checkbox, image, title/attrs, price, qty stepper --}}
<div class="flex gap-4 p-4 transition-colors duration-200 hover:bg-ink-50/60" data-cart-line="{{ $item->id }}">

    <label class="shrink-0 pt-1">
        <input type="checkbox" class="checkbox" data-cart-select="{{ $item->id }}"
               {{ $item->is_selected ? 'checked' : '' }}
               aria-label="انتخاب {{ $item->product->name }}">
    </label>

    <a href="{{ route('products.show', $item->product->slug) }}" class="shrink-0">
        <img src="{{ asset($item->product->primary_image) }}" alt="{{ $item->product->name }}"
             class="h-24 w-24 rounded-lg object-contain transition-transform duration-300 hover:scale-105 md:h-28 md:w-28"
             loading="lazy" width="112" height="112">
    </a>

    <div class="flex min-w-0 flex-1 flex-col gap-2">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div class="min-w-0 flex-1">
                <a href="{{ route('products.show', $item->product->slug) }}"
                   class="line-clamp-2 text-sm font-medium leading-6 text-ink-800 transition-colors hover:text-brand-500">
                    {{ $item->product->name }}
                </a>

                <div class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-2xs text-ink-500">
                    @if($item->variant?->color_name)
                        <span class="flex items-center gap-1.5">
                            رنگ:
                            <span class="inline-block h-3 w-3 rounded-full ring-1 ring-ink-200"
                                  style="background-color: {{ $item->variant->color_hex ?: '#ccc' }}"></span>
                            {{ $item->variant->color_name }}
                        </span>
                    @endif
                    @if($item->variant?->option_value)
                        <span>{{ $item->variant->option_name }}: {{ $item->variant->option_value }}</span>
                    @endif
                    @if($item->product->warranty)
                        <span class="flex items-center gap-1">
                            <x-icon name="shield-check" class="h-3.5 w-3.5 text-success-500" />
                            {{ $item->product->warranty }}
                        </span>
                    @endif
                </div>

                @if($item->available_stock <= config('digino.catalog.low_stock_threshold'))
                    <p class="mt-1.5 flex items-center gap-1 text-2xs font-bold text-brand-500">
                        <x-icon name="alert" class="h-3.5 w-3.5" />
                        تنها {{ fa_number($item->available_stock) }} عدد در انبار باقی مانده
                    </p>
                @endif
            </div>

            <div class="text-end">
                @if($item->product->has_discount)
                    <p class="price-old">{{ fa_number(toman($item->product->compare_at_price * $item->quantity)) }}</p>
                @endif
                <p class="text-sm font-extrabold text-ink-900" data-line-total="{{ $item->id }}">
                    {{ fa_number(toman($item->line_total)) }}
                    <span class="text-2xs font-medium text-ink-600">تومان</span>
                </p>
            </div>
        </div>

        {{-- quantity stepper --}}
        <div class="mt-auto flex items-center gap-2">
            <div class="inline-flex items-center rounded-field border border-ink-200 bg-white">
                <button type="button" class="grid h-8 w-8 place-items-center rounded-s-field text-ink-600 transition-colors hover:bg-brand-50 hover:text-brand-500 disabled:opacity-30"
                        data-qty-inc="{{ $item->id }}"
                        {{ $item->quantity >= min($item->available_stock, config('digino.cart.max_qty_per_item')) ? 'disabled' : '' }}
                        aria-label="افزایش تعداد">
                    <x-icon name="plus" class="h-4 w-4" />
                </button>
                <span class="grid h-8 w-10 place-items-center border-x border-ink-200 text-sm font-bold text-ink-900"
                      data-qty-value="{{ $item->id }}">{{ fa_number($item->quantity) }}</span>
                <button type="button" class="grid h-8 w-8 place-items-center rounded-e-field text-ink-600 transition-colors hover:bg-brand-50 hover:text-brand-500"
                        data-qty-dec="{{ $item->id }}" aria-label="کاهش تعداد">
                    @if($item->quantity <= 1)
                        <x-icon name="trash" class="h-4 w-4" />
                    @else
                        <x-icon name="minus" class="h-4 w-4" />
                    @endif
                </button>
            </div>

            <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500"
                    data-cart-remove="{{ $item->id }}" aria-label="حذف {{ $item->product->name }} از سبد">
                <x-icon name="trash" class="h-4 w-4" />
            </button>

            @auth
                <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500"
                        data-wishlist-toggle="{{ $item->product_id }}" aria-label="انتقال به علاقه‌مندی‌ها">
                    <x-icon name="heart" class="h-4 w-4" />
                </button>
            @endauth
        </div>
    </div>
</div>
