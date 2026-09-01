@props(['amount' => 0, 'old' => null, 'discount' => 0, 'size' => 'base', 'align' => 'end'])

@php
    $sizes = [
        'sm'   => ['amount' => 'text-sm font-bold',      'label' => 'text-[10px]'],
        'base' => ['amount' => 'text-base font-extrabold','label' => 'text-2xs'],
        'lg'   => ['amount' => 'text-2xl font-extrabold', 'label' => 'text-xs'],
    ];
    $s = $sizes[$size] ?? $sizes['base'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col gap-0.5 items-' . $align]) }}>
    @if($old && $discount > 0)
        <div class="flex items-center gap-2">
            <span class="chip-discount">{{ fa_number($discount) }}٪</span>
            <span class="price-old">{{ fa_number(toman($old)) }}</span>
        </div>
    @endif
    <div class="flex items-baseline gap-1">
        <span class="{{ $s['amount'] }} text-ink-900">{{ fa_number(toman($amount)) }}</span>
        <span class="{{ $s['label'] }} font-medium text-ink-600">تومان</span>
    </div>
</div>
