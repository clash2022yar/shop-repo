@extends('layouts.admin')
@section('title','جزئیات مشتری')
@section('page_title','مشتری')
@section('content')
<div class="bg-white rounded-xl border p-6">
    <div class="flex items-center gap-4"><div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><div><div class="font-bold">علی محمدی</div><div class="text-sm text-gray-500">ali@example.com — 09120000000</div></div><span class="mr-auto bg-green-50 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs">فعال</span></div>
    <div class="mt-6 grid md:grid-cols-3 gap-4 text-sm">
        <div class="border rounded-xl p-4 text-center"><div class="text-2xl font-black">8</div><div class="text-xs text-gray-500">سفارش</div></div>
        <div class="border rounded-xl p-4 text-center"><div class="text-2xl font-black">245,000,000</div><div class="text-xs text-gray-500">مجموع خرید</div></div>
        <div class="border rounded-xl p-4 text-center"><div class="text-2xl font-black">4.2</div><div class="text-xs text-gray-500">امتیاز</div></div>
    </div>
</div>
@endsection
