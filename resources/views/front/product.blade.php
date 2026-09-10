@extends('layouts.app')
@section('title','گوشی موبایل سامسونگ Galaxy S24 Ultra')
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4">
    <nav class="text-[11px] text-gray-500 flex items-center gap-1 mb-4 overflow-auto no-scrollbar">خانه › موبایل › گوشی موبایل › Samsung Galaxy S24 Ultra</nav>

    <div class="bg-white rounded-xl border border-gray-100 p-4 lg:p-6 flex flex-col lg:flex-row gap-6">
        <!-- Thumbs -->
        <div class="hidden lg:flex flex-col gap-2 shrink-0">
            @for($i=0;$i<5;$i++)
            <button class="w-[72px] h-[72px] border-2 {{ $i==0? 'border-[#ef4056]': 'border-gray-100' }} rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center hover:border-gray-300 transition">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><rect x="5" y="2" width="14" height="20" rx="2"/></svg>
            </button>
            @endfor
            <button class="w-[72px] h-[64px] border border-gray-100 rounded-xl flex items-center justify-center text-xs font-bold hover:bg-gray-50">+6</button>
        </div>

        <!-- Main Image -->
        <div class="flex-1 flex items-center justify-center bg-gray-50 rounded-xl lg:bg-white relative min-h-[340px] group overflow-hidden">
            <div class="w-[280px] h-[480px] bg-gradient-to-br from-[#1f2937] to-[#0f172a] rounded-[32px] shadow-2xl border-[6px] border-black flex flex-col overflow-hidden relative">
                <div class="h-6 bg-black flex items-center justify-center"><span class="w-16 h-3 bg-[#111] rounded-full"></span></div>
                <div class="flex-1 bg-gradient-to-br from-gray-800 via-gray-700 to-black flex items-center justify-center relative">
                    <div class="absolute inset-0 opacity-20" style="background: radial-gradient(circle at 30% 20%, #6366f1, transparent 50%)"></div>
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.2" class="opacity-80"><circle cx="12" cy="13" r="8"/><path d="M12 8v-2"/><path d="M8.5 10.5 12 7l3.5 3.5"/></svg>
                </div>
                <div class="absolute left-3 top-1/2 -translate-y-1/2 flex flex-col gap-2">
                    <span class="w-2 h-2 bg-white/80 rounded-full"></span><span class="w-2 h-2 bg-white/30 rounded-full"></span><span class="w-2 h-2 bg-white/30 rounded-full"></span>
                </div>
            </div>
            <button class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white shadow rounded-full hidden lg:flex items-center justify-center hover:bg-gray-50"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg></button>
            <button class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-white shadow rounded-full hidden lg:flex items-center justify-center hover:bg-gray-50"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg></button>
        </div>

        <!-- Details -->
        <div class="lg:w-[420px] shrink-0">
            <h1 class="font-bold text-[15px] leading-6">گوشی موبایل سامسونگ مدل Galaxy S24 Ultra</h1>
            <div class="text-[11px] text-gray-500 mt-1">دو سیم کارت - 512 گیگابایت و 12 گیگابایت رم - IP68</div>
            <div class="flex items-center gap-2 mt-3 text-[11px]">
                <span class="bg-[#f0fdf4] text-[#15803d] border border-[#bbf7d0] px-2 py-0.5 rounded-full">موجود</span>
                <span class="text-amber-400">★★★★★</span><span class="text-gray-500">(124)</span>
            </div>

            <div class="mt-5 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex items-baseline gap-2">
                    <span class="font-black text-xl">56,900,000</span><span class="text-xs">تومان</span>
                    <span class="mr-auto text-[11px] bg-[#fff1f2] text-[#ef4056] px-2 py-1 rounded-full">امکان پرداخت اقساطی از 2,843,000 تومان در ماه</span>
                </div>

                <div class="mt-4">
                    <div class="text-xs font-bold mb-2">رنگ: تیتانیوم مشکی</div>
                    <div class="flex gap-2">
                        <button class="w-8 h-8 rounded-full bg-black border-2 border-[#ef4056] ring-2 ring-[#ef4056]/20 relative"><span class="absolute inset-0 rounded-full border border-white/20"></span></button>
                        <button class="w-8 h-8 rounded-full bg-gray-400 border-2 border-white shadow"></button>
                        <button class="w-8 h-8 rounded-full bg-[#e7d6c8] border-2 border-white shadow"></button>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="text-xs font-bold mb-2">حافظه داخلی: 512 گیگابایت</div>
                    <div class="flex gap-2 text-xs">
                        <button class="px-3 py-1.5 rounded-lg border bg-white">256 گیگابایت</button>
                        <button class="px-3 py-1.5 rounded-lg border border-[#ef4056] bg-[#fff1f2] text-[#ef4056] font-bold">512 گیگابایت</button>
                        <button class="px-3 py-1.5 rounded-lg border bg-white">1 ترابایت</button>
                    </div>
                </div>

                <div class="mt-4 space-y-2 text-[11.5px] bg-white rounded-lg p-3 border">
                    <div class="flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg> ارسال سریع <span class="mr-auto text-gray-500">ارسال امروز تهران</span></div>
                    <div class="flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg> گارانتی اصالت و سلامت فیزیکی</div>
                    <div class="flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> 7 روز ضمانت بازگشت</div>
                    <div class="flex items-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#62666d"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg> پشتیبانی 24 ساعته</div>
                </div>

                <button onclick="addToCart('Galaxy S24 Ultra',56900000)" class="mt-4 w-full h-11 bg-[#ef4056] hover:bg-[#d32f44] text-white rounded-lg font-bold flex items-center justify-center gap-2 shadow-lg shadow-[#ef4056]/20 hover:shadow-xl hover:-translate-y-0.5 transition">
                    افزودن به سبد خرید <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </button>
                <button class="mt-2 w-full h-10 border border-gray-200 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center justify-center gap-2"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> افزودن به علاقه‌مندی‌ها</button>

                <!-- Specs quick -->
                <div class="grid grid-cols-5 gap-2 mt-4 text-center text-[10.5px]">
                    <div class="bg-white rounded-lg p-2 border"><div class="text-xs font-bold">5000 میلی‌آمپر</div><div class="text-gray-500">ظرفیت باتری</div></div>
                    <div class="bg-white rounded-lg p-2 border"><div class="text-xs font-bold">200 مگاپیکسل</div><div class="text-gray-500">دوربین اصلی</div></div>
                    <div class="bg-white rounded-lg p-2 border"><div class="text-xs font-bold">512 گیگابایت</div><div class="text-gray-500">حافظه داخلی</div></div>
                    <div class="bg-white rounded-lg p-2 border"><div class="text-xs font-bold">12 گیگابایت</div><div class="text-gray-500">حافظه RAM</div></div>
                    <div class="bg-white rounded-lg p-2 border"><div class="text-xs font-bold">6.8 اینچ</div><div class="text-gray-500">اندازه نمایشگر</div></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="mt-6 bg-white rounded-xl border border-gray-100 p-6">
        <div class="flex gap-6 border-b text-sm overflow-auto no-scrollbar">
            <button class="pb-3 border-b-2 border-[#ef4056] text-[#ef4056] font-bold whitespace-nowrap">معرفی محصول</button>
            <button class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-900 whitespace-nowrap">مشخصات فنی</button>
            <button class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-900 whitespace-nowrap">نظرات کاربران</button>
            <button class="pb-3 border-b-2 border-transparent text-gray-500 hover:text-gray-900 whitespace-nowrap">پرسش و پاسخ</button>
        </div>
        <div class="grid lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2 text-[13px] leading-7 text-gray-700">
                <p>گوشی موبایل سامسونگ Galaxy S24 Ultra ترکیبی از قدرت، زیبایی و هوشمندی است. با دوربین 200 مگاپیکسلی پردازنده قدرتمند و نمایشگر Dynamic AMOLED 2X تجربه‌ای بی‌نظیر از سرعت و کیفیت را برای شما به ارمغان می‌آورد.</p>
                <ul class="list-disc pr-5 mt-4 space-y-1 text-gray-600">
                    <li>صفحه نمایش 6.8 اینچی Dynamic AMOLED 2X با نرخ نوسازی 120 هرتز</li>
                    <li>پردازنده قدرتمند Snapdragon 8 Gen 3 for Galaxy</li>
                    <li>دوربین چهارگانه با سنسور اصلی 200 مگاپیکسلی</li>
                    <li>باتری 5000 میلی‌آمپر با شارژ سریع 45 وات</li>
                </ul>
            </div>
            <div class="bg-[#f8f9ff] rounded-xl p-6 border border-[#e0e7ff] text-center">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-violet-500 to-purple-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <div class="font-bold mt-3">گارانتی اصالت و سلامت فیزیکی کالا</div>
                <div class="text-xs text-gray-500 mt-2 leading-5">تمامی محصولات دیجی‌نو دارای گارانتی معتبر و تضمین تضمین اصالت کالا هستند...</div>
            </div>
        </div>
    </div>

    <!-- Related -->
    <section class="mt-6 bg-white rounded-xl border border-gray-100 p-4">
        <h2 class="font-bold text-sm mb-4">محصولات مرتبط</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3" id="relatedGrid"></div>
    </section>
