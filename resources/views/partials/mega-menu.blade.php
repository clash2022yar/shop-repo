{{-- Category mega-menu. Sub-category panels are fetched lazily over AJAX. --}}
<div data-mega-panel
     class="absolute start-0 top-full z-50 hidden w-[62rem] max-w-[92vw] overflow-hidden rounded-b-card bg-white shadow-pop ring-1 ring-ink-200">
    <div class="flex min-h-[22rem]">

        {{-- level 1 --}}
        <ul class="w-60 shrink-0 border-e border-ink-100 bg-ink-50/60 py-2">
            @foreach($menuCategories ?? [] as $category)
                <li>
                    <a href="{{ route('categories.show', $category->slug) }}"
                       data-mega-item="{{ $category->slug }}"
                       class="group flex items-center justify-between gap-2 px-4 py-2.5 text-[0.8125rem] text-ink-700 transition-all duration-200 hover:bg-white hover:text-brand-500 data-[active]:bg-white data-[active]:font-bold data-[active]:text-brand-500">
                        <span class="flex items-center gap-2.5">
                            <x-icon :name="$category->icon ?: 'box'" class="h-4.5 w-4.5 text-ink-400 transition-colors group-hover:text-brand-500" />
                            {{ $category->name }}
                        </span>
                        <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300 transition-transform duration-200 group-hover:-translate-x-0.5" />
                    </a>
                </li>
            @endforeach

            <li class="mt-1 border-t border-ink-200 pt-1">
                <a href="{{ route('categories.index') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-[0.8125rem] font-bold text-brand-500">
                    <x-icon name="grid" class="h-4.5 w-4.5" />
                    همه دسته‌بندی‌ها
                </a>
            </li>
        </ul>

        {{-- level 2 & 3 (loaded on hover) --}}
        <div class="flex-1 p-5" data-mega-content>
            <div class="grid h-full place-items-center text-center text-sm text-ink-400">
                <div>
                    <x-icon name="layers" class="mx-auto mb-3 h-10 w-10 text-ink-200" />
                    برای دیدن زیر‌دسته‌ها، روی یک دسته‌بندی نگه دارید.
                </div>
            </div>
        </div>
    </div>
</div>
