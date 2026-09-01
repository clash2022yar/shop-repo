@extends('layouts.account')

@section('title', 'تیکت‌های پشتیبانی')
@section('heading', 'تیکت‌های پشتیبانی')
@section('subheading', 'گفت‌وگوهای شما با تیم پشتیبانی دیجی‌نو')

@section('actions')
    <a href="{{ route('account.tickets.create') }}" class="btn-primary btn-sm">
        <x-icon name="plus" class="h-4 w-4" />
        ثبت تیکت جدید
    </a>
@endsection

@section('content')
@php
    $statusMeta = [
        'open' => ['label' => 'باز', 'class' => 'badge-amber'],
        'answered' => ['label' => 'پاسخ داده شده', 'class' => 'badge-green'],
        'closed' => ['label' => 'بسته شده', 'class' => 'badge-gray'],
    ];
    $priorityMeta = [
        'low' => ['label' => 'کم', 'class' => 'badge-gray'],
        'normal' => ['label' => 'عادی', 'class' => 'badge-blue'],
        'high' => ['label' => 'زیاد', 'class' => 'badge-red'],
    ];
@endphp

<div class="overflow-hidden rounded-card bg-white shadow-card">
    @forelse($tickets as $ticket)
        <a href="{{ route('account.tickets.show', $ticket) }}"
           class="flex flex-wrap items-center gap-4 border-b border-ink-50 p-4 transition-colors last:border-0 hover:bg-ink-50/60">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-ink-100 text-ink-500">
                <x-icon name="chat" class="h-5 w-5" />
            </span>

            <span class="min-w-0 flex-1">
                <span class="flex flex-wrap items-center gap-2">
                    <span class="text-2xs font-bold text-ink-900">{{ $ticket->subject }}</span>
                    <span class="{{ $statusMeta[$ticket->status]['class'] ?? 'badge-gray' }}">
                        {{ $statusMeta[$ticket->status]['label'] ?? $ticket->status }}
                    </span>
                    <span class="{{ $priorityMeta[$ticket->priority]['class'] ?? 'badge-gray' }}">
                        اولویت {{ $priorityMeta[$ticket->priority]['label'] ?? $ticket->priority }}
                    </span>
                </span>
                <span class="mt-1 block text-[11px] text-ink-500">
                    کد {{ fa_number($ticket->code) }} — {{ fa_number($ticket->messages_count) }} پیام —
                    آخرین به‌روزرسانی {{ jalali_human($ticket->updated_at) }}
                </span>
            </span>

            <x-icon name="chevron-left" class="h-4 w-4 shrink-0 text-ink-300" />
        </a>
    @empty
        <x-empty-state icon="headset" title="تیکتی ثبت نکرده‌اید"
                       message="اگر سؤال یا مشکلی دارید، تیکت جدیدی ثبت کنید تا کارشناسان ما بررسی کنند."
                       :action-url="route('account.tickets.create')" action-label="ثبت تیکت جدید" />
    @endforelse
</div>

{{ $tickets->links() }}
@endsection
