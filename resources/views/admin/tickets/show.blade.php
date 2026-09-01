@extends('layouts.admin')

@section('title', 'تیکت ' . $ticket->code)
@section('heading', $ticket->subject)
@section('subheading', 'کد پیگیری ' . fa_number($ticket->code))

@section('breadcrumb')
    <a href="{{ route('admin.tickets.index') }}" class="link-muted">تیکت‌ها</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="ltr text-ink-700">{{ fa_number($ticket->code) }}</span>
@endsection

@section('content')
<div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_20rem] xl:items-start">

    <section class="rounded-card bg-white p-5 shadow-card">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-ink-100 pb-4">
            <span class="badge {{ $ticket->status_badge }}">{{ $ticket->status_label }}</span>

            <div class="flex flex-wrap items-center gap-1.5">
                @foreach(['open' => 'بازگشایی', 'answered' => 'علامت‌گذاری پاسخ‌داده‌شده', 'closed' => 'بستن تیکت'] as $status => $label)
                    @continue($ticket->status === $status)
                    <button type="button" class="btn-outline btn-sm"
                            data-action="{{ route('admin.tickets.status', $ticket) }}" data-method="POST"
                            data-payload='@json(['status' => $status])' data-reload>
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div id="ticket-thread" class="space-y-4">
            @foreach($ticket->messages as $message)
                @include('admin.tickets.partials.message', ['message' => $message])
            @endforeach
        </div>

        @if($ticket->status !== 'closed')
            <form data-ajax-form action="{{ route('admin.tickets.reply', $ticket) }}" data-method="POST" data-reset
                  id="ticket-reply" class="mt-6 border-t border-ink-100 pt-5">
                <label class="label" for="reply-body">پاسخ شما</label>
                <textarea id="reply-body" name="body" rows="4" class="field" placeholder="پاسخ خود را بنویسید..."></textarea>
                <p class="error-text" data-error-for="body"></p>

                <div class="mt-3 flex justify-end">
                    <button type="submit" class="btn-primary">
                        <span data-submit-spinner class="hidden h-4 w-4 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
                        <span data-submit-text>ارسال پاسخ</span>
                    </button>
                </div>
            </form>
        @else
            <p class="mt-6 flex items-center gap-2 rounded-field bg-ink-50 px-4 py-3 text-2xs text-ink-600">
                <x-icon name="lock" class="h-5 w-5 shrink-0" />
                این تیکت بسته شده است. برای ادامه گفت‌وگو ابتدا آن را بازگشایی کنید.
            </p>
        @endif
    </section>

    <aside class="space-y-4 xl:sticky xl:top-24">
        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">اطلاعات تیکت</h2>
            <dl class="space-y-2.5 text-2xs">
                @php
                    $rows = [
                        'دپارتمان' => $ticket->department_label,
                        'اولویت' => $ticket->priority_label,
                        'تاریخ ثبت' => jalali($ticket->created_at, 'j F Y — H:i'),
                        'آخرین به‌روزرسانی' => jalali_human($ticket->updated_at),
                        'تعداد پیام' => fa_number($ticket->messages->count()),
                    ];
                @endphp
                @foreach($rows as $label => $value)
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-ink-500">{{ $label }}</dt>
                        <dd class="font-medium text-ink-800">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>

        <section class="rounded-card bg-white p-5 shadow-card">
            <h2 class="mb-4 text-sm font-extrabold text-ink-900">مشتری</h2>
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-brand-50 text-2xs font-bold text-brand-500">
                    {{ $ticket->user?->initials }}
                </span>
                <span class="min-w-0">
                    <span class="block truncate text-2xs font-bold text-ink-900">{{ $ticket->user?->name }}</span>
                    <span class="ltr block text-[11px] text-ink-500">{{ fa_number($ticket->user?->mobile) }}</span>
                </span>
            </div>

            @if($ticket->user)
                <a href="{{ route('admin.customers.show', $ticket->user) }}" class="btn-outline btn-sm mt-4 w-full">
                    <x-icon name="user" class="h-4 w-4" />
                    پرونده مشتری
                </a>
            @endif
        </section>

        @if($ticket->order)
            <section class="rounded-card bg-white p-5 shadow-card">
                <h2 class="mb-4 text-sm font-extrabold text-ink-900">سفارش مرتبط</h2>
                <p class="ltr text-2xs font-bold text-ink-900">{{ fa_number($ticket->order->code) }}</p>
                <p class="mt-1 text-[11px] text-ink-500">{{ jalali($ticket->order->created_at) }}</p>
                <a href="{{ route('admin.orders.show', $ticket->order) }}" class="btn-outline btn-sm mt-4 w-full">
                    <x-icon name="receipt" class="h-4 w-4" />
                    مشاهده سفارش
                </a>
            </section>
        @endif
    </aside>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('dg:ready', function () {
        var form = document.getElementById('ticket-reply');
        if (!form) return;

        form.addEventListener('dg:submitted', function (e) {
            if (!e.detail || !e.detail.html) return;
            var thread = document.getElementById('ticket-thread');
            thread.insertAdjacentHTML('beforeend', e.detail.html);
            thread.lastElementChild.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });
</script>
@endpush
