@extends('layouts.admin')
@section('title','داشبورد ادمین')
@section('page_title','داشبورد')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white rounded-xl border p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between"><div class="w-10 h-10 rounded-xl bg-[#fff1f2] text-[#ef4056] flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg></div><span class="text-xs bg-[#f0fdf4] text-[#15803d] px-2 py-1 rounded-full border">+12%</span></div>
        <div class="text-2xl font-black mt-3">1,284</div><div class="text-xs text-gray-500">کل سفارش‌ها</div>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden"><div class="h-full w-[72%] bg-[#ef4056]"></div></div>
    </div>
    <div class="bg-white rounded-xl border p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between"><div class="w-10 h-10 rounded-xl bg-[#eff6ff] text-[#2563eb] flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div><span class="text-xs bg-[#eff6ff] text-[#2563eb] px-2 py-1 rounded-full">60 کالا</span></div>
        <div class="text-2xl font-black mt-3">60</div><div class="text-xs text-gray-500">کل محصولات</div>
        <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden"><div class="h-full w-[60%] bg-[#2563eb]"></div></div>
    </div>
    <div class="bg-white rounded-xl border p-5 hover:shadow-md transition">
        <div class="flex items-center justify-between"><div class="w-10 h-10 rounded-xl bg-[#f0fdf4] text-[#15803d] flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><span class="text-xs bg-[#fef9c3] text-[#a16207] px-2 py-1 rounded-full">22 کاربر</span></div>
        <div class="text-2xl font-black mt-3">1,420</div><div class="text-xs text-gray-500">مشتریان</div>
    </div>
    <div class="bg-gradient-to-br from-[#ef4056] to-[#be123c] rounded-xl p-5 text-white relative overflow-hidden">
        <div class="absolute -left-6 -top-6 w-20 h-20 bg-white/10 rounded-full"></div>
        <div class="text-xs opacity-80">درآمد کل</div>
        <div class="text-2xl font-black mt-2">1,842,500,000 <span class="text-xs font-normal">تومان</span></div>
        <div class="text-xs opacity-80 mt-1">+8.2% نسبت به ماه قبل</div>
        <svg class="absolute left-4 bottom-3 opacity-20" width="60" height="30" viewBox="0 0 60 30" fill="none" stroke="white" stroke-width="2"><path d="M0 20 Q15 5 30 15 T60 10"/></svg>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-4 mt-6">
    <div class="lg:col-span-2 bg-white rounded-xl border overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between"><h3 class="font-bold text-sm">سفارش‌های اخیر</h3><a href="/admin/orders" class="text-xs text-[#19bfd3] border rounded-lg px-3 py-1 hover:bg-gray-50">مشاهده همه</a></div>
        <div class="overflow-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 text-gray-500"><tr><th class="p-3 text-right">کد</th><th class="p-3 text-right">مشتری</th><th class="p-3 text-right">مبلغ</th><th class="p-3 text-right">وضعیت</th><th class="p-3"></th></tr></thead>
                <tbody class="divide-y">
                    @for($i=1;$i<=6;$i++)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-mono">#102{{$i}}</td><td class="p-3">علی محمدی</td><td class="p-3 font-bold">{{ (2000000*$i+5000000) }} تومان</td><td class="p-3"><span class="px-2 py-1 rounded-full text-[11px] bg-amber-50 text-amber-700 border">در حال پردازش</span></td><td class="p-3"><a href="/admin/orders/1" class="w-7 h-7 border rounded-full flex items-center justify-center hover:bg-white"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></a></td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="p-4 border-b flex items-center justify-between"><h3 class="font-bold text-sm">موجودی کم</h3><a href="/admin/inventory" class="text-xs text-[#ef4056]">مدیریت</a></div>
        <div class="divide-y">
            @for($i=0;$i<5;$i++)
            <div class="p-3 flex items-center gap-3">
                <div class="w-10 h-10 bg-gray-50 rounded-lg border flex items-center justify-center"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
                <div class="flex-1"><div class="text-xs font-medium">Galaxy S24 Ultra</div><div class="text-[11px] text-red-600">تنها {{ 2+$i }} عدد باقی مانده</div></div>
                <button onclick="showRestockModal()" class="text-[11px] bg-[#fff1f2] text-[#ef4056] border border-[#fecdd3] px-3 py-1 rounded-full hover:bg-[#ef4056] hover:text-white transition">شارژ</button>
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-4 mt-6">
    <div class="bg-white rounded-xl border p-5">
        <h3 class="font-bold text-sm mb-3">عملکرد فروش (7 روز)</h3>
        <div class="h-32 flex items-end gap-1">
            @for($i=0;$i<7;$i++)<div class="flex-1 bg-[#f1f5f9] rounded-t relative overflow-hidden" style="height: {{40+rand(0,60)}}%"><div class="absolute bottom-0 inset-x-0 bg-[#ef4056] rounded-t" style="height: {{rand(40,100)}}%"></div></div>@endfor
        </div>
        <div class="flex justify-between text-[10px] text-gray-400 mt-2"><span>شنبه</span><span>یکشنبه</span><span>دوشنبه</span><span>سه‌شنبه</span><span>چهارشنبه</span><span>پنجشنبه</span><span>جمعه</span></div>
    </div>
    <div class="bg-white rounded-xl border p-5">
        <h3 class="font-bold text-sm mb-3">دسته‌های پرفروش</h3>
        <div class="space-y-3 text-xs">
            <div class="flex items-center gap-2"><span class="flex-1">موبایل</span><div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-[#ef4056] w-[68%]"></div></div><span class="w-8 text-left">68%</span></div>
            <div class="flex items-center gap-2"><span class="flex-1">لپتاپ</span><div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-[#2563eb] w-[42%]"></div></div><span class="w-8 text-left">42%</span></div>
            <div class="flex items-center gap-2"><span class="flex-1">هدفون</span><div class="w-24 h-2 bg-gray-100 rounded-full overflow-hidden"><div class="h-full bg-[#16a34a] w-[31%]"></div></div><span class="w-8 text-left">31%</span></div>
        </div>
    </div>
    <div class="bg-[#1e293b] rounded-xl p-5 text-white">
        <h3 class="font-bold text-sm">دسترسی سریع</h3>
        <div class="grid grid-cols-2 gap-2 mt-4 text-xs">
            <a href="/admin/products/create" class="bg-white/10 hover:bg-white/20 rounded-xl p-3 flex flex-col items-center gap-2 transition"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> افزودن محصول</a>
            <a href="/admin/orders" class="bg-white/10 hover:bg-white/20 rounded-xl p-3 flex flex-col items-center gap-2 transition"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg> مشاهده سفارش</a>
            <a href="/admin/coupons" class="bg-white/10 hover:bg-white/20 rounded-xl p-3 flex flex-col items-center gap-2 transition"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white"><polyline points="20 12 20 22 4 22 4 12"/></svg> کوپن جدید</a>
            <a href="/admin/banners" class="bg-white/10 hover:bg-white/20 rounded-xl p-3 flex flex-col items-center gap-2 transition"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white"><rect x="3" y="3" width="18" height="18" rx="2"/></svg> بنر جدید</a>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function showRestockModal(){ showToast('موجودی بروز شد (دمو)','success'); }
</script>
@endpush
