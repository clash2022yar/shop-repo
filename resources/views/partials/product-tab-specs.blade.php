<div class="animate-fade-in">
    <h2 class="mb-4 text-base font-extrabold text-ink-900">مشخصات فنی {{ $product->name }}</h2>

    @php $groups = $product->attributes->groupBy(fn($a) => $a->group ?: 'مشخصات عمومی'); @endphp

    @forelse($groups as $groupName => $attributes)
        <section class="mb-5 overflow-hidden rounded-card border border-ink-100">
            <h3 class="border-b border-ink-100 bg-ink-50 px-4 py-2.5 text-2xs font-bold text-ink-800">{{ $groupName }}</h3>
            <dl class="divide-y divide-ink-50">
                @foreach($attributes->sortBy('sort_order') as $attribute)
                    <div class="grid grid-cols-[9rem_minmax(0,1fr)] gap-3 px-4 py-3 transition-colors hover:bg-ink-50/60 sm:grid-cols-[13rem_minmax(0,1fr)]">
                        <dt class="text-2xs text-ink-500">{{ $attribute->name }}</dt>
                        <dd class="text-2xs leading-6 text-ink-800">{{ $attribute->value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @empty
        <x-empty-state icon="list" title="مشخصات فنی ثبت نشده است"
                       message="برای این کالا هنوز جدول مشخصات فنی تکمیل نشده است." />
    @endforelse

    @if(filled($product->specs))
        <section class="overflow-hidden rounded-card border border-ink-100">
            <h3 class="border-b border-ink-100 bg-ink-50 px-4 py-2.5 text-2xs font-bold text-ink-800">اطلاعات تکمیلی</h3>
            <dl class="divide-y divide-ink-50">
                @foreach($product->specs as $name => $value)
                    <div class="grid grid-cols-[9rem_minmax(0,1fr)] gap-3 px-4 py-3 sm:grid-cols-[13rem_minmax(0,1fr)]">
                        <dt class="text-2xs text-ink-500">{{ $name }}</dt>
                        <dd class="text-2xs leading-6 text-ink-800">{{ is_array($value) ? implode('، ', $value) : $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
    @endif
</div>
