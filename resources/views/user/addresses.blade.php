@extends('layouts.app')
@section('title','آدرس‌ها')
@php $hideFooter=true; @endphp
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4 flex gap-4">
  <div class="hidden lg:block w-[280px] shrink-0">@include('includes.sidebar-user')</div>
  <div class="flex-1">
    <div class="flex justify-between items-center mb-4"><h1 class="font-bold">آدرس‌های من</h1><button onclick="document.getElementById('addrModal').classList.remove('hidden'); document.getElementById('addrModal').classList.add('flex')" class="h-9 px-4 bg-[#ef4056] text-white rounded-lg text-sm font-bold">+ افزودن آدرس جدید</button></div>
    <div class="grid md:grid-cols-2 gap-4">
      @for($i=1;$i<=3;$i++)
      <div class="bg-white rounded-xl border p-4 hover:shadow-md transition">
        <div class="flex justify-between"><span class="font-bold text-sm">خانه {{$i}}</span><span class="text-[11px] bg-gray-100 px-2 py-1 rounded-full">پیش‌فرض</span></div>
        <div class="text-xs text-gray-500 mt-2 leading-5">تهران، خیابان انقلاب، پلاک {{$i}}23، واحد {{$i}} — کد پستی 12345{{$i}}6789</div>
        <div class="text-xs mt-2">گیرنده: علی محمدی — 09120000000</div>
        <div class="flex gap-2 mt-3"><button class="text-xs border rounded-lg px-3 py-1 hover:bg-gray-50">ویرایش</button><button onclick="showToast('حذف شد','info')" class="text-xs border rounded-lg px-3 py-1 hover:bg-[#fff1f2] hover:text-[#ef4056]">حذف</button></div>
      </div>
      @endfor
    </div>
  </div>
</div>
<div id="addrModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
  <div class="bg-white rounded-2xl max-w-lg w-full p-6">
    <h3 class="font-bold">افزودن آدرس (AJAX)</h3>
    <form class="mt-4 space-y-3" onsubmit="event.preventDefault(); fetch('/dashboard/addresses',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({address:this.addr.value})}).then(()=>{showToast('آدرس اضافه شد','success'); document.getElementById('addrModal').classList.add('hidden')});">
      <input name="addr" placeholder="آدرس کامل" required class="w-full h-10 border rounded-lg px-3 text-sm">
      <input placeholder="کد پستی" class="w-full h-10 border rounded-lg px-3 text-sm">
      <div class="flex gap-2"><button type="submit" class="flex-1 h-10 bg-[#ef4056] text-white rounded-lg font-bold">ذخیره</button><button type="button" onclick="document.getElementById('addrModal').classList.add('hidden')" class="flex-1 h-10 border rounded-lg">انصراف</button></div>
    </form>
  </div>
</div>
@endsection
