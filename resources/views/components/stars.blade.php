@props(['rating' => 0, 'count' => null, 'size' => 'h-3.5 w-3.5', 'showValue' => false])

@php
    $rating = (float) $rating;
    $full = (int) floor($rating);
    $half = ($rating - $full) >= 0.25 && ($rating - $full) < 0.75;
    $roundedUp = ($rating - $full) >= 0.75;
    if ($roundedUp) { $full++; }
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1']) }}>
    <span class="stars text-star" role="img" aria-label="امتیاز {{ fa_number(number_format($rating, 1)) }} از ۵">
        @for($i = 1; $i <= 5; $i++)
            @if($i <= $full)
                <x-icon name="star" :class="$size" />
            @elseif($i === $full + 1 && $half)
                <x-icon name="star-half" :class="$size" />
            @else
                <x-icon name="star-outline" :class="$size . ' text-ink-300'" />
            @endif
        @endfor
    </span>

    @if($showValue)
        <span class="text-2xs font-bold text-ink-700">{{ fa_number(number_format($rating, 1)) }}</span>
    @endif

    @if(!is_null($count))
        <span class="text-2xs text-ink-400">({{ fa_number($count) }})</span>
    @endif
</span>
