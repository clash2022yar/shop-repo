@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-1.5 py-6" role="navigation" aria-label="صفحه‌بندی">

    {{-- previous (RTL: points right) --}}
    @if ($paginator->onFirstPage())
        <span class="page-link" aria-disabled="true"><x-icon name="chevron-right" class="h-4 w-4" /></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-link" rel="prev" aria-label="صفحه قبلی" data-page="{{ $paginator->currentPage() - 1 }}">
            <x-icon name="chevron-right" class="h-4 w-4" />
        </a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="page-link pointer-events-none border-transparent bg-transparent">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="page-link" aria-current="page">{{ fa_number($page) }}</span>
                @else
                    <a href="{{ $url }}" class="page-link" data-page="{{ $page }}">{{ fa_number($page) }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-link" rel="next" aria-label="صفحه بعدی" data-page="{{ $paginator->currentPage() + 1 }}">
            <x-icon name="chevron-left" class="h-4 w-4" />
        </a>
    @else
        <span class="page-link" aria-disabled="true"><x-icon name="chevron-left" class="h-4 w-4" /></span>
    @endif
</nav>
@endif
