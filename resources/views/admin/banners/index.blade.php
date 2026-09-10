@extends('layouts.admin')
@section('title','بنرها')
@section('page_title','بنرها')
@section('content')
<div class="flex justify-between items-center mb-4"><h3 class="font-bold text-sm">مدیریت بنرها</h3><a href="/admin/banners/create" class="h-9 px-4 bg-[#ef4056] text-white rounded-lg text-sm font-bold">+ افزودن بنر</a></div>
<div class="grid md:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border overflow-hidden hover:shadow-lg transition">
        <div class="h-40 bg-gradient-to-br from-[#ffe9ec] to-[#ffd1d7] flex items-center justify-center">بنر هدفون</div>
        <div class="p-4"><div class="font-bold text-sm">تخفیف هدفون 30%</div><div class="text-xs text-gray-500 mt-1">موقعیت: صفحه اصلی — وضعیت: فعال</div><div class="flex gap-2 mt-3"><a href="/admin/banners/1/edit" class="h-7 px-3 border rounded-full text-xs">ویرایش</a><button onclick="showToast('حذف شد','success')" class="h-7 px-3 border rounded-full text-xs hover:bg-[#fff1f2] hover:text-[#ef4056]">حذف با AJAX</button></div></div>
    </div>
    <div class="bg-white rounded-xl border overflow-hidden hover:shadow-lg transition">
        <div class="h-40 bg-gradient-to-br from-[#e6f7f5] to-[#c7ebe7] flex items-center justify-center">بنر ساعت هوشمند</div>
        <div class="p-4"><div class="font-bold text-sm">ساعت هوشمند 25%</div><div class="text-xs text-gray-500 mt-1">موقعیت: صفحه اصلی — وضعیت: فعال</div><div class="flex gap-2 mt-3"><a href="/admin/banners/1/edit" class="h-7 px-3 border rounded-full text-xs">ویرایش</a><button class="h-7 px-3 border rounded-full text-xs">حذف</button></div></div>
    </div>
</div>
@endsection
