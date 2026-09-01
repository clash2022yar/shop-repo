{{-- Horizontally scrolling product rail with prev / next controls. --}}
@props(['id' => null])

@php $id = $id ?? 'rail-' . uniqid(); @endphp

<div class="relative" data-rail-root>
    <div class="rail" data-rail id="{{ $id }}">
        {{ $slot }}
    </div>

    <button type="button" class="rail-btn -start-3" data-rail-prev aria-label="قبلی">
        <x-icon name="chevron-right" class="h-5 w-5" />
    </button>
    <button type="button" class="rail-btn -end-3" data-rail-next aria-label="بعدی">
        <x-icon name="chevron-left" class="h-5 w-5" />
    </button>
</div>
