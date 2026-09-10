@extends('layouts.app')
@section('title','درباره ما — دیجینو')
@section('content')
<div class="max-w-[900px] mx-auto px-4 lg:px-8 mt-8">
    <div class="bg-white rounded-2xl border p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-xl bg-[#ef4056] flex items-center justify-center text-white"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg></div>
            <div><h1 class="font-black text-xl">درباره دیجینو</h1><p class="text-xs text-gray-500">فروشگاه تخصصی کالای دیجیتال — خرید هوشمند</p></div>
        </div>

        <div class="prose prose-sm max-w-none text-[13px] leading-7 text-gray-700">
            <p>دیجینو با الهام از بهترین تجربه‌های فروشگاهی ایران، تلاش می‌کند تا خریدی <span class="font-bold text-gray-900">سریع، مطمئن و لذت‌بخش</span> را برای شما فراهم کند. ما با گلچین کردن محصولات اصلی و ارائه بهترین قیمت، همراه همیشگی شما در دنیای تکنولوژی هستیم.</p>
            
            <h3 class="font-bold text-gray-900 mt-6">ماموریت ما</h3>
            <p>ارائه محصولات دیجیتال با ضمانت اصالت، قیمت رقابتی و ارسال سریع به سراسر کشور. رضایت شما سرمایه ماست.</p>

            <div class="grid md:grid-cols-3 gap-4 mt-6 not-prose">
                <div class="border rounded-xl p-4 text-center hover:shadow-md transition"><div class="w-10 h-10 mx-auto bg-[#fff1f2] text-[#ef4056] rounded-full flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div><div class="font-bold text-sm mt-2">ضمانت اصالت</div><div class="text-xs text-gray-500 mt-1">همه کالاها با گارانتی معتبر</div></div>
                <div class="border rounded-xl p-4 text-center hover:shadow-md transition"><div class="w-10 h-10 mx-auto bg-[#f0fdf4] text-[#16a34a] rounded-full flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg></div><div class="font-bold text-sm mt-2">ارسال سریع</div><div class="text-xs text-gray-500 mt-1">تحویل در سریع‌ترین زمان</div></div>
                <div class="border rounded-xl p-4 text-center hover:shadow-md transition"><div class="w-10 h-10 mx-auto bg-[#eff6ff] text-[#2563eb] rounded-full flex items-center justify-center"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg></div><div class="font-bold text-sm mt-2">پشتیبانی</div><div class="text-xs text-gray-500 mt-1">7 روز هفته، 24 ساعته</div></div>
            </div>

            <h3 class="font-bold text-gray-900 mt-8 flex items-center gap-2"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4056" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> حقوق معنوی و لایسنس</h3>
            <div class="bg-[#fff1f2] border border-[#fecdd3] rounded-xl p-5 mt-3 text-[12.5px] leading-6">
                <p class="font-bold text-[#9f1239]">این قالب و کد به صورت اختصاصی برای دیجینو توسط یارمحمدی طراحی و توسعه یافته است.</p>
                <p class="mt-2 text-gray-700">تمامی حقوق مادی و معنوی این وب‌سایت شامل <span class="font-bold">طراحی رابط کاربری، کدنویسی، ساختار دیتابیس، و محتوای اختصاصی</span> متعلق به دیجینو می‌باشد. هرگونه <span class="font-bold text-[#be123c]">کپی‌برداری، بازنشر، اقتباس، مهندسی معکوس یا استفاده تجاری بدون اجازه کتبی</span> حتی با تغییر جزئی، غیرمجاز بوده و پیگرد قانونی به همراه دارد.</p>
                <p class="mt-2">ما با احترام از جامعه توسعه‌دهندگان دعوت می‌کنیم به جای کپی، با الهام گرفتن و خلق اثر اصیل خود، به رشد اکوسیستم کمک کنند. برای دریافت مجوز یا همکاری با ما تماس بگیرید.</p>
                <p class="mt-3 text-[11px] text-gray-500">© 1403 دیجینو — سازنده: یارمحمدی — کلیه حقوق محفوظ است. Proprietary License — All Rights Reserved.</p>
            </div>

            <h3 class="font-bold text-gray-900 mt-6">تماس با ما</h3>
            <p>تهران، خیابان انقلاب، پلاک 123 — تلفن: 021-12345678 — ایمیل: info@digino.com</p>

        </div>
    </div>
</div>
@endsection
