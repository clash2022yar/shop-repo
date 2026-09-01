@forelse($orders as $order)
    <tr data-row-id="{{ $order->id }}" class="animate-fade-in">
        <td>
            <a href="{{ route('admin.orders.show', $order) }}" class="ltr text-2xs font-bold text-ink-900 transition-colors hover:text-brand-500">
                {{ fa_number($order->code) }}
            </a>
        </td>

        <td>
            <div class="flex items-center gap-2.5">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-ink-100 text-[10px] font-bold text-ink-600">
                    {{ $order->user?->initials ?? '؟' }}
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-2xs text-ink-800">{{ $order->user?->name ?? $order->receiver_name }}</span>
                    <span class="ltr block text-[10px] text-ink-400">{{ fa_number($order->receiver_mobile) }}</span>
                </span>
            </div>
        </td>

        <td class="whitespace-nowrap text-2xs text-ink-500">
            {{ jalali($order->created_at) }}
            <span class="mt-0.5 block text-[10px] text-ink-400">{{ jalali($order->created_at, 'H:i') }}</span>
        </td>

        <td class="text-2xs text-ink-600">{{ fa_number($order->items_count) }} کالا</td>

        <td class="whitespace-nowrap text-2xs font-bold text-ink-900">{{ fa_number(toman($order->grand_total)) }}</td>

        <td>
            <span class="badge {{ $order->payment_status->badgeClass() }}">{{ $order->payment_status->label() }}</span>
            <span class="mt-1 block text-[10px] text-ink-400">
                {{ ['online' => 'پرداخت اینترنتی', 'cod' => 'پرداخت در محل', 'wallet' => 'کیف پول'][$order->payment_method] ?? '—' }}
            </span>
        </td>

        <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>

        <td>
            <div class="flex items-center gap-1">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn-icon h-8 w-8" aria-label="جزئیات سفارش">
                    <x-icon name="eye" class="h-4 w-4" />
                </a>
                <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" rel="noopener" class="btn-icon h-8 w-8" aria-label="فاکتور">
                    <x-icon name="printer" class="h-4 w-4" />
                </a>
                <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف سفارش"
                        data-action="{{ route('admin.orders.destroy', $order) }}" data-method="DELETE"
                        data-remove-row="tr"
                        data-confirm="سفارش {{ $order->code }} حذف شود؟" data-confirm-title="حذف سفارش" data-confirm-accept="حذف کن">
                    <x-icon name="trash" class="h-4 w-4" />
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8">
            <x-empty-state icon="receipt" title="سفارشی یافت نشد" message="با تغییر فیلترها دوباره جستجو کنید." />
        </td>
    </tr>
@endforelse
