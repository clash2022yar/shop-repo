@php $logs = $order->statusLogs->sortByDesc('created_at'); @endphp

<ol class="relative space-y-5" style="padding-inline-start:1.35rem">
    <span class="absolute top-2 bottom-2 w-px bg-ink-200" style="inset-inline-start:.32rem"></span>

    @forelse($logs as $log)
        @php $status = \App\Enums\OrderStatus::tryFrom($log->to_status); @endphp
        <li class="relative animate-fade-in">
            <span class="absolute top-1 grid h-3.5 w-3.5 place-items-center rounded-full border-2 border-white bg-brand-500 shadow-ring"
                  style="inset-inline-start:-1.35rem"></span>

            <p class="flex flex-wrap items-center gap-2 text-2xs font-bold text-ink-900">
                {{ $status?->label() ?? ($log->label ?? $log->to_status) }}
                @if($log->from_status)
                    <span class="text-[10px] font-normal text-ink-400">
                        از {{ \App\Enums\OrderStatus::tryFrom($log->from_status)?->label() ?? $log->from_status }}
                    </span>
                @endif
            </p>

            @if($log->note)
                <p class="mt-1 rounded-field bg-ink-50 px-3 py-2 text-[11px] leading-6 text-ink-600">{{ $log->note }}</p>
            @endif

            <p class="mt-1 flex flex-wrap items-center gap-x-3 text-[10px] text-ink-400">
                <span>{{ jalali($log->created_at, 'j F Y — H:i') }}</span>
                <span>{{ $log->user?->name ?? 'سیستم' }}</span>
            </p>
        </li>
    @empty
        <li class="text-2xs text-ink-500">هنوز تغییر وضعیتی ثبت نشده است.</li>
    @endforelse
</ol>
