{{-- Rendered into the mega-menu by ajax.catalog.mega-menu --}}
<div class="animate-fade-in">
    <div class="mb-4 flex items-center justify-between">
        <h3 class="flex items-center gap-2 text-sm font-extrabold text-ink-900">
            <x-icon :name="$category->icon ?: 'box'" class="h-5 w-5 text-brand-500" />
            {{ $category->name }}
        </h3>
        <a href="{{ route('categories.show', $category->slug) }}" class="btn-link text-2xs">
            مشاهده همه {{ $category->name }}
            <x-icon name="chevron-left" class="h-3.5 w-3.5" />
        </a>
    </div>

    @if($category->children->isEmpty())
        <p class="rounded-field bg-ink-50 p-4 text-center text-xs text-ink-500">
            زیر‌دسته‌ای برای این بخش تعریف نشده است.
        </p>
    @else
        <div class="grid grid-cols-2 gap-x-6 gap-y-5 md:grid-cols-3">
            @foreach($category->children as $child)
                <div class="stagger">
                    <a href="{{ route('categories.show', $child->slug) }}"
                       class="mb-2 block text-[0.8125rem] font-bold text-ink-800 transition-colors hover:text-brand-500">
                        {{ $child->name }}
                    </a>
                    @if($child->children->isNotEmpty())
                        <ul class="space-y-1.5">
                            @foreach($child->children->take(6) as $grand)
                                <li>
                                    <a href="{{ route('categories.show', $grand->slug) }}"
                                       class="block text-2xs text-ink-500 transition-all duration-200 hover:translate-x-[-3px] hover:text-brand-500">
                                        {{ $grand->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
