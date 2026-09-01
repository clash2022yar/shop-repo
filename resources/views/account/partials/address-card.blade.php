<article class="relative rounded-card border border-ink-200 bg-white p-4 transition-all duration-200 hover:border-brand-200 hover:shadow-card"
         data-address-card="{{ $address->id }}">

    <div class="mb-2 flex flex-wrap items-center gap-2">
        <span class="text-2xs font-bold text-ink-900">{{ $address->receiver_name }}</span>
        @if($address->label)<span class="badge-gray">{{ $address->label }}</span>@endif
        @if($address->is_default)<span class="badge-green">پیش‌فرض</span>@endif
    </div>

    <p class="text-2xs leading-7 text-ink-600">{{ $address->full }}</p>

    <div class="mt-2 flex flex-wrap items-center gap-x-4 text-[11px] text-ink-500">
        <span class="ltr">{{ fa_number($address->receiver_mobile) }}</span>
        @if($address->postal_code)<span>کد پستی: {{ fa_number($address->postal_code) }}</span>@endif
    </div>

    <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-ink-100 pt-3">
        @unless($address->is_default)
            <button type="button" class="btn-ghost btn-sm"
                    data-action="{{ route('ajax.account.addresses.default', $address) }}" data-method="POST" data-reload>
                <x-icon name="check-circle" class="h-4 w-4" />
                پیش‌فرض کن
            </button>
        @endunless

        <button type="button" class="btn-ghost btn-sm text-brand-500"
                data-action="{{ route('ajax.account.addresses.destroy', $address) }}" data-method="DELETE"
                data-remove-row="[data-address-card]"
                data-confirm="این آدرس حذف شود؟" data-confirm-title="حذف آدرس" data-confirm-accept="حذف کن">
            <x-icon name="trash" class="h-4 w-4" />
            حذف
        </button>
    </div>
</article>
