@extends('layouts.admin')
@section('title','سفارش‌ها')
@section('page_title','سفارش‌ها')
@section('content')
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="p-4 flex gap-2 border-b"><input placeholder="جستجوی سفارش..." class="h-9 flex-1 border rounded-lg px-3 text-sm"><select class="h-9 border rounded-lg px-3 text-sm"><option>همه وضعیت‌ها</option></select><button class="h-9 px-4 border rounded-lg text-sm">فیلتر</button></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500"><tr><th class="p-3 text-right">کد</th><th class="p-3 text-right">مشتری</th><th class="p-3 text-right">تاریخ</th><th class="p-3 text-right">مبلغ</th><th class="p-3 text-right">وضعیت</th><th class="p-3"></th></tr></thead>
        <tbody class="divide-y">
            @for($i=1;$i<=10;$i++)
            <tr class="hover:bg-gray-50">
                <td class="p-3 font-mono">#TRK{{ 800000+$i }}</td><td class="p-3">کاربر {{ $i }}</td><td class="p-3 text-xs text-gray-500">1403/05/{{10+$i}}</td><td class="p-3 font-bold">{{ (1000000*$i*3)+5000000 }} تومان</td><td class="p-3"><span class="px-2 py-1 rounded-full text-[11px] bg-amber-50 text-amber-700 border">در حال پردازش</span></td><td class="p-3"><a href="/admin/orders/1" class="w-8 h-8 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></a></td>
            </tr>
            @endfor
        </tbody>
    </table>
</div>
@endsection
