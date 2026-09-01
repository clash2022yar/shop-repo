{{-- Admin side mirrors the customer thread: staff messages sit on the right. --}}
<div class="flex gap-3 {{ $message->is_staff ? 'flex-row-reverse' : '' }}">
    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full text-[10px] font-bold {{ $message->is_staff ? 'bg-brand-50 text-brand-600' : 'bg-ink-100 text-ink-600' }}">
        @if($message->is_staff)
            <x-icon name="headset" class="h-4 w-4" />
        @else
            {{ $message->user?->initials }}
        @endif
    </span>

    <div class="min-w-0 max-w-[85%] rounded-card px-4 py-3 animate-fade-up {{ $message->is_staff ? 'bg-brand-50/60' : 'bg-ink-50' }}">
        <div class="mb-1 flex flex-wrap items-center gap-2">
            <span class="text-[11px] font-bold text-ink-800">
                {{ $message->is_staff ? ($message->user?->name ?? 'پشتیبانی دیجی‌نو') : ($message->user?->name ?? 'مشتری') }}
            </span>
            @if($message->is_staff)<span class="badge-red-soft">همکار</span>@endif
            <span class="text-[10px] text-ink-400">{{ jalali($message->created_at, 'Y/m/d — H:i') }}</span>
        </div>
        <p class="whitespace-pre-line text-2xs leading-7 text-ink-700">{{ $message->body }}</p>
    </div>
</div>
