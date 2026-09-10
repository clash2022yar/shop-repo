@extends('layouts.admin')
@section('title','نظرات')
@section('page_title','نظرات')
@section('content')
<div class="bg-white rounded-xl border overflow-hidden">
    <div class="p-4 border-b flex justify-between"><h3 class="font-bold text-sm">مدیریت نظرات</h3><span class="text-xs bg-amber-50 text-amber-700 border px-3 py-1 rounded-full">3 نظر در انتظار تایید</span></div>
    <div class="divide-y">
        @for($i=1;$i<=5;$i++)
        <div class="p-4 flex gap-4">
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center shrink-0"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
            <div class="flex-1">
                <div class="flex items-center gap-2"><span class="font-bold text-sm">کاربر {{$i}}</span><span class="text-amber-400 text-xs">★★★★☆</span><span class="text-xs text-gray-400">برای Galaxy S24 Ultra</span><span class="mr-auto text-xs text-gray-400">2 ساعت پیش</span></div>
                <p class="text-sm text-gray-600 mt-2 leading-5">محصول فوق‌العاده‌ای است، کیفیت ساخت عالی و دوربین بی‌نظیر! پیشنهاد می‌کنم.</p>
                <div class="flex gap-2 mt-3">
                    <button onclick="fetch('/admin/reviews/{{$i}}',{method:'PATCH',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=> showToast('تایید شد','success'))" class="h-7 px-3 bg-[#f0fdf4] text-[#15803d] border border-[#bbf7d0] rounded-full text-xs hover:bg-[#15803d] hover:text-white transition">تایید</button>
                    <button onclick="fetch('/admin/reviews/{{$i}}',{method:'DELETE',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=> showToast('حذف شد','success'))" class="h-7 px-3 bg-[#fff1f2] text-[#ef4056] border border-[#fecdd3] rounded-full text-xs hover:bg-[#ef4056] hover:text-white transition">حذف</button>
                    <button class="h-7 px-3 border rounded-full text-xs hover:bg-gray-50 mr-auto">پاسخ</button>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
@endsection
