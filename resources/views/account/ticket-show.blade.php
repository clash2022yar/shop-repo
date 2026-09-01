@extends('layouts.account')

@section('title', 'تیکت ' . $ticket->code)
@section('heading', $ticket->subject)
@section('subheading', 'کد تیکت ' . fa_number($ticket->code) . ' — ثبت‌شده در ' . jalali($ticket->created_at, 'Y/m/d — H:i'))

@section('actions')
    <div class="flex flex-wrap items-center gap-2">
        @if($ticket->status !== 'closed')
            <button type="button" class="btn-ghost btn-sm text-brand-500"
                    data-action="{{ route('ajax.account.tickets.close', $ticket) }}" data-method="POST" data-reload
                    data-confirm="این تیکت بسته شود؟ پس از بستن، امکان ارسال پیام جدید وجود ندارد."
                    data-confirm-title="بستن تیکت" data-confirm-accept="ببند">
                <x-icon name="x-circle" class="h-4 w-4" />
                بستن تیکت
            </button>
        @endif
        <a href="{{ route('account.tickets.index') }}" class="btn-ghost btn-sm">
            <x-icon name="arrow-right" class="h-4 w-4" />
            بازگشت
        </a>
    </div>
@endsection

@section('content')
<div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_16rem]">

    <div class="min-w-0 space-y-4">
        <section class="space-y-4 rounded-card bg-white p-5 shadow-card">
            @foreach($ticket->messages as $message)
                @include('account.partials.ticket-message', ['message' => $message])
            @endforeach
        </section>

        @if($ticket->status !== 'closed')
            <section class="rounded-card bg-white p-5 shadow-card">
                <h2 class="mb-3 text-sm font-extrabold text-ink-900">ارسال پاسخ</h2>
                <form action="{{ route('ajax.account.tickets.reply', $ticket) }}" method="POST" data-ajax-form data-reload>
                    @csrf
                    <textarea name="body" rows="4" class="field" required placeholder="پاسخ خود را بنویسید..."></textarea>
                    <p data-error-for="body" class="hidden"></p>
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="btn-primary">
                            <span data-submit-text>ارسال پاسخ</span>
                            <x-icon name="spinner" class="hidden h-4 w-4 animate-spin-slow" data-submit-spinner />
                        </button>
                    </div>
                </form>
            </section>
        @else
            <div class="flex items-center gap-3 rounded-card bg-ink-100 p-4 text-2xs text-ink-600">
                <x-icon name="lock" class="h-5 w-5 shrink-0" />
                این تیکت بسته شده است. برای پیگیری موضوع جدید، تیکت تازه‌ای ثبت کنید.
            </div>
        @endif
    </div>

    <aside>
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-3 text-sm font-extrabold text-ink-900">مشخصات تیکت</h2>
            <dl class="space-y-3 text-2xs">
                <div class="flex justify-between">
                    <dt class="text-ink-500">وضعیت</dt>
                    <dd>
                        @php
                            $meta = ['open' => ['باز', 'badge-amber'], 'answered' => ['پاسخ داده شده', 'badge-green'], 'closed' => ['بسته شده', 'badge-gray']];
                        @endphp
                        <span class="{{ $meta[$ticket->status][1] ?? 'badge-gray' }}">{{ $meta[$ticket->status][0] ?? $ticket->status }}</span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-500">دپارتمان</dt>
                    <dd class="font-medium text-ink-800">
                        {{ ['support' => 'پشتیبانی عمومی', 'orders' => 'سفارش‌ها', 'technical' => 'فنی', 'finance' => 'مالی'][$ticket->department] ?? $ticket->department }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-500">اولویت</dt>
                    <dd class="font-medium text-ink-800">
                        {{ ['low' => 'کم', 'normal' => 'عادی', 'high' => 'زیاد'][$ticket->priority] ?? $ticket->priority }}
                    </dd>
                </div>
                @if($ticket->order)
                    <div class="divider"></div>
                    <a href="{{ route('account.orders.show', $ticket->order) }}" class="flex items-center gap-2 text-brand-500 hover:underline">
                        <x-icon name="receipt" class="h-4 w-4" />
                        سفارش {{ fa_number($ticket->order->code) }}
                    </a>
                @endif
            </dl>
        </section>
    </aside>
</div>
@endsection
