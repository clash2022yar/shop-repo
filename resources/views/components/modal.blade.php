{{--
    Declarative modal. Toggle it from anywhere with:
      <button data-modal-open="my-id">…</button>
    The JS in public/js/app.js handles focus trapping, ESC and backdrop clicks.
--}}
@props(['id', 'title' => '', 'size' => 'md', 'closable' => true])

@php
    $widths = [
        'sm' => 'max-w-sm', 'md' => 'max-w-lg', 'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl', 'full' => 'max-w-6xl',
    ];
@endphp

<div id="modal-{{ $id }}" data-modal="{{ $id }}" class="fixed inset-0 z-[90] hidden" role="dialog" aria-modal="true"
     aria-labelledby="modal-title-{{ $id }}">
    <div class="modal-backdrop" data-modal-backdrop></div>

    <div class="fixed inset-0 grid place-items-center overflow-y-auto p-4">
        <div class="modal-box {{ $widths[$size] ?? $widths['md'] }}" data-modal-box>
            <div class="sticky top-0 z-10 flex items-center justify-between gap-3 border-b border-ink-100 bg-white px-5 py-4">
                <h3 id="modal-title-{{ $id }}" data-modal-title class="text-base font-extrabold text-ink-900">{{ $title }}</h3>
                @if($closable)
                    <button type="button" class="btn-icon h-8 w-8" data-modal-close aria-label="بستن">
                        <x-icon name="close" class="h-5 w-5" />
                    </button>
                @endif
            </div>

            <div class="p-5">{{ $slot }}</div>

            @isset($footer)
                <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-ink-100 bg-ink-50 px-5 py-3.5">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
