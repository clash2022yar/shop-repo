@extends('layouts.admin')

@section('title', 'دفتر گردش انبار')
@section('heading', 'دفتر گردش انبار')
@section('subheading', 'تمام ورود و خروج‌های کالا به ترتیب زمان')

@section('breadcrumb')
    <a href="{{ route('admin.inventory.index') }}" class="link-muted">انبار</a>
    <x-icon name="chevron-left" class="h-3.5 w-3.5 text-ink-300" />
    <span class="text-ink-700">گردش انبار</span>
@endsection

@section('content')
@php
    $types = [
        'in' => ['ورود', 'badge-green'],
        'out' => ['خروج', 'badge-red'],
        'adjust' => ['اصلاح', 'badge-blue'],
        'sale' => ['فروش', 'badge-amber'],
        'return' => ['مرجوعی', 'badge-gray'],
    ];
@endphp

<div>
    <div class="mb-4 flex flex-wrap items-center gap-1.5 rounded-card bg-white p-4 shadow-card">
        @foreach(array_merge(['' => 'همه'], collect($types)->map(fn ($t) => $t[0])->all()) as $value => $label)
            <a href="{{ route('admin.inventory.movements', array_filter(['type' => $value])) }}"
               @class([
                   'rounded-pill px-3 py-1.5 text-2xs transition-colors',
                   'bg-brand-50 font-bold text-brand-600' => (string) request('type') === (string) $value,
                   'text-ink-600 hover:bg-ink-50' => (string) request('type') !== (string) $value,
               ])>{{ $label }}</a>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-card bg-white shadow-card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>کالا</th>
                        <th>نوع</th>
                        <th>تغییر</th>
                        <th>موجودی پس از تغییر</th>
                        <th>مرجع</th>
                        <th>کاربر</th>
                        <th>زمان</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr class="animate-fade-in">
                            <td>
                                <span class="line-clamp-1 max-w-xs text-2xs text-ink-800">{{ $movement->product?->name ?? 'کالای حذف‌شده' }}</span>
                                <span class="ltr mt-0.5 block text-[10px] text-ink-400">{{ $movement->product?->sku }}</span>
                            </td>
                            <td>
                                <span class="{{ ($types[$movement->type] ?? $types['adjust'])[1] }}">
                                    {{ ($types[$movement->type] ?? ['نامشخص'])[0] }}
                                </span>
                            </td>
                            <td class="text-2xs font-extrabold {{ $movement->quantity >= 0 ? 'text-success-600' : 'text-brand-500' }}">
                                {{ $movement->quantity >= 0 ? '+' : '−' }}{{ fa_number(abs($movement->quantity)) }}
                            </td>
                            <td class="text-2xs text-ink-700">{{ fa_number($movement->stock_after) }}</td>
                            <td class="text-2xs text-ink-600">{{ $movement->reference ?: '—' }}</td>
                            <td class="text-2xs text-ink-600">{{ $movement->user?->name ?? 'سیستم' }}</td>
                            <td class="whitespace-nowrap text-[11px] text-ink-500">{{ jalali($movement->created_at, 'j F Y — H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="list" title="گردشی ثبت نشده است" message="با ثبت سفارش یا اصلاح موجودی، رکوردها اینجا ساخته می‌شوند." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $movements->links() }}</div>
</div>
@endsection
