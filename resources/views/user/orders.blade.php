@extends('layouts.app')
@section('title','سفارش‌های من')
@php $hideFooter = true; @endphp
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4">
    <div class="flex gap-4">
        <div class="hidden lg:block w-[280px] shrink-0">@include('includes.sidebar-user')</div>
        <div class="flex-1">
            <h1 class="font-bold text-lg mb-4">سفارش‌های من</h1>
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="flex gap-2 p-3 border-b text-xs overflow-auto no-scrollbar">
                    <button class="px-4 py-1.5 bg-[#ef4056] text-white rounded-full font-bold">همه</button>
                    <button class="px-4 py-1.5 border rounded-full hover:bg-gray-50">در حال پردازش</button>
                    <button class="px-4 py-1.5 border rounded-full hover:bg-gray-50">ارسال شده</button>
                    <button class="px-4 py-1.5 border rounded-full hover:bg-gray-50">تحویل شده</button>
                    <button class="px-4 py-1.5 border rounded-full hover:bg-gray-50">لغو شده</button>
                </div>
                <div class="divide-y">
                    @for($i=0;$i<6;$i++)
                    <a href="/dashboard/orders/{{$i+1}}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg border flex items-center justify-center"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
                        <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 gap-2 text-xs">
                            <div><div class="font-bold">سفارش #{{ 1024+$i }}</div><div class="text-gray-500">{{ 12000000 +$i*2000000 }} تومان</div></div>
                            <div class="text-gray-500">1403/05/{{ 10+$i }}</div>
                            <div><span class="bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-[11px]">در حال پردازش</span></div>
                        </div>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
