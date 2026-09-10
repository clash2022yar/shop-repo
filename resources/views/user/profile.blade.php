@extends('layouts.app')
@section('title','پروفایل')
@php $hideFooter=true; @endphp
@section('content')
<div class="max-w-[1680px] mx-auto px-4 lg:px-8 mt-4 flex gap-4">
  <div class="hidden lg:block w-[280px] shrink-0">@include('includes.sidebar-user')</div>
  <div class="flex-1 bg-white rounded-xl border p-6">
    <h1 class="font-bold">اطلاعات حساب کاربری</h1>
    <form class="mt-6 grid md:grid-cols-2 gap-4" onsubmit="event.preventDefault(); fetch('/dashboard/profile',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},body:JSON.stringify({name:this.name.value})}).then(()=> showToast('پروفایل بروز شد','success'));">
      <div><label class="text-xs font-bold">نام و نام خانوادگی</label><input name="name" value="علی محمدی" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
      <div><label class="text-xs font-bold">ایمیل</label><input value="ali.mohammadi@gmail.com" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm ltr" dir="ltr"></div>
      <div><label class="text-xs font-bold">موبایل</label><input value="09120000000" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm ltr" dir="ltr"></div>
      <div><label class="text-xs font-bold">کد ملی</label><input placeholder="اختیاری" class="mt-1 w-full h-10 border rounded-lg px-3 text-sm"></div>
      <div class="md:col-span-2 flex gap-2 mt-2"><button type="submit" class="h-10 px-6 bg-[#ef4056] text-white rounded-lg font-bold hover:bg-[#d32f44]">ذخیره تغییرات (AJAX)</button><button type="button" class="h-10 px-6 border rounded-lg">انصراف</button></div>
    </form>
  </div>
</div>
@endsection
