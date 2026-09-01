@extends('layouts.admin')

@section('title', 'تیکت‌ها')
@section('heading', 'تیکت‌های پشتیبانی')
@section('subheading', fa_number($counts['open']) . ' تیکت در انتظار پاسخ')

@section('breadcrumb')
    <span class="text-ink-700">تیکت‌ها</span>
@endsection

@section('content')
<div>
    <div class="mb-4 rounded-card bg-white p-4 shadow-card">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-1.5">
                @foreach(['' => ['همه', $counts['all']], 'open' => ['در انتظار پاسخ', $counts['open']], 'answered' => ['پاسخ داده شده', $counts['answered']], 'closed' => ['بسته شده', $counts['closed']]] as $value => $meta)
                    <a href="{{ route('admin.tickets.index', array_filter(['status' => $value, 'department' => request('department')])) }}"
                       @class([
                           'flex items-center gap-1.5 rounded-pill px-3 py-1.5 text-2xs transition-colors',
                           'bg-brand-50 font-bold text-brand-600' => (string) request('status') === (string) $value,
                           'text-ink-600 hover:bg-ink-50' => (string) request('status') !== (string) $value,
                       ])>
                        {{ $meta[0] }}
                        <span class="text-[10px] text-ink-400">{{ fa_number($meta[1]) }}</span>
                    </a>
                @endforeach
            </div>

            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <select name="department" class="field-sm w-44" onchange="this.form.submit()">
                    <option value="">همه دپارتمان‌ها</option>
                    @foreach(['support' => 'پشتیبانی', 'orders' => 'سفارش‌ها', 'technical' => 'فنی', 'finance' => 'مالی'] as $key => $label)
                        <option value="{{ $key }}" @selected(request('department') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کد</th>
                        <th>موضوع</th>
                        <th>مشتری</th>
                        <th>دپارتمان</th>
                        <th>اولویت</th>
                        <th>پیام‌ها</th>
                        <th>آخرین به‌روزرسانی</th>
                        <th>وضعیت</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="animate-fade-in">
                            <td class="ltr text-2xs font-bold text-ink-900">{{ fa_number($ticket->code) }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket) }}"
                                   class="line-clamp-1 max-w-xs text-2xs text-ink-800 transition-colors hover:text-brand-500">{{ $ticket->subject }}</a>
                            </td>
                            <td class="text-2xs text-ink-600">{{ $ticket->user?->name ?? '—' }}</td>
                            <td class="text-2xs text-ink-600">{{ $ticket->department_label }}</td>
                            <td>
                                <span @class([
                                    'badge',
                                    'badge-red' => $ticket->priority === 'high',
                                    'badge-blue' => $ticket->priority === 'normal',
                                    'badge-gray' => $ticket->priority === 'low',
                                ])>{{ $ticket->priority_label }}</span>
                            </td>
                            <td class="text-2xs text-ink-600">{{ fa_number($ticket->messages_count) }}</td>
                            <td class="whitespace-nowrap text-[11px] text-ink-500">{{ jalali_human($ticket->updated_at) }}</td>
                            <td><span class="badge {{ $ticket->status_badge }}">{{ $ticket->status_label }}</span></td>
                            <td>
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-icon h-8 w-8" aria-label="مشاهده تیکت">
                                    <x-icon name="eye" class="h-4 w-4" />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9">
                                <x-empty-state icon="headset" title="تیکتی وجود ندارد" message="تیکت‌های ثبت‌شده مشتریان اینجا نمایش داده می‌شوند." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $tickets->links() }}</div>
</div>
@endsection
