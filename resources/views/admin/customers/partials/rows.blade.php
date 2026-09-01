@forelse($customers as $customer)
    <tr data-row-id="{{ $customer->id }}" class="animate-fade-in">
        <td>
            <div class="flex items-center gap-3">
                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-50 text-[11px] font-bold text-brand-500">
                    {{ $customer->initials }}
                </span>
                <div class="min-w-0">
                    <a href="{{ route('admin.customers.show', $customer) }}"
                       class="block truncate text-2xs font-medium text-ink-900 transition-colors hover:text-brand-500">
                        {{ $customer->name }}
                    </a>
                    <span class="ltr block truncate text-[10px] text-ink-400">{{ $customer->email }}</span>
                </div>
            </div>
        </td>

        <td class="ltr whitespace-nowrap text-2xs text-ink-600">{{ fa_number($customer->mobile) }}</td>
        <td class="whitespace-nowrap text-2xs text-ink-500">{{ jalali($customer->created_at) }}</td>
        <td class="text-2xs text-ink-700">{{ fa_number($customer->orders_count) }}</td>
        <td class="whitespace-nowrap text-2xs font-bold text-ink-900">{{ fa_number(toman((int) $customer->paid_sum)) }}</td>

        <td>
            @if($customer->is_active)
                <span class="badge-green">فعال</span>
            @else
                <span class="badge-gray">غیرفعال</span>
            @endif
        </td>

        <td>
            <div class="flex items-center gap-1">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn-icon h-8 w-8" aria-label="پرونده مشتری">
                    <x-icon name="eye" class="h-4 w-4" />
                </a>
                <button type="button" class="btn-icon h-8 w-8" aria-label="فعال یا غیرفعال کردن"
                        data-action="{{ route('admin.customers.toggle', $customer) }}" data-method="POST" data-reload>
                    <x-icon :name="$customer->is_active ? 'lock' : 'unlock'" class="h-4 w-4" />
                </button>
                <button type="button" class="btn-icon h-8 w-8 hover:text-brand-500" aria-label="حذف مشتری"
                        data-action="{{ route('admin.customers.destroy', $customer) }}" data-method="DELETE"
                        data-remove-row="tr"
                        data-confirm="حساب «{{ $customer->name }}» حذف شود؟" data-confirm-title="حذف مشتری" data-confirm-accept="حذف کن">
                    <x-icon name="trash" class="h-4 w-4" />
                </button>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7">
            <x-empty-state icon="users" title="مشتری‌ای یافت نشد" message="با تغییر فیلترها دوباره جستجو کنید." />
        </td>
    </tr>
@endforelse
