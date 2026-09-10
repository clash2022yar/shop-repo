@extends('layouts.app')
@section('title','تماس با ما')
@section('content')
<div class="max-w-[900px] mx-auto px-4 mt-8">
    <h1 class="font-bold text-lg">تماس با ما</h1>
    <div class="grid md:grid-cols-2 gap-6 mt-4">
        <div class="bg-white rounded-xl border p-6">
            <h3 class="font-bold text-sm mb-4">فرم تماس</h3>
            <form class="space-y-3" onsubmit="event.preventDefault(); showToast('پیام شما ارسال شد','success'); this.reset();">
                <input placeholder="نام شما" required class="w-full h-10 border rounded-lg px-3 text-sm">
                <input placeholder="ایمیل" type="email" required class="w-full h-10 border rounded-lg px-3 text-sm">
                <textarea placeholder="پیام شما" rows="4" required class="w-full border rounded-lg p-3 text-sm"></textarea>
                <button type="submit" class="w-full h-10 bg-[#ef4056] text-white rounded-lg font-bold hover:bg-[#d32f44] transition">ارسال پیام</button>
            </form>
        </div>
        <div class="bg-white rounded-xl border p-6 space-y-4 text-sm">
            <div><div class="font-bold">آدرس</div><div class="text-gray-500 mt-1">تهران، خیابان انقلاب، پلاک 123</div></div>
            <div><div class="font-bold">تلفن</div><div class="text-gray-500 mt-1">021-12345678</div></div>
            <div><div class="font-bold">ایمیل</div><div class="text-gray-500 mt-1">info@digino.com</div></div>
            <div class="h-40 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> نقشه</div>
        </div>
    </div>
</div>
@endsection
