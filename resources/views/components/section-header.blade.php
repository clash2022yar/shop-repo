@props(['title', 'moreUrl' => null, 'moreLabel' => 'مشاهده همه', 'icon' => null])

<div class="section-head">
    <h2 class="section-title flex items-center gap-2">
        @if($icon)<x-icon :name="$icon" class="h-5 w-5 text-brand-500" />@endif
        {{ $title }}
    </h2>
    <div class="flex items-center gap-2">
        {{ $slot }}
        @if($moreUrl)
            <a href="{{ $moreUrl }}" class="btn-link">
                {{ $moreLabel }}
                <x-icon name="chevron-left" class="h-4 w-4" />
            </a>
        @endif
    </div>
</div>
