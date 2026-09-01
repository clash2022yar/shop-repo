@props([
    'icon' => 'box',
    'title' => 'موردی یافت نشد',
    'message' => '',
    'actionUrl' => null,
    'actionLabel' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center px-6 py-14 text-center animate-fade-up']) }}>
    <span class="mb-4 grid h-20 w-20 place-items-center rounded-full bg-ink-100 text-ink-400">
        <x-icon :name="$icon" class="h-9 w-9" />
    </span>
    <h3 class="text-base font-bold text-ink-800">{{ $title }}</h3>
    @if($message)
        <p class="mt-2 max-w-sm text-sm leading-7 text-ink-500">{{ $message }}</p>
    @endif
    {{ $slot }}
    @if($actionUrl && $actionLabel)
        <a href="{{ $actionUrl }}" class="btn-primary mt-5">
            {{ $actionLabel }}
            <x-icon name="arrow-left" class="h-4 w-4" />
        </a>
    @endif
</div>
