@php
    $types = [
        'in' => ['ورود به انبار', 'bg-success-50 text-success-600', 'trend-up'],
        'out' => ['خروج از انبار', 'bg-brand-50 text-brand-500', 'trend-down'],
        'adjust' => ['اصلاح موجودی', 'bg-info-50 text-info-600', 'edit'],
        'sale' => ['فروش', 'bg-warning-50 text-warning-600', 'shopping-bag'],
        'return' => ['مرجوعی', 'bg-ink-100 text-ink-600', 'refresh'],
    ];
@endphp

<div class="mb-4 flex items-center gap-3 rounded-field bg-ink-50 p-3">
    <img src="{{ asset($product->primary_image) }}" alt="" class="h-12 w-12 shrink-0 rounded-lg bg-white object-contain">
    <div class="min-w-0">
        <p class="line-clamp-1 text-2xs font-bold text-ink-900">{{ $product->name }}</p>
        <p class="mt-0.5 text-[11px] text-ink-500">موجودی فعلی: {{ fa_number($product->stock) }} عدد</p>
    </div>
</div>

<ol class="space-y-2.5">
    @forelse($movements as $movement)
        @php $meta = $types[$movement->type] ?? $types['adjust']; @endphp
        <li class="flex items-start gap-3 rounded-field border border-ink-200 p-3 animate-fade-in">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full {{ $meta[1] }}">
                <x-icon :name="$meta[2]" class="h-[18px] w-[18px]" />
            </span>

            <span class="min-w-0 flex-1">
                <span class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-2xs font-bold text-ink-800">{{ $meta[0] }}</span>
                    <span class="text-2xs font-extrabold {{ $movement->quantity >= 0 ? 'text-success-600' : 'text-brand-500' }}">
                        {{ $movement->quantity >= 0 ? '+' : '−' }}{{ fa_number(abs($movement->quantity)) }}
                    </span>
                </span>

                <span class="mt-1 flex flex-wrap items-center gap-x-3 text-[10px] text-ink-400">
                    <span>موجودی پس از تغییر: {{ fa_number($movement->stock_after) }}</span>
                    <span>{{ jalali($movement->created_at, 'j F Y — H:i') }}</span>
                    <span>{{ $movement->user?->name ?? 'سیستم' }}</span>
                </span>

                @if($movement->note)
                    <span class="mt-1.5 block text-[11px] leading-6 text-ink-600">{{ $movement->note }}</span>
                @endif
            </span>
        </li>
    @empty
        <li class="py-8 text-center text-2xs text-ink-500">گردشی برای این کالا ثبت نشده است.</li>
    @endforelse
</ol>
