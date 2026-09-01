{{-- One selectable delivery address on the checkout page --}}
<label class="group relative flex cursor-pointer gap-3 rounded-card border border-ink-200 p-4 transition-all duration-200 hover:border-brand-300 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40">
    <input type="radio" name="address_id" value="{{ $address->id }}" class="radio mt-0.5 shrink-0"
           {{ $address->is_default ? 'checked' : '' }} data-address-option>
    <span class="min-w-0 flex-1">
        <span class="flex flex-wrap items-center gap-2">
            <span class="text-sm font-bold text-ink-900">{{ $address->receiver_name }}</span>
            <span class="badge-gray">{{ $address->label }}</span>
            @if($address->is_default)<span class="badge-green">پیش‌فرض</span>@endif
        </span>
        <span class="mt-1.5 block text-2xs leading-6 text-ink-600">{{ $address->full }}</span>
        <span class="mt-1 flex flex-wrap items-center gap-x-4 text-2xs text-ink-500">
            <span class="ltr">{{ fa_number($address->receiver_mobile) }}</span>
            @if($address->postal_code)<span>کد پستی: {{ fa_number($address->postal_code) }}</span>@endif
        </span>
    </span>
</label>
