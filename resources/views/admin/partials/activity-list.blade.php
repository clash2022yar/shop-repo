@php
    $icons = [
        'created' => 'plus', 'updated' => 'edit', 'deleted' => 'trash',
        'login' => 'login', 'status' => 'refresh', 'stock' => 'warehouse',
    ];
    $tones = [
        'created' => 'bg-success-50 text-success-600',
        'updated' => 'bg-info-50 text-info-600',
        'deleted' => 'bg-brand-50 text-brand-500',
        'login' => 'bg-ink-100 text-ink-600',
        'status' => 'bg-warning-50 text-warning-600',
        'stock' => 'bg-ink-100 text-ink-600',
    ];
@endphp

<ol class="space-y-3">
    @forelse($activity as $log)
        @php
            $key = collect(array_keys($icons))->first(fn ($k) => str_contains((string) $log->action, $k)) ?? 'updated';
        @endphp
        <li class="flex items-start gap-3 animate-fade-up">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full {{ $tones[$key] }}">
                <x-icon :name="$icons[$key]" class="h-[18px] w-[18px]" />
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-2xs leading-6 text-ink-800">{{ $log->description }}</span>
                <span class="mt-0.5 flex flex-wrap items-center gap-x-3 text-[10px] text-ink-400">
                    <span>{{ $log->user?->name ?? 'سیستم' }}</span>
                    <span>{{ jalali_human($log->created_at) }}</span>
                    @if($log->ip)<span class="ltr">{{ $log->ip }}</span>@endif
                </span>
            </span>
        </li>
    @empty
        <li class="py-8 text-center text-2xs text-ink-500">فعالیتی ثبت نشده است.</li>
    @endforelse
</ol>