</div>
@endsection
@push('scripts')
<script>
const related=[{name:'هدفون پرسرو صدای سونی WH-1000XM5',price:15900000,count:65},{name:'گوشی موبایل شیائومی 14 Ultra',price:42200000,count:98},{name:'گوشی موبایل سامسونگ Galaxy S23 Ultra',price:47500000,count:124},{name:'ساعت هوشمند سامسونگ Galaxy Watch 6',price:13900000,count:67},{name:'ایرپادز پرو 2 USB-C',price:11490000,count:88}];
document.getElementById('relatedGrid').innerHTML = related.map(p=>`
<div class="border border-gray-100 rounded-xl p-3 hover:shadow-lg hover:-translate-y-1 transition duration-300 group">
  <div class="h-24 bg-gray-50 rounded-lg flex items-center justify-center"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1"><rect x="5" y="2" width="14" height="20" rx="2"/></svg></div>
  <div class="text-xs mt-2 line-clamp-2 min-h-[32px] group-hover:text-[#ef4056]">${p.name}</div>
  <div class="text-amber-400 text-[11px] mt-1">★★★★★ <span class="text-gray-400">(${p.count})</span></div>
  <div class="font-bold text-sm mt-1">${p.price.toLocaleString('fa-IR')} تومان</div>
</div>`).join('');
</script>
@endpush
