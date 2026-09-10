@extends('layouts.app')
@section('title','حساب کاربری من — دیجینو')
@php $hideFooter = true; @endphp
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4">
    <nav class="text-[11px] text-gray-500 mb-3">خانه › حساب کاربری</nav>
    <h1 class="font-extrabold text-lg mb-4">حساب کاربری من</h1>

    <div class="flex flex-col lg:flex-row gap-4 items-start">
        <div class="w-full lg:w-[280px] shrink-0">
            @include('includes.sidebar-user')
        </div>

        <div class="flex-1 space-y-4 w-full">
            <!-- Recent Orders -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="p-4 flex items-center justify-between border-b">
                    <h2 class="font-bold text-sm">سفارش‌های اخیر من</h2>
                    <a href="/dashboard/orders" class="text-xs border rounded-lg px-3 py-1.5 hover:bg-gray-50">مشاهده همه</a>
                </div>
                <div class="divide-y">
                    @php $orders=[['سفارش #24 سفارشی', '24,290,000', '1402/05/25', 'در حال پردازش','yellow'],['سفارش #132 سفارش','15,900,000','1402/05/15','ارسال شده','green'],['سفارش #92 سفارشی','11,490,000','1402/05/08','تحویل شده','emerald'],['سفارش #24 سفارشی','6,200,000','1402/05/02','لغو شده','red']]; @endphp
                    @foreach($orders as $o)
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50/50 transition group">
                        <img src="/images/products/product-1.jpg" onerror="this.style.display='none'" class="w-14 h-14 object-contain bg-gray-50 rounded-lg border hidden lg:block" alt="">
                        <div class="w-14 h-14 bg-gray-50 rounded-lg border hidden lg:flex items-center justify-center lg:group-hover:bg-white"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
                        <div class="flex-1 grid grid-cols-2 lg:grid-cols-4 gap-3 items-center text-xs">
                            <div><div class="text-gray-500 lg:hidden">مبلغ</div><div class="font-bold">{{ $o[1] }} تومان</div></div>
                            <div><div class="font-medium">{{ $o[0] }}</div></div>
                            <div class="text-gray-500">{{ $o[2] }}</div>
                            <div><span class="px-3 py-1 rounded-full text-[11px] font-medium
                                @if($o[4]=='yellow') bg-amber-50 text-amber-700 border border-amber-200
                                @elseif($o[4]=='green') bg-green-50 text-green-700 border border-green-200
                                @elseif($o[4]=='emerald') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @else bg-red-50 text-red-600 border border-red-200 @endif
                            ">{{ $o[3] }}</span></div>
                        </div>
                        <a href="/dashboard/orders/1" class="w-8 h-8 rounded-full border flex items-center justify-center hover:bg-gray-100"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="bg-white rounded-xl border p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                    <div class="w-10 h-10 mx-auto bg-gray-50 rounded-xl flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div>
                    <div class="text-xs text-gray-500 mt-2">تیکت‌های من</div><div class="font-bold">2 تیکت</div>
                </div>
                <div class="bg-white rounded-xl border p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                    <div class="w-10 h-10 mx-auto bg-gray-50 rounded-xl flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/></svg></div>
                    <div class="text-xs text-gray-500 mt-2">آدرس‌ها</div><div class="font-bold">3 آدرس</div>
                </div>
                <div class="bg-white rounded-xl border p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                    <div class="w-10 h-10 mx-auto bg-gray-50 rounded-xl flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></div>
                    <div class="text-xs text-gray-500 mt-2">سفارش‌ها</div><div class="font-bold">8 سفارش</div>
                </div>
                <div class="bg-white rounded-xl border p-5 text-center hover:shadow-md hover:-translate-y-0.5 transition">
                    <div class="w-10 h-10 mx-auto bg-gray-50 rounded-xl flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
                    <div class="text-xs text-gray-500 mt-2">علاقه‌مندی‌ها</div><div class="font-bold">12 کالا</div>
                </div>
            </div>

            <!-- Recently Viewed -->
            <div class="bg-white rounded-xl border p-4">
                <div class="flex items-center justify-between mb-4"><h2 class="font-bold text-sm">مشاهده شده اخیر</h2><a href="#" class="text-xs border rounded-lg px-3 py-1.5">مشاهده همه</a></div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3" id="recentView"></div>
            </div>

            <!-- App Banner -->
            <div class="bg-[#f5f5f7] rounded-xl p-6 flex flex-col lg:flex-row items-center gap-6 border">
                <div class="flex-1 text-right">
                    <div class="font-black">اپلیکیشن دیجینو</div>
                    <div class="text-xs text-gray-500 mt-1">خریدی آسان‌تر و سریع‌تر با اپلیکیشن دیجینو</div>
                    <div class="flex gap-2 mt-3">
                        <a href="#" class="flex-1 lg:flex-none bg-white border rounded-xl px-4 py-2 flex items-center gap-2 hover:shadow-md transition"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.11 1.23-2.12 2.98.02 2.38 2.07 3.46 2.1 3.47-.03.07-.39 1.35-1.83 2.73zM12.36 2.18c.64-.78 1.07-1.87.95-2.95-.92.04-2.04.62-2.7 1.4-.6.7-1.12 1.82-.98 2.89 1.04.08 2.1-.53 2.73-1.34z"/></svg><div class="text-xs leading-none"><div class="font-bold">دریافت برای</div><div class="text-[10px]">iOS - نسخه وب</div></div></a>
                        <a href="#" class="flex-1 lg:flex-none bg-white border rounded-xl px-4 py-2 flex items-center gap-2 hover:shadow-md transition"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3 20.5a.5.5 0 0 0 .5.5h17a.5.5 0 0 0 .5-.5v-17a.5.5 0 0 0-.5-.5h-17a.5.5 0 0 0-.5.5v17zM12 7a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg><div class="text-xs leading-none"><div class="font-bold">دریافت برای</div><div class="text-[10px]">Android - نسخه وب</div></div></a>
                    </div>
                </div>
                <div class="w-[260px] h-[140px] bg-white rounded-2xl shadow-xl border overflow-hidden relative">
                    <div class="h-8 bg-[#ef4056] flex items-center px-3 gap-2"><div class="w-2 h-2 bg-white rounded-full"></div><div class="text-white text-xs font-bold">دیجینو</div></div>
                    <div class="p-3 grid grid-cols-3 gap-2"><div class="h-10 bg-gray-100 rounded-lg"></div><div class="h-10 bg-gray-100 rounded-lg"></div><div class="h-10 bg-gray-100 rounded-lg"></div><div class="h-10 bg-[#ef4056]/10 border border-[#ef4056]/20 rounded-lg col-span-3"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
const recent=[{name:'ایرپادز پرو 2 USB-C',price:11490000},{name:'JBL Charge 5',price:8900000},{name:'ساعت هوشمند Watch Series 9',price:23500000},{name:'Galaxy S24 Ultra',price:56900000},{name:'MacBook Air M2 2023',price:46500000}];
document.getElementById('recentView').innerHTML = recent.map(p=>`
<div class="border rounded-xl p-3 hover:shadow-lg hover:-translate-y-1 transition group">
  <div class="relative"><span class="absolute top-0 left-0 w-6 h-6 flex items-center justify-center text-gray-300"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></span><div class="h-24 bg-gray-50 rounded-lg flex items-center justify-center"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div></div>
  <div class="text-xs mt-2 line-clamp-2 font-medium group-hover:text-[#ef4056]">${p.name}</div>
  <div class="font-bold text-sm mt-1">${p.price.toLocaleString('fa-IR')} تومان</div>
</div>`).join('');
</script>
@endpush
