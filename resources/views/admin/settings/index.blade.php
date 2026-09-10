@extends('layouts.admin')
@section('title','تنظیمات')
@section('page_title','تنظیمات')
@section('content')
<div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 bg-white rounded-xl border p-6 space-y-5">
        <h3 class="font-bold">تنظیمات عمومی</h3>
        <form class="space-y-4" onsubmit="event.preventDefault(); fetch('/admin/settings',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({site_name:this.site_name.value})}).then(()=> showToast('ذخیره شد','success'));">
            <div><label class="text-xs font-bold">نام سایت</label><input name="site_name" value="دیجینو" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
            <div><label class="text-xs font-bold">توضیحات سایت</label><textarea rows="2" class="mt-1 w-full border rounded-lg p-3 text-sm">فروشگاه تخصصی کالای دیجیتال</textarea></div>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="text-xs font-bold">ایمیل پشتیبانی</label><input value="info@digino.com" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm ltr" dir="ltr"></div>
                <div><label class="text-xs font-bold">تلفن</label><input value="021-12345678" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm ltr" dir="ltr"></div>
            </div>
            <div><label class="text-xs font-bold">آدرس</label><input value="تهران، خیابان انقلاب، پلاک 123" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="text-xs font-bold">هزینه ارسال</label><input value="0" type="number" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
                <div><label class="text-xs font-bold">مالیات (%)</label><input value="9" type="number" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
            </div>
            <button type="submit" class="h-10 px-6 bg-[#ef4056] text-white rounded-lg font-bold hover:bg-[#d32f44]">ذخیره تنظیمات (AJAX)</button>
        </form>
    </div>
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-sm mb-3">وضعیت سایت</h3>
            <label class="flex items-center justify-between"><span class="text-sm">فعال</span><input type="checkbox" checked class="accent-[#ef4056]"></label>
            <label class="flex items-center justify-between mt-3"><span class="text-sm">حالت تعمیر</span><input type="checkbox" class="accent-[#ef4056]"></label>
        </div>
        <div class="bg-[#1e293b] rounded-xl p-5 text-white">
            <h3 class="font-bold text-sm">اطلاعات سیستم</h3>
            <div class="mt-3 space-y-2 text-xs">
                <div class="flex justify-between"><span class="text-white/60">PHP</span><span>8.3.28</span></div>
                <div class="flex justify-between"><span class="text-white/60">Laravel</span><span>13.0.1</span></div>
                <div class="flex justify-between"><span class="text-white/60">دیتابیس</span><span>SQLite</span></div>
                <div class="flex justify-between"><span class="text-white/60">سازنده</span><span class="font-bold">یارمحمدی</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
