@extends('layouts.admin')
@section('title','کوپن‌ها')
@section('page_title','کوپن‌ها')
@section('content')
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="p-4 flex justify-between border-b"><h3 class="font-bold text-sm">کوپن‌های تخفیف</h3><a href="/admin/coupons/create" class="h-8 px-4 bg-[#ef4056] text-white rounded-lg text-xs font-bold">+ افزودن کوپن</a></div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500"><tr><th class="p-3 text-right">کد</th><th class="p-3 text-right">درصد</th><th class="p-3 text-right">حداقل خرید</th><th class="p-3 text-right">انقضا</th><th class="p-3 text-right">وضعیت</th><th class="p-3"></th></tr></thead>
        <tbody class="divide-y">
            <tr class="hover:bg-gray-50"><td class="p-3 font-mono font-bold">DIGINO10</td><td class="p-3">10%</td><td class="p-3">500,000 تومان</td><td class="p-3">1403/06/30</td><td class="p-3"><span class="bg-green-50 text-green-700 border border-green-200 px-2 py-1 rounded-full text-xs">فعال</span></td><td class="p-3"><div class="flex gap-1"><a href="/admin/coupons/1/edit" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a><button onclick="showToast('کپی شد','success')" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v3"/></svg></button></div></td></tr>
            <tr class="hover:bg-gray-50"><td class="p-3 font-mono font-bold">WELCOME20</td><td class="p-3">20%</td><td class="p-3">1,000,000 تومان</td><td class="p-3">1403/07/15</td><td class="p-3"><span class="bg-green-50 text-green-700 border border-green-200 px-2 py-1 rounded-full text-xs">فعال</span></td><td class="p-3"><div class="flex gap-1"><a href="/admin/coupons/1/edit" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a></div></td></tr>
        </tbody>
    </table>
</div>
@endsection
