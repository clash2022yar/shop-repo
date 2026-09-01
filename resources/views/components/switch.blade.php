@props(['name', 'checked' => false, 'label' => null, 'help' => null, 'value' => 1])

<label class="flex cursor-pointer items-start gap-3">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" class="peer sr-only"
           {{ $checked ? 'checked' : '' }} {{ $attributes }}>
    <span class="switch mt-0.5 shrink-0 peer-checked:bg-success-500" data-switch><span></span></span>
    @if($label)
        <span class="leading-5">
            <span class="block text-sm font-medium text-ink-800">{{ $label }}</span>
            @if($help)<span class="mt-0.5 block text-2xs text-ink-500">{{ $help }}</span>@endif
        </span>
    @endif
</label>
