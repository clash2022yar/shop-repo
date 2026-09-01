@props(['items' => []])

@if(count($items))
<nav class="breadcrumb py-3" aria-label="مسیر صفحه">
    <a href="{{ route('home') }}" class="flex items-center gap-1 hover:text-brand-500">
        <x-icon name="store" class="h-3.5 w-3.5" />
        خانه
    </a>
    @foreach($items as $item)
        <x-icon name="chevron-left" class="h-3 w-3 shrink-0 text-ink-300" />
        @if($loop->last)
            <span class="truncate font-bold text-ink-700" aria-current="page">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}" class="truncate">{{ $item['label'] }}</a>
        @endif
    @endforeach
</nav>
@endif
