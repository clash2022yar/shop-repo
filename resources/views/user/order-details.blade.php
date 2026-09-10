@extends('layouts.app')
@section('title','جزئیات سفارش')
@php $hideFooter = true; @endphp
@section('content')
<div class="max-w-[900px] mx-auto px-4 mt-6">
    <a href="/dashboard/orders" class="text-xs flex items-center gap-1 hover:text-[#ef4056]">› بازگشت به سفارش‌ها</a>
    <h1 class="font-bold text-lg mt-2">جزئیات سفارش #1024</h1>
    <div class="mt-4 bg-white rounded-xl border p-5">
        <div class="flex flex-wrap gap-4 text-xs">
            <div><span class="text-gray-500">تاریخ ثبت:</span> 1403/05/25</div>
            <div><span class="text-gray-500">وضعیت:</span> <span class="bg-amber-50 text-amber-700 px-2 py-1 rounded-full border">در حال پردازش</span></div>
            <div><span class="text-gray-500">کد پیگیری:</span> <span class="font-mono font-bold">TRK824592</span></div>
            <div><span class="text-gray-500">مبلغ کل:</span> <span class="font-bold">72,800,000 تومان</span></div>
        </div>
        <div class="mt-6">
            <h3 class="font-bold text-sm mb-3">محصولات</h3>
            <div class="space-y-3">
                <div class="flex gap-3 border rounded-xl p-3"><div class="w-16 h-16 bg-gray-50 rounded-lg border flex items-center justify-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div><div class="flex-1"><div class="text-sm font-medium">Galaxy S24 Ultra - تیتانیوم مشکی</div><div class="text-xs text-gray-500">تعداد 1 × 56,900,000</div></div><div class="font-bold text-sm">56,900,000</div></div>
                <div class="flex gap-3 border rounded-xl p-3"><div class="w-16 h-16 bg-gray-50 rounded-lg border flex items-center justify-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/></svg></div><div class="flex-1"><div class="text-sm font-medium">WH-1000XM5</div><div class="text-xs text-gray-500">تعداد 1 × 15,900,000</div></div><div class="font-bold text-sm">15,900,000</div></div>
            </div>
        </div>
        <div class="mt-6 grid md:grid-cols-2 gap-4 text-xs">
            <div class="border rounded-xl p-4"><div class="font-bold mb-2">آدرس تحویل</div><div class="text-gray-600 leading-5">تهران، خیابان انقلاب، پلاک 123، واحد 5 — کد پستی 1234567890 — گیرنده: علی محمدی 09120000000</div></div>
            <div class="border rounded-xl p-4"><div class="font-bold mb-2">اطلاعات پرداخت</div><div>روش: پرداخت آنلاین</div><div>وضعیت: پرداخت شده</div><div>تخفیف: 0 تومان</div></div>
        </div>
        <div class="mt-6 flex gap-2">
            <button onclick="showToast('درخواست مرجوعی ثبت شد','success')" class="h-9 px-4 border rounded-lg text-xs hover:bg-gray-50">درخواست مرجوعی</button>
            <button onclick="showToast('فاکتور دانلود شد','info')" class="h-9 px-4 border rounded-lg text-xs hover:bg-gray-50">دانلود فاکتور</button>
            <button onclick="showToast('پشتیبانی تماس می‌گیرد','success')" class="mr-auto h-9 px-6 bg-[#ef4056] text-white rounded-lg text-xs font-bold hover:bg-[#d32f44]">تماس با پشتیبانی</button>
        </div>
    </div>
</div>
@endsection
