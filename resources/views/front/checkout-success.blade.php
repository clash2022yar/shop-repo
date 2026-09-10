@extends('layouts.app')
@section('title','سفارش موفق')
@section('content')
<div class="max-w-[560px] mx-auto px-4 mt-10">
    <div class="bg-white rounded-2xl border p-8 text-center">
        <div class="w-20 h-20 mx-auto bg-[#f0fdf4] border border-[#bbf7d0] rounded-full flex items-center justify-center text-[#16a34a]">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h1 class="font-black text-lg mt-4">سفارش شما با موفقیت ثبت شد 🎉</h1>
        <p class="text-sm text-gray-500 mt-2">کد پیگیری: <span class="font-mono font-bold text-gray-900">TRK824592</span></p>
        <p class="text-xs text-gray-400 mt-1">همکاران ما به زودی سفارش شما را آماده و ارسال خواهند کرد.</p>
        <div class="grid grid-cols-3 gap-3 mt-6 text-xs">
            <div class="bg-gray-50 rounded-xl p-3"><div class="text-gray-500">مبلغ پرداخت</div><div class="font-bold mt-1">72,800,000 تومان</div></div>
            <div class="bg-gray-50 rounded-xl p-3"><div class="text-gray-500">روش پرداخت</div><div class="font-bold mt-1">آنلاین</div></div>
            <div class="bg-gray-50 rounded-xl p-3"><div class="text-gray-500">وضعیت</div><div class="font-bold mt-1 text-amber-600">در حال پردازش</div></div>
        </div>
        <div class="flex gap-3 mt-6">
            <a href="/dashboard/orders" class="flex-1 h-10 bg-[#ef4056] text-white rounded-lg flex items-center justify-center font-bold hover:bg-[#d32f44] transition">مشاهده سفارش</a>
            <a href="/shop" class="flex-1 h-10 border rounded-lg flex items-center justify-center font-bold hover:bg-gray-50">ادامه خرید</a>
        </div>
    </div>
</div>
@endsection
