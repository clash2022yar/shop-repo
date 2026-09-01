@extends('layouts.account')

@section('title', 'اعلان‌ها')
@section('heading', 'اعلان‌ها')
@section('subheading', 'اطلاع‌رسانی‌های مربوط به سفارش‌ها، تخفیف‌ها و حساب کاربری شما')

@section('content')
<div class="overflow-hidden rounded-card bg-white shadow-card">
    @forelse($notifications as $notification)
        @php
            $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true) ?? [];
            $unread = is_null($notification->read_at);
        @endphp
        <a href="{{ $data['url'] ?? '#' }}"
           class="flex items-start gap-3.5 border-b border-ink-50 p-4 transition-colors last:border-0 hover:bg-ink-50/60 {{ $unread ? 'bg-brand-50/30' : '' }}">
            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full {{ $unread ? 'bg-brand-50 text-brand-500' : 'bg-ink-100 text-ink-400' }}">
                <x-icon :name="$data['icon'] ?? 'bell'" class="h-5 w-5" />
            </span>
            <span class="min-w-0 flex-1">
                <span class="flex items-center gap-2">
                    <span class="text-2xs font-bold text-ink-900">{{ $data['title'] ?? 'اعلان جدید' }}</span>
                    @if($unread)<span class="h-1.5 w-1.5 rounded-full bg-brand-500"></span>@endif
                </span>
                <span class="mt-1 block text-[11px] leading-6 text-ink-600">{{ $data['message'] ?? '' }}</span>
                <span class="mt-1 block text-[10px] text-ink-400">{{ jalali_human($notification->created_at) }}</span>
            </span>
        </a>
    @empty
        <x-empty-state icon="bell" title="اعلانی ندارید"
                       message="هر زمان خبر تازه‌ای درباره سفارش‌ها یا حساب شما باشد، اینجا نمایش داده می‌شود." />
    @endforelse
</div>

{{ $notifications->links() }}
@endsection
